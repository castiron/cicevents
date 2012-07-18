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

	/**
	 * initializeAction
	 */
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


	/******************************************
	Events Calendar Actions

	 * calendar

	 ******************************************/

	/**
	 * action calendar
	 *
	 */
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


	/******************************************
	Events Listing Actions

	 * list
	 * listMinimal
	 * past
	 * pastMinimal
	 * month
	 * monthMinimal
	 * detail

	 ******************************************/

	/**
	 * action list
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param string $range
	 * @param int $currentPage
	 * @param bool $minimal
	 */
	public function listAction($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null, $currentPage = 1, $minimal = false) {
		if($minimal){
			$this->view->assign('minimal', true);
		}
		$this->listEvents($location, $category, $type, $range, $currentPage);
	}

	/**
	 * action listMinimal
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param string $range
	 * @param int $currentPage
	 */
	public function listMinimalAction($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'range' => $range,
			'currentPage' => $currentPage,
			'minimal' => true);
		$this->forward('list', null, null, $args);
	}

	/**
	 * action past
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param string $range
	 * @param int $currentPage
	 */
	public function pastAction($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'range' => ($range === null ? Tx_Cicevents_Domain_Repository_EventRepository::RANGE_PAST.'' : $range),
			'currentPage' => $currentPage);
		$this->forward('list', null, null, $args);
	}

	/**
	 * action pastMinimal
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param string $range
	 * @param int $currentPage
	 */
	public function pastMinimalAction($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'range' => ($range === null ? Tx_Cicevents_Domain_Repository_EventRepository::RANGE_PAST.'' : $range),
			'currentPage' => $currentPage,
			'minimal' => true);
		$this->forward('listMinimal', null, null, $args);
	}

	/**
	 * action month
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param string $range
	 * @param int $currentPage
	 */
	public function monthAction($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'range' => ($range === null ? Tx_Cicevents_Domain_Repository_EventRepository::RANGE_THIS_MONTH.'' : $range),
			'currentPage' => $currentPage);
		$this->forward('list', null, null, $args);
	}

	/**
	 * action monthMinimal
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param string $range
	 * @param int $currentPage
	 */
	public function monthMinimalAction($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'range' => ($range === null ? Tx_Cicevents_Domain_Repository_EventRepository::RANGE_THIS_MONTH.'' : $range),
			'currentPage' => $currentPage,
			'minimal' => true);
		$this->forward('listMinimal', null, null, $args);
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

	/******************************************
	 Events Administration Actions

	 * new
	 * create

	 ******************************************/

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


	/******************************************
	 Other Functions
	*******************************************/

	/**
	 * @param null $location
	 * @param null|Tx_Cicevents_Domain_Model_Category $category
	 * @param null|Tx_Cicevents_Domain_Model_Type $type
	 * @param null $range
	 * @param int $currentPage
	 */
	protected function listEvents($location = null, Tx_Cicevents_Domain_Model_Category $category = null, Tx_Cicevents_Domain_Model_Type $type = null, $range = null, $currentPage = 1) {
		// Get form data
		$params = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'range' => intval($range)
		);


		$limit = $this->findLimit();
		$offset = $this->findOffset($limit, $currentPage);

		$this->eventRepository->addFilters($params);
		$events = $this->eventRepository->findAll($limit, $offset);
		$this->view->assign('events', $events);
		$allFilteredEvents = $this->eventRepository->findAll();
		$numberOfPages = ceil($allFilteredEvents->count() / $limit);

		$this->setupFiltersForm();
		$this->setupPagination($currentPage, $numberOfPages);



		// Maintain form state
		$this->view->assign('category',$category);
		$this->view->assign('range',$range);
		$this->view->assign('type', $type);
		$this->view->assign('location', $location);
	}


	/**
	 * @return int
	 */
	protected function findLimit(){
		$limit = 0; // 0 means all, in this case
		if($this->settings['pagination'] && $this->settings['itemsPerPage']){
			$limit = $this->settings['itemsPerPage'];
		} else if($this->settings['max']){
			$limit = $this->settings['max'];
		}
		return intval($limit);
	}

	/**
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @return int
	 */
	protected function findOffset($itemsPerPage, $currentPage){
		if(!$this->settings['pagination'] || $currentPage <= 1){
			return 0;
		}

		return $itemsPerPage * ($currentPage - 1);
	}


	/**
	 * Setup the filters form.
	 */
	protected function setupFiltersForm() {
		if(!$this->settings['filtersOn']){
			return;
		}
		// Parse flexform settings
		$filterSettings = explode(',', $this->settings['filtersArray']);
		foreach($filterSettings as $filter){
			$filters[$filter] = true;
		}

		$this->view->assign('filters', $filters);
		$this->view->assign('categories', $this->categoryRepository->findAll());
		$this->view->assign('types', $this->typeRepository->findAll());
		$this->view->assign('ranges', $this->determineRanges());

	}


	protected function setupPagination($currentPage, $numberOfPages){
		$pagination['currentPage'] = $currentPage;
		$pagination['numberOfPages'] = $numberOfPages;

		if($currentPage < $numberOfPages){
			$pagination['nextPage'] = $currentPage + 1;
		}

		if($currentPage < $numberOfPages - 1){
			$pagination['nextNextPage'] = $currentPage + 2;
		}

		if($currentPage > 1) {
			$pagination['previousPage'] = $currentPage - 1;
		}

		if($currentPage > 2) {
			$pagination['previousPreviousPage'] = $currentPage - 2;
		}

		$this->view->assign('pagination', $pagination);
	}

	/**
	 * The user can specify which ranges are available on the filters form using
	 * the flexform settings. We need to see which ranges were selected and create
	 * an array that can be used in the view.
	 *
	 * @return array
	 */
	protected function determineRanges(){
		// Parse flexform settings
		$rangeSettings = explode(',', $this->settings['rangesArray']);

		$ranges = array();
		foreach($rangeSettings as $setting){
			switch($setting){
				case 'current':
					$ranges[Tx_Cicevents_Domain_Repository_EventRepository::RANGE_CURRENT] = 'Upcoming Events';
					break;
				case 'thisMonth':
					$ranges[Tx_Cicevents_Domain_Repository_EventRepository::RANGE_THIS_MONTH] = 'This Month';
					break;
				case 'nextMonth':
					$ranges[Tx_Cicevents_Domain_Repository_EventRepository::RANGE_NEXT_MONTH] = 'Next Month';
					break;
				case 'nextThreeMonths':
					$ranges[Tx_Cicevents_Domain_Repository_EventRepository::RANGE_THREE_MONTHS] = 'Next Three Months';
					break;
				case 'past':
					$ranges[Tx_Cicevents_Domain_Repository_EventRepository::RANGE_PAST] = 'Past Events';
					break;
				default:
					continue;
			}
		}
		return $ranges;
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