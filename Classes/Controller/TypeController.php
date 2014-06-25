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

/**
 *
 *
 * @package cicevents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class TypeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$types = $this->typeRepository->findAll();
		$this->view->assign('types', $types);
	}

	/**
	 * action show
	 *
	 * @param $type
	 * @return void
	 */
	public function showAction(\CIC\Cicevents\Domain\Model\Type $type) {
		$this->view->assign('type', $type);
	}

	/**
	 * action new
	 *
	 * @param $newType
	 * @dontvalidate $newType
	 * @return void
	 */
	public function newAction(\CIC\Cicevents\Domain\Model\Type $newType = NULL) {
		$this->view->assign('newType', $newType);
	}

	/**
	 * action create
	 *
	 * @param $newType
	 * @return void
	 */
	public function createAction(\CIC\Cicevents\Domain\Model\Type $newType) {
		$this->typeRepository->add($newType);
		$this->flashMessageContainer->add('Your new Type was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param $type
	 * @return void
	 */
	public function editAction(\CIC\Cicevents\Domain\Model\Type $type) {
		$this->view->assign('type', $type);
	}

	/**
	 * action update
	 *
	 * @param $type
	 * @return void
	 */
	public function updateAction(\CIC\Cicevents\Domain\Model\Type $type) {
		$this->typeRepository->update($type);
		$this->flashMessageContainer->add('Your Type was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param $type
	 * @return void
	 */
	public function deleteAction(\CIC\Cicevents\Domain\Model\Type $type) {
		$this->typeRepository->remove($type);
		$this->flashMessageContainer->add('Your Type was removed.');
		$this->redirect('list');
	}

}
?>