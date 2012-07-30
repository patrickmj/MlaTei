<?php

/**
 * almomst identical to BibEntry, but differs in the file drawn from (front.xml) 
 * and the tei in front.xml uses <name> where bibliography.xml uses <author> inside <bibl>
 * The result is unfortunate cut-and-paste between the two
 * 
 * Also differs in trickery to get the xml:id
 * @author patrickmj
 *
 */

class MlaTeiImporter_EditionEntry extends MlaTeiImporter
{
    

    public function importEl($mlaEl, $domNode)
    {
        //look for a siglum
        
        $siglumNode = $this->xpath->query("preceding-sibling::nvs:siglum", $domNode)->item(0);
        if($siglumNode) {
            $mlaEl->siglum = $siglumNode->textContent;
        }
        
        $listWitNode = $this->xpath->query("parent::nvs:witness/parent::nvs:listWit", $domNode)->item(0);
        if($listWitNode) {
            $id = $listWitNode->getAttribute('xml:id');
            if($id == 'listwit_editions') {
                $mlaEl->type = 'Edition';
            } else {
                $mlaEl->type = 'Other';
            }
             
        } else {
            $mlaEl->type = 'Occasional';
        }
        $mlaEl = parent::importEl($mlaEl, $domNode);

        return $mlaEl;        
    }
    
    public function parseToItem($domNode, $mlaEl)
    {
        $bibEntryType = get_db()->getTable('ItemType')->findByName('Edition Entry');
        $metadataArray = array('public'=>true, 'item_type_id'=>$bibEntryType->id);
    
        $titleNode = $this->getFirstChildNodeByName('title', $domNode);
        $authorNodes = $domNode->getElementsByTagName('name');
        $authorsArray = array();
        foreach($authorNodes as $authorNode) {
            $authorsArray[] = array('text'=>$authorNode->textContent, 'html'=>0);
        }
    
        $elSetsArray = array(
                'Dublin Core'=>array(
                        'Title'=>array(array('text'=>$titleNode->textContent, 'html'=>0)),
                        'Contributor'=>$authorsArray,
                        'Type' => array(array('text'=>'edition', 'html'=>0)),
                        'Date' => array(array('text'=>$this->getFirstChildNodeByName('data', $domNode)->textContent, 'html'=>0))
                )
        );
        if(substr($mlaEl->xml_id,0,2) == 's_') {
            //looks like a witness
            $elSetsArray['Dublin Core']['Type'][] = array('text'=>'witness', 'html'=>0);     
            $elSetsArray['Dublin Core']['Identifier'] = array(array('text'=>$mlaEl->siglum, 'html'=>0));
            $elSetsArray['Item Type Metadata']['Siglum'] = array(array('text'=>$mlaEl->siglum, 'html'=>0));
        }
        return insert_item($metadataArray, $elSetsArray);
    }
    
    public function getCommentators($domNode, $mlaEl)
    {
        $authorNodes = $domNode->getElementsByTagName('name');
        $commentatorItems = array(); //commentators only exist as Omeka Items, w/o a TEI representation
        foreach($authorNodes as $authorNode) {
            $commentatorItems[] = $this->findCommentatorByNameOrNew($authorNode->textContent);
        }
        return $commentatorItems;
    }
    
    public function findCommentatorByNameOrNew($textContent)
    {
        $textContent = preg_replace( '/\s+/', ' ', $textContent );
        $db = get_db();
        $dcTitle = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Title');
        $searchParams = array('item_type'=>'Commentator',
                'advanced_search' => array(array('terms'=>$textContent,
                        'type'=>'is exactly',
                        'element_id'=>$dcTitle->id
                )
                )
        );
        $commentatorItems = $db->getTable('Item')->findBy($searchParams);
        if(empty($commentatorItems)) {
            $itemType = $db->getTable('ItemType')->findByName('Commentator');
            $elements = array(
                    'Dublin Core' => array(
                            'Title'=>array(array('text'=>$textContent, 'html'=>0))
                    )
            );
            return insert_item(array('public'=>true, 'item_type_id'=>$itemType->id), $elements);
        } else {
            return $commentatorItems[0];
        }
    }
    
    public function postProcessHtml($doc, $mlaEl)
    {
        //stylesheet misses the id to stuff into the span class, so do that here.
        $span = $doc->getElementsByTagName('span')->item(0);
        $span->setAttribute('class', $mlaEl->xml_id);
    }
    
    public function getXmlId($domNode)
    {        
        $id = parent::getXmlId($domNode);
        if($id) {
            return $id;
        } else {
            //it's probably a witness, so dig the id up
            //hopefully, ids beginning with s_ are all witnesses!
            $witnessNode = $domNode->parentNode;            
            return $witnessNode->getAttribute('xml:id');
        }        
    }
}