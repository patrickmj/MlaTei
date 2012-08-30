<?php


/* _discussion covers CommentaryNotes, AppendixPs, AppendixNotes, etc. functions will figure it out based on the class passed in

*/

function mla_bib_secondary_html($entry)
{    
    
    $commentators = mla_get_commentators_for_bibliography($entry);
    $secondaryHTML = "<div class='mla-bib-entry'>";
    $secondaryHTML .= $entry->html;
    $secondaryHTML .= ' ' . mla_link_to_item_by_id($entry->item_id, "View");
    $secondaryHTML .= '<ul>';
    foreach($commentators as $commentator) {
        $secondaryHTML .= '<li>' . link_to_item(null, array(), 'show', $commentator) . '</li>';
    }
    $secondaryHTML .= '</ul>';
    $secondaryHTML .= "</div>";        
    return $secondaryHTML; 
}

function mla_link_to_item_by_id($itemId, $text = null) 
{
    $item = get_db()->getTable('Item')->find($itemId);
    return link_to_item($text, array(), 'show', $item);    
}

function mla_get_passages_for_discussion($discussion)
{
    $refsSpeechId = record_relations_property_id(MLATEINS, 'refsSpeech');
    $refsStageDirId = record_relations_property_id(MLATEINS, 'refsStageDirection');

    $relTable = get_db()->getTable('RecordRelationsRelation');
    $type = get_class($discussion);
    //splice together Speeches and StageDirections
    $speechParams = array(
            'subject_record_type' => $type,
            'subject_id' => $discussion->id,
            'property_id' => $refsSpeechId,
            'object_record_type' => 'MlaTeiElement_Speech'
    );


    $stageDirParams = array(
            'subject_record_type' => $type,
            'subject_id' => $discussion->id,
            'property_id' => $refsStageDirId,
            'object_record_type' => 'MlaTeiElement_StageDir'
    );  
    
    $speeches = $relTable->findObjectRecordsByParams($speechParams);
    $stageDirs = $relTable->findObjectRecordsByParams($stageDirParams);
    $passages = array_merge($speeches, $stageDirs);
    uasort($passages, 'mla_passage_sort');
    
    return $passages;
    
}

function mla_passage_sort($a, $b)
{
    $aLine = (int) $a->n;
    $bLine = (int) $b->n;
    if($aLine > $bLine) {
        return 1;
    } else {
        return -1;
    }
    
}

function mla_get_editions_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'object_id' => $commentator->id,
            'object_record_type' => 'Item',
            'subject_record_type' => 'MlaTeiElement_EditionEntry'
    );
    return $relTable->findSubjectRecordsByParams($params);
}


function mla_get_bibliography_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'object_id' => $commentator->id,
            'object_record_type' => 'Item',
            'subject_record_type' => 'MlaTeiElement_BibEntry'
    );
    return $relTable->findSubjectRecordsByParams($params);     
}


function mla_get_commentators_for_speech($speech)
{
    $commentsOnSpeechId = record_relations_property_id(MLATEINS, 'commentsOnSpeech');
    $props = array(
            'property_id'=>$commentsOnSpeechId,
            'object_id' => $speech->id,
            'subject_record_type' => 'Item',
            'object_record_type' => 'MlaTeiElement_Speech'
    );
    return get_db()->getTable('RecordRelationsRelation')->findSubjectRecordsByParams($props);

}


function mla_get_commentators_for_bibliography($bibliography = null)
{
    $db = get_db();
    if(!$bibliography) {
        $bibliography = get_current_item();        
    }
    
    switch(get_class($bibliography)) {
        case 'MlaTeiElement_BibEntry':
            $bibEntry = $bibliography;
            $subject_record_type = 'MlaTeiElement_BibEntry';
            $propId = record_relations_property_id(DCTERMS, 'creator');
            break;
        case 'MlaTeiElement_EditionEntry':
            $bibEntry = $bibliography;
            $subject_record_type = 'MlaTeiElement_EditionEntry';
            $propId = record_relations_property_id(DCTERMS, 'contributor');
            break;
        case 'Item':
            $bibEntry = $db->getTable('MlaTeiElement_BibEntry')->findByItemId($bibliography->id);
            $subject_record_type = 'MlaTeiElement_BibEntry';
            $propId = record_relations_property_id(DCTERMS, 'creator');
            break;
    }
    
    
    $relTable = $db->getTable('RecordRelationsRelation');
    
    $params = array(
            'subject_id' => $bibEntry->id,
            'subject_record_type' => $subject_record_type,
            'object_record_type' => 'Item',
            'property_id' => $propId
            );

    return $relTable->findObjectRecordsByParams($params);
    
}

