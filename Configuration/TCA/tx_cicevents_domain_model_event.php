<?php

defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_cicevents_domain_model_event');

$tx_cicevents_domain_model_event = array(
	'ctrl' => array(
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
			'iconfile' => 'EXT:cicevents/Resources/Public/Icons/tx_cicevents_domain_model_event.gif'
		),
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, type,title, url, link_to_url, link_to_url_target, localities, teaser, description, categories, occurrences, ongoing, tbd',
	),
	'types' => array(
		'1' => array('showitem' => 'type, title, url, link_to_url, link_to_url_target, ongoing, localities, tbd, occurrences, teaser, description,--div--;Categories, categories, --div--;Access, sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_cicevents_domain_model_event',
				'foreign_table_where' => 'AND tx_cicevents_domain_model_event.pid=###CURRENT_PID### AND tx_cicevents_domain_model_event.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'ongoing' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.ongoing',
			'config' => array(
				'type' => 'check',
			),
		),
		'tbd' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.tbd',
			'config' => array(
				'type' => 'check',
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'localities' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.locality',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_cicevents_domain_model_locality',
				'MM' => 'tx_cicevents_domain_model_event_locality_mm',
				'size' => 3,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'type' => 'popup',
						'title' => 'Edit',
						'module' => array(
							'name' => 'wizard_edit'
						),
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
					),
					'add' => Array(
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'fe_users',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
						'module' => array(
							'wizard_add'
						),
					),
				),
			),
		),
		'occurrences' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.occurrences',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_cicevents_domain_model_occurrence',
				'foreign_field' => 'event',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
				'multiple' => 0,
			),
		),
		'teaser' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.teaser',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.description',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'wizard_rte2.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'module' => array(
							'wizard_rte'
						),
						'title' => 'LLL:EXT:cms/locallang_ttc.xml:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
			'defaultExtras' => 'richtext[]',
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'categories' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.category',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_cicevents_domain_model_category',
				'MM' => 'tx_cicevents_domain_model_event_category_mm',
				'size' => 5,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'type' => 'popup',
						'title' => 'Edit',
						'module' => array(
							'wizard_edit'
						),
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
					),
					'add' => Array(
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'fe_users',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
						'module' => array(
							'wizard_add'
						),
					),
				),
			),
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.type',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_cicevents_domain_model_type',
				'minitems' => 0,
				'maxitems' => 1,
				'appearance' => array(
					'collapse' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'link_to_url' => array(
			'exclude' => 1,
			'label' => 'Link Directly to Event URL?',
			'config' => array(
				'type' => 'check',
			),
		),
		'link_to_url_target' => array(
			'exclude' => 1,
			'label' => 'Open Event URL in same tab?',
			'config' => array(
				'type' => 'check',
			),
		),
		'url' => array(
			'exclude' => 0,
			'label' => 'URL',
			'config' => array(
				'type' => 'input',
				'size' => '15',
				'max' => '255',
				'checkbox' => '',
				'eval' => 'trim',
				'wizards' => array(
					'_PADDING' => 2,
					'link' => array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'file'
							),
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'image1' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.image1',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_cicbase_domain_model_file',
				'size' => '1',
				'minitems' => 0,
				'maxitems' => 1,
				'items' => array('' => ''),
				'appearance' => array(
					'collapse' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'image2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.image2',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_cicbase_domain_model_file',
				'size' => '1',
				'minitems' => 0,
				'maxitems' => 1,
				'items' => array('' => ''),
				'appearance' => array(
					'collapse' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'image3' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.image3',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_cicbase_domain_model_file',
				'size' => '1',
				'minitems' => 0,
				'maxitems' => 1,
				'items' => array('' => ''),
				'appearance' => array(
					'collapse' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'images' => Array (
			"exclude" => 0,
			'label' => 'Images',
			"config" => Array(
				'type' => 'group',
				'uploadfolder' => 'uploads/tx_cicevents',
				'show_thumbs' => true,
				'internal_type' => 'file',
				'allowed' => 'gif,jpg,jpeg,tif,tiff,bmp,pcx,tga,png,pdf,ai',
				'max_size' => 10000,
				'size' => 6,
				'maxitems' => 20,
				'minitems' => 0,
				'autoSizeMax' => 30
			)
		),
	),
);
$confVars = @unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cicevents']);
if($confVars['teaserRTE']) {
	$TCA['tx_cicevents_domain_model_event']['columns']['teaser'] = array(
	'exclude' => 0,
	'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.teaser',
	'config' => array(
		'type' => 'text',
		'cols' => 40,
		'rows' => 15,
		'eval' => 'trim',
		'wizards' => array(
			'RTE' => array(
				'icon' => 'wizard_rte2.gif',
				'notNewRecords'=> 1,
				'RTEonly' => 1,
				'module' => array(
					'wizard_rte'
				),
				'title' => 'LLL:EXT:cms/locallang_ttc.xml:bodytext.W.RTE',
				'type' => 'script'
			)
		)
	),
	'defaultExtras' => 'richtext[]',
);
}

if($confVars['eventUserImages'] && $confVars['eventAdminImages']) {
	$tx_cicevents_domain_model_event['interface']['showRecordFieldList'] .= ', images, image1, image2, image3';
	$tx_cicevents_domain_model_event['types']['1']['showitem'] .= ',--div--;Images, images,--div--;User Images, image1, image2, image3';
} else if($confVars['eventUserImages']) {
	$tx_cicevents_domain_model_event['interface']['showRecordFieldList'] .= ',image1, image2, image3';
	$tx_cicevents_domain_model_event['types']['1']['showitem'] .= ',--div--;User Images, image1, image2, image3';
} else if($confVars['eventAdminImages']) {
	$tx_cicevents_domain_model_event['interface']['showRecordFieldList'] .= ',images';
	$tx_cicevents_domain_model_event['types']['1']['showitem'] .= ',--div--;Images, images';
}

return $tx_cicevents_domain_model_event;
