<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_cicevents_domain_model_occurrence'] = array(
	'ctrl' => $TCA['tx_cicevents_domain_model_occurrence']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, begin_time, finish_time, venue, address, directions',
	),
	'types' => array(
		'1' => array('showitem' => '--palette--;When;when_palette, --palette--;Where;where_palette, --div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access, hidden;;1, starttime, endtime'),
	),
	'palettes' => array(
		'when_palette' => array('showitem' => 'begin_time, finish_time', 'canNotCollapse' => TRUE),
		'where_palette' => array('showitem' => 'venue, --linebreak--, address, --linebreak--, directions', 'canNotCollapse' => TRUE),
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
				'foreign_table' => 'tx_cicevents_domain_model_occurrence',
				'foreign_table_where' => 'AND tx_cicevents_domain_model_occurrence.pid=###CURRENT_PID### AND tx_cicevents_domain_model_occurrence.sys_language_uid IN (-1,0)',
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
		'begin_time' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_occurrence.begin_time',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime, required',
				'checkbox' => 0,
				'default' => 0,
			),
		),
		'finish_time' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_occurrence.finish_time',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
			),
		),
		'venue' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_occurrence.venue',
			'config' => array(
				'type' => 'input',
				'size' => 38,
				'eval' => 'trim, required'
			),
		),
		'address' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_occurrence.address',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 2,
				'eval' => 'trim'
			),
		),
		'directions' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:cicevents/Resources/Private/Language/locallang_db.xml:tx_cicevents_domain_model_occurrence.directions',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 3,
				'eval' => 'trim'
			),
		),
	),
);

?>
