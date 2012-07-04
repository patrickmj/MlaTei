<?php

class MlaTeiPlugin extends Omeka_Plugin_Abstract {
    
    protected $_hooks = array('install');
    
    
    
    public function hookInstall()
    {
        $db = get_db();
        $sql = "CREATE TABLE IF NOT EXISTS `$db->MlaTeiElement_Role` (                        
                        ";
        $sql .= $this->coreMlaElementSql();
        $sql .= $this->finishSql();
        $db->query($sql);
        
        //first part like coreMlaElementSql, but no item_id
        $sql = "CREATE TABLE IF NOT EXISTS `$db->MlaTeiElement_Speech` ( ";
        $sql .= $this->coreMlaElementSql();
        $sql .= " 

              `role_id` int(10) unsigned NOT NULL,
              `role_xml_id` text COLLATE utf8_unicode_ci NOT NULL,
              `first_line_xml_id` text COLLATE utf8_unicode_ci NOT NULL,
              `last_line_xml_id` text COLLATE utf8_unicode_ci NOT NULL,
              `n` int(10) unsigned NOT NULL,
              `last_n` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;               
        
        ";
        
        $db->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS `$db->MlaTeiElement_StageDir` ( ";
        $sql .= $this->coreMlaElementSql();
        $sql .= "                                
              `first_line_xml_id` text COLLATE utf8_unicode_ci NOT NULL,
              `last_line_xml_id` text COLLATE utf8_unicode_ci NOT NULL,                        
              `n` int(10) unsigned NOT NULL,
              `last_n` int(10) unsigned NOT NULL,     
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;                                            
                        ";
        
        $db->query($sql);        
        
        $sql = "CREATE TABLE IF NOT EXISTS `$db->MlaTeiElement_BibEntry` ( ";
        $sql .= $this->coreMlaElementSql();
        $sql .= $this->finishSql();
        
        $db->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS `$db->MlaTeiElement_CommentaryNote` ( ";
        $sql .= $this->coreMlaElementSql();
        $sql .= " `label` text COLLATE utf8_unicode_ci NOT NULL,";
        $sql .= $this->finishSql();
        
        $db->query($sql);
        

        $sql = "CREATE TABLE IF NOT EXISTS `$db->MlaTeiElement_AppendixP` ( ";
        $sql .= $this->coreMlaElementSql();
        $sql .= $this->finishSql();
        
        $db->query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS `$db->MlaTeiElement_AppendixNote` ( ";
        $sql .= $this->coreMlaElementSql();
        $sql .= " `type` text COLLATE utf8_unicode_ci NULL,";
        $sql .= " `label` text COLLATE utf8_unicode_ci NULL,";
        $sql .= $this->finishSql();
        
        $db->query($sql);
                
        
        //insert some props for RecordRelations
        $properties = array(
                           array(
                            'name' => 'CITO',
                            'description' => '',
                            'namespace_prefix' => 'cito',
                            'namespace_uri' => CITO,
                            'properties' => array(
                                array(
                                    'local_part' => 'cites',
                                    'label' => 'cites',
                                    'description' => "A statement that the citing entity cites the cited entity, either directly and explicitly (as in the reference list of a journal article), indirectly (e.g. by citing a more recent paper by the same group on the same topic), or implicitly (e.g. as in artistic quotations or parodies, or in cases of plagiarism)."
                                    )                                    
                               ),
                           ),
                           array(
                            'name' => 'MLATEI Rels',
                            'description' => 'Relations for MlaTei data',
                            'namespace_prefix' => 'mlatei',
                            'namespace_uri' => MLATEINS,
                            'properties' => array(
                                array(
                                    'local_part' => 'commentsOnSpeech',
                                    'label' => 'comments on speech',
                                    'description' => 'The subject Commentator comments on a Speech in a CommentaryNote '
                                ),
                                array(
                                        'local_part' => 'commentsOnStageDirection',
                                        'label' => 'comments on stage direction',
                                        'description' => 'The subject Commentator comments on a Stage Direction in a CommentaryNote '
                                ),
                                    array(
                                            'local_part' => 'commentsOnCharacter',
                                            'label' => 'comments on character',
                                            'description' => 'The subject Commentator comments on a Character in a CommentaryNote '
                                    ),                                    
                                    array(
                                            'local_part' => 'commentsOnText',
                                            'label' => 'comments on text',
                                            'description' => 'The subject Commentator comments on a Speech in a TextualNote '
                                    ),
                                    array(
                                            'local_part' => 'citedInCommentaryNote',
                                            'label' => 'cited in commentary note',
                                            'description' => 'The subject Item (Commentator) is cited in the object CommentaryNote.'
                                    ),
                                    array(
                                            'local_part' => 'citedInAppendixNote',
                                            'label' => 'cited in appendix note',
                                            'description' => 'The subject Commentator is cited in the object AppendixNote'
                                    ),
                                    array(
                                            'local_part' => 'citedInAppendixP',
                                            'label' => 'cited in appendix p',
                                            'description' => 'The subject Commentator is cited in the object AppendixP'
                                    ),                                                                        
                                    array(
                                            'local_part' => 'citedInTextualNote',
                                            'label' => 'cited in commentary note',
                                            'description' => 'The subject Commentator is cited in the object TextualNote'
                                    ),
                                    array(
                                            'local_part' => 'refsBibl',
                                            'label' => 'refers to bibliography entry',
                                            'description' => 'The subject (CommentaryNote | AppendixP | AppendixNote) refers to a bibliographic entry'
                                    ),
                                    array(
                                            'local_part' => 'refsSpeech',
                                            'label' => 'refers to speech',
                                            'description' => 'The subject CommentaryNote includes a reference to a line contained by the object Speech'
                                    ),
                                    array(
                                            'local_part' => 'refsStageDirection',
                                            'label' => 'refers to stage direction',
                                            'description' => 'The subject CommentaryNote includes a reference to a line contained by the object Stage Direction'
                                    )                                                                                                                                                
                            )
                        )
                  );
        record_relations_install_properties($properties);
    }
    
    private function coreMlaElementSql()
    {
        $sql = "
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `item_id` int(10) unsigned NULL,
              `xml` text COLLATE utf8_unicode_ci NOT NULL,
              `xml_id` text COLLATE utf8_unicode_ci NULL,
              `html` text COLLATE utf8_unicode_ci NULL,
                        ";
        return $sql;
    }
    private function finishSql()
    {
        return "
              PRIMARY KEY (`id`),
              UNIQUE KEY `item_id` (`item_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;                        
                        ";
    }
    
}