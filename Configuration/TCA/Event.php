<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_cicevents_domain_model_event'] = array(
	'ctrl' => $TCA['tx_cicevents_domain_model_event']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, type,title, start_time, end_time, url, link_to_url, venue, address, teaser, description, categories',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1,type, title, start_time, end_time, url, link_to_url, venue, address, teaser, description,--div--;Categories, categories, --div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access, starttime, endtime'),
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
		'start_time' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.startTime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime,required',
				'checkbox' => 0,
				'default' => 0,
			),
		),
		'end_time' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.endTime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime,required',
				'checkbox' => 0,
				'default' => 0,
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
		'venue' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.venue',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'address' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.address',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
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
						'script' => 'wizard_rte.php',
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
						'script' => 'wizard_edit.php',
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
						'script' => 'wizard_add.php',
					),
				),
			),
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_event.type',
			'config' => array(
				'type' => 'select',
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
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
	),
);

?>
