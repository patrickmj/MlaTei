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
        $titleNodeText = $this->normalizeText($titleNode->textContent, true);
        $authorNodes = $domNode->getElementsByTagName('name');
        $authorsArray = array();
        foreach($authorNodes as $authorNode) {
            $authorsArray[] = array('text'=>$authorNode->textContent, 'html'=>0);
        }
    
        $elSetsArray = array(
                'Dublin Core'=>array(
                        'Title'=>array(array('text'=>$titleNodeText, 'html'=>0)),
                        'Contributor'=>$authorsArray,
                        'Type' => array(array('text'=>'edition', 'html'=>0)),
                        'Date' => array(array('text'=>$this->getFirstChildNodeByName('date', $domNode)->textContent, 'html'=>0))
                )
        );
        if(substr($mlaEl->xml_id,0,2) == 's_') {
            //looks like a witness
            $elSetsArray['Dublin Core']['Type'][] = array('text'=>'witness', 'html'=>0);     
            $elSetsArray['Dublin Core']['Identifier'] = array(array('text'=>$mlaEl->siglum, 'html'=>0));
            $elSetsArray['Item Type Metadata']['Siglum'] = array(array('text'=>$mlaEl->siglum, 'html'=>0));
        }
        $elSetsArray['Item Type Metadata']['Citation'] = array(array('text'=>$mlaEl->html, 'html'=>1));
        
        return insert_item($metadataArray, $elSetsArray);
    }
    
    public function getCommentators($domNode, $mlaEl)
    {
        $contributorNodes = $domNode->getElementsByTagName('name');
        $commentatorItems = array(); //commentators only exist as Omeka Items, w/o a TEI representation
        foreach($contributorNodes as $authorNode) {
            $commentatorItems[] = $this->findCommentatorByNameOrNew($authorNode->textContent);
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
                $textContent = 'Bevington, David';
                break;
            case 'Edmond Malone':
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