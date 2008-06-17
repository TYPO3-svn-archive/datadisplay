<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types'][$_EXTKEY.'_pi1']['showitem']='CType;;4;button;1-1-1, header;;3;;2-2-2';

t3lib_extMgm::addPlugin(array('LLL:EXT:datadisplay/locallang_db.xml:tt_content.CType_pi1', $_EXTKEY.'_pi1'),'CType');

$tempColumns = Array (
	'tx_datadisplay_tx_datadisplay_query' => Array (		
		'exclude' => 0,
		'label' => 'LLL:EXT:datadisplay/locallang_db.xml:tt_content.tx_datadisplay_tx_datadisplay_query',		
		'config' => Array (
			'type' => 'group',	
			'internal_type' => 'db',	
			'allowed' => 'tx_dataquery_queries',	
			'size' => 1,	
			'minitems' => 1,
			'maxitems' => 1,
		)
	),
	'tx_datadisplay_tx_datadisplay_options' => Array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:datadisplay/locallang_db.xml:tt_content.tx_datadisplay_tx_datadisplay_options',
		'config' => Array (
			'type' => 'flex',
			'ds_pointerField' => 'CType',
			'ds' => array(
				'default' => 'FILE:EXT:datadisplay/flexform_ds.xml'
			)
		)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_datadisplay_tx_datadisplay_query;;;;1-1-1, tx_datadisplay_tx_datadisplay_options',$_EXTKEY.'_pi1');

t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/','Data engine');

//if (TYPO3_MODE == 'BE') $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_datadisplay_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_datadisplay_pi1_wizicon.php';
?>