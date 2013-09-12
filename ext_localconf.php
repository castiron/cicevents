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
	array('Event' => 'list')
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


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_Cicevents_Task_EventArchiver'] = array(
	'extension' => $_EXTKEY,
	'title' => 'Event Archiver',
	'description' => 'This task will archive expired events according to configurations set in typoscript. Events
	                  can be deleted, hidden, or removed. For each method, you can determine whether an event is
	                  archived based on its type or categories. See the class comments for more information on the
	                  typoscript.',
);

?>