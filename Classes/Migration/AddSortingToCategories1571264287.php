<?php
namespace CIC\Cicevents\Migration;

/**
 * Class AddSortingToCategories1571264287
 * @package CIC\Cicevents\Migration
 */
class AddSortingToCategories1571264287 extends \CIC\Cicbase\Migration\AbstractMigration {
	public function run() {
		$this->setForgiving(true);
		$this->addIntField('tx_cicevents_domain_model_category', 'sorting', 11);
	}

	public function rollback() {
		$this->setForgiving(true);
		$this->dropFieldFromTable('tx_cicevents_domain_model_category', 'sorting');
	}
}
