<?php

class MlaTeiImporter_BibEntry extends MlaTeiImporter
{
    
    public function parseToItem($domNode, $mlaEl)
    {        
        $bibEntryType = get_db()->getTable('ItemType')->findByName('Bibliography Entry');
        $metadataArray = array('public'=>true, 'item_type_id'=>$bibEntryType->id);
                
        $titleNode = $this->getFirstChildNodeByName('title', $domNode);
        $authorNodes = $domNode->getElementsByTagName('author');
        $authorsArray = array();
        foreach($authorNodes as $authorNode) {
            $authorsArray[] = array('text'=>$authorNode->textContent, 'html'=>0);
        }
        
        $elSetsArray = array(
                'Dublin Core'=>array(
                        'Title'=>array(array('text'=>$titleNode->textContent, 'html'=>0)),
                        'Contributor'=>$authorsArray,
                        'Date' => array(array('text'=>$this->getFirstChildNodeByName('data', $domNode)->textContent, 'html'=>0))
                        )
                );
        return insert_item($metadataArray, $elSetsArray);
    }
    
    public function getCommentators($domNode, $mlaEl)
    {
        $authorNodes = $domNode->getElementsByTagName('author');
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

}