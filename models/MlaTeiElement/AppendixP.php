<?php

class MlaTeiElement_AppendixP extends MlaTeiElement
{
    
    
    public function getCommentatorItems()
    {
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
        $citoCitesPropId = record_relations_property_id(CITO, 'cites');
        $params = array(
                'subject_record_type' => 'MlaTeiElement_AppendixP',
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