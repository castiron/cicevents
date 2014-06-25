<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'EventsListing',
	'CIC Events: List Events'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'EventsAdmin',
	'CIC Events: Administration'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'EventsCalendar',
	'CIC Events: Calendar View'
);

/********************************************************************************************
 *	PLUGIN FLEXFORM CONF
 *******************************************************************************************/
$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignatureListing = strtolower($extensionName) . '_eventslisting';
$pluginSignatureAdmin = strtolower($extensionName) . '_eventsadmin';
$pluginSignatureCal = strtolower($extensionName) . '_eventscalendar';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureListing] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureListing] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignatureListing, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_eventslisting.xml');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureAdmin] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureAdmin] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignatureAdmin, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_eventsadmin.xml');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignatureCal] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureCal] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignatureCal, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_eventscalendar.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'CIC Events');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_event', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_event.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_event');
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
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Event.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_event.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_category', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_category.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_category');
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
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Category.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_category.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_locality', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_locality.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_locality');
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
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Locality.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_locality.gif'
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_type', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_type.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_type');
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
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Type.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_type.gif'
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_occurrence', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_occurrence.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_occurrence');
$TCA['tx_cicevents_domain_model_occurrence'] = array(
	'ctrl' => array(
		'title'	=> 'Occurrence',
		'label' => 'venue',
		'label_userFunc' => 'CIC\Cicevents\Domain\Model\Occurrence->getTCALabel',
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
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Occurrence.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_cicevents_domain_model_occurrence.gif'
	),
);

?>