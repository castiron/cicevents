<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'EventsListing',
	'CIC Events: List Events'
);

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'EventsAdmin',
	'CIC Events: Administration'
);

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'EventsCalendar',
	'CIC Events: Calendar View'
);

/********************************************************************************************
 *	PLUGIN FLEXFORM CONF
 *******************************************************************************************/
$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignatureListing = strtolower($extensionName) . '_eventslisting';
$pluginSignatureAdmin = strtolower($extensionName) . '_eventsadmin';
$pluginSignatureCal = strtolower($extensionName) . '_eventscalendar';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureListing] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureListing] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignatureListing, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_eventslisting.xml');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureAdmin] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureAdmin] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignatureAdmin, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_eventsadmin.xml');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureCal] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureCal] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignatureCal, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_eventscalendar.xml');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'CIC Events');

t3lib_extMgm::addLLrefForTCAdescr('tx_cicevents_domain_model_event', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_event.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_cicevents_domain_model_event');
$TCA['tx_cicevents_domain_model_event'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Event.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_event.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_cicevents_domain_model_category', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_category.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_cicevents_domain_model_category');
$TCA['tx_cicevents_domain_model_category'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_category',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Category.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_category.gif'
	),
);

t3lib_extMgm::addLLrefForTCAdescr('tx_cicevents_domain_model_locality', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_locality.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_cicevents_domain_model_locality');
$TCA['tx_cicevents_domain_model_locality'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_locality',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Locality.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_locality.gif'
	),
);


t3lib_extMgm::addLLrefForTCAdescr('tx_cicevents_domain_model_type', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_type.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_cicevents_domain_model_type');
$TCA['tx_cicevents_domain_model_type'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_type',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Type.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_type.gif'
	),
);

?>