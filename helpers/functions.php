<?php

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




function mla_count_citations_for_commentator($commentator = null)
{

}


