<?php

define('MLATEINS', 'http://coe.hackinghthehumanities.org/ns');
define('MLA_TEI_PLUGIN_DIR', dirname(__FILE__));
define('MLA_TEI_FILES_PATH', MLA_TEI_PLUGIN_DIR . '/files/xml');
//define('MLA_TEI_XSL_PATH', MLA_TEI_PLUGIN_DIR . '/files/xsl/tei-xsl-5.59-bak/xml/tei/stylesheet/html');
define('MLA_TEI_XSL_PATH', MLA_TEI_PLUGIN_DIR . '/files/xsl');
define('CITO', "http://purl.org/spar/cito");

include_once('MlaTeiPlugin.php');
include_once(MLA_TEI_PLUGIN_DIR . '/helpers/functions.php');
include_once(MLA_TEI_PLUGIN_DIR . '/helpers/NameNormalizer.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/Role.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/Speech.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/BibEntry.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/EditionEntry.php');
include_once(MLA_TEI_PLUGIN_DIR . '/libraries/MlaTeiImporter/CommentaryNote.php');
$plugin = new MlaTeiPlugin;
$plugin->setup();