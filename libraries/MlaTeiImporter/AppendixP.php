<?php

class MlaTeiImporter_AppendixP extends MlaTeiImporter
{
    public $xsl = "/component.xsl";
    public $debug = array();
    public function parseToItem($domNode, $mlaEl)
    {
        //no item for appendix ps
    }
    
    public function buildRelations($mlaEl, $domNode)
    {
        $db = get_db();
        $speechTable = $db->getTable('MlaTeiElement_Speech');
        $stageDirTable = $db->getTable('MlaTeiElement_StageDir');
        $bibEntryTable = $db->getTable('MlaTeiElement_BibEntry');
        $editionEntryTable = $db->getTable('MlaTeiElement_EditionEntry');
        $commentsOnSpeechId = record_relations_property_id(MLATEINS, 'commentsOnSpeech');
        $commentsOnStageDirId = record_relations_property_id(MLATEINS, 'commentsOnStageDirection');
        $commentsOnCharacterId = record_relations_property_id(MLATEINS, 'commentsOnCharacter');
        $citedInAppendixPId = record_relations_property_id(MLATEINS, 'citedInAppendixP');
        $refsSpeechId = record_relations_property_id(MLATEINS, 'refsSpeech');
        $refsStageDirId = record_relations_property_id(MLATEINS, 'refsStageDirection');        
        
        //for tagging
        $userOne = get_db()->getTable('User')->find(1);
        $tags = $this->getTags($domNode);
        $mlaEl->addTags($tags, $userOne);
        
        $lbRefs = $this->xpath->query("nvs:ref[@targType='lb']", $domNode);        
        //targets go to Speeches or StageDirections
        
        
        //refs to bibEntries
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
        $biblRefs = $this->xpath->query("nvs:ref", $domNode);
        
        foreach($biblRefs as $biblRefNode) {
            $biblXmlRefIdsRaw = $biblRefNode->getAttribute('target');
            //can be multiple target ids
            $biblXmlRefIdsArrayRaw = explode(' ', $biblXmlRefIdsRaw);
            foreach($biblXmlRefIdsArrayRaw as $hashedRefId) {
       
                $biblRef = $bibEntryTable->findByXmlId(substr($hashedRefId, 1));
                

                //try the editions if it isn't in the bibEntries
                if(!$biblRef) {
                    $biblRef = $editionEntryTable->findByXmlId(substr($hashedRefId, 1));
                }                
                if($biblRef) {
                    $this->buildRelation($mlaEl, $biblRef, $refsBiblId);
                    
                    //while I have the biblRef, grab the commentators and build a 'shortcut' relation
                    //depends on the sequence of data import following the order of actions in the controller
                    $commentatorItems = $biblRef->getCommentatorItems();
                   
                    foreach($commentatorItems as $commentator) {
                        $commentator->addTags($tags, $userOne);
                        $this->buildRelation($commentator, $mlaEl, $citedInAppendixPId);
                    }
                }
            }
        }
        

        foreach($lbRefs as $lbRef)
        {
            $targetAtt = $lbRef->getAttribute('target');
            //some targets include more than one reference
            $targetsRaw = explode(' ', $targetAtt);
            $targets = array();
            foreach($targetsRaw as $targetRaw) {
                //strip off the #tln_ in xml id references and cast to int to ignore leading 0
                $lineNum = (int) substr($targetRaw, 5);
                $context = $speechTable->findSurroundingSpeech($lineNum);
                if(!$context) {
                    //look in the stage directions
                    $context = $stageDirTable->findSurroundingStageDir($lineNum);        
                }

                
                if($context) {
                    //I'm missing cases where the lb is on a major division, like an act or a scene
                    $targets[] = $context;
                    
                }
                                
                foreach($targets as $target) {
                    if(get_class($target) == 'MlaTeiElement_Speech') {
                        $propId = $refsSpeechId;
                    } else {
                        $propId = $refsStageDirId;
                    }
                    $this->buildRelation($mlaEl, $target, $propId);
        
                    //grab the commentators for the $mlaElement (CommentaryNote), and
                    //build more shortcuts between the commentator and the context (Speech or StageDir)
        
                    //safe because the importController saves the mlaElement before building relations
                    //
                    $commentatorItems = $mlaEl->getCommentatorItems();
                    foreach($commentatorItems as $commentator) {
                        $commentator->addTags($tags, $userOne);
                        if(get_class($target) == 'MlaTeiElement_Speech') {
                            $propId = $commentsOnSpeechId;
                        } else {
                            $propId = $commentsOnStageDirId;
                        }
                        $this->buildRelation($commentator, $target, $propId);
                    }
                }
            }
        }
    }
    
    public function getTags($domNode)
    {

        //the tags are the preceeding headings in the appendix
        //$headNodes = $this->xpath->query("preceding::nvs:head", $domNode);
                
        $headNodes = $this->xpath->query("ancestor::nvs:div/nvs:head", $domNode);
        $tags = array();
        
        foreach($headNodes as $headNode) {
            $parentNode = $headNode->parentNode;
            //this should clean up most, but not all, the oddities. check the xml
            if($parentNode->nodeName == 'div') {
                $tagString = $headNode->textContent;
                //commas in the text play havoc
                //as does See Map sub elements
                if($tagString != 'Appendix') {
                    $tags[] = $headNode->textContent;
                }
                
            }
        }
        return $tags;        
    }
    
    public function getXmlId($domNode)
    {
        
        //paragraphs in appendix don't have an id, so fake one here
        $position = $this->xpath->evaluate("count(preceding-sibling::nvs:p)", $domNode);
        $parentId = $this->xpath->query("parent::*/@xml:id", $domNode)->item(0);
        $parentIdText = $parentId->textContent;
        $id = "apara_" . $parentIdText . "_$position";

        if(in_array($id, $this->debug)) {
            //echo "pid: $parentId<br/>";
            echo "pid: $parentIdText<br/>";
            echo $id . "<br/>";           
            echo $this->dom->saveXml($domNode); 
            die();
        } else {
            $this->debug[] = $id;
        }
        
        return $id;
        
    }
    
}