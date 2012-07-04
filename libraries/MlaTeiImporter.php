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
        $this->xpath->registerNamespace('tei', "http://www.tei-c.org/ns/1.0");

        $xsl = MLA_TEI_XSL_PATH . "/html/tei.xsl";
        $this->xslProcessor = new XSLTProcessor();
        $xslDOM = new DOMDocument();
        $xslDOM->load($xsl);
        $this->xslProcessor->importStylesheet($xslDOM);
    }
    
    abstract public function parseToItem($domNode, $mlaEl);
    
    public function processXSL($domNode)
    {
        //echo $this->dom->saveXml($domNode);
//tei xsl files have had tei: prefix removed to make it go through the processor and match up correctly        
        $wtfDoc = new DomDocument();
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE container [
<!ENTITY hellip     " &#x2E;&#xA0;&#x2E;&#xA0;&#x2E; ">
<!ENTITY inked     "&#x2759;">
<!ENTITY caret     "&#x2038;">
<!ENTITY minus     "&#x2212;">
<!ENTITY plus     "&#x002B;">
<!ENTITY shy     "&#x00AD;">
<!ENTITY sigrange     "&#x002D;">
<!ENTITY swdash     "&#x2002;&#x007E;&#x2002;">
<!ENTITY verbar     "&#x2002;&#x007C;&#x2002;">
<!ENTITY cmacr     "&#x63;&#x304;">
]>
                                              
               ';
        $xml .=  $this->dom->saveXml($domNode);
        
        $wtfDoc->loadXml($xml);
        $doc = $this->xslProcessor->transformToDoc($wtfDoc);

        return $doc->saveHtml();
        
        
        
    }
    
    public function importEl($mlaEl, $domNode) 
    {        
        $mlaEl->xml = $this->dom->saveXML($domNode);
        $mlaEl->xml_id = $this->getXmlId($domNode); 
        $mlaEl->html = $this->processXSL($domNode);
        $item = $this->parseToItem($domNode, $mlaEl);
        if(!$item) {
            $mlaEl->item_id = null;
        } else {
            $mlaEl->item_id = $item->id;
        }
        
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
            echo $e;
            die();
        }
    
        return $rel;
    }    
}