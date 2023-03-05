<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Represent a change to a single column.
 *
 */
class ColumnChange {
	/**
	 * @var string
	 *
	 * The new name of the column. If null, the column
	 * is to be destroyed.
	 *
	 */
	public $name;
	/**
	 * @var string
	 *
	 * The original name of the column. If null, the column
	 * is to be created.
	 *
	 */
	public $prevName;
	/**
	 * @var PropertyChange[]|\Array_hx
	 *
	 * A list of changes to properties of the column.
	 *
	 */
	public $props;

	/**
	 *
	 * Constructor.
	 *
	 * 
	 * @return void
	 */
	public function __construct () {
	}
}

Boot::registerClass(ColumnChange::class, 'coopy.ColumnChange');
