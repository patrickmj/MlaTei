<?php

class MlaTeiImporter_CommentaryNote extends MlaTeiImporter
{
    public $xsl = "/component.xsl";
    
    public function importEl($mlaEl, $domNode)
    {
        $labelNode = $this->getFirstChildNodeByName('label', $domNode);        
        $mlaEl->label = $this->normalizeText($labelNode->textContent);
        $targetAtt =  $this->xpath->query("@target", $domNode)->item(0);
        $targetEndAtt =  $this->xpath->query("@targetEnd", $domNode)->item(0);
        $mlaEl->target = $targetAtt->textContent;
        $mlaEl->target_end = $targetEndAtt->textContent;
        $mlaEl = parent::importEl($mlaEl, $domNode);
        
        return $mlaEl;
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
        $citedInCommentaryNoteId = record_relations_property_id(MLATEINS, 'citedInCommentaryNote');
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl'); 
        $refsSpeechId = record_relations_property_id(MLATEINS, 'refsSpeech');
        $refsStageDirId = record_relations_property_id(MLATEINS, 'refsStageDirection');
        
        $biblRefs = $this->xpath->query(".//nvs:ref[@targType='bibl']", $domNode);
        $lbRefs = $this->xpath->query(".//nvs:ref[@targType='lb']", $domNode);

        
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
                    $commentatorItems = $biblRef->getCommentatorItems();
                    //while I have the biblRef, grab the commentators and build a 'shortcut' relation
                    //depends on the sequence of data import following the order of actions in the controller
          
                    foreach($commentatorItems as $commentator) {
                        $this->buildRelation($commentator, $mlaEl, $citedInCommentaryNoteId);
                    }
                }            
            }
        }
     
        //FIRST get the targets on the domNode, then the targets on the internal lbRefs
        //PROBLEM! domNode target attributes to id like "cast_line_#" instead of "tln_#" 
        // no targType attributes are set on notes!!!!!!
        
        $targets = array();
        $targetAtt = $domNode->getAttribute('target');
        $elTargetsRaw = explode(' ', $targetAtt);
        foreach($elTargetsRaw as $targetRaw) {
            //strip off the #tln_ in xml id references and cast to int to ignore leading 0
            //@TODO: this ignores alternate target types
            $lineNum = (int) substr($targetRaw, 5);
            
            $context = $speechTable->findSurroundingSpeech($lineNum);
            if($context) {
                $targets[] = $context;
            } else {
                //look in the stage directions                
                $context = $stageDirTable->findSurroundingStageDir($lineNum);
                if($context) {
                    $targets[] = $context;
                }                                
            }        
        }

        foreach($lbRefs as $lbRef)
        {
            $targetAtt = $lbRef->getAttribute('target');
            //some targets include more than one reference
            $targetsRaw = explode(' ', $targetAtt);
            foreach($targetsRaw as $targetRaw) {           
                //strip off the #tln_ in xml id references and cast to int to ignore leading 0
                $lineNum = (int) substr($targetRaw, 5);
                $context = $speechTable->findSurroundingSpeech($lineNum);
                if(!$context) {
                    //look in the stage directions
                    $context = $stageDirTable->findSurroundingStageDir($lineNum);                    
                }

                $targets[] = $context;
            }           
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
                if(get_class($target) == 'MlaTeiElement_Speech') {
                    $propId = $commentsOnSpeechId;
                } else {
                    $propId = $commentsOnStageDirId;
                }
                    $this->buildRelation($commentator, $target, $propId);
                }
                if($target->role_id) {
                    if($commentator) {
                        
                        
                        $rel = new RecordRelationsRelation;
                        $rel->subject_id = $commentator->id;
                        $rel->subject_record_type = 'Item';
                        $rel->object_id = $target->role_id;
                        $rel->object_record_type = 'MlaTeiElement_Role';
                        $rel->property_id = $commentsOnCharacterId;
                        $rel->public = true;    
                        try {
                            $rel->save();
                        } catch(Exception $e) {
                            echo get_class($commentator);
                            echo "commentator wtf: " . $commentator;
                            if(!$commentator) {
                                echo 'not comm???';
                            }
                            print_r($commentator->toArray());
                            print_r($rel->toArray());
                            die();
                        }
                    }       
                }
            }                      
    }

    
    public function parseToItem($domNode, $mlaEl)
    {
        //no item for commentary notes
    }

}