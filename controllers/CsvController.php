<?php

class MlaTei_CsvController extends Omeka_Controller_Action
{
    
    public function init()
    {
        $this->_modelClass = null;
    }
    
    public function notesAction()
    {
        ini_set('max_execution_time', 6000);
        $notes = $this->getTable('MlaTeiElement_CommentaryNote')->findAll();
        $csv = array(array('label' , 
                        'count_commentators', 
                        'count_speeches', 
                        'count_stage_dirs')
                    );
        foreach ($notes as $note) {
            
            
            $commentatorsCount =  mla_count_commentators_for_commentary_note($note);
            $speechesCount = mla_count_speeches_for_note($note);
            $stageDirsCount = mla_count_stagedirs_for_note($note);

            $csv[] = array(
                    $note->label,
                    $commentatorsCount,
                    $speechesCount,
                    $stageDirsCount,
               
                    );
        }
        
        $this->outputFile($csv, 'notes.csv');
    
    }
    
    
    public function commentatorsAction()
    {
        ini_set('max_execution_time', 6000);
        $commentators = $this->getTable('Item')->findBy(array('type'=>'Commentator'));
        $csv = array(array('name', 
                'count_notes',
                'count_appendix_ps',
                'count_appendix_notes',
                'count_bib',
                'count_stage_dirs',
                'count_speeches',
                'count_editions',                
                'count_in_convo'));

        
        foreach($commentators as $commentator) {
            $name = item('Dublin Core', 'Title', array(), $commentator);
            $appendixPCount = mla_count_appendix_ps_for_commentator($commentator);
            $appendixNoteCount = mla_count_appendix_notes_for_commentator($commentator);
            $stageDirCount = mla_count_stagedirections_for_commentator($commentator);
            $speechesCount = mla_count_speeches_for_commentator($commentator);
            $editionCount = mla_count_editions_for_commentator($commentator);
            $inConvo = mla_get_commentators_in_convo_with_commentator($commentator);
            $inConvoCount = count($inConvo);
            $bibCount = mla_count_bibliography_for_commentator($commentator);
            $editionCount = mla_count_editions_for_commentator($commentator);
                        
            $csv[] = array(
                    $name,
                    $appendixPCount,
                    $appendixNoteCount,
                    $bibCount,
                    $stageDirCount,
                    $speechesCount,
                    $editionCount,
                    $inConvoCount
                    );
        }
        $this->outputFile($csv, 'commentators.csv');
    }
    
    public function speechesAction()
    {
        ini_set('max_execution_time', 6000);
        $speeches = $this->getTable('MlaTeiElement_Speech')->findAll();
        $csv = array(array(
                'n', 
                'count_commentators', 
                'count_lines', 
                'text'                
                ));

        foreach($speeches as $speech) {
            $commentatorsOnSpeechCount = mla_count_commentators_for_speech($speech);
            $linesCount = $speech->countLines();
            
            $csv[] = array(
                    $speech->n,
                    $commentatorsOnSpeechCount,
                    $linesCount,
                    $speech->html                    
                    );
            
        }
        $this->outputFile($csv, 'speeches.csv');
    }
    
    public function appendixPsAction()
    {
        $ps = $this->getTable('MlaTeiElement_AppendixP')->findAll();
        $csv = array(array(
                'count_commentators',
                'count_editions',
                'count_bibentries',   
                'count_lines',             
                'html'
        ));        
        foreach($ps as $p) {
            $commentatorsCount = mla_count_commentators_for_discussion($p);
            $editionsCount = mla_count_editions_for_discussion($p);
            $bibEntriesCount = mla_count_bibliography_for_discussion($p);
            $linesCount = $p->countLines();
            $csv[] = array(
                    $commentatorsCount,
                    $editionsCount,
                    $bibEntriesCount,
                    $linesCount,
                    $p->html                    
                    );
        }
        $this->outputFile($csv, 'appendixps.csv');
    }
    
    public function appendixNotesAction()
    {
        
        
    }
    
    public function rolesAction()
    {
        
        
    }
    
    
    private function outputFile($csv, $filename = 'output.csv')
    {

        $fp = fopen(MLA_TEI_PLUGIN_DIR . "/csv/" . $filename, 'w');     
        foreach ($csv as $row) {
            fputcsv($fp, $row);
        }
        
        fclose($fp);
        /*
    	 header("Content-type: text/csv; name='$filename'");
    	 header('Content-Disposition: attachment; filename="' . $filename . '"');
    	 header("Pragma: no-cache");
    	 header("Expires: 0");
    
    	 readfile(MLA_TEI_PLUGIN_DIR . "/csv/" . $filename);
          */  
    }
    
}