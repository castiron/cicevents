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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_category', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_category.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_category');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_locality', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_locality.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_locality');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_type', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_type.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_type');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_cicevents_domain_model_occurrence', 'EXT:cicevents/Resources/Private/Language/locallang_csh_tx_cicevents_domain_model_occurrence.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_cicevents_domain_model_occurrence');
