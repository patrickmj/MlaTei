<?php

class MlaTeiImporter_AppendixP extends MlaTeiImporter
{
    
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
        $commentsOnSpeechId = record_relations_property_id(MLATEINS, 'commentsOnSpeech');
        $commentsOnStageDirId = record_relations_property_id(MLATEINS, 'commentsOnStageDirection');
        $commentsOnCharacterId = record_relations_property_id(MLATEINS, 'commentsOnCharacter');
        $citedInAppendixPId = record_relations_property_id(MLATEINS, 'citedInAppendixP');
        
        
        //targets go to Speeches or StageDirections
        
        
        //refs to bibEntries
        $refsBiblId = record_relations_property_id(MLATEINS, 'refsBibl');
        $biblRefs = $this->xpath->query("nvs:ref[@targType='bibl']", $domNode);
        
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
                    $this->buildRelation($commentator, $mlaEl, $citedInAppendixPId);
                }
            }
        }
    }
}