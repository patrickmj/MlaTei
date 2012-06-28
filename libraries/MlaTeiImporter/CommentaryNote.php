<?php

class MlaTeiImporter_CommentaryNote extends MlaTeiImporter
{

    
    public function importEl($mlaEl, $domNode)
    {
        $labelNode = $this->getFirstChildNodeByName('label', $domNode);
        $mlaEl->label = $labelNode->textContent;
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
        $commentsOnCharacterId = record_relations_property_id(MLATEINS, 'commentsOnCharacter');        
        $citedInCommentaryNoteId = record_relations_property_id(MLATEINS, 'citedInCommentaryNote');
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
        $refsSpeechId = record_relations_property_id(MLATEINS, 'refsSpeech');
        
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
                $speech = $speechTable->findSurroundingSpeech($lineNum);
                if(!$speech) {
                    //look in the stage directions
                    $speech = $stageDirTable->findSurroundingStageDir($lineNum);
                    
                }
                if(! array_key_exists($speech->xml_id, $targets)) {
                    $targets[$speech->xml_id] = $speech;
                }
                foreach($targets as $targetSpeech) {
                    $this->buildRelation($mlaEl, $targetSpeech, $refsSpeechId);
                }
            }           
        }      
        
    }

    
    public function parseToItem($domNode, $mlaEl)
    {
        $commentaryNoteType = get_db()->getTable('ItemType')->findByName('Commentary Note');
        $elements = array('Dublin Core'=>array(
                                'Title'=>array(array('text'=>$mlaEl->label, 'html'=>false))
                
                ));
        return insert_item(array('public'=>true, 'item_type_id'=>$commentaryNoteType->id), $elements);
    }
    

    public function processXSL($domNode) {}

}