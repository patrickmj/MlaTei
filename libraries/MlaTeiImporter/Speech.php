<?php

class MlaTeiImporter_Speech extends MlaTeiImporter
{
    
    public function parseToItem($domNode, $mlaEl)
    {
        //no item for this content type. I'm putting the text at lower significance in Omeka, that means it ain't an Item
        
    }

    
    public function importEl($mlaEl, $domNode)
    {
        $mlaEl = parent::importEl($mlaEl, $domNode);
        $who = $domNode->getAttribute('who');        
        $mlaEl->role_xml_id = substr($who, 1);
        $role = get_db()->getTable('MlaTeiElement_Role')->findByXmlId($mlaEl->role_xml_id);
        $mlaEl->role_id = $role->id;
        $prevLb = $this->getPrevLb($domNode);
        $mlaEl->n = $prevLb->getAttribute('n');
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
    
    public function getXmlId($domNode)
    {
        //get the previous sibling lb and use that id
        $lb = $this->getPrevLb($domNode);  
        return $lb->getAttribute("xml:id");
    }
    
    public function postProcessHtml($doc, $mlaEl)
    {
        //<dt> is the speaker, which gets a new line
        $dtNodes = $doc->getElementsByTagName('dt');
        $dt = $dtNodes->item(0);

        $speakerLine = $doc->createElement('span');
        $speakerLine->setAttribute('class', 'line');
        $speakerLine->setAttribute('id', $mlaEl->xml_id);
                
        if($dt->firstChild) {
            $speakerLine->appendChild($dt->removeChild($dt->firstChild));
        } else {
            $speakerLine->appendChild($doc->createTextNode(''));
        }
        
        $dt->appendChild($speakerLine);
        
        
        //change anchors into spans around the text node to the next anchor
        $aNodes = $doc->getElementsByTagName('a');
        
        foreach($aNodes as $a) {
            
            $span = $doc->createElement('span');
            $span->setAttribute('class', 'line');
            $tlId = $a->getAttribute('xml:id');
            $span->setAttribute('id', $tlId);
            $textNode = $a->nextSibling;
            $span->appendChild($textNode);
            $a->parentNode->appendChild($span);
        }

        while($aNodes->length != 0) {
            $a = $aNodes->item(0); 
            $a->parentNode->removeChild($a);
            $aNodes = $doc->getElementsByTagName('a');            
        }
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