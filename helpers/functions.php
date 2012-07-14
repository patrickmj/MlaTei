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
    print_r($speechParams);
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
    return $relTable->findObjectRecordsByParams($params);
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
    return $relTable->findObjectRecordsByParams($params);
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