function mla_get_editions_for_discussion($discussion)
{
    $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $type = get_class($discussion);
    
    $params = array(
            'subject_record_type' => $type,
            'subject_id' => $discussion->id,
            'property_id' => $refsBiblId,
            'object_record_type' => 'MlaTeiElement_EditionEntry'
    );
    return $relTable->findObjectRecordsByParams($params);    
}

function mla_count_editions_for_discussion($discussion)
{
    
    $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $type = get_class($discussion);
    
    $params = array(
            'subject_record_type' => $type,
            'subject_id' => $discussion->id,
            'property_id' => $refsBiblId,
            'object_record_type' => 'MlaTeiElement_EditionEntry'
    );
    return $relTable->count($params);    
}

function mla_get_bibliography_for_discussion($discussion)
{
    $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $type = get_class($discussion);

    $params = array(
            'subject_record_type' => $type,
            'subject_id' => $discussion->id,
            'property_id' => $refsBiblId,
            'object_record_type' => 'MlaTeiElement_BibEntry'
    ); 
    $result = $relTable->findObjectRecordsByParams($params);
    uasort($result, 'mla_element_xml_id_sort'); 
    return $result;
}

function mla_count_bibliography_for_discussion($discussion)
{
    $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $type = get_class($discussion);

    $params = array(
            'subject_record_type' => $type,
            'subject_id' => $discussion->id,
            'property_id' => $refsBiblId,
            'object_record_type' => 'MlaTeiElement_BibEntry'
    );
    return $relTable->count($params);
}


function mla_get_discussions_for_commentator($discussionType, $commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    $relTable = get_db()->getTable('RecordRelationsRelation'); 
    $params = array(
            'subject_id' => $commentator->id,
            'subject_record_type' => 'Item',
            'object_record_type' => $discussionType
    );
    $discussions = $relTable->findObjectRecordsByParams($params);

    return $discussions;
}

function mla_get_commentators_for_discussion($discussion)
{
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $discussionType = get_class($discussion);
    $params = array(
            'object_id' => $discussion->id,
            'subject_record_type' => 'Item',
            'object_record_type' => $discussionType
    );
    return $relTable->findSubjectRecordsByParams($params);
}

function mla_count_commentators_for_discussion($discussion)
{
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $discussionType = get_class($discussion);
    $params = array(
            'object_id' => $discussion->id,
            'subject_record_type' => 'Item',
            'object_record_type' => $discussionType
    );
    return $relTable->count($params);    
    
}

//horrible churn on the database with this, since it chains through two queries with a loop in between

function mla_get_commentators_in_convo_with_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $inConvo = array();
    $discussions = array();
    $commentators = array(); 
    
    $discussions = array_merge($discussions, mla_get_discussions_for_commentator('MlaTeiElement_CommentaryNote', $commentator));
    $discussions = array_merge($discussions, mla_get_discussions_for_commentator('MlaTeiElement_AppendixP', $commentator));
    $discussions = array_merge($discussions, mla_get_discussions_for_commentator('MlaTeiElement_AppendixNote', $commentator));
    
    foreach($discussions as $discussion) {
        $class = get_class($discussion);
        $params = array(
                'subject_record_type' => 'Item',
                'object_record_type' => $class,
                'object_id' => $discussion->id
                );
        $refData = array('xml_id'=>$discussion->xml_id);
        switch($class) {
            case 'MlaTeiElement_CommentaryNote':
                $propId = record_relations_property_id(MLATEINS, 'citedInCommentaryNote');
                $refData['text'] = $discussion->label;
                $refData['class'] = "note";
                break;
                
            case 'MlaTeiElement_AppendixP':
                $propId = record_relations_property_id(MLATEINS, 'citedInAppendixP');
                $refData['text'] = substr($discussion->html, 58, 20) . "</p>";
                $refData['class'] = "apara";                
                break;
                
            case 'MlaTeiElement_AppendixNote':
                $propId = record_relations_property_id(MLATEINS, 'citedInAppendixNote');
                $refData['text'] = substr($discussion->html, 58, 20) . "</p>";
                $refData['class'] = "anote";                
                break;
                
            default:
                echo $class;
                echo 'uh-oh. fail in get_commentators_in_convo_with_commentator';
                //die();
        }
        $params['property_id'] = $propId;
        $discCommentators = $relTable->findSubjectRecordsByParams($params);
        foreach($discCommentators as $c) {
            if($c->id != $commentator->id) {
                if(array_key_exists($c->id, $commentators)) {
                    $commentators[$c->id]['refs'][] = $refData;                
                } else {
                    $commentators[$c->id] = array('item'=>$c, 'refs'=>array($refData));
                }                
            }            
        }
    }
    uasort($commentators, 'mla_convo_sort');
    return $commentators;    
}

