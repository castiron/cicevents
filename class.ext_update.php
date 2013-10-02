<?php

class ext_update {

	const EVENT_TABLE = 'tx_cicevents_domain_model_event';
	const OCCURRENCE_TABLE = 'tx_cicevents_domain_model_occurrence';

	/**
	 * @var array
	 */
	protected $msgs = array();

	/**
	 * @var t3lib_DB
	 */
	protected $db;

	/**
	 * A function for initializing things
	 */
	protected function initialize() {
		$this->db = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @return bool
	 */
	public function access() {

		// Checks happen in main() to deliver appropriate msgs

		return TRUE;
	}

	/**
	 * @return string
	 */
	public function main() {

		$this->initialize();

		$fields = $this->db->admin_get_fields(self::EVENT_TABLE);
		if(!isset($fields['occurrences'])) {
			return 'You need to run the database migrations first.';
		}

		$countOccurrences = $this->db->exec_SELECTcountRows('*', self::OCCURRENCE_TABLE);
		if($countOccurrences) {
			return 'Existing Event Occurrences have been found. Aborting update.';
		}

		$rows = $this->db->exec_SELECTgetRows('*', self::EVENT_TABLE, '');
		foreach($rows as $row) {
			$this->db->exec_INSERTquery(self::OCCURRENCE_TABLE, array(
				'begin_time' => $row['start_time'],
				'finish_time' => $row['end_time'],
				'venue' => $row['venue'],
				'event' => $row['uid'],
				'address' => $row['address'],
			));
			$this->logSQL();

			// Update the event table to show that there is 1 occurrence record related to it.
			// (Only one because this update is run just once.)
			$this->db->exec_UPDATEquery(self::EVENT_TABLE, 'uid = '.$row['uid'], array('occurrences' => 1));
			$this->logSQL();
		}


		$list = '<ul>';
		foreach($this->msgs as $msg) {
			$list .= "<li>$msg</li>";
		}
		$list .= '</ul>';

		return "$list<br><p>All done.</p>";
	}


	protected function logSQL() {
		$this->msgs[] = '<strong>SQL:</strong> '.$this->db->debug_lastBuiltQuery;

		$error = $this->db->sql_error();
		if($error !== '') {
			$this->msgs[] = '<strong>DATABASE ERROR:<strong> '.$error;
		}
	}

}
?>