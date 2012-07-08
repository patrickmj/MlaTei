<?php

/**
 * run this import before Speech importer so that who attributes can be looked up an put into Speech::role_id
 * @author patrickmj
 *
 */

class MlaTeiImporter_Role extends MlaTeiImporter
{    

    public function processXSL($domNode)
    {
        //castItem produces a <li>, so just get the span produced by role
        if($domNode->nodeName=='castItem') {
            $domNode = $this->getFirstChildNodeByName('role', $domNode);
        }
        return parent::processXSL($domNode);
    }
    
    public function parseToItem($domNode, $mlaEl)
    {        
        $roleType = get_db()->getTable('ItemType')->findByName('Role');
        $itemMetadata = array('public'=>true, 'item_type_id'=>$roleType->id);        
        $itemElementTexts = $this->processElementTexts($domNode, $mlaEl);
        $item = insert_item($itemMetadata, $itemElementTexts);
        return $item;
    }

    public function getXmlId($domNode)
    {
        $roleNodes = $domNode->getElementsByTagName('role');
        $roleNode = $roleNodes->item(0);    
        $id = $roleNode->getAttribute('xml:id'); 
        return $id;
    }
    
    private function processElementTexts($domNode, $mlaEl)
    {
        $elTexts = array('Dublin Core'=>array('Title'=>array()));
        $roleNodes = $domNode->getElementsByTagName('role');
        $roleNode = $roleNodes->item(0);
        $title = $roleNode->textContent;
         
        if(empty($title)) {            
            $title = $mlaEl->xml_id;
        }
        $title = preg_replace( '/\s+/', ' ', $title );
        $elTexts['Dublin Core']['Title'][] = array('text'=>$title, 'html'=>false);
        
        $roleDescNodes = $domNode->getElementsByTagName('roleDesc');
        if($roleDescNodes->length == 0) {
            //try for a parent castGroup, which might have the same description for more than one castItem
            $parentNode = $domNode->parentNode;
            if($parentNode->nodeName == 'castGroup') {
                //for the twins, include both names in the description
                $roles = $parentNode->getElementsByTagName('role');
                $descText = "[";
                foreach($roles as $role) {
                    $descText .= $role->textContent;
                    $descText .= ", ";                     
                }
                $descText = substr($descText, 0, -2);
                $descText .= "] ";
                
                $rdNodes = $parentNode->getElementsByTagName('roleDesc');
                if($rdNodes->length !=0) {
                    $rdNode = $rdNodes->item(0);
                    $descText .= $rdNode->textContent;
                    $elTexts['Dublin Core']['Description'][] = array('text'=>preg_replace( '/\s+/', ' ', $descText ), 'html'=>false);
                }
            }
        } else {
            $rdNode = $roleDescNodes->item(0);
            $elTexts['Dublin Core']['Description'][] = array('text'=>preg_replace( '/\s+/', ' ', $rdNode->textContent ), 'html'=>false);            
        }
        return $elTexts;
    }
}