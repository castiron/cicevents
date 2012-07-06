<?php

class Tx_Cicevents_Domain_Repository_EventRepository extends Tx_Cicbase_Persistence_Repository {

	const RANGE_CURRENT = 0;
	const RANGE_THIS_MONTH = 1;
	const RANGE_NEXT_MONTH = 2;
	const RANGE_THREE_MONTHS = 3;
	const RANGE_PAST = 4;

	/**
	 * @var Tx_Extbase_Persistence_Query
	 */
	protected $tempQuery;

	protected $filters;

	protected $filterOrderings;

	/**
	 * Returns all objects of this repository (overridden)
	 *
	 * @param integer $limit An optional limit on the number of events returned. Defaults to all events.
	 * @param integer $offset An optional starting point for all records. Defaults to the beginning.
	 * @return array An array of objects, empty if no objects found
	 */
	public function findAll($limit = 0, $offset = 0) {
		$query = $this->getQuery();
		$query->setOrderings(array('startTime' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING));
		if($limit > 1) {
			$query->setLimit($limit);
			$query->setOffset($offset);
		}
		if(count($this->filters) > 0) {
			$query->setOrderings($this->filterOrderings);
			$query->matching($query->logicalAnd($this->filters));
		}
		$result = $query->execute();
		return $result;
	}


	/**
	 * This returns a QueryResult according to a given set of parameters
	 *
	 * Acceptable Parameters:
	 * 'location' => string (a search value for address OR venue)
	 * 'date' => DateTime (shows all events on this day)
	 * 'range' => One of the RANGE_* constants
	 * 'category' => integer
	 * 'type' => integer
	 *
	 * NOTE: If beginDate and endDate are both specified, then the range of events
	 * within those dates (and including) are returned.
	 *
	 * NOTE: Unrecognized parameters are skipped.
	 *
	 * @param array $params
	 * @return void
	 */
	public function addFilters(array $params) {
		$query = $this->createQuery();
		$this->filters = array();
		$this->filterOrderings = array('startTime' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING);

		foreach($params as $key => $value) {
			if($value !== 0 &&$value == null)
				continue;
			switch($key) {
				case 'location':
						$this->filters[] = $query->logicalOr(
							$query->like('address', '%'.$value.'%'),
							$query->like('venue', '%'.$value.'%')
						);
					break;
				case 'date':
						$dateEnd = clone $value;
						$dateStart = clone $value;
						$dateEnd->setTime(23,59,59);
						$dateStart->setTime(0,0,0);
						$A = $query->lessThanOrEqual('startTime', $dateEnd);
						$B = $query->greaterThanOrEqual('endTime', $dateStart);
						$this->filters[] = $query->logicalAnd($A, $B);
					break;
				case 'range':
					$today = new DateTime();
					switch($value) {
						case self::RANGE_CURRENT:
							$start = new DateTime();
							$start->setTime(0,0,0);
							$this->filters[] = $query->greaterThanOrEqual('endTime', $start);
							break;
						case self::RANGE_NEXT_MONTH:
							$start = new DateTime();
							$end = new DateTime();
							$start->setTime(0, 0, 0);
							$end->setTime(23, 59, 59);
							$start->setDate($today->format('Y'), $today->format('n') + 1, 1);
							$end->setDate($today->format('Y'), $today->format('n') + 2, 0);
							$A = $query->lessThanOrEqual('startTime', $end);
							$B = $query->greaterThanOrEqual('endTime', $start);
							$this->filters[] = $query->logicalAnd($A, $B);
							break;
						case self::RANGE_THIS_MONTH:
							$start = new DateTime();
							$end = new DateTime();
							$start->setTime(0, 0, 0);
							$end->setTime(23, 59, 59);
							$end->setDate($today->format('Y'), $today->format('n') + 1, 0);
							$A = $query->lessThanOrEqual('startTime', $end);
							$B = $query->greaterThanOrEqual('endTime', $start);
							$this->filters[] = $query->logicalAnd($A, $B);
							break;
						case self::RANGE_THREE_MONTHS:
							$start = new DateTime();
							$end = new DateTime();
							$start->setTime(0, 0, 0);
							$end->setTime(23, 59, 59);
							$start->setDate($today->format('Y'), $today->format('n') + 1, 1);
							$end->setDate($today->format('Y'), $today->format('n') + 4, 0);
							$A = $query->lessThanOrEqual('startTime', $end);
							$B = $query->greaterThanOrEqual('endTime', $start);
							$this->filters[] = $query->logicalAnd($A, $B);
							break;
						case self::RANGE_PAST:
							$start = $today;
							$start->setTime(0,0,0);
							$this->filters[] = $query->lessThanOrEqual('endTime', $start);
							$this->filterOrderings = array('startTime' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING);
							break;
					}
					break;
				case 'category':
					$this->filters[] = $query->contains('categories', $value);
					break;
				case 'type':
					$this->filters[] = $query->equals('type', $value);
					break;
				default:
					// skip unknown parameters
					continue;
			}
		}

		$this->tempQuery = $query;
	}

	/**
	 * Reduces a string to a single word with no funny chars.
	 * @param sring $stringToSimplify
	 * @return mixed
	 */
	private static function simplify($stringToSimplify) {
		return preg_replace('[^A-Za-z0-9 ]','',$stringToSimplify);
	}

	/**
	 * Returns a Query object.
	 *
	 * @return null|Tx_Extbase_Persistence_Query
	 */
	private function getQuery() {
		if($this->tempQuery) {
			$query = $this->tempQuery;
			$this->tempQuery = null;
			return $query;
		} else {
			return $this->createQuery();
		}
	}
}
?>
