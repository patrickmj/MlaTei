<?php

class MlaTeiImporter_Speech extends MlaTeiImporter
{

    
    public function processXSL($domNode) {}
    
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