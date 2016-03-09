<?php
namespace CIC\Cicevents\Domain\Model;

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
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * title
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var boolean
	 */
	protected $linkToUrl;

	/**
	 * @var boolean
	 */
	protected $linkToUrlTarget;

	/**
	 * teaser
	 *
	 * @var string
	 */
	protected $teaser;

	/**
	 * description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $images;

	/**
	 * image1
	 *
	 * @var \CIC\Cicbase\Domain\Model\File
	 */
	protected $image1;

	/**
	 * image2
	 *
	 * @var \CIC\Cicbase\Domain\Model\File
	 */
	protected $image2;

	/**
	 * image3
	 *
	 * @var \CIC\Cicbase\Domain\Model\File
	 */
	protected $image3;

	/**
	 * categories
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CIC\Cicevents\Domain\Model\Category>
	 */
	protected $categories;

	/**
	 * type
	 *
	 * @var \CIC\Cicevents\Domain\Model\Type
	 */
	protected $type;

	/**
	 * @var boolean
	 */
	protected $hidden;

	/**
	 * Localities
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CIC\Cicevents\Domain\Model\Locality>
	 */
	protected $localities;

	/**
	 * Occurrences
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CIC\Cicevents\Domain\Model\Occurrence>
	 */
	protected $occurrences;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var boolean
	 */
	protected $ongoing;

	/**
	 * @var boolean
	 */
	protected $tbd;

	/**
	 * Generic int so we can use this with multiple fe_user types
	 * @var integer
	 */
	protected $submitterFeuser;

	/**
	 * A string, formatted like "Lucas Thurston <lucas@thurson.name>"
	 * @var string
	 */
	protected $submitterFeuserEmail;

	/**
	 * inject the objectManager
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 *
	 */
	public function initializeObject() {
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
	}

	/**
	 * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->localities = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->occurrences = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the startTime
	 *
	 * @return \DateTime $startTime
	 * @deprecated use occurrences
	 */
	public function getStartTime() {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			return $firstOccurrence->getBeginTime();
		}
		return NULL;
	}

	/**
	 * Sets the startTime
	 *
	 * @param \DateTime $startTime
	 * @return void
	 * @deprecated use occurrences
	 */
	public function setStartTime($startTime) {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			$firstOccurrence->setBeginTime($startTime);
		} else {
			$firstOccurrence = $this->objectManager->create('CIC\Cicevents\Domain\Model\Occurrence');
			$firstOccurrence->setBeginTime($startTime);
			$this->occurrences->attach($firstOccurrence);
		}
	}

	/**
	 * Returns the endTime
	 *
	 * @return \DateTime $endTime
	 * @deprecated use occurrences
	 */
	public function getEndTime() {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			return $firstOccurrence->getFinishTime();
		}
		return NULL;
	}

	/**
	 * Sets the endTime
	 *
	 * @param \DateTime $endTime
	 * @return void
	 * @deprecated use occurrences
	 */
	public function setEndTime($endTime) {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			$firstOccurrence->setFinishTime($endTime);
		} else {
			$firstOccurrence = $this->objectManager->create('CIC\Cicevents\Domain\Model\Occurrence');
			$firstOccurrence->setFinishTime($endTime);
			$this->occurrences->attach($firstOccurrence);
		}
	}


	/**
	 * @return bool
	 * @deprecated use occurrences
	 */
	public function getSpansMultipleDays() {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			return $firstOccurrence->spansMultipleDays();
		}
		return FALSE;
	}

	/**
	 * Also returns FALSE if no end time is found
	 *
	 * @return bool
	 * @deprecated use occurrences
	 */
	public function alreadyHappened() {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			return $firstOccurrence->alreadyHappened();
		}
		return FALSE;
	}

	/**
	 * Also returns FALSE if no start or end time is found
	 *
	 * @return bool
	 * @deprecated use occurrences
	 */
	public function getIsCurrentlyHappening() {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			return $firstOccurrence->currentlyHappening();
		}
		return FALSE;
	}

	/**
	 * Returns the venue
	 *
	 * @return string $venue
	 * @deprecated use occurrences
	 */
	public function getVenue() {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			return $firstOccurrence->getVenue();
		}
		return NULL;
	}

	/**
	 * Sets the venue
	 *
	 * @param string $venue
	 * @return void
	 * @deprecated use occurrences
	 */
	public function setVenue($venue) {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			$firstOccurrence->setVenue($venue);
		} else {
			$firstOccurrence = $this->objectManager->create('CIC\Cicevents\Domain\Model\Occurrence');
			$firstOccurrence->setVenue($venue);
			$this->occurrences->attach($firstOccurrence);
		}
	}

	/**
	 * Returns the address
	 *
	 * @return string $address
	 * @deprecated use occurrences
	 */
	public function getAddress() {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			return $firstOccurrence->getAddress();
		}
		return NULL;
	}

	/**
	 * Sets the address
	 *
	 * @param string $address
	 * @return void
	 * @deprecated use occurrences
	 */
	public function setAddress($address) {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			$firstOccurrence->setAddress($address);
		} else {
			$firstOccurrence = $this->objectManager->create('CIC\Cicevents\Domain\Model\Occurrence');
			$firstOccurrence->setAddress($address);
			$this->occurrences->attach($firstOccurrence);
		}
	}

	/**
	 * Returns the teaser
	 *
	 * @return string $teaser
	 */
	public function getTeaser() {
		return $this->teaser;
	}

	/**
	 * Sets the teaser
	 *
	 * @param string $teaser
	 * @return void
	 */
	public function setTeaser($teaser) {
		$this->teaser = $teaser;
	}

	/**
	 * Returns the description
	 *
	 * @return string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Adds a Category
	 *
	 * @param \CIC\Cicevents\Domain\Model\Category $category
	 * @return void
	 */
	public function addCategory(\CIC\Cicevents\Domain\Model\Category $category) {
		$this->categories->attach($category);
	}

	/**
	 * Removes a Category
	 *
	 * @param \CIC\Cicevents\Domain\Model\Category $categoryToRemove The Category to be removed
	 * @return void
	 */
	public function removeCategory(\CIC\Cicevents\Domain\Model\Category $categoryToRemove) {
		$this->categories->detach($categoryToRemove);
	}

	/**
	 * Returns the categories
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CIC\Cicevents\Domain\Model\Category> $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @return \CIC\Cicevents\Domain\Model\Category
	 */
	public function getPrimaryCategory() {
		$this->categories->rewind();
		return $this->categories->current();
	}

	/**
	 * @return string
	 */
	public function getLinkCssColorStyleDeclaration() {
		$out = '';
		if(
			$this->getCategoryCount()
			&& $style = $this->getPrimaryCategory()->getColorStyleDeclaration()
		) {
			$out = $style;
		}
		return $out;
	}

	/**
	 * @return int
	 */
	public function getCategoryCount() {
		return $this->categories->count();
	}

	/**
	 * Sets the categories
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\CIC\Cicevents\Domain\Model\Category> $categories
	 * @return void
	 */
	public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) {
		$this->categories = $categories;
	}

	/**
	 * Returns the type
	 *
	 * @return \CIC\Cicevents\Domain\Model\Type $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type
	 *
	 * @param \CIC\Cicevents\Domain\Model\Type $type
	 * @return void
	 */
	public function setType(\CIC\Cicevents\Domain\Model\Type $type) {
		$this->type = $type;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param boolean $linkToUrl
	 */
	public function setLinkToUrl($linkToUrl) {
		$this->linkToUrl = $linkToUrl;
	}

	/**
	 * @return boolean
	 */
	public function getLinkToUrl() {
		return $this->linkToUrl;
	}

	/**
	 * @param boolean $linkToUrlTarget
	 */
	public function setLinkToUrlTarget($linkToUrlTarget) {
		$this->linkToUrlTarget = $linkToUrlTarget;
	}

	/**
	 * @return boolean
	 */
	public function getLinkToUrlTarget() {
		return $this->linkToUrlTarget;
	}

	/**
	 * @return bool
	 */
	public function getHasImage() {
		$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cicevents']);
		$result = FALSE;
		if($conf['eventUserImages']) {
			$result = $this->image1 || $this->image2 || $this->image3;
		}
		if($conf['eventAdminImages']) {
			$result = $result || $this->images;
		}
		return $result;
	}

	public function getFirstImage() {
		$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cicevents']);
		if($conf['eventAdminImages']) {
			$images = explode(',', $this->images);
			if(count($images)) {
				return 'uploads/tx_cicevents/'.$images[0];
			}
		}
		if($conf['eventUserImages']) {
			if($this->image1) return $this->image1;
			if($this->image2) return $this->image2;
			if($this->image3) return $this->image3;
		}
		return '';
	}

	/**
	 * @param \CIC\Cicbase\Domain\Model\File $image1
	 * @param \CIC\Cicbase\Domain\Model\File $image2
	 * @param \CIC\Cicbase\Domain\Model\File $image3
	 */
	public function setUserImages(\CIC\Cicbase\Domain\Model\File $image1 = null, \CIC\Cicbase\Domain\Model\File $image2 = null, \CIC\Cicbase\Domain\Model\File $image3 = null) {
		$this->image1 = $image1;
		$this->image2 = $image2;
		$this->image3 = $image3;
	}

	/**
	 * @param string $images
	 */
	public function setImages($images) {
		$this->images = $images;
	}

	/**
	 * @return array
	 */
	public function getImages() {
		return array($this->image1, $this->image2, $this->image3);
	}

	/**
	 * @param \CIC\Cicbase\Domain\Model\File $image1
	 */
	public function setImage1($image1) {
		$this->image1 = $image1;
	}

	/**
	 * @return \CIC\Cicbase\Domain\Model\File
	 */
	public function getImage1() {
		return $this->image1;
	}

	/**
	 * @param \CIC\Cicbase\Domain\Model\File $image2
	 */
	public function setImage2($image2) {
		$this->image2 = $image2;
	}

	/**
	 * @return \CIC\Cicbase\Domain\Model\File
	 */
	public function getImage2() {
		return $this->image2;
	}

	/**
	 * @param \CIC\Cicbase\Domain\Model\File $image3
	 */
	public function setImage3($image3) {
		$this->image3 = $image3;
	}

	/**
	 * @return \CIC\Cicbase\Domain\Model\File
	 */
	public function getImage3() {
		return $this->image3;
	}

	/**
	 * @param boolean $hidden
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * @return boolean
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * @param \CIC\Cicevents\Domain\Model\Locality $locality
	 */
	public function addLocality($locality) {
		$this->localities->attach($locality);
	}

	/**
	 * @return \CIC\Cicevents\Domain\Model\Locality
	 */
	public function getLocalities() {
		return $this->localities;
	}

	/**
	 * @return int
	 */
	public function getLocalityCount() {
		return $this->localities->count();
	}

	/**
	 * @return \CIC\Cicevents\Domain\Model\Locality
	 */
	public function getPrimaryLocality() {
		$this->localities->rewind();
		return $this->localities->current();
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $occurrences
	 */
	public function setOccurrences($occurrences) {
		$this->occurrences = $occurrences;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getOccurrences() {
		return $this->occurrences;
	}


	/**
	 * @param \CIC\Cicevents\Domain\Model\Occurrence $occurrence
	 */
	public function addOccurrence($occurrence) {
		$this->occurrences->attach($occurrence);
	}


	/**
	 * @param \CIC\Cicevents\Domain\Model\Occurrence $occurrence
	 */
	public function removeOccurrence($occurrence) {
		$this->occurrences->detach($occurrence);
	}


	/**
	 * Grabs the occurrence that hasn't happened yet but
	 * will be the next occurrence to happen.
	 */
	public function getSoonestOccurrence() {
		if(!$this->occurrences->count()) {
			return NULL;
		}
		$soonest = NULL;
		$now = new \DateTime();

		// Loop through all occurrences saving the soonest
		// occurrence that is valid and in the future
		foreach($this->occurrences as $occ) {
			$occBegin = $occ->getBeginTime();
			if(!$occBegin || $occBegin < $now) {
				continue;
			}
			if(!$soonest) {
				$soonest = $occ;
				$soonestBegin = $occBegin; // cache the call
			} elseif($occBegin < $soonestBegin) {
				$soonest = $occ;
			}
		}
		return $soonest;
	}


	/**
	 * Grabs the occurrence that has happened and was
	 * the last occurrence to happen.
	 */
	public function getMostRecentOccurrence() {
		if(!$this->occurrences->count()) {
			return NULL;
		}
		$recent = NULL;
		$now = new \DateTime();

		// Loop through all occurrences saving the latest
		// occurrence that is valid and in the past
		foreach($this->occurrences as $occ) {
			$occFinish = $occ->getFinishTime();
			if(!$occFinish || $occFinish > $now) {
				continue;
			}
			if(!$recent) {
				$recent = $occ;
				$recentFinish = $occFinish; // cache the call
			} elseif($occFinish > $recentFinish) {
				$recent = $occ;
			}
		}
		return $recent;
	}

	/**
	 * @return array
	 */
	public function getSortedOccurrences() {
		$occurrences = $this->occurrences->toArray();
		usort($occurrences, function(\CIC\Cicevents\Domain\Model\Occurrence $a, \CIC\Cicevents\Domain\Model\Occurrence $b) {
			return $a->getBeginTime() < $b->getBeginTime() ? -1 : 1;
		});
		return $occurrences;
	}

	/**
	 * @return array
	 */
	public function getSortedOccurrencesReverse() {
		$sorted = $this->getSortedOccurrences();
		return array_reverse($sorted);
	}

	/**
	 * @param boolean $ongoing
	 */
	public function setOngoing($ongoing) {
		$this->ongoing = $ongoing;
	}

	/**
	 * @return boolean
	 */
	public function getOngoing() {
		return $this->ongoing;
	}

	/**
	 * @param boolean $tbd
	 */
	public function setTbd($tbd) {
		$this->tbd = $tbd;
	}

	/**
	 * @return boolean
	 */
	public function getTbd() {
		return $this->tbd;
	}

	/**
	 * Returns the submitter feuser email
	 *
	 * @return string $submitterFeuserEmail
	 */
	public function getSubmitterFeuserEmail() {
		return $this->submitterFeuserEmail;
	}

	/**
	 * Sets the submitter feuser email
	 *
	 * @param string $submitterFeuserEmail
	 * @return void
	 */
	public function setSubmitterFeuserEmail($submitterFeuserEmail) {
		$this->submitterFeuserEmail = $submitterFeuserEmail;
	}

	/**
	 * Returns the submitter feuser id
	 *
	 * @return integer $submitterFeuser
	 */
	public function getSubmitterFeuser() {
		return $this->submitterFeuser;
	}

	/**
	 * Sets the submitter feuser
	 *
	 * @param string $submitterFeuser
	 * @return void
	 */
	public function setSubmitterFeuser($submitterFeuser) {
		$this->submitterFeuser = $submitterFeuser;
	}
}
?>
