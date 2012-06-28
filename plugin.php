<?php

define('MLATEINS', 'http://coe.hackinghthehumanities.org/ns');
define('MLA_TEI_PLUGIN_DIR', dirname(__FILE__));
define('MLA_TEI_FILES_PATH', MLA_TEI_PLUGIN_DIR . '/files/xml');

include_once('MlaTeiPlugin.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/Role.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/Speech.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/BibEntry.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/CommentaryNote.php');
$plugin = new MlaTeiPlugin;
$plugin->setup();