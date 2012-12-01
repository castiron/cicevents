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
	 * @return bool
	 */
	public function getHasImage() {
		return $this->image1 || $this->image2 || $this->image3;
	}

	public function getFirstImage() {
		if($this->image1) return $this->image1;
		if($this->image2) return $this->image2;
		if($this->image3) return $this->image3;

	}

	/**
	 * @param Tx_Cicbase_Domain_Model_File $image1
	 * @param Tx_Cicbase_Domain_Model_File $image2
	 * @param Tx_Cicbase_Domain_Model_File $image3
	 */
	public function setImages(Tx_Cicbase_Domain_Model_File $image1 = null, Tx_Cicbase_Domain_Model_File $image2 = null, Tx_Cicbase_Domain_Model_File $image3 = null) {
		$this->image1 = $image1;
		$this->image2 = $image2;
		$this->image3 = $image3;
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
}
?>
