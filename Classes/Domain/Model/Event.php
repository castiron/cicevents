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
	 * startTime
	 *
	 * @var DateTime
	 * @validate NotEmpty
	 */
	protected $startTime;

	/**
	 * endTime
	 *
	 * @var DateTime
	 * @validate NotEmpty
	 */
	protected $endTime;

	/**
	 * venue
	 *
	 * @validate NotEmpty
	 * @var string
	 */
	protected $venue;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var boolean
	 */
	protected $linkToUrl;

	/**
	 * address
	 *
	 * @validate NotEmpty
	 * @var string
	 */
	protected $address;

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
	 * images
	 *
	 * @var string
	 */
	protected $images;

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
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}


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
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * Sets the startTime
	 *
	 * @param DateTime $startTime
	 * @return void
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * Returns the endTime
	 *
	 * @return DateTime $endTime
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * Sets the endTime
	 *
	 * @param DateTime $endTime
	 * @return void
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}


	public function getSpansMultipleDays() {
		return intval($this->startTime->format('j')) != intval($this->endTime->format('j'));
	}

	public function alreadyHappened() {
		return $this->endTime->format('U') < time();
	}

	public function getIsCurrentlyHappening() {
		return $this->startTime->format('U') < time() && !$this->getIsPast();
	}

	/**
	 * Returns the venue
	 *
	 * @return string $venue
	 */
	public function getVenue() {
		return $this->venue;
	}

	/**
	 * Sets the venue
	 *
	 * @param string $venue
	 * @return void
	 */
	public function setVenue($venue) {
		$this->venue = $venue;
	}

	/**
	 * Returns the address
	 *
	 * @return string $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Sets the address
	 *
	 * @param string $address
	 * @return void
	 */
	public function setAddress($address) {
		$this->address = $address;
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
	 * @return bool
	 */
	public function getHasImage() {
		if($this->getImages()) {
			return true;
		} else {
			return false;
		}
	}

	public function getFirstImage() {
		$images = explode(',',$this->getImages());
		if(count($images)) {
			return 'uploads/tx_cicevents/'.$images[0];
		} else {
			return false;
		}
	}

	/**
	 * @param string $images
	 */
	public function setImages($images) {
		$this->images = $images;
	}

	/**
	 * @return string
	 */
	public function getImages() {
		return $this->images;
	}
}
?>
