<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Cast Iron Coding
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
class Tx_Cicevents_Domain_Model_Occurrence extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * @var DateTime
	 */
	protected $beginTime;


	/**
	 * @var DateTime
	 */
	protected $finishTime;


	/**
	 * @var string
	 */
	protected $venue;


	/**
	 * @var string
	 */
	protected $address;


	/**
	 * @var string
	 */
	protected $directions;

	/**
	 * @var Tx_Cicevents_Domain_Model_Event
	 */
	protected $event;

	/**
	 * @param String $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * @return String
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param \DateTime $beginTime
	 */
	public function setBeginTime($beginTime) {
		$this->beginTime = $beginTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getBeginTime() {
		return $this->beginTime;
	}

	/**
	 * @param String $directions
	 */
	public function setDirections($directions) {
		$this->directions = $directions;
	}

	/**
	 * @return String
	 */
	public function getDirections() {
		return $this->directions;
	}

	/**
	 * @param \DateTime $finishTime
	 */
	public function setFinishTime($finishTime) {
		$this->finishTime = $finishTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getFinishTime() {
		return $this->finishTime;
	}

	/**
	 * @param String $venue
	 */
	public function setVenue($venue) {
		$this->venue = $venue;
	}

	/**
	 * @return String
	 */
	public function getVenue() {
		return $this->venue;
	}

	/**
	 * TCA label for occurrence records to look like:
	 *
	 * 'Coliseum (Oct 11 @ 11:16 am)'
	 *
	 * @param array $params
	 * @param $parent
	 */
	public function getTCALabel(array &$params, $parent) {
		$occ = t3lib_BEfunc::getRecord($params['table'], $params['row']['uid']);
		if(!$occ) return;
		$format = "M j @ g:i a";
		$venue = $occ['venue'];
		$start = date($format, $occ['begin_time']);

		$params['title'] = "$venue ($start)";
	}

	/**
	 * @param \Tx_Cicevents_Domain_Model_Event $event
	 */
	public function setEvent($event) {
		$this->event = $event;
	}

	/**
	 * @return \Tx_Cicevents_Domain_Model_Event
	 */
	public function getEvent() {
		return $this->event;
	}


	/**
	 * @return bool
	 */
	public function spansMultipleDays() {
		if($this->beginTime instanceof DateTime && $this->finishTime instanceof DateTime) {
			return $this->beginTime->format('Y m d') == $this->finishTime->format('Y m d');
		}
		return FALSE;
	}

	/**
	 * @return bool
	 */
	public function alreadyHappened() {
		if($this->finishTime instanceof DateTime) {
			return $this->finishTime < new DateTime();
		}
		return FALSE;
	}

	/**
	 * @return bool
	 */
	public function currentlyHappening() {
		if($this->beginTime instanceof DateTime && $this->finishTime instanceof DateTime) {
			$now = new DateTime();
			return $this->beginTime < $now && $this->finishTime > $now;
		}
		return FALSE;
	}

	/**
	 * @return bool
	 */
	public function getSpansMultipleDays() { return $this->spansMultipleDays(); }

	/**
	 * @return bool
	 */
	public function getAlreadyHappened() { return $this->alreadyHappened(); }

	/**
	 * @return bool
	 */
	public function getCurrentlyHappening() { return $this->currentlyHappening(); }
}