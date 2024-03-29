<?php

########################################################################
# Extension Manager/Repository config file for ext "datadisplay".
#
# Auto generated 27-09-2010 16:26
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Data Display Engine (Data Consumer)',
	'description' => 'Display data coming from a Data Provider in the FE, using TypoScript for templating. Acts as a Data Consumer for the Display Controller.',
	'category' => 'fe',
	'author' => 'Francois Suter (Cobweb)',
	'author_email' => 'typo3@cobweb.ch',
	'shy' => '',
	'dependencies' => 'displaycontroller',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'experimental',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.3.1',
	'constraints' => array(
		'depends' => array(
			'displaycontroller' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:15:{s:9:"ChangeLog";s:4:"b93a";s:10:"README.txt";s:4:"5241";s:8:"TODO.txt";s:4:"967d";s:24:"class.tx_datadisplay.php";s:4:"7c3e";s:12:"ext_icon.gif";s:4:"1b66";s:17:"ext_localconf.php";s:4:"beba";s:14:"ext_tables.php";s:4:"ff9f";s:14:"ext_tables.sql";s:4:"31cf";s:15:"flexform_ds.xml";s:4:"bac6";s:16:"locallang_db.xml";s:4:"a322";s:17:"locallang_tca.xml";s:4:"0ffb";s:7:"tca.php";s:4:"5dac";s:36:"res/icons/add_datadisplay_wizard.gif";s:4:"66fc";s:42:"res/icons/icon_tx_datadisplay_displays.gif";s:4:"1b66";s:16:"static/setup.txt";s:4:"c83b";}',
	'suggests' => array(
	),
);

?>