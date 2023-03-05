<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Summarize the changes in a diff of a pair of tables
 *
 */
class DiffSummary {
	/**
	 * @var int
	 */
	public $col_count_final;
	/**
	 * @var int
	 */
	public $col_count_initial;
	/**
	 * @var int
	 */
	public $col_deletes;
	/**
	 * @var int
	 */
	public $col_inserts;
	/**
	 * @var int
	 */
	public $col_renames;
	/**
	 * @var int
	 */
	public $col_reorders;
	/**
	 * @var int
	 */
	public $col_updates;
	/**
	 * @var bool
	 */
	public $different;
	/**
	 * @var int
	 */
	public $row_count_final;
	/**
	 * @var int
	 */
	public $row_count_final_with_header;
	/**
	 * @var int
	 */
	public $row_count_initial;
	/**
	 * @var int
	 */
	public $row_count_initial_with_header;
	/**
	 * @var int
	 */
	public $row_deletes;
	/**
	 * @var int
	 */
	public $row_inserts;
	/**
	 * @var int
	 */
	public $row_reorders;
	/**
	 * @var int
	 */
	public $row_updates;

	/**
	 * @return void
	 */
	public function __construct () {
	}
}

Boot::registerClass(DiffSummary::class, 'coopy.DiffSummary');
