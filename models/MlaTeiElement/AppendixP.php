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
    
    public function countLines()
    {
        $dom = new DomDocument();
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <!DOCTYPE container [
                <!ENTITY hellip     "&#x2E;&#xA0;&#x2E;&#xA0;&#x2E; ">
                <!ENTITY inked     "&#x2759;">
                <!ENTITY caret     "&#x2038;">
                <!ENTITY minus     "&#x2212;">
                <!ENTITY plus     "&#x002B;">
                <!ENTITY shy     "-&#x00AD;">
                <!ENTITY sigrange     "&#x002D;">
                <!ENTITY swdash     "&#x2002;&#x007E;&#x2002;">
                <!ENTITY verbar     "&#x2002;&#x007C;&#x2002;">
                <!ENTITY cmacr     "&#x63;&#x304;">
                ]>';
        $xml .= $this->xml;
        $dom->loadXml($xml);
        $xpath = new DomXPath($dom);
        $xpath->registerNamespace('nvs', 'http://www.mla.org/NVSns');
        $xpath->registerNamespace('tei', "http://www.tei-c.org/ns/1.0");
        $lbCount = $xpath->evaluate("count(//ref[@targType='lb'])");        
        return $lbCount;
    }
}