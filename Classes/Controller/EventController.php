<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Peter Soots <peter@castironcoding.com>, Cast Iron Coding
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 ***************************************************************/

/**
 *
 *
 * @package cicevents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Cicevents_Controller_EventController extends Tx_Extbase_MVC_Controller_ActionController {

	const DATE_FORMAT = 'm/d/Y h:i a';
	const DATE_FORMAT_FILTERS = 'm/d/Y';

	/**
	 * Determines how many Event records to pull. If 0, then pull all records.
	 *
	 * @var int
	 */
	private $maxCount = 0;

	/**
	 * @var Tx_Cicevents_Domain_Repository_EventRepository
	 */
	private $eventRepository = null;


	/**
	 * @var Tx_Cicevents_Domain_Repository_CategoryRepository
	 */
	private $categoryRepository = null;

	/**
	 * @var Tx_Cicevents_Domain_Repository_TypeRepository
	 */
	private $typeRepository = null;

	/**
	 * Dependency injection of the Event Repository
	 *
	 * @param Tx_Cicevents_Domain_Repository_EventRepository $eventRepository
	 * @return void
	 */
	public function injectEventRepository(Tx_Cicevents_Domain_Repository_EventRepository $eventRepository) {
		$this->eventRepository = $eventRepository;
	}


	/**
	 * Dependency injection of the Category Repository
	 *
	 * @param Tx_Cicevents_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_Cicevents_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}


	/**
	 * Dependency injection of the Type Repository
	 *
	 * @param Tx_Cicevents_Domain_Repository_TypeRepository $typeRepository
	 * @return void
	 */
	public function injectTypeRepository(Tx_Cicevents_Domain_Repository_TypeRepository $typeRepository) {
		$this->typeRepository = $typeRepository;
	}


	/**
	 * Initialize the create action
	 *
	 * @return void
	 */
	public function initializeCreateAction() {
		// start date property mapping
		$this->arguments['event']
				->getPropertyMappingConfiguration()
				->forProperty('startTime')
				->setTypeConverterOption(
			'Tx_Extbase_Property_TypeConverter_DateTimeConverter',
			Tx_Extbase_Property_TypeConverter_DateTimeConverter::CONFIGURATION_DATE_FORMAT,
			self::DATE_FORMAT
		);
		// end date property mapping
		$this->arguments['event']
				->getPropertyMappingConfiguration()
				->forProperty('endTime')
				->setTypeConverterOption(
			'Tx_Extbase_Property_TypeConverter_DateTimeConverter',
			Tx_Extbase_Property_TypeConverter_DateTimeConverter::CONFIGURATION_DATE_FORMAT,
			self::DATE_FORMAT
		);


	}

	public function initializeAction() {
		// handle null arguments... a temporary work-around until I can address it more permanently -ZD
		foreach($this->arguments as $argument) {
			$n = $argument->getName();
			if($this->request->hasArgument($n)) {
				$v = $this->request->getArgument($n);
				if ($argument->getDefaultValue() == NULL
						&& (
								($v == '') ||
										(
												is_array($v)
														&& array_key_exists('__identity', $v)
														&& $v['__identity'] == ''
										)
						)
				) {
					$this->request->setArgument($argument->getName(), null);
				}
			}
		}
	}


	public function calendarAction() {
		$dateFormat = 'D, d M Y H:i:s';// Wed, 09 Aug 1995 00:00:00 // <-- to match JavaScript Date format
		$events = $this->eventRepository->findAll();
		$eventsArray = array();
		foreach($events as $event) {
			$uriBuilder = $this->uriBuilder;
			$uri = $uriBuilder
				->reset()
				->uriFor('detail', array('event' => $event));
			$eventsArray[] = array(
				'title' => $event->getTitle(),
				'start' => $event->getStartTime()->format($dateFormat),
				'end' => $event->getEndTime()->format($dateFormat),
				'url' => $uri
			);
		}
		$this->view->assign('eventData', $eventsArray);
	}


	/**
	 * action list
	 *
	 * @param string $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param string $range
	 */
	public function listAction($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null) {
		// Get form data
		$params = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'range' => intval($range)
		);


		$this->eventRepository->addFilters($params);
		$this->setSettings();
		$events = $this->eventRepository->findAll($this->maxCount);
		$this->view->assign('category',$category);
		$this->view->assign('range',$range);
		$this->view->assign('type', $type);
		$this->view->assign('events', $events);
	}

	/**
	 * action detail
	 *
	 * @param $event
	 * @return void
	 */
	public function detailAction(Tx_Cicevents_Domain_Model_Event $event) {
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		$register = array();
		$register['eventTitle'] = $event->getTitle();
		$cObj->LOAD_REGISTER($register, '');
		$this->view->assign('event', $event);
	}

	public function upcomingAction() {
		$this->setSettings();
		$this->eventRepository->addFilters(array('range' => Tx_Cicevents_Domain_Repository_EventRepository::RANGE_THREE_MONTHS));
		$events = $this->eventRepository->findAll($this->maxCount)->toArray();
		$this->view->assign('events', $events);
	}

	/**
	 * action new
	 *
	 * @return void
	 */
	public function newAction() {
		$originalRequest = $this->request->getOriginalRequest();
		$today = date('m/d/Y', time());
		$startTime = $endTime = $today;
		$startTimeSelectedHour = 8;
		$startTimeSelectedMinute = 0;
		$startTimeSelectedDaypart = 'am';
		$endTimeSelectedHour = 9;
		$endTimeSelectedMinute = 0;
		$endTimeSelectedDaypart = 'am';

		if($originalRequest) {
			$submittedArgs = $originalRequest->getArguments();
			if($submittedArgs['event']) {
				$startTimeUnformatted = $submittedArgs['event']['startTime'];
				list($startTime, $startTimeSelectedHour, $startTimeSelectedMinute, $startTimeSelectedDaypart) = sscanf($startTimeUnformatted, "%s %d:%d %s");
				$endTimeUnformatted = $submittedArgs['event']['endTime'];
				list($endTime, $endTimeSelectedHour, $endTimeSelectedMinute, $endTimeSelectedDaypart) = sscanf($endTimeUnformatted, "%s %d:%d %s");

			}
		}

		$this->view->assign('startTime', $startTime);
		$this->view->assign('endTime', $endTime);

		$this->view->assign('startTimeSelectedHour', $startTimeSelectedHour);
		$this->view->assign('startTimeSelectedMinute', $startTimeSelectedMinute);
		$this->view->assign('startTimeSelectedDaypart', $startTimeSelectedDaypart);

		$this->view->assign('endTimeSelectedHour', $endTimeSelectedHour);
		$this->view->assign('endTimeSelectedMinute', $endTimeSelectedMinute);
		$this->view->assign('endTimeSelectedDaypart', $endTimeSelectedDaypart);

		$hours = array();
		for($i = 1; $i <= 12; ++$i)
			$hours[$i] = $i;
		$this->view->assign('hours', $hours);

		$minutes = array();
		for($j = 0; $j < 60; $j += 15) {
			if($j < 10) {
				$t = "0" . $j;
				$minutes[$t] = $t;
			} else {
				$minutes[$j] = $j;
			}
		}
		$this->view->assign('minutes', $minutes);

		if($this->settings['maxImages'] == "") {
			$this->view->assign('numImages', 5);
		} else {
			$this->view->assign('numImages', $this->settings['maxImages']);
		}

		$cats = $this->categoryRepository->findAll();
		$types = $this->typeRepository->findAll();
		$this->view->assign('categories', $cats);
		$this->view->assign('types', $types);

	}


	/**
	 * action create
	 *
	 * @param Tx_Cicevents_Domain_Model_Event $event
	 */
	public function createAction(Tx_Cicevents_Domain_Model_Event $event) {
		$post = array_values(t3lib_div::_POST());
		$post = $post[0];
		foreach($post as $key => $val) {
			if(strpos($key, 'categories') !== false) {
				if(strcmp("", $val) == 0)
					continue;
				$cat = $this->categoryRepository->findByUid(intval($val));
				$event->addCategory($cat);
			}
		}
		$this->eventRepository->add($event);
	}


	/**
	 * Prepare the backend settings to be used in the templates
	 */
	private function setSettings() {

		// Needed for the filters form
		$this->view->assign('categories', $this->categoryRepository->findAll());
		$this->view->assign('types', $this->typeRepository->findAll());

		$args = $this->request->getArguments();
		$this->view->assign('location', $args['location']);

		if($args['date']) {
			if(is_object($args['date'])) {
				$date = $args['date']->format($this::DATE_FORMAT_FILTERS);
			} else {
				$date = $args['date'];
			}
			$this->view->assign('dateValue', $date);
		} else {
			$date = date($this::DATE_FORMAT_FILTERS);
		}
		$this->view->assign('date',$date);
		$this->view->assign('type', $args['type']);
		$this->view->assign('category', $args['category']);


		// If the user didn't specify a maxCount or the user turned on pagination,
		// then retrieve all events (maxCount = 0, will do that).
		if(!$this->settings['max'] || $this->settings['pagination']){

			// Get all events
			$this->maxCount = 0;

			// Set the event limit for pages
			if($this->settings['pagination']) {
			 	if($this->settings['max']) {
					$this->view->assign('eventsPerPage', $this->settings['max']);
				} else {
					$this->view->assign('eventsPerPage', 10);
				}
			}
		} else {
			$this->maxCount = (int) $this->settings['max'];
		}
	}

	/**
	 * Grabs string values from the locallang.xml file.
	 *
	 * @static
	 * @param string $string The name of the key in the locallang.xml file.
	 * @return string The value of that key
	 */
	protected static function translate($string) {
		return htmlspecialchars(Tx_Extbase_Utility_Localization::translate('tx_cicevents_domain_model_event.'.$string, 'cicevents'));
	}


}
?>