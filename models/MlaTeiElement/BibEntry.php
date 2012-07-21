<?php

class MlaTeiElement_BibEntry extends MlaTeiElement
{
    public $siglum;
    
    
    public function getCommentatorItems()
    {
        $citoCitesPropId = record_relations_property_id(CITO, 'cites');
        $params = array(
                'subject_record_type' => 'MlaTeiElement_BibEntry',
                'subject_id' => $this->id,
                'object_record_type' =>  'Item' ,
                'property_id' => $citoCitesPropId
                );
        $commentators = get_db()->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);
        return $commentators;
    }
}
