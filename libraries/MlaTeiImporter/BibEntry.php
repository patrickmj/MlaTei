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
        $journalTitle = $this->xpath->query("nvs:title[@level = 'j']", $domNode)->item(0);
        
        if($journalTitle) {
            $elSetsArray['Dublin Core']['Type'] = array(array('text'=>'Journal Article', 'html'=>0));
            $elSetsArray['Dublin Core']['Publisher'] = array(array('text'=>$journalTitle->textContent, 'html'=>0));
            
            $volume = $this->xpath->query("nvs:biblScope[@type = 'vol']", $domNode)->item(0);
            $pages = $this->xpath->query("nvs:biblScope[@type = 'pages']", $domNode)->item(0);            
            $elSetsArray['Item Type Metadata']['Volume'] = array(array('text'=>$volume->textContent, 'html'=>0));
            $elSetsArray['Item Type Metadata']['Pages'] = array(array('text'=>$pages->textContent, 'html'=>0));
            
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
        $textContent = preg_replace( '/\s+/', ' ', $textContent );
        
        //normalize names that appear differently in different sections
        switch($textContent) {
            case 'T. W. Baldwin':
            case 'Thomas W. Baldwin':
                $textContent = 'Baldwin, T[homas] W.';
                break;
                
            case 'Alexander Dyce':
                $textContent = 'Dyce, Alexander';
                break;
                
            case 'Arthur Quiller-Couch':
                $textContent = 'Quiller-Couch, Arthur';
                break;
                //Charles and Mary Cowden Clarke are a mess
            //@TODO: file an issue. 718 vs 815 of bibliography
            case 'Charles J. Sisson':
                $textContent = 'Sisson, Charles, J.';
                break;
                //filter Bevington et al. 1148
            case 'David Bevington':
            case 'David Bevington et al.':
                $textContent = 'Bevington, David';
                break;
            case 'Edmond Malone':
                //fuck! different types of dashes for Edmond? srsly? WTF!!
            case 'Malone, Edmond (1741–1812)':
                $textContent = 'Malone, Edmond';
                break;
                
            case 'Edward Capell':
                $textContent = 'Capell, Edward';
                break;
                
            case 'G. Blakemore Evans et al.':
                $textContent = 'Evans, G. Blakemore';
                break;
                
            case 'Gary Taylor':
                $textContent = 'Taylor, Gary';
                break;
                
            case 'George Steevens':
            case 'Steevens, George (1736–1800)';
                $textContent = 'Steevens, George';
                break;
                
            case 'Gerard Langbaine':
                $textContent = 'Langbaine, Gerard';
                break;
                
            case 'Harry Levin':
                $textContent = 'Levin, Harry';
                break;
                
            case 'Reed, Isaac (1742–1807)':
            case 'Isaac Reed':
                $textContent = 'Reed, Isaac';
                break;
                
            case 'J[ohn] C. Trewin':
                $textContent = 'Trewin, J[ohn] C.';
                break;
                
                
            case 'James O. Halliwell':
                $textContent = 'Halliwell[-Phillipps], J[ames] O.';
                break;
                
            case 'John Dover Wilson':
                $textContent = 'Wilson, [John] Dover';
                break;
                
            case 'John Nichols':
                $textContent = 'Nichols, John';
                break;
                
            case 'John Payne Collier':
                $textContent = 'Collier, J[ohn] Payne';
                break;
                
            case 'John Philip Kemble':
                $textContent = 'Kemble, John Philip';
                break;
                
            case 'Karl Wentersdorf':
                $textContent = 'Wentersdorf, Karl';
                break;
                
            case 'Lewis Theobald':
                $textContent = 'Theobald, Lewis';
                break;
                
            case 'M. R. Ridley':
                $textContent = 'Ridley, M[aurice] R.';
                break;
                
            case 'Mariko Ichikawa':
                $textContent = 'Ichikawa, Mariko';
                break;
                
            case 'Miriam Joseph, Sr.':
                $textContent = 'Joseph, Sr. Miriam';
                break;
                
            case 'Paul Werstine':
                $textContent = 'Werstine, Paul';
                break;
                
            case 'Peter Alexander':
                $textContent = 'Alexander, Peter';
                break;
                
            case 'Richard Grant White':
                $textContent = 'White, Richard G.';
                break;
                
            case 'Samuel Johnson':
                $textContent = 'Johnson, Samuel';
                break;
                
            case 'Stanley Wells':
                $textContent = 'Wells, Stanley';
                break;
                
            case 'Stephen Greenblatt et al.':
                $textContent = 'Greenblatt, Stephen';
                break;
                
            case 'Styan Thirlby':
                $textContent = 'Thirlby, Styan';
                break;
                
            case 'Thiselton Dyer':
                $textContent = 'Dyer, T[homas] F. Thiselton';
                break;
                            
            case 'Thomas Keightley':
                $textContent = 'Keightley, Thomas';
                break;
                

                //since I removed dates above, also remove other dates regardless of ambiguity
                
                case 'Blackstone, William (1723–80)':
                    $textContent = 'Blackstone, William';
                    break;
                
                case 'Brooks, Harold F. (1907–90)':
                    $textContent = 'Brooks, Harold F.';
                    break;
                
                case 'Craig, W[illiam] J. (1843–1906)':
                    $textContent = 'Craig, W[illiam] J.';
                    break;
                
                case 'Douce, Francis (1757–1834)':
                    $textContent= 'Douce, Francis';
                    break;
                
                case 'Furnivall, Frederick J. (1825–1910)':
                    $textContent= 'Furnivall, Frederick J.';
                    break;
                
                case 'Henley, Samuel (1740–1815)':
                    $textContent= 'Henley, Samuel';
                    break;
                case 'Ingleby, C[lement] M. (1823–86)':
                    $textContent= 'Ingleby, C[lement] M.';
                    break;
                
                case 'Jervis, Swynfen (1797–1867)':
                    $textContent= 'Jervis, Swynfen';
                    break;
                
                case 'Mason, John Monck (1726–1809)':
                    $textContent= 'Mason, John Monck';
                    break;
                
                
                case 'McKerrow, R[onald] B. (1872–1940)':
                    $textContent= 'McKerrow, R[onald] B.';
                    break;
                
                case 'Nicholson, Brinsley (1824–92)':
                    $textContent= 'Nicholson, Brinsley';
                    break;
                
                case 'Perring, Philip. (1828–1920)':
                    $textContent= 'Perring, Philip.';
                    break;
                
                
                case 'Ritson, Joseph (1752–1803)':
                    $textContent= 'Ritson, Joseph';
                    break;
                
                case 'Spedding, James (1808–81)':
                    $textContent= 'Spedding, James';
                    break;
                
                case 'Staunton, Howard (1810–74)':
                    $textContent= 'Staunton, Howard';
                    break;
                
                
                case 'Seager, H[erbert] W. (1848–?)':
                    $textContent= 'Seager, H[erbert] W.';
                    break;
                
                case 'Tollet, George (1725–79)':
                    $textContent= 'Tollet, George';
                    break;
                
                case 'Tyrwhitt, Thomas (1730–86)':
                    $textContent= 'Tyrwhitt, Thomas';
                    break;
                
                
                case 'Warburton, William (1698–1779)':
                    $textContent= 'Warburton, William';
                    break;
                
                case 'Weston, Stephen (1747–1830)':
                    $textContent= 'Weston, Stephen';
                    break;
                
        }
        
        
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