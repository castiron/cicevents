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
class Type extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * title
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;


	/**
	 * hideTimes
	 *
	 * @var boolean
	 * @validate NotEmpty
	 */
	protected $hideTimes = FALSE;

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
	 * Returns whether to hide start/end times for events of this type
	 *
	 * @return boolean $hideTimes
	 */
	public function getHideTimes() {
		return $this->hideTimes;
	}

	/**
	 * Sets whether to hide start/end times for events of this type
	 *
	 * @param boolean $hideTimes
	 * @return void
	 */
	public function setHideTimes($hideTimes) {
		$this->hideTimes = $hideTimes;
	}

	/**
	 * Returns the boolean state of hideTimes
	 *
	 * @return boolean
	 */
	public function isHideTimes() {
		return $this->getHideTimes();
	}

}
?>
