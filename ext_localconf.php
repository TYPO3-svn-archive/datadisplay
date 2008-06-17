<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_datadisplay_pi1.php','_pi1','CType',1);

t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/','Data display engine');
?>