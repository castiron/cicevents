<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'CIC.' . $_EXTKEY,
	'EventsListing',
	array(
		'Event' => 'list, detail'),
	// non-cacheable actions
	array('Event' => 'list')
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'CIC.' . $_EXTKEY,
	'EventsAdmin',
	array(
		'Event' => 'new, create, edit, update, delete'),
	// non-cacheable actions
	array('Event' => 'new, create, update, delete')
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'CIC.' . $_EXTKEY,
	'EventsCalendar',
	array('Event' => 'calendar, detail'),
	// non-cacheable actions
	array('Event' => '')
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['CIC\Cicevents\Task\EventArchiver'] = array(
	'extension' => $_EXTKEY,
	'title' => 'Event Archiver',
	'description' => 'This task will archive expired events according to configurations set in typoscript. Events
	                  can be deleted, hidden, or removed. For each method, you can determine whether an event is
	                  archived based on its type or categories. See the class comments for more information on the
	                  typoscript.',
);
