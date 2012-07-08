<?php

class MlaTeiImporter_CommentaryNote extends MlaTeiImporter
{
    public $xsl = "/component.xsl";
    
    public function importEl($mlaEl, $domNode)
    {
        $labelNode = $this->getFirstChildNodeByName('label', $domNode);        
        $mlaEl->label = $this->stripWhitespace($labelNode->textContent); 
        $mlaEl = parent::importEl($mlaEl, $domNode);
        return $mlaEl;
    }
    
    public function buildRelations($mlaEl, $domNode)
    {
        $db = get_db();
        $speechTable = $db->getTable('MlaTeiElement_Speech');
        $stageDirTable = $db->getTable('MlaTeiElement_StageDir');
        $bibEntryTable = $db->getTable('MlaTeiElement_BibEntry');
        $commentsOnSpeechId = record_relations_property_id(MLATEINS, 'commentsOnSpeech');
        $commentsOnStageDirId = record_relations_property_id(MLATEINS, 'commentsOnStageDirection');
        $commentsOnCharacterId = record_relations_property_id(MLATEINS, 'commentsOnCharacter'); //@TODO
        $citedInCommentaryNoteId = record_relations_property_id(MLATEINS, 'citedInCommentaryNote'); //@TODO
        $citoCitesId = record_relations_property_id(CITO, 'cites');
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl'); 
        $refsSpeechId = record_relations_property_id(MLATEINS, 'refsSpeech');
        $refsStageDirId = record_relations_property_id(MLATEINS, 'refsStageDirection');
        
        $biblRefs = $this->xpath->query("nvs:p/nvs:ref[@targType='bibl']", $domNode);
        $lbRefs = $this->xpath->query("nvs:p/nvs:ref[@targType='lb']", $domNode);
/*
 * problem ref: <ref targType="bibl"
                    target="#b_muik1957 #b_muik1977">
 */
        
        foreach($biblRefs as $biblRefNode) {
            $biblXmlRefIdsRaw = $biblRefNode->getAttribute('target');
            //can be multiple target ids      
            $biblXmlRefIdsArrayRaw = explode(' ', $biblXmlRefIdsRaw);
            foreach($biblXmlRefIdsArrayRaw as $hashedRefId) {
                
                $biblRef = $bibEntryTable->findByXmlId(substr($hashedRefId, 1));
                $this->buildRelation($mlaEl, $biblRef, $refsBiblId);    
                $commentatorItems = $biblRef->getCommentatorItems();
                //while I have the biblRef, grab the commentators and build a 'shortcut' relation
                //depends on the sequence of data import following the order of actions in the controller
      
                foreach($commentatorItems as $commentator) {
                    $this->buildRelation($commentator, $mlaEl, $citedInCommentaryNoteId);
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
                if(! array_key_exists($context->xml_id, $targets)) {
                    $targets[$context->xml_id] = $context;
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
            }           
        }      
        
    }

    
    public function parseToItem($domNode, $mlaEl)
    {
        //no item for commentary notes
    }

}