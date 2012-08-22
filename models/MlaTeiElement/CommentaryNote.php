<?php

class MlaTeiElement_CommentaryNote extends MlaTeiElement
{
    public $label;
    public $target;
    public $target_end;
       
    
    
    public function getCommentatorItems()
    {
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
        $citoCitesPropId = record_relations_property_id(CITO, 'cites');
        $params = array(
                'subject_record_type' => 'MlaTeiElement_CommentaryNote',
                'subject_id' => $this->id,
                'object_record_type' => 'MlaTeiElement_BibEntry',
                'property_id' => $refsBiblId
        );
        $commentatorItems = array();
        $biblEntries = get_db()->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);
        foreach($biblEntries as $bibEntry) {
            $commentators = $bibEntry->getCommentatorItems();
            $commentatorItems = array_merge($commentatorItems, $commentators);
        }
        return $commentatorItems;
    }

}