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
        return $lb->getAttribute("xml:id") . '-speech';
    }
    
    public function postProcessHtml($doc, $mlaEl, $domNode)
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
        
        //all but the first speech has the closest lb before the speech, so outside the document
        //so make an a and stuff it into the top of the dd
        $lb = $this->getPrevLb($domNode);
        $firstA = $doc->createElement('a');
        $firstA->setAttribute('xml:id', $lb->getAttribute('xml:id'));
        $firstA->setAttribute('class', 'line');
        $p = $doc->getElementsByTagName('p')->item(0);
        if($mlaEl->xml_id != 'tln_0004-speech') {            
            $p->insertBefore($firstA, $p->firstChild);
        }
        
        
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