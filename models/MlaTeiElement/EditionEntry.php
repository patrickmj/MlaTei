<?php

class MlaTeiElement_EditionEntry extends MlaTeiElement
{

    public $siglum;
    public $type; // type refers to the way the work is cited. see explanations in front_matter.xml
    
    public function getCommentatorItems()
    {
        $citoCitesPropId = record_relations_property_id(CITO, 'cites');
        $params = array(
                'subject_record_type' => 'MlaTeiElement_EditionEntry',
                'subject_id' => $this->id,
                'object_record_type' =>  'Item' ,
                'property_id' => $citoCitesPropId
        );
        $commentators = get_db()->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);
        return $commentators;
    }
}
