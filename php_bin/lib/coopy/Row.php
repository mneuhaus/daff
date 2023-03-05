<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * A trivial interface for indexable sources.
 *
 */
interface Row {
	/**
	 *
	 * Get the content in a given column.
	 *
	 * @param c the column to look in
	 * @return the content of column `c`
	 *
	 * 
	 * @param int $c
	 * 
	 * @return string
	 */
	public function getRowString ($c) ;

	/**
	 *
	 * @return true if row is header row (or before)
	 *
	 * 
	 * @return bool
	 */
	public function isPreamble () ;
}

Boot::registerClass(Row::class, 'coopy.Row');
