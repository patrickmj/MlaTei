<?php

class MlaTeiElementTable extends Omeka_Db_Table
{
    
    public function findByXmlId($xmlId)
    {
        $select = $this->getSelect();
        $select->where('xml_id = ?', $xmlId);
        return $this->fetchObject($select);        
    }
    
}