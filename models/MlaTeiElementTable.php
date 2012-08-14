<?php

class MlaTeiElementTable extends Omeka_Db_Table
{
    
    
    public function applySearchFilters($select, $params)
    {
        $columns = $this->getColumns();
        $alias = $this->getTableAlias();
        foreach($params as $param=>$value) {
            if(in_array($param, $columns)) {
                $select->where("$alias.$param = ?", $value );
            }
            if($param == 'search') {
                $this->filterBySearch($select, $value);
            }
        }        
    }    
        
    public function findByXmlId($xmlId)
    {
        $select = $this->getSelect();
        $select->where('xml_id = ?', $xmlId);
        return $this->fetchObject($select);        
    }
    
    public function findByItemId($itemId)
    {
        $select = $this->getSelect();
        $select->where('item_id = ?', $itemId);
        return $this->fetchObject($select);
    }
    
    public function filterBySearch($select, $terms)
    {
        $db = get_db();
        $quotedTerms = $db->quote($terms);
    
        $select->where("MATCH (html) AGAINST ($quotedTerms)");
    
    }    
    
}