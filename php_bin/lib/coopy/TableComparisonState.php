<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

/**
 *
 * State of a comparison between tables.
 *
 */
class TableComparisonState {
	/**
	 * @var Table
	 *
	 * The reference "local" table.
	 *
	 */
	public $a;
	/**
	 * @var Meta
	 */
	public $a_meta;
	/**
	 * @var Alignment
	 */
	public $alignment;
	/**
	 * @var Table
	 *
	 * The modified "remote" table.
	 *
	 */
	public $b;
	/**
	 * @var Meta
	 */
	public $b_meta;
	/**
	 * @var string[]|\Array_hx
	 */
	public $child_order;
	/**
	 * @var StringMap
	 */
	public $children;
	/**
	 * @var CompareFlags
	 *
	 * The flags that should be used during comparison.
	 *
	 */
	public $compare_flags;
	/**
	 * @var bool
	 *
	 * Has the comparison run to completion?
	 *
	 */
	public $completed;
	/**
	 * @var bool
	 *
	 * Do tables have blatantly the same set of columns?
	 *
	 */
	public $has_same_columns;
	/**
	 * @var bool
	 *
	 * Has `has_same_columns` been determined yet?
	 *
	 */
	public $has_same_columns_known;
	/**
	 * @var bool
	 *
	 * Are the tables identical?
	 *
	 */
	public $is_equal;
	/**
	 * @var bool
	 *
	 * Has `is_equal` been determined yet?
	 *
	 */
	public $is_equal_known;
	/**
	 * @var Table
	 *
	 * The common ancestor ("parent") table - null if none.
	 *
	 */
	public $p;
	/**
	 * @var Meta
	 */
	public $p_meta;
	/**
	 * @var bool
	 *
	 * Should the comparison run to completion?
	 *
	 */
	public $run_to_completion;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/TableComparisonState.hx:93: characters 9-16
		$this->reset();
	}

	/**
	 * @return void
	 */
	public function getMeta () {
		#coopy/TableComparisonState.hx:116: characters 9-58
		if (($this->p !== null) && ($this->p_meta === null)) {
			#coopy/TableComparisonState.hx:116: characters 38-58
			$this->p_meta = $this->p->getMeta();
		}
		#coopy/TableComparisonState.hx:117: characters 9-58
		if (($this->a !== null) && ($this->a_meta === null)) {
			#coopy/TableComparisonState.hx:117: characters 38-58
			$this->a_meta = $this->a->getMeta();
		}
		#coopy/TableComparisonState.hx:118: characters 9-58
		if (($this->b !== null) && ($this->b_meta === null)) {
			#coopy/TableComparisonState.hx:118: characters 38-58
			$this->b_meta = $this->b->getMeta();
		}
	}

	/**
	 *
	 * Set the comparison back to a default state, as if no computation
	 * has been done.
	 *
	 * 
	 * @return void
	 */
	public function reset () {
		#coopy/TableComparisonState.hx:103: characters 9-26
		$this->completed = false;
		#coopy/TableComparisonState.hx:104: characters 9-33
		$this->run_to_completion = true;
		#coopy/TableComparisonState.hx:105: characters 9-31
		$this->is_equal_known = false;
		#coopy/TableComparisonState.hx:106: characters 9-25
		$this->is_equal = false;
		#coopy/TableComparisonState.hx:107: characters 9-33
		$this->has_same_columns = false;
		#coopy/TableComparisonState.hx:108: characters 9-39
		$this->has_same_columns_known = false;
		#coopy/TableComparisonState.hx:109: characters 9-29
		$this->compare_flags = null;
		#coopy/TableComparisonState.hx:110: characters 9-25
		$this->alignment = null;
		#coopy/TableComparisonState.hx:111: characters 9-24
		$this->children = null;
		#coopy/TableComparisonState.hx:112: characters 9-27
		$this->child_order = null;
	}
}

Boot::registerClass(TableComparisonState::class, 'coopy.TableComparisonState');
