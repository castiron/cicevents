<?php

namespace CIC\Cicevents\Controller;

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
use CIC\Cicevents\Domain\Model\Event;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 *
 *
 * @package cicevents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	const DATE_FORMAT = 'm/d/Y h:i a';
	const DATE_FORMAT_FILTERS = 'm/d/Y';

	/**
	 * @var \CIC\Cicevents\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository = null;

	/**
	 * @var \CIC\Cicevents\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository = null;

	/**
	 * @var \CIC\Cicevents\Domain\Repository\LocalityRepository
	 * @inject
	 */
	protected $localityRepository = null;

	/**
	 * @var \CIC\Cicevents\Domain\Repository\TypeRepository
	 * @inject
	 */
	protected $typeRepository = null;

	/**
	 * @var \CIC\Cicbase\Domain\Repository\FileRepository
	 * @inject
	 */
	protected $fileRepository;

	/**
	 * @var \CIC\Cicbase\Service\EmailService
	 * @inject
	 */
	protected $emailService;

	/**
	 * @var \CIC\Cicevents\Domain\Repository\OccurrenceRepository
	 * @inject
	 */
	protected $occurrenceRepository;

	/**
	 * Initialize the create action
	 *
	 * @return void
	 */
	public function initializeCreateAction() {

		$eventArg = $this->arguments['event'];
		$base = $this->validatorResolver->getBaseValidatorConjunction('CIC\Cicevents\Domain\Model\Occurrence');

		$notEmptyValidator = $this->objectManager->get('TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator');
		/** @var \TYPO3\CMS\Extbase\Validation\Validator\GenericObjectValidator $objectValidator */
		$objectValidator = $this->objectManager->get('TYPO3\CMS\Extbase\Validation\Validator\GenericObjectValidator');
		foreach (['beginTime','finishTime', 'venue', 'address'] as $prop) {
			$objectValidator->addPropertyValidator($prop, $notEmptyValidator);
		}
		$base->addValidator($objectValidator);


		$eventConf = $eventArg->getPropertyMappingConfiguration();
		$eventConf->allowCreationForSubProperty("occurrences");
		$eventConf->forProperty("occurrences")
			->allowProperties(0,1,2,3,4,5,6,7,8,9);

		// Allow 10 occurrences to be created when mapping form elements to the Event object
		for($i = 0; $i < 10; ++$i) {

			// And use the DateTime converter for the start/end fields of the Occurrence

			$eventConf->allowCreationForSubProperty("occurrences.$i");
			$eventConf->forProperty("occurrences.$i")
				->allowProperties('beginTime','finishTime','venue','address');

			$eventConf->forProperty("occurrences.$i.beginTime")
				->setTypeConverterOption(
					'TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter',
					\TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
					self::DATE_FORMAT
				);
			$eventConf->forProperty("occurrences.$i.finishTime")
				->setTypeConverterOption(
					'TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter',
					\TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
					self::DATE_FORMAT
				);
		}

		$this->arguments['image1']
			->getPropertyMappingConfiguration()
			->setTypeConverterOption('CIC\Cicbase\Property\TypeConverter\File', 'propertyPath', 'image1');
		$this->arguments['image2']
			->getPropertyMappingConfiguration()
			->setTypeConverterOption('CIC\Cicbase\Property\TypeConverter\File', 'propertyPath', 'image2');
		$this->arguments['image3']
			->getPropertyMappingConfiguration()
			->setTypeConverterOption('CIC\Cicbase\Property\TypeConverter\File', 'propertyPath', 'image3');

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
						($v == '')
						|| (
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

		$occurrences = $this->occurrenceRepository->findAll();

		/** @var \CIC\Cicevents\Domain\Model\Occurrence $occurrence */
		foreach($occurrences as $occurrence) {
			/** @var \CIC\Cicevents\Domain\Model\Event $event */
			$event = $occurrence->getEvent();
			if(!$event || $event->getTbd() || $event->getOngoing()) {
				continue;
			}

			$uriBuilder = $this->uriBuilder->reset();

			if ($this->settings['singlePid']) {
				$uriBuilder->setTargetPageUid($this->settings['singlePid']);
			}

			$uri = $uriBuilder->uriFor('detail', array('event' => $event));

			$eventDetails = array(
				'title' => $event->getTitle(),
				'url' => $uri,
				'colorStyle' => $event->getLinkCssColorStyleDeclaration(),
			);

			$begin = $occurrence->getBeginTime();
			$finish = $occurrence->getFinishTime();
			if($begin instanceof \DateTime) {
				$eventDetails['start'] = $begin->format($dateFormat);
			}
			if($finish instanceof \DateTime) {
				$eventDetails['end'] = $finish->format($dateFormat);
			}
			$eventsArray[] = $eventDetails;
		}
		$this->view->assign('legendCategories', $this->categoryRepository->findAllHavingColor());
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
	 * @param null|\CIC\Cicevents\Domain\Model\Category $category
	 * @param null|\CIC\Cicevents\Domain\Model\Type $type
	 * @param null|\CIC\Cicevents\Domain\Model\Locality $locality
	 * @param int $range
	 * @param int $currentPage
	 * @param bool $minimal
	 */
	public function listAction($location = null, \CIC\Cicevents\Domain\Model\Category $category = null, \CIC\Cicevents\Domain\Model\Type $type = null, \CIC\Cicevents\Domain\Model\Locality $locality = null, $range = null, $currentPage = 1, $minimal = false) {
		if($minimal){
			$this->view->assign('minimal', true);
		}
		$this->listEvents($location, $category, $type, $locality, $range, $currentPage);
	}

	/**
	 * action listMinimal
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|\CIC\Cicevents\Domain\Model\Category $category
	 * @param null|\CIC\Cicevents\Domain\Model\Type $type
	 * @param null|\CIC\Cicevents\Domain\Model\Locality $locality
	 * @param int $range
	 * @param int $currentPage
	 * @throws
	 */
	public function listMinimalAction($location = null, \CIC\Cicevents\Domain\Model\Category $category = null, \CIC\Cicevents\Domain\Model\Type $type = null, \CIC\Cicevents\Domain\Model\Locality $locality = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'locality' => $locality,
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
	 * @param null|\CIC\Cicevents\Domain\Model\Category $category
	 * @param null|\CIC\Cicevents\Domain\Model\Type $type
	 * @param null|\CIC\Cicevents\Domain\Model\Locality $locality
	 * @param int $range
	 * @param int $currentPage
	 * @throws
	 */
	public function pastAction($location = null, \CIC\Cicevents\Domain\Model\Category $category = null, \CIC\Cicevents\Domain\Model\Type $type = null, \CIC\Cicevents\Domain\Model\Locality $locality = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'locality' => $locality,
			'range' => ($range === null ? \CIC\Cicevents\Domain\Repository\EventRepository::RANGE_PAST.'' : $range),
			'currentPage' => $currentPage);
		$this->forward('list', null, null, $args);
	}

	/**
	 * action postminimal
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|\CIC\Cicevents\Domain\Model\Category $category
	 * @param null|\CIC\Cicevents\Domain\Model\Type $type
	 * @param null|\CIC\Cicevents\Domain\Model\Locality $locality
	 * @param int $range
	 * @param int $currentPage
	 * @throws
	 */
	public function pastMinimalAction($location = null, \CIC\Cicevents\Domain\Model\Category $category = null, \CIC\Cicevents\Domain\Model\Type $type = null, \CIC\Cicevents\Domain\Model\Locality $locality = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'locality' => $locality,
			'type' => $type,
			'range' => ($range === null ? \CIC\Cicevents\Domain\Repository\EventRepository::RANGE_PAST.'' : $range),
			'currentPage' => $currentPage,
			'minimal' => true);
		$this->forward('listMinimal', null, null, $args);
	}

	/**
	 * action montAction
	 *
	 * Displays all current events by default.
	 *
	 * @param string $location
	 * @param null|\CIC\Cicevents\Domain\Model\Category $category
	 * @param null|\CIC\Cicevents\Domain\Model\Type $type
	 * @param null|\CIC\Cicevents\Domain\Model\Locality $locality
	 * @param int $range
	 * @param int $currentPage
	 * @throws
	 */
	public function monthAction($location = null, \CIC\Cicevents\Domain\Model\Category $category = null, \CIC\Cicevents\Domain\Model\Type $type = null, \CIC\Cicevents\Domain\Model\Locality $locality = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'locality' => $locality,
			'range' => ($range === null ? \CIC\Cicevents\Domain\Repository\EventRepository::RANGE_THIS_MONTH.'' : $range),
			'currentPage' => $currentPage);
		$this->forward('list', null, null, $args);
	}

	/**
	 * @param string $location
	 * @param null|\CIC\Cicevents\Domain\Model\Category $category
	 * @param null|\CIC\Cicevents\Domain\Model\Type $type
	 * @param null|\CIC\Cicevents\Domain\Model\Locality $locality
	 * @param int $range
	 * @param int $currentPage
	 * @throws
	 */
	public function monthMinimalAction($location = null, \CIC\Cicevents\Domain\Model\Category $category = null, \CIC\Cicevents\Domain\Model\Type $type = null, \CIC\Cicevents\Domain\Model\Locality $locality = null, $range = null, $currentPage = 1) {
		$args = array(
			'location' => $location,
			'category' => $category,
			'type' => $type,
			'locality' => $locality,
			'range' => ($range === null ? \CIC\Cicevents\Domain\Repository\EventRepository::RANGE_THIS_MONTH.'' : $range),
			'currentPage' => $currentPage,
			'minimal' => true);
		$this->forward('listMinimal', null, null, $args);
	}

	/**
	 * action detail
	 *
	 * @ignorevalidation $event
	 * @param $event
	 * @return void
	 */
	public function detailAction(\CIC\Cicevents\Domain\Model\Event $event) {
		$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
		$cObj->cObjGetSingle('LOAD_REGISTER',['eventTitle' => $event->getTitle()]);
		$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cicevents']);
		$this->view->assign('userImagesEnabled', (boolean) $conf['eventUserImages']);
		$this->view->assign('adminImagesEnabled', (boolean) $conf['eventAdminImages']);

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
	 * @param Event $event
	 * @return void
	 */
	public function newAction(Event $event = NULL) {
		if (!$event) {
			$event = $this->objectManager->get('CIC\Cicevents\Domain\Model\Event');
		}

		$originalRequest = $this->request->getOriginalRequest();

		$today = date('m/d/Y', time());
		$startTime = $endTime = $today;

		if($originalRequest) {
			$submittedArgs = $originalRequest->getArguments();
			$originalResults = $this->request->getOriginalRequestMappingResults();

			$results = array();
			foreach ($submittedArgs['event']['occurrences'] as $key => $args) {
				$results[$key] = array('data' => $args);
			}

			$occurrenceErrors = $originalResults->forProperty('event.occurrences')->getSubResults();

			$i = 0; # Hope to god this maps correctly!!
			foreach($occurrenceErrors as $singleOccurrenceErrors) {
				$results[$i]['errors'] = array();
				foreach ($singleOccurrenceErrors->getFlattenedErrors() as $prop => $propErrors) {
					$results[$i]['errors'][$prop] = array();
					foreach ($propErrors as $error) {
						$message = $error->getMessage();
						$code = $error->getCode();
						$localized = LocalizationUtility::translate("form-fieldError-occurrence-$prop-$code", 'cicevents');
						$errorMessage = $localized ? $localized : "$message - $code";
						$results[$i]['errors'][$prop][] = $errorMessage;
					}
				}
				++$i;
			}

			$this->view->assign('bootstrapData', json_encode($results));
		}

		$this->view->assign('startTime', $startTime);
		$this->view->assign('endTime', $endTime);


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
			$this->view->assign('numImages', 3);
		} else {
			$this->view->assign('numImages', $this->settings['maxImages']);
		}

		if($this->request->getOriginalRequest()) {
			$this->view->assign('image1', $this->fileRepository->getHeld('image1'));
			$this->view->assign('image2', $this->fileRepository->getHeld('image2'));
			$this->view->assign('image3', $this->fileRepository->getHeld('image3'));
		} else {
			$this->fileRepository->clearHeld('image1');
			$this->fileRepository->clearHeld('image2');
			$this->fileRepository->clearHeld('image3');
		}

		$cats = $this->categoryRepository->findAll();
		$types = $this->typeRepository->findAll();
		$this->view->assign('categories', $cats);
		$this->view->assign('types', $types);
		$this->view->assign('event', $event);

	}


	/**
	 * @param \CIC\Cicevents\Domain\Model\Event $event
	 * @param \CIC\Cicbase\Domain\Model\File $image1
	 * @param \CIC\Cicbase\Domain\Model\File $image2
	 * @param \CIC\Cicbase\Domain\Model\File $image3
	 * @throws
	 */
	public function createAction(\CIC\Cicevents\Domain\Model\Event $event,
									\CIC\Cicbase\Domain\Model\File $image1 = null,
									\CIC\Cicbase\Domain\Model\File $image2 = null,
									\CIC\Cicbase\Domain\Model\File $image3 = null ) {
		$post = array_values(\TYPO3\CMS\Core\Utility\GeneralUtility::_POST());
		$post = $post[0];
		foreach($post as $key => $val) {
			if(strpos($key, 'categories') !== false) {
				if(strcmp("", $val) == 0)
					continue;
				$cat = $this->categoryRepository->findByUid(intval($val));
				$event->addCategory($cat);
			}
		}
		if($image1) {
			$this->fileRepository->add($image1, 'image1');
		}
		if($image2) {
			$this->fileRepository->add($image2, 'image2');
		}
		if($image3) {
			$this->fileRepository->add($image3, 'image3');
		}
		$event->setUserImages($image1, $image2, $image3);
		$event->setHidden(true);
		$this->eventRepository->add($event);

		$emailSettings = $this->settings['emailNotifications'];
		$senders = array();
		$recipients = array();
		foreach($emailSettings['senders'] as $sender) {
			$senders[$sender['email']] = $sender['name'];
		}
		foreach($emailSettings['recipients'] as $recipient) {
			$recipients[$recipient['email']] = $recipient['name'];
		}

		$this->emailService->sendTemplateEmail($recipients, $senders, $emailSettings['subject'], 'NewEvent.html', array('event' => $event));
	}


	/******************************************
	 Other Functions
	*******************************************/

	/**
	 * @param string $location
	 * @param \CIC\Cicevents\Domain\Model\Category $category
	 * @param \CIC\Cicevents\Domain\Model\Type $type
	 * @param \CIC\Cicevents\Domain\Model\Locality $locality
	 * @param int $range
	 * @param int $currentPage
	 */
	protected function listEvents($location = null, \CIC\Cicevents\Domain\Model\Category $category = null, \CIC\Cicevents\Domain\Model\Type $type = null, \CIC\Cicevents\Domain\Model\Locality $locality = null, $range = null, $currentPage = 1) {
		// Get form data
		$params = array(
			'location' => $location,
			'category' => $category,
			'locality' => $locality,
			'type' => $type,
			'range' => intval($range)
		);
		$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cicevents']);

		$limit = $this->findLimit();
		$offset = $this->findOffset($limit, $currentPage);

		$this->eventRepository->addFilters($params);
		$events = $this->eventRepository->findAll($limit, $offset);
		$allFilteredEvents = $this->eventRepository->findAll();
		$numberOfPages = $limit ? ceil(count($allFilteredEvents) / $limit) : 1;

		$this->setupFiltersForm();
		$this->setupPagination($currentPage, $numberOfPages);

		// View variables
		$this->view->assign('events', $events);
		$this->view->assign('userImagesEnabled', (boolean) $conf['eventUserImages']);
		$this->view->assign('adminImagesEnabled', (boolean) $conf['eventAdminImages']);

		// Maintain form state
		$this->view->assign('category',$category);
		$this->view->assign('range', $range);
		$this->view->assign('type', $type);
		$this->view->assign('locality', $locality);
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
		$this->view->assign('localities', $this->localityRepository->findAll());
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
					$ranges[\CIC\Cicevents\Domain\Repository\EventRepository::RANGE_CURRENT] = 'Upcoming Events';
					break;
				case 'thisMonth':
					$ranges[\CIC\Cicevents\Domain\Repository\EventRepository::RANGE_THIS_MONTH] = 'This Month';
					break;
				case 'nextMonth':
					$ranges[\CIC\Cicevents\Domain\Repository\EventRepository::RANGE_NEXT_MONTH] = 'Next Month';
					break;
				case 'nextThreeMonths':
					$ranges[\CIC\Cicevents\Domain\Repository\EventRepository::RANGE_THREE_MONTHS] = 'Next Three Months';
					break;
				case 'past':
					$ranges[\CIC\Cicevents\Domain\Repository\EventRepository::RANGE_PAST] = 'Past Events';
					break;
				default:
					continue;
			}
		}
		return $ranges;
	}

	/**
	 * A template method for displaying custom error flash messages, or to
	 * display no flash message at all on errors. Override this to customize
	 * the flash message in your action controller.
	 *
	 * @override
	 * @return string|boolean The flash message or FALSE if no flash message should be set
	 * @api
	 */
	protected function getErrorFlashMessage() {
		switch ($this->actionMethodName) {
			case 'createAction':
				return 'Please fix the errors below.';
			default:
				return 'An error occurred while trying to call ' . get_class($this) . '->' . $this->actionMethodName . '()';
		}
	}
}
