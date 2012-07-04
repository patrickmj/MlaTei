<?php

class MlaTeiElement_BibEntry extends MlaTeiElement
{
    
    
    
    public function getCommentatorItems()
    {
        $citoCitesPropId = record_relations_property_id(CITO, 'cites');
        $params = array(
                'subject_record_type' => 'Item',
                'subject_id' => $this->id,
                'object_record_type' => 'MlaTeiElement_BibEntry',
                'property_id' => $citoCitesPropId
                );
        return get_db()->getTable('RecordRelationsRelation')->findSubjectRecordsByParams($params);
    }
}
