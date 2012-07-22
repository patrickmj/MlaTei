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
                    //skip the usual $importer->importEl to get the correct id
                    $mlaEl->xml = $importer->dom->saveXML($role);
                    $mlaEl->xml_id = $role->getAttribute('xml:id'); 
                    $mlaEl->html = $importer->processXSL($role);
                    $item = $importer->parseToItem($role, $mlaEl);
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
        ini_set('max_execution_time', 6000);
        $importer = new MlaTeiImporter_BibEntry(MLA_TEI_FILES_PATH . '/coe_bibliography.xml');
        //there are bibls in the bibls, so use XPath
        $nodes = $importer->xpath->query('//nvs:listBibl/nvs:bibl');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_BibEntry();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
            //build the authors, and some relationships
            $commentatorItems = $importer->getCommentators($node, $mlaEl);
            $citoCitesId = record_relations_property_id(CITO, 'cites');
            foreach($commentatorItems as $commentator) {
                $rel = $importer->buildRelation($mlaEl, $commentator, $citoCitesId);
                $rel->save();
            }            
        }        
    }

    public function witnessBibImportAction()
    {
        ini_set('max_execution_time', 6000);
        $importer = new MlaTeiImporter_WitnessBibEntry(MLA_TEI_FILES_PATH . '/coe_front.xml');
        //there are bibls in the bibls, so use XPath
        $nodes = $importer->xpath->query('//nvs:bibl');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_BibEntry();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
            //build the authors, and some relationships
            $commentatorItems = $importer->getCommentators($node, $mlaEl);
            $citoCitesId = record_relations_property_id(CITO, 'cites');
            foreach($commentatorItems as $commentator) {
                $rel = $importer->buildRelation($mlaEl, $commentator, $citoCitesId);
                $rel->save();
            }
        }        
        
    }
    
    
    public function commentaryImportAction()
    {
        ini_set('max_execution_time', 6000);
        $importer = new MlaTeiImporter_CommentaryNote(MLA_TEI_FILES_PATH . '/coe_commentary.xml');
        $nodes = $importer->dom->getElementsByTagName('note');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_CommentaryNote();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
            $importer->buildRelations($mlaEl, $node);                        
        }
    }
    
    public function appendixPImportAction()
    {
        ini_set('max_execution_time', 6000);
        $importer = new MlaTeiImporter_AppendixP(MLA_TEI_FILES_PATH . '/coe_appendix.xml');
        $nodes = $importer->dom->getElementsByTagName('p');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_AppendixP();
            $mlaEl = $importer->importEl($mlaEl, $node);
            $mlaEl->save();
            $importer->buildRelations($mlaEl, $node);
        }        
    }

    public function appendixNoteImportAction()
    {
        ini_set('max_execution_time', 6000);
        $importer = new MlaTeiImporter_AppendixNote(MLA_TEI_FILES_PATH . '/coe_appendix.xml');
        $nodes = $importer->xpath->query('//nvs:div/nvs:note');
        foreach($nodes as $node) {
            $mlaEl = new MlaTeiElement_AppendixNote();
            $mlaEl = $importer->importEl($mlaEl, $node);
            try {
                $mlaEl->save();
            } catch (Exception $e) {
                echo $e;
                die();
            }            
            $importer->buildRelations($mlaEl, $node);
        }
    }
    
}