function mla_get_commentators_for_speech_role($role = null)
{
    if(!$role) {
        $role = get_current_item();
    }
    $commentsOnCharacterId = record_relations_property_id(MLATEINS, 'commentsOnCharacter');
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $relParams = array(
                        'property_id' => $commentsOnCharacterId,
                        'object_record_type' => 'MlaTeiElement_Role',
                        'object_id'=> $role->id,
                        'subject_record_type' => 'Item'
            );
    return $relTable->findSubjectRecordsByParams($relParams, array(), array('sort_field'=>'Dublin Core,Title'));
    
}


function mla_count_speeches_for_role($role = null)
{
    if(!$role) {
        $role = get_current_item();
    }
    return get_db()->getTable('MlaTeiElement_Speech')->count(array('role_id'=>$role->id));    
}




function mla_convo_sort($a, $b)
{
    $aTitle = item('Dublin Core', 'Title', array(), $a['item']);
    $bTitle = item('Dublin Core', 'Title', array(), $b['item']);
    if($aTitle > $bTitle) {
        return 1;
    } else {
        return -1;
    }
}

function mla_element_xml_id_sort($a, $b)
{
    if($a->xml_id > $b->xml_id) {
        return 1;
    } else {
        return -1;
    }    
}

function mla_count_speeches_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    $commentsOnSpeechId = record_relations_property_id(MLATEINS, 'commentsOnSpeech');
    $props = array(
            'property_id'=>$commentsOnSpeechId,
            'subject_id' => $commentator->id,
            'subject_record_type' => 'Item',
            'object_record_type' => 'MlaTeiElement_Speech'
            );
    return get_db()->getTable('RecordRelationsRelation')->count($props);
    
}


function mla_count_commentators_for_speech($speech)
{
    $commentsOnSpeechId = record_relations_property_id(MLATEINS, 'commentsOnSpeech');
    $props = array(
            'property_id'=>$commentsOnSpeechId,
            'object_id' => $speech->id,
            'subject_record_type' => 'Item',
            'object_record_type' => 'MlaTeiElement_Speech'
    );
    return get_db()->getTable('RecordRelationsRelation')->count($props);

}

function mla_count_commentators_for_commentary_note($note)
{

    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'object_id' => $note->id,
            'subject_record_type' => 'Item',
            'object_record_type' => 'MlaTeiElement_CommentaryNote'
    );
    return $relTable->count($params);   
}

function mla_count_speeches_for_note($note)
{
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $refsSpeechId = record_relations_property_id(MLATEINS, 'refsSpeech');

    $params = array(
            'subject_record_type' => 'MlaTeiElement_CommentaryNote',
            'subject_id' => $note->id,
            'property_id' => $refsSpeechId,
            'object_record_type' => 'MlaTeiElement_Speech'
    );
    return $relTable->count($params);    
}

function mla_count_stagedirs_for_note($note)
{
    $refsStageDirId = record_relations_property_id(MLATEINS, 'refsStageDirection');
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'subject_record_type' => 'MlaTeiElement_CommentaryNote',
            'subject_id' => $note->id,
            'property_id' => $refsStageDirId,
            'object_record_type' => 'MlaTeiElement_Speech'
    );
    return $relTable->count($params);    
    
}

function mla_count_stagedirections_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }

    $commentsOnStageDirId = record_relations_property_id(MLATEINS, 'commentsOnStageDirection');
    $props = array(
            'property_id'=> $commentsOnStageDirId,
            'subject_id' => $commentator->id,
            'subject_record_type' => 'Item',
            'object_record_type' => 'MlaTeiElement_StageDir'
    );
    return get_db()->getTable('RecordRelationsRelation')->count($props);    
}

