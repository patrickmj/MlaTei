<?php

class MlaTeiElement_BibEntry extends MlaTeiElement
{
    
    public function getCommentatorItems()
    {
        
        $dctCreatorId = record_relations_property_id(DCTERMS, 'creator');
        $params = array(
                'subject_record_type' => 'MlaTeiElement_BibEntry',
                'subject_id' => $this->id,
                'object_record_type' =>  'Item' ,
                'property_id' => $dctCreatorId
                );
        $commentators = get_db()->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);
        return $commentators;
    }
}
