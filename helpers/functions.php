<?php


/* _discussion covers CommentaryNotes, AppendixPs, AppendixNotes, etc. functions will figure it out based on the class passed in

*/

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
    return $passages;
    
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
                die();
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
    return mla_count_stagedirections_for_commentator($commentator) + mla_count_speeches_for_commentator($commentator);    
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
    return $count + $bibCount;
    
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
