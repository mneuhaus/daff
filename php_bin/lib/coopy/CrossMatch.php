<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Query results when looking for a row in an index pair.
 *
 */
class CrossMatch {
	/**
	 * @var IndexItem
	 *
	 * List of occurance in table A.
	 *
	 */
	public $item_a;
	/**
	 * @var IndexItem
	 *
	 * List of occurance in table B.
	 *
	 */
	public $item_b;
	/**
	 * @var int
	 *
	 * How many times was the query seen in table A.
	 *
	 */
	public $spot_a;
	/**
	 * @var int
	 *
	 * How many times was the query seen in table B.
	 *
	 */
	public $spot_b;

	/**
	 * @return void
	 */
	public function __construct () {
	}
}

Boot::registerClass(CrossMatch::class, 'coopy.CrossMatch');
