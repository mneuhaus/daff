<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

/**
 *
 * An iterator for rows of a table.
 *
 */
interface RowStream {
	/**
	 * @return string[]|\Array_hx
	 */
	public function fetchColumns () ;

	/**
	 * @return StringMap
	 */
	public function fetchRow () ;
}

Boot::registerClass(RowStream::class, 'coopy.RowStream');
