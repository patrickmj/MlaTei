<?php

class MlaTeiImporter_StageDir extends MlaTeiImporter
{
    
    public function importEl($mlaEl, $domNode) 
    {
        $prevLb = $this->getPrevLb($domNode);        
        $mlaEl->n = $prevLb->getAttribute('n');
        $mlaEl = parent::importEl($mlaEl, $domNode);
        
        
        $mlaEl->first_line_xml_id = $prevLb->getAttribute('id');
        $lastLb = $this->getLastLb($domNode);
        if($lastLb) {
            $mlaEl->last_line_xml_id = $lastLb->getAttribute('id');
            $mlaEl->last_n = $lastLb->getAttribute('n');
        } else {
            $mlaEl->last_line_xml_id = $mlaEl->first_line_xml_id;
            $mlaEl->last_n = $mlaEl->n;
        }
                
        return $mlaEl;        
    }
    
    public function parseToItem($domNode, $mlaEl) 
    {
        //no item for Stage Directions
        
    }
    
    public function postProcessHtml($doc, $mlaEl, $domNode)
    {

        //previous lb isn't in the current doc, so stuff in the a it would've made
        $lb = $this->getPrevLb($domNode);
        $firstA = $doc->createElement('a');
        $firstA->setAttribute('xml:id', $lb->getAttribute('xml:id'));
        $firstA->setAttribute('class', 'line');        
        $span = $doc->getElementsByTagName('span')->item(0);
        $span->insertBefore($firstA, $span->firstChild);
        
        
        //change anchors into spans around the text node to the next anchor
        $aNodes = $doc->getElementsByTagName('a');
        
        foreach($aNodes as $a) {
        
            $span = $doc->createElement('span');
            $span->setAttribute('class', 'line');
            $tlId = $a->getAttribute('xml:id');
            $span->setAttribute('id', $tlId);
            $textNode = $a->nextSibling;
            if($textNode) {
                $span->appendChild($textNode);
            }        
            $a->parentNode->appendChild($span);
        }
        
        while($aNodes->length != 0) {
            $a = $aNodes->item(0);
            $a->parentNode->removeChild($a);
            $aNodes = $doc->getElementsByTagName('a');
        }               
    }

    public function getXmlId($domNode)
    {
        //get the previous sibling lb and use that id
        $lb = $this->getPrevLb($domNode);
        return $lb->getAttribute("id");
    }    
    
    public function getPrevLb($domNode)
    {
        $lbNodes = $this->xpath->query("preceding-sibling::nvs:lb[position() = 1]", $domNode);
        return $lbNodes->item(0);
    }    
    
    public function getLastLb($domNode)
    {
        $lbNodes = $domNode->getElementsByTagName('lb');
        if($lbNodes->length == 0) {
            return false;
        } else {
            return $lbNodes->item($lbNodes->length - 1);
        }
    
    }    
}