function mla_count_passages_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    
    return mla_count_stagedirections_for_commentator($commentator) + mla_count_speeches_for_commentator($commentator);    
}

function mla_count_appendix_ps_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'subject_id' => $commentator->id,
            'subject_record_type' => 'Item',
            'object_record_type' => 'MlaTeiElement_AppendixP'
    );
    return $relTable->count($params);
        
}

function mla_count_appendix_notes_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }

    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'subject_id' => $commentator->id,
            'subject_record_type' => 'Item',
            'object_record_type' => 'MlaTeiElement_AppendixNote'
    );
    return $relTable->count($params);

}

function mla_count_total_citations_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }

    $props = array(
            'subject_id' => $commentator->id,
            'subject_record_type' => 'Item',
    );
    $count = get_db()->getTable('RecordRelationsRelation')->count($props); 
    $bibCount = mla_count_bibliography_for_commentator($commentator);
    $editionCount = mla_count_editions_for_commentator($commentator);
    return $count + $bibCount + $editionCount;
    
}

function mla_count_bibliography_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'object_id' => $commentator->id,
            'object_record_type' => 'Item',
            'subject_record_type' => 'MlaTeiElement_BibEntry'
    );
    return $relTable->count($params);    
    
}


function mla_count_editions_for_commentator($commentator = null)
{
    if(!$commentator) {
        $commentator = get_current_item();
    }
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'object_id' => $commentator->id,
            'object_record_type' => 'Item',
            'subject_record_type' => 'MlaTeiElement_EditionEntry'
    );
    return $relTable->count($params);

}

function mla_count_discussions_for_commentator($discussionType, $commentator = null)
{    
    if(!$commentator) {
        $commentator = get_current_item();
    }
    $relTable = get_db()->getTable('RecordRelationsRelation');
    $params = array(
            'subject_id' => $commentator->id,
            'subject_record_type' => 'Item',
            'object_record_type' => $discussionType
    );
    return $relTable->count($params);    
}


function mla_remove_id($html) {
    return preg_replace('#\sid="[^"]+"#', '', $html);
}



function mla_simple_search($buttonText = null, $formProperties=array('id'=>'simple-search'), $uri = null)
{
    if (!$buttonText) {
        $buttonText = __('Search');
    }

    // Always post the 'items/browse' page by default (though can be overridden).
    if (!$uri) {
        $uri = apply_filters('simple_search_default_uri', uri('items/browse'));
    }

    $searchQuery = array_key_exists('search', $_GET) ? $_GET['search'] : '';
    $formProperties['action'] = $uri;
    $formProperties['method'] = 'get';
    $html  = '<form ' . _tag_attributes($formProperties) . '>' . "\n";
    $html .= '<fieldset>' . "\n\n";
    switch($formProperties['id']) {
        case 'simple-search':
            $html .= "<label for='simple-search'>Search Scholars and Bibliography</label>";
            break;
        case 'simple-search-commentary':
            $html .= "<label for='simple-search-commentary'>Search Commentary</label>";
            break;
        case 'simple-search-appendix':
            $html .= "<label for='simple-search-appendix'>Search Appendix</label>";
            break;
        
    }
    $html .= __v()->formText('search', $searchQuery, array('name'=>'search','class'=>'textinput'));
    $html .= __v()->formSubmit('submit_search', $buttonText);
    $html .= '</fieldset>' . "\n\n";

    // add hidden fields for the get parameters passed in uri
    $parsedUri = parse_url($uri);
    if (array_key_exists('query', $parsedUri)) {
        parse_str($parsedUri['query'], $getParams);
        foreach($getParams as $getParamName => $getParamValue) {
            $html .= __v()->formHidden($getParamName, $getParamValue);
        }
    }
    
    //PMJ: only change to simple_search() is sorting by DC:Title
    //only applies to Items, and if not items, I don't use default uri
    switch($uri) {
        case 'mla-tei/search/appendix':
            
            break;
        case 'mla-tei/search/commentary':
            
            break;
            
        default:
            $html .= __v()->formHidden('sort_field', 'Dublin Core,Title');
            
    }

    

    $html .= '</form>';
    return $html;
}





