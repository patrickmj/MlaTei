<?php

class MlaTeiImporter_AppendixNote extends MlaTeiImporter
{
    public $xsl = "/component.xsl";
    
    public function importEl($mlaEl, $domNode)
    {
        $mlaEl = parent::importEl($mlaEl, $domNode);
        $labelNode = $this->getFirstChildNodeByName('label', $domNode);        
        $mlaEl->label = preg_replace( '/\s+/', ' ', $labelNode->textContent );
        $mlaEl->type = $domNode->getAttribute('type');
        return $mlaEl;
    }

    public function parseToItem($domNode, $mlaEl)
    {
        //no item for appendix notes
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
        $citedInAppendixNoteId = record_relations_property_id(MLATEINS, 'citedInAppendixNote'); 
        $refsSpeechId = record_relations_property_id(MLATEINS, 'refsSpeech');
        $refsStageDirId = record_relations_property_id(MLATEINS, 'refsStageDirection');
        
        
        //targets go to Speeches or StageDirections
        $targetId = $domNode->getAttribute('target');
        $lineNum = (int) substr($targetId, 5);
        $targets = array();
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
        }        
        
        //refs to bibEntries
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
        $biblRefs = $this->xpath->query(".//nvs:ref[@targType='bibl']", $domNode);   

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
                        $this->buildRelation($commentator, $mlaEl, $citedInAppendixNoteId);
                    }                    
                }

            }
        }        
    }    
    
}