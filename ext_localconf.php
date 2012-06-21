<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'EventsListing',
	array(
		'Event' => 'list, detail'),
	// non-cacheable actions
	array('Event' => '')
);


Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'EventsAdmin',
	array(
		'Event' => 'new, create, edit, update, delete'),
	// non-cacheable actions
	array('Event' => 'new, create, update, delete')
);


Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'EventsCalendar',
	array('Event' => 'calendar, detail'),
	// non-cacheable actions
	array('Event' => '')
);


?>