<?php
namespace CIC\Cicevents\Domain\Repository;

class EventRepository extends \CIC\Cicbase\Persistence\Repository {

	const RANGE_CURRENT = 0;
	const RANGE_THIS_MONTH = 1;
	const RANGE_NEXT_MONTH = 2;
	const RANGE_THREE_MONTHS = 3;
	const RANGE_PAST = 4;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\Query
	 */
	protected $tempQuery;

	protected $filters;

	protected $filterOrderings;

	protected $sortBy;

	/**
	 * @var \CIC\Cicevents\Domain\Repository\OccurrenceRepository
	 */
	protected $occurrenceRepository;

	/**
	 * inject the occurrenceRepository
	 *
	 * @param \CIC\Cicevents\Domain\Repository\OccurrenceRepository $occurrenceRepository
	 * @return void
	 */
	public function injectOccurrenceRepository(\CIC\Cicevents\Domain\Repository\OccurrenceRepository $occurrenceRepository) {
		$this->occurrenceRepository = $occurrenceRepository;
	}

	/**
	 * Returns all objects of this repository (overridden)
	 *
	 * @param integer $limit An optional limit on the number of events returned. Defaults to all events.
	 * @param integer $offset An optional starting point for all records. Defaults to the beginning.
	 * @return array An array of objects, empty if no objects found
	 */
	public function findAll($limit = 0, $offset = 0) {
		$query = $this->getQuery();

		if(count($this->filters) > 0) {
			$query->matching($query->logicalAnd($this->filters));
		}

		$result = $query->execute()->toArray();

		$sorted = $this->sortEvents($result);

		if($limit > 0) {
			$sorted =  array_slice($sorted, $offset, $limit);
		}

		return $sorted;
	}

	/**
	 * Sorts an array of events based on an event's soonest occurrence or
	 * based on an event's most recent occurrence.
	 *
	 * @param $events
	 * @return array
	 */
	public function sortEvents($events) {

		if(!$this->sortBy) {
			$this->sortBy = 'soonest';
		}

		$occurrenceGetterFunctionName = null;
		$chronological = true;
		switch(strtolower($this->sortBy)) {
			case 'soonest':
				$occurrenceGetterFunctionName = 'getSoonestOccurrence';
				break;
			case 'mostrecent':
			case 'most-recent':
			case 'most_recent':
				$occurrenceGetterFunctionName = 'getMostRecentOccurrence';
				$chronological = false;
				break;
			}

		if($occurrenceGetterFunctionName) {
			$sorter = $this->getSorterFunction($occurrenceGetterFunctionName, $chronological);

			if ($events instanceof \TYPO3\CMS\Extbase\Persistence\QueryResultInterface || $events instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {

				$array = $events->toArray();
				$array = array_filter($array, $this->getFilterFunction($occurrenceGetterFunctionName)());
				usort($array, $sorter);

				// Return it as an iterator
				$storage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
				foreach ($array as $item) {
					$storage->attach($item);
				}

				return $storage;

			} elseif (is_array($events)) {
				$events = array_filter($events, $this->getFilterFunction($occurrenceGetterFunctionName));
				usort($events, $sorter);
			}
		}

		return $events;
	}

	/**
	 * @param $occurrenceGetterFunctionName
	 * @return \Closure
	 */
	protected function getSorterFunction($occurrenceGetterFunctionName, $chronological = true) {
		return function(\CIC\Cicevents\Domain\Model\Event $a, \CIC\Cicevents\Domain\Model\Event $b) use ($occurrenceGetterFunctionName, $chronological) {
			$aOcc = $a->$occurrenceGetterFunctionName();
			$bOcc = $b->$occurrenceGetterFunctionName();
			if($aOcc && $bOcc) {
				$aOccBegin = $aOcc->getBeginTime();
				$bOccBegin = $bOcc->getBeginTime();
				if($aOccBegin && $bOccBegin) {
					return ($chronological ? $aOccBegin < $bOccBegin : $aOccBegin > $bOccBegin) ? -1 : 1;
				}
			}
		};
	}

	/**
	 * @param $occurrenceGetterFunctionName
	 * @return \Closure
	 */
	protected function getFilterFunction($occurrenceGetterFunctionName) {
		return function($event) use ($occurrenceGetterFunctionName) {
			return $event->$occurrenceGetterFunctionName() !== null;
		};
	}

	/**
	 * This returns a QueryResult according to a given set of parameters
	 *
	 * Acceptable Parameters:
	 * 'location' => string (a search value for address OR venue)
	 * 'date' => \DateTime (shows all events on this day)
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

		$occurrenceQuery = $this->occurrenceRepository->createQuery();
		$occurrenceFilters = array();

		foreach($params as $key => $value) {
			if($value !== 0 && $value == null) continue;
			switch($key) {
				case 'location':
						$occurrenceFilters[] = $query->logicalOr(
							$query->like('address', '%'.$value.'%'),
							$query->like('venue', '%'.$value.'%')
						);
					break;
				case 'date':
						$dateEnd = clone $value;
						$dateStart = clone $value;
						$dateEnd->setTime(23,59,59);
						$dateStart->setTime(0,0,0);
						$A = $occurrenceQuery->lessThanOrEqual('beginTime', $dateEnd);
						$B = $occurrenceQuery->greaterThanOrEqual('finishTime', $dateStart);
						$occurrenceFilters[] = $occurrenceQuery->logicalAnd($A, $B);
					break;
				case 'range':
					$today = new \DateTime();
					switch($value) {
						case self::RANGE_CURRENT:
							$start = new \DateTime();
							$start->setTime(0,0,0);
							$occurrenceFilters[] = $occurrenceQuery->greaterThanOrEqual('finishTime', $start);
							break;
						case self::RANGE_NEXT_MONTH:
							$start = new \DateTime();
							$end = new \DateTime();
							$start->setTime(0, 0, 0);
							$end->setTime(23, 59, 59);
							$start->setDate($today->format('Y'), $today->format('n') + 1, 1);
							$end->setDate($today->format('Y'), $today->format('n') + 2, 0);
							$A = $occurrenceQuery->lessThanOrEqual('beginTime', $end);
							$B = $occurrenceQuery->greaterThanOrEqual('finishTime', $start);
							$occurrenceFilters[] = $occurrenceQuery->logicalAnd($A, $B);
							break;
						case self::RANGE_THIS_MONTH:
							$start = new \DateTime();
							$end = new \DateTime();
							$start->setTime(0, 0, 0);
							$end->setTime(23, 59, 59);
							$end->setDate($today->format('Y'), $today->format('n') + 1, 0);
							$A = $occurrenceQuery->lessThanOrEqual('beginTime', $end);
							$B = $occurrenceQuery->greaterThanOrEqual('finishTime', $start);
							$occurrenceFilters[] = $occurrenceQuery->logicalAnd($A, $B);
							break;
						case self::RANGE_THREE_MONTHS:
							$start = new \DateTime();
							$end = new \DateTime();
							$start->setTime(0, 0, 0);
							$end->setTime(23, 59, 59);
							$start->setDate($today->format('Y'), $today->format('n') + 1, 1);
							$end->setDate($today->format('Y'), $today->format('n') + 4, 0);
							$A = $occurrenceQuery->lessThanOrEqual('beginTime', $end);
							$B = $occurrenceQuery->greaterThanOrEqual('finishTime', $start);
							$occurrenceFilters[] = $occurrenceQuery->logicalAnd($A, $B);
							break;
						case self::RANGE_PAST:
							$start = $today;
							$start->setTime(0,0,0);
							$occurrenceFilters[] = $occurrenceQuery->lessThanOrEqual('finishTime', $start);
							$this->sortBy = 'mostRecent';
							break;
					}
					break;
				case 'category':
					$this->filters[] = $query->contains('categories', $value);
					break;
				case 'locality':
					$this->filters[] = $query->contains('localities', $value);
					break;
				case 'type':
					$this->filters[] = $query->equals('type', $value);
					break;
			}
		}

		$occurrences = $occurrenceQuery->matching($occurrenceQuery->logicalAnd($occurrenceFilters))->execute();
		foreach($occurrences as $occurrence) {
			$occurrenceConstraints[] = $query->contains('occurrences', $occurrence);
		}
		if(count($occurrenceConstraints)) {
			$this->filters[] = $query->logicalOr($occurrenceConstraints);
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
	 * @return null|\TYPO3\CMS\Extbase\Persistence\Generic\Query
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

