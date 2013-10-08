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
class Tx_Cicevents_Domain_Model_Event extends Tx_Extbase_DomainObject_AbstractEntity {

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
	 * @var Tx_Cicbase_Domain_Model_File
	 */
	protected $image1;

	/**
	 * image2
	 *
	 * @var Tx_Cicbase_Domain_Model_File
	 */
	protected $image2;

	/**
	 * image3
	 *
	 * @var Tx_Cicbase_Domain_Model_File
	 */
	protected $image3;

	/**
	 * categories
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Cicevents_Domain_Model_Category>
	 */
	protected $categories;

	/**
	 * type
	 *
	 * @var Tx_Cicevents_Domain_Model_Type
	 */
	protected $type;

	/**
	 * @var boolean
	 */
	protected $hidden;

	/**
	 * Localities
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Cicevents_Domain_Model_Locality>
	 */
	protected $localities;

	/**
	 * Occurrences
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Cicevents_Domain_Model_Occurrence>
	 */
	protected $occurrences;

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface
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
	 * inject the objectManager
	 *
	 * @param Tx_Extbase_Object_ObjectManagerInterface objectManager
	 * @return void
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager) {
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
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
	}

	/**
	 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->categories = new Tx_Extbase_Persistence_ObjectStorage();
		$this->localities = new Tx_Extbase_Persistence_ObjectStorage();
		$this->occurrences = new Tx_Extbase_Persistence_ObjectStorage();
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
	 * @return DateTime $startTime
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
	 * @param DateTime $startTime
	 * @return void
	 * @deprecated use occurrences
	 */
	public function setStartTime($startTime) {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			$firstOccurrence->setBeginTime($startTime);
		} else {
			$firstOccurrence = $this->objectManager->create('Tx_Cicevents_Domain_Model_Occurrence');
			$firstOccurrence->setBeginTime($startTime);
			$this->occurrences->attach($firstOccurrence);
		}
	}

	/**
	 * Returns the endTime
	 *
	 * @return DateTime $endTime
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
	 * @param DateTime $endTime
	 * @return void
	 * @deprecated use occurrences
	 */
	public function setEndTime($endTime) {
		if($this->occurrences->count()) {
			$this->occurrences->rewind();
			$firstOccurrence = $this->occurrences->current();
			$firstOccurrence->setFinishTime($endTime);
		} else {
			$firstOccurrence = $this->objectManager->create('Tx_Cicevents_Domain_Model_Occurrence');
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
			$firstOccurrence = $this->objectManager->create('Tx_Cicevents_Domain_Model_Occurrence');
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
			$firstOccurrence = $this->objectManager->create('Tx_Cicevents_Domain_Model_Occurrence');
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
	 * @param Tx_Cicevents_Domain_Model_Category $category
	 * @return void
	 */
	public function addCategory(Tx_Cicevents_Domain_Model_Category $category) {
		$this->categories->attach($category);
	}

	/**
	 * Removes a Category
	 *
	 * @param Tx_Cicevents_Domain_Model_Category $categoryToRemove The Category to be removed
	 * @return void
	 */
	public function removeCategory(Tx_Cicevents_Domain_Model_Category $categoryToRemove) {
		$this->categories->detach($categoryToRemove);
	}

	/**
	 * Returns the categories
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Cicevents_Domain_Model_Category> $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @return Tx_Cicevents_Domain_Model_Category
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
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Cicevents_Domain_Model_Category> $categories
	 * @return void
	 */
	public function setCategories(Tx_Extbase_Persistence_ObjectStorage $categories) {
		$this->categories = $categories;
	}

	/**
	 * Returns the type
	 *
	 * @return Tx_Cicevents_Domain_Model_Type $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type
	 *
	 * @param Tx_Cicevents_Domain_Model_Type $type
	 * @return void
	 */
	public function setType(Tx_Cicevents_Domain_Model_Type $type) {
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
	 * @param Tx_Cicbase_Domain_Model_File $image1
	 * @param Tx_Cicbase_Domain_Model_File $image2
	 * @param Tx_Cicbase_Domain_Model_File $image3
	 */
	public function setUserImages(Tx_Cicbase_Domain_Model_File $image1 = null, Tx_Cicbase_Domain_Model_File $image2 = null, Tx_Cicbase_Domain_Model_File $image3 = null) {
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
	 * @param \Tx_Cicbase_Domain_Model_File $image1
	 */
	public function setImage1($image1) {
		$this->image1 = $image1;
	}

	/**
	 * @return \Tx_Cicbase_Domain_Model_File
	 */
	public function getImage1() {
		return $this->image1;
	}

	/**
	 * @param \Tx_Cicbase_Domain_Model_File $image2
	 */
	public function setImage2($image2) {
		$this->image2 = $image2;
	}

	/**
	 * @return \Tx_Cicbase_Domain_Model_File
	 */
	public function getImage2() {
		return $this->image2;
	}

	/**
	 * @param \Tx_Cicbase_Domain_Model_File $image3
	 */
	public function setImage3($image3) {
		$this->image3 = $image3;
	}

	/**
	 * @return \Tx_Cicbase_Domain_Model_File
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
	 * @param Tx_Cicevents_Domain_Model_Locality $locality
	 */
	public function addLocality($locality) {
		$this->localities->attach($locality);
	}

	/**
	 * @return Tx_Cicevents_Domain_Model_Locality
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
	 * @return Tx_Cicevents_Domain_Model_Locality
	 */
	public function getPrimaryLocality() {
		$this->localities->rewind();
		return $this->localities->current();
	}

	/**
	 * @param \Tx_Extbase_Persistence_ObjectStorage $occurrences
	 */
	public function setOccurrences($occurrences) {
		$this->occurrences = $occurrences;
	}

	/**
	 * @return \Tx_Extbase_Persistence_ObjectStorage
	 */
	public function getOccurrences() {
		return $this->occurrences;
	}


	/**
	 * @param Tx_Cicevents_Domain_Model_Occurrence $occurrence
	 */
	public function addOccurrence($occurrence) {
		$this->occurrences->attach($occurrence);
	}


	/**
	 * @param Tx_Cicevents_Domain_Model_Occurrence $occurrence
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
		$now = new DateTime();

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
		$now = new DateTime();

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

}
?>
