<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Francois Suter (Cobweb) <typo3@cobweb.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
*
* $Id: class.tx_datadisplay_pi1.php 3938 2008-06-04 08:39:01Z fsuter $
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('dataquery','class.tx_dataquery_wrapper.php'));

/**
 * Plugin 'Data Displayer' for the 'datadisplay' extension.
 *
 * @author	Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package	TYPO3
 * @subpackage	tx_datadisplay
 */
class tx_datadisplay_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_datadisplay_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_datadisplay_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'datadisplay';	// The extension key.
	var $pi_checkCHash = true;
	var $localconf;
	static $currentIndex = 0;
	static $data;

	/**
	 * This method performs general intialisation tasks
	 *
	 * @param	array		The TS configuration of the plugin
	 *
	 * @return	void
	 */
	function init($conf) {
		$this->localconf = $conf;
		if (!empty($this->cObj->data['tx_datadisplay_tx_datadisplay_options'])) $this->pi_initPIflexForm('tx_datadisplay_tx_datadisplay_options');
	}

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 *
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf) {
//t3lib_div::debug($conf);
		$this->init($conf);

// Get the flexform values

		if (empty($this->cObj->data['tx_datadisplay_tx_datadisplay_options'])) {
			$useSearch = 0;
			$displayIfNoSearch = 0;
		}
		else {
	    	$useSearch = $this->pi_getFFvalue($this->cObj->data['tx_datadisplay_tx_datadisplay_options'], 'use_search', 'sDEF');
	    	$displayIfNoSearch = $this->pi_getFFvalue($this->cObj->data['tx_datadisplay_tx_datadisplay_options'], 'if_no_search', 'sDEF');
		}

// Get the query to be called

		$dataQuery = $this->cObj->data['tx_datadisplay_tx_datadisplay_query'];
		if (empty($dataQuery)) {
		}
		else {

// Set general display flag
// May be set to false on some conditions

			$display = true;

// Get search parameters, if any

			if (isset($this->piVars['search']) && $useSearch == 1) { // but only if useSearch flag is set
				$searchParameters = $this->piVars['search'];
			}
			else {
				$searchParameters = array();

// If there's no search parameters, but the element is supposed to use those parameters
// define what to display depending on the displayIfNoSearch flag (value is either 'all' or 'none')

				if ($useSearch == 1 && $displayIfNoSearch == 'none') {
					$display = false;
				}
			}

// Continue only if display is true

			if ($display) {

// Get the data using standardised Data Query object

				$dataQueryWrapper = t3lib_div::makeInstance('tx_dataquery_wrapper');
				$data = $dataQueryWrapper->getData($dataQuery, $searchParameters);
				self::$data = $data;
//t3lib_div::debug($data);

// Get the name and configuration for the main table

				$maintableName = $dataQueryWrapper->getMainTableName();
				$maintableConf = $this->getConfigForTable($maintableName);
//t3lib_div::debug($maintableConf);

// Display the data
// First set some flags depending on TS template

				$hasFieldConfig = (isset($maintableConf['field.'])) ? true : false;
				$sectionBreak = (empty($maintableConf['section'])) ? '' : $maintableConf['section.']['field'];

// Initialise some values
// Get an instance of tslib_cobj for rendering the data

				$localContent = '';
				$sectionContent = '';
				$oldSectionValue = '';
				$sectionCount = 1;
				$localCObj = t3lib_div::makeInstance('tslib_cObj');

				foreach ($data['records'] as $index => $record) {
					$record['section_count'] = $sectionCount;
					self::$currentIndex = $index;

// Load the local cObj with data from the database

					$localCObj->start($record);

// If sections are activated, check if a new section has started

					if (!empty($sectionBreak)) {
						$newSectionValue = $record[$sectionBreak];

// New section

						if ($newSectionValue != $oldSectionValue) {

// Wrap content from previous section
// Then add section header

							if ($sectionCount > 1) $localContent .= $localCObj->stdWrap($sectionContent,$maintableConf['section.']['content.']);
							$localContent .= $localCObj->stdWrap($newSectionValue,$maintableConf['section.']['header.']);

// Switch section value, reinitialise section content and increase section count

							$oldSectionValue = $newSectionValue;
							$sectionContent = '';
							$sectionCount++;
						}
					}
					$rowContent = '';

// If there's a generic configuration for the fields, loop on all fields and apply configuration to each

					if ($hasFieldConfig) {
						foreach ($record as $field) {
							if ($field == 'section_count') continue; // Ignore special section count field
							$rowContent .= $localCObj->stdWrap($field,$maintableConf['field.']);
						}
					}

// Apply stdWrap to the row (i.e. data record)

					$sectionContent .= $localCObj->stdWrap($rowContent,$maintableConf['row.']);
				}

// If sections were not activated, just take the result from the loop
// Otherwise apply section content stdWrap to last section

				if (empty($sectionBreak)) {
					$localContent = $sectionContent;
				}
				else {
					$localContent .= $localCObj->stdWrap($sectionContent,$maintableConf['section.']['content.']);
				}

// Apply global stdWrap

				$content .= $localCObj->stdWrap($localContent,$maintableConf['allWrap.']);
			}
			else {
				$content = '';
			}
		}

// Wrap the whole result, with baseWrap if defined, else with standard pi_wrapInBaseClass() call

		if (isset($this->localconf['baseWrap.'])) {
			return $this->cObj->stdWrap($content,$this->localconf['baseWrap.']);
		}
		else {
			return $this->pi_wrapInBaseClass($content);
		}
	}

	function sub($content, $conf) {
		$this->init($conf);
//t3lib_div::debug($conf);
		$subContent = '';
		$subConfig = $this->getConfigForTable($conf['name']);
//t3lib_div::debug($subConfig);
		$limit = (isset($subConfig['limit'])) ? $subConfig['limit'] : 0;
		if (isset(self::$data['records'][self::$currentIndex]['subtables'])) {
			foreach (self::$data['records'][self::$currentIndex]['subtables'] as $subtableData) {
				if ($subtableData['name'] == $conf['name']) {
					$theSubtable = $subtableData['records'];
					break;
				}
			}
		}
		if (isset($theSubtable)) {
//t3lib_div::debug($theSubtable);
			$localCObj = t3lib_div::makeInstance('tslib_cObj');
			$counter = 0;
			$hasFieldConfig = (isset($subConfig['field.'])) ? true : false;
			foreach ($theSubtable as $record) {
				$localCObj->start($record);
				$subrowContent = '';

// If there's a generic configuration for the fields, loop on all fields and apply configuration to each

				if ($hasFieldConfig) {
					foreach ($record as $field) {
						if ($field == 'section_count') continue; // Ignore special section count field
						$subrowContent .= $localCObj->stdWrap($field, $subConfig['field.']);
					}
				}
				$subContent .= $localCObj->stdWrap($subrowContent, $subConfig['row.']);
				$counter++;
				if ($limit > 0 && $counter >= $limit) break;
			}

// Apply global stdWrap

			$subContent = $localCObj->stdWrap($subContent, $subConfig['allWrap.']);
		}
		return $subContent;
	}

	/**
	 * This method checks whether a config exists for a given table name
	 * If yes, it returns that config, if not it returns the default one
	 *
	 * @param	string	table name
	 *
	 * @return	array	TS configuration for the rendering of the table
	 */
	function getConfigForTable($tableName) {
		if (isset($this->localconf['configs.'][$tableName.'.'])) {
			return $this->localconf['configs.'][$tableName.'.'];
		}
		elseif (isset($this->localconf['configs.']['default.'])) {
			return $this->localconf['configs.']['default.'];
		}
		else { // Default configuration shouldn't be missing really. Bad boy! TODO: issue error message
			return array();
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/datadisplay/pi1/class.tx_datadisplay_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/datadisplay/pi1/class.tx_datadisplay_pi1.php']);
}

?>