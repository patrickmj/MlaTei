<?php

abstract class MlaTeiImporter
{
    public $dom;
    public $xpath;
    public $xslProcessor;
    
    

    public function __construct($file)
    {
        $this->dom = new DomDocument();
        $this->dom->load($file);
        $this->xpath = new DomXPath($this->dom);
        $this->xpath->registerNamespace('nvs', 'http://www.mla.org/NVSns');
    }
    
    abstract public function parseToItem($domNode, $mlaEl);
    
    abstract public function processXSL($domNode);
    
    public function importEl($mlaEl, $domNode) 
    {        
        $mlaEl->xml = $this->dom->saveXML($domNode);
        $mlaEl->xml_id = $this->getXmlId($domNode); 
        $mlaEl->html = $this->processXSL($domNode);
        $item = $this->parseToItem($domNode, $mlaEl);
        $mlaEl->item_id = $item->id;
        return $mlaEl;
    }    
    
    public function getXmlId($domNode)
    {        
        $id = $domNode->getAttribute('xml:id');
        return $id;
    }
    
    /**
     * Utility to return just the first childnode by name in the domNode, e.g., 'title', 'description'
     * @param unknown_type $nodeName
     */
    
    public function getFirstChildNodeByName($nodeName, $domNode)
    {
        $nodes = $domNode->getElementsByTagName($nodeName);
        return $nodes->item(0);
    }
    
    public function buildRelation($subject, $object, $propId)
    {
        $rel = new RecordRelationsRelation();
        $rel->subject_record_type = get_class($subject);
        $rel->object_record_type = get_class($object);
        $rel->subject_id = $subject->id;
        $rel->object_id = $object->id;
        $rel->property_id = $propId;
        $rel->public = true;
        try {
            $rel->save();
        } catch (Exception $e) {
            print_r($rel->toArray());
            die();
        }
    
        return $rel;
    }    
}