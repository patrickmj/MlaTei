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
            $text = $authorNode->textContent;
            if($text != 'et al.') {
                $authorsArray[] = array('text'=>$authorNode->textContent, 'html'=>0);
            }            
        }
        $title = $this->normalizeText($titleNode->textContent, true);
        $elSetsArray = array(
                'Dublin Core'=>array(
                        'Title'=>array(array('text'=>$title, 'html'=>0)),
                        'Creator'=>$authorsArray,
                        'Date' => array(array('text'=>$this->getFirstChildNodeByName('date', $domNode)->textContent, 'html'=>0))
                        ),
                'Item Type Metadata' => array(
                        'Citation' => array(array('text'=>$mlaEl->html, 'html'=>1))
                        )
                );
        $journalTitle = $this->xpath->query(".//nvs:title[@level = 'j']", $domNode)->item(0);
        
        if($journalTitle) {
            $elSetsArray['Dublin Core']['Type'] = array(array('text'=>'Journal Article', 'html'=>0));
            $elSetsArray['Dublin Core']['Publisher'] = array(array('text'=>$journalTitle->textContent, 'html'=>0));
            
            $volume = $this->xpath->query(".//nvs:biblScope[@type = 'vol']", $domNode)->item(0);
            $pages = $this->xpath->query(".//nvs:biblScope[@type = 'pages']", $domNode)->item(0);
            $issue = $this->xpath->query(".//nvs:biblScope[@type = 'issue']", $domNode)->item(0);
            $series = $this->xpath->query(".//nvs:biblScope[@type = 'series']", $domNode)->item(0);
            $elSetsArray['Item Type Metadata']['Volume'] = array(array('text'=>$volume->textContent, 'html'=>0));
            $elSetsArray['Item Type Metadata']['Pages'] = array(array('text'=>$pages->textContent, 'html'=>0));
            $elSetsArray['Item Type Metadata']['Issue'] = array(array('text'=>$issue->textContent, 'html'=>0));
            $elSetsArray['Item Type Metadata']['Series'] = array(array('text'=>$series->textContent, 'html'=>0));            
            
        } else {
            $elSetsArray['Dublin Core']['Type'] = array(array('text'=>'Book', 'html'=>0));
        }
        return insert_item($metadataArray, $elSetsArray);
    }
    
    public function getCommentators($domNode, $mlaEl)
    {
        $authorNodes = $domNode->getElementsByTagName('author');
        $commentatorItems = array(); //commentators only exist as Omeka Items, w/o a TEI representation
        foreach($authorNodes as $authorNode) {
            if($authorNode->textContent != 'et al.') {
                $commentatorItems[] = $this->findCommentatorByNameOrNew($authorNode->textContent);
            }                        
        }
        return $commentatorItems;
    }
    
    public function findCommentatorByNameOrNew($textContent)
    {
        
        $normalizer = new NameNormalizer();
        $textContent = $normalizer->normalize($textContent);
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