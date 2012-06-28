<?php

class MlaTei_ImportController extends Omeka_Controller_Action
{
    
    public function init()
    {
        $this->_modelClass = 'MlaTeiImport';
    }
    
    public function rolesImportAction()
    {
        $importer = new MlaTeiImporter_Role(MLA_TEI_FILES_PATH . '/coe_playtext.xml');
        $nodes = $importer->dom->getElementsByTagName('castItem');
        //misses multiple roles per castItem
        foreach($nodes as $node) {       
            //do some special handling for when castItem contains more than one role
            //actually an interesting trickiness of the represetantation of the text making for messy data
            $roles = $node->getElementsByTagName('role');
            if($roles->length > 1) {
                foreach($roles as $role) {
                    $mlaEl = new MlaTeiElement_Role();                    
                    $mlaEl->xml = $importer->dom->saveXML($role);
                    $mlaEl->xml_id = $role->getAttribute('xml:id');
                    //$mlaEl->html = $this->processXSL($role);
                    $itemMetadata = array('public'=>true);
                    $itemElementTexts = array('Dublin Core'=>array('Title'=>array()));
                    $itemElementTexts['Dublin Core']['Title'][] = array('text'=>$role->textContent, 'html'=>false);
                    $item = insert_item($itemMetadata, $itemElementTexts);
                    $mlaEl->item_id = $item->id;
                    $mlaEl->save();
                }
            } else {
                $mlaEl = new MlaTeiElement_Role();
                $mlaEl = $importer->importEl($mlaEl, $node);
                $mlaEl->save();                
            }
        }        
    }
    
    public function speechImportAction()
    {
        $importer = new MlaTeiImporter_Speech(MLA_TEI_FILES_PATH . '/coe_playtext.xml');        
        $nodes = $importer->dom->getElementsByTagName('sp');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_Speech();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
        }
    }
    
    public function stageDirectionImportAction()
    {
        $importer = new MlaTeiImporter_StageDir(MLA_TEI_FILES_PATH . '/coe_playtext.xml');
        $nodes = $importer->dom->getElementsByTagName('stage');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_StageDir();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
        }        
        
    }
    
    public function bibImportAction()
    {
        ini_set('max_execution_time', 600);
        $importer = new MlaTeiImporter_BibEntry(MLA_TEI_FILES_PATH . '/coe_bibliography.xml');
        //there are bibls in the bibls, so use XPath
        $nodes = $importer->xpath->query('//nvs:listBibl/nvs:bibl');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_BibEntry();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
            //build the authors, and some relationships
            $commentatorItems = $importer->getCommentators($node, $mlaEl);
            $dcContributorPropId = record_relations_property_id(DCTERMS, 'contributor');
            foreach($commentatorItems as $commentator) {
                $rel = $this->buildRelation($commentator, $mlaEl, $dcContributorId);
                $rel->save();
            }            
        }        
    }    
    
    public function commentaryImportAction()
    {
        ini_set('max_execution_time', 600);
        $importer = new MlaTeiImporter_CommentaryNote(MLA_TEI_FILES_PATH . '/coe_commentary.xml');
        $nodes = $importer->dom->getElementsByTagName('note');
        echo "node count: " . $nodes->length;
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_CommentaryNote();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
            $importer->buildRelations($mlaEl, $node);                        
        }
    }
}