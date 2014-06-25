<?php

namespace CIC\Cicevents\Task;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Zach Davis <zach@castironcoding.com>, Cast Iron Coding
 *  Lucas Thurston <lucas@castironcoding.com>, Cast Iron Coding
 *  Gabe Blair <gabe@castironcoding.com>, Cast Iron Coding
 *  Peter Soots <peter@castironcoding.com>, Cast Iron Coding
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
 * Convoluted typoscript example:
 *
 * archiver {
 *	enabled = 1
 *	move {
 *		targetPID = 148
 *		types {
 *			only = 3,5
 *			exclude = 2
 *		}
 *		categories {
 *			only = 23
 *			exclude 32
 *		}
 *	}
 *	delete {
 *		types {
 *			only = 3,5
 *			exclude = 2
 *		}
 *		categories {
 *			only = 23
 *			exclude 32
 *		}
 *	}
 *	hide {
 *		types {
 *			only = 3,5
 *			exclude = 2
 *		}
 *		categories {
 *			only = 23
 *			exclude 32
 *		}
 *	}
 *}
 *
 *
 * Class \CIC\Cicevents\Task\EventArchiver
 */
class EventArchiver extends \CIC\Cicbase\Scheduler\AbstractTask {

	/**
	 * @var \CIC\Cicevents\Domain\Repository\EventRepository
	 */
	protected $eventRepository;

	/**
	 * @var \CIC\Cicevents\Domain\Repository\TypeRepository
	 */
	protected $typeRepository;

	/**
	 * @var \CIC\Cicevents\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * inject the categoryRepository
	 *
	 * @param \CIC\Cicevents\Domain\Repository\CategoryRepository categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(\CIC\Cicevents\Domain\Repository\CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * inject the typeRepository
	 *
	 * @param \CIC\Cicevents\Domain\Repository\TypeRepository typeRepository
	 * @return void
	 */
	public function injectTypeRepository(\CIC\Cicevents\Domain\Repository\TypeRepository $typeRepository) {
		$this->typeRepository = $typeRepository;
	}

	/**
	 * inject the eventRepository
	 *
	 * @param \CIC\Cicevents\Domain\Repository\EventRepository eventRepository
	 * @return void
	 */
	public function injectEventRepository(\CIC\Cicevents\Domain\Repository\EventRepository $eventRepository) {
		$this->eventRepository = $eventRepository;
	}

	protected $onlyTypes = array();
	protected $excludeTypes = array();
	protected $onlyCategories = array();
	protected $excludeCategories = array();



	public function execute() {
		parent::initialize('cicevents', 'EventsListing');

		$conf = $this->settings['archiver'];

		// Quit if archiver is disabled
		if(!$conf['enabled']) return;

		$events = $this->findAllExpired();

		// MOVE
		if($conf['move']) {
			$this->prepareArchiver($conf['move']);

			$moveTarget = $conf['move']['targetPID'];
			if(!$moveTarget)
				throw new \Exception('You must designate a target for archiving events by moving them. Use the targetPID configuration.');

			/** @var $event \CIC\Cicevents\Domain\Model\Event */
			foreach($events as $event) {
				if($this->shouldArchive($event, $conf['move'])) {
					$this->setPID($event, $moveTarget);
				}
			}
		}

		// DELETE
		if($conf['delete']) {
			$this->prepareArchiver($conf['delete']);
			/** @var $event \CIC\Cicevents\Domain\Model\Event */
			foreach($events as $event) {
				if($this->shouldArchive($event, $conf['delete'])) {
					$this->eventRepository->remove($event);
				}
			}
		}

		// HIDE
		if($conf['hide']) {
			$this->prepareArchiver($conf['hide']);
			/** @var $event \CIC\Cicevents\Domain\Model\Event */
			foreach($events as $event) {
				if($this->shouldArchive($event, $conf['hide'])) {
					$event->setHidden(TRUE);
					$this->eventRepository->update($event);
				}
			}
		}

		$this->persistenceManager->persistAll();

		return TRUE;
	}

	/**
	 * Determines if the event should be archived based on the configuration.
	 *
	 * If Type constraints are provided (only or exclude), those are checked first.
	 * If Category constraints are provided, those are checked last.
	 * Thus, if both are provided, type constraints will take precedence and
	 * category constraints may be ignored.
	 *
	 * @param \CIC\Cicevents\Domain\Model\Event $event
	 * @param array $conf
	 * @return bool
	 */
	protected function shouldArchive(\CIC\Cicevents\Domain\Model\Event $event, $conf = array()) {
		$typeUID = $event->getType()->getUid();
		$hasOnlyCategories = count($this->onlyCategories);
		$hasExcludeCategories = count($this->excludeCategories);

		if(count($this->excludeTypes) && in_array($typeUID, $this->excludeTypes)) {
			return FALSE;
		}
		if(count($this->onlyTypes) && !in_array($typeUID, $this->onlyTypes)) {
			return FALSE;
		}

		if($hasOnlyCategories || $hasExcludeCategories) {
			/** @var $category \CIC\Cicevents\Domain\Model\Category */
			foreach($event->getCategories() as $category) {
				$categoryUID = $category->getUid();
				if($hasExcludeCategories && in_array($categoryUID, $this->excludeCategories)) {
					return FALSE;
				}
				if($hasOnlyCategories && in_array($categoryUID, $this->excludeTypes)) {
					return TRUE;
				}
			}
		}

		// If we get this far and a certain category is required,
		// then we haven't found that category yet. So, don't archive.
		if($hasOnlyCategories) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Fill $this->onlyTypes, excludeTypes, onlyCategories, & excludeCategories
	 * for the given configuration.
	 *
	 * @param array $conf
	 */
	protected function prepareArchiver($conf = array()) {
		if(!count($conf)) return;
		$this->onlyTypes = array();
		$this->excludeTypes = array();
		$this->onlyCategories = array();
		$this->excludeCategories = array();
		$this->explodeConf($this->onlyTypes, $this->excludeTypes, $conf['types']);
		$this->explodeConf($this->onlyCategories, $this->excludeCategories, $conf['categories']);
	}

	/**
	 * @param array $conf
	 * @param array $only
	 * @param array $exclude
	 */
	protected function explodeConf(array &$only, array &$exclude, $conf = array()) {
		if(!count($conf)) return;
		if($conf['only']) {
			$only = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$conf['only']);
		}
		if($conf['exclude']) {
			$exclude = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',',$conf['exclude']);
		}
	}

	/**
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	protected function findAllExpired() {
		$query = $this->eventRepository->createQuery();
		$settings = $query->getQuerySettings();
		$settings->setRespectStoragePage(FALSE);
		$query->setQuerySettings($settings);
		$constraint = $query->lessThan('endTime', new \DateTime());
		return $query->matching($constraint)->execute();
	}

	/**
	 * @param \CIC\Cicevents\Domain\Model\Event $event
	 * @param $pid
	 */
	protected function setPID(\CIC\Cicevents\Domain\Model\Event $event, $pid) {
		$table = 'tx_cicevents_domain_model_event';
		$where = 'uid = '. $event->getUid();
		$values = array('pid' => $pid);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, $where, $values);
	}

}