<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Interface for interpreting cell contents. In most cases the implementation
 * will be entirely trivial.
 *
 */
interface View {
	/**
	 *
	 * Compare two cells.
	 * @param d1 the first cell
	 * @param d2 the second cell
	 * @return true if the cells are equal
	 *
	 * 
	 * @param mixed $d1
	 * @param mixed $d2
	 * 
	 * @return bool
	 */
	public function equals ($d1, $d2) ;

	/**
	 * @param mixed $t
	 * 
	 * @return Table
	 */
	public function getTable ($t) ;

	/**
	 *
	 * Check if a hash/map contains a given key
	 *
	 * @param h hash/map to check
	 * @param str key to check
	 * @return true if hash/map contains the given key
	 *
	 * 
	 * @param mixed $h
	 * @param string $str
	 * 
	 * @return bool
	 */
	public function hashExists ($h, $str) ;

	/**
	 *
	 * Check if a hash/map contains a given key
	 *
	 * @param h hash/map to check
	 * @param str key to check
	 * @return true if hash/map contains the given key
	 *
	 * 
	 * @param mixed $h
	 * @param string $str
	 * 
	 * @return mixed
	 */
	public function hashGet ($h, $str) ;

	/**
	 *
	 * Add something to a native hash/map object.
	 * @param h the hash/map
	 * @param str the key to use
	 * @param d the value to use
	 *
	 * 
	 * @param mixed $h
	 * @param string $str
	 * @param mixed $d
	 * 
	 * @return void
	 */
	public function hashSet(&$h, $str, $d) ;

	/**
	 *
	 * @param h possible hash/map to check
	 * @return true if h is a hash/map
	 *
	 * 
	 * @param mixed $h
	 * 
	 * @return bool
	 */
	public function isHash ($h) ;

	/**
	 * @param mixed $t
	 * 
	 * @return bool
	 */
	public function isTable ($t) ;

	/**
	 *
	 * Create a native hash/map object.
	 * @return the newly created hash/map, or null if not available
	 *
	 * 
	 * @return mixed
	 */
	public function makeHash () ;

	/**
	 *
	 * Convert a string to a cell.
	 * @param str the string
	 * @return the string converted to a cell
	 *
	 * 
	 * @param string $str
	 * 
	 * @return mixed
	 */
	public function toDatum ($str) ;

	/**
	 *
	 * Convert a cell to text form.
	 * @param d a cell
	 * @return the cell in text form
	 *
	 * 
	 * @param mixed $d
	 * 
	 * @return string
	 */
	public function toString ($d) ;

	/**
	 * @param Table $t
	 * 
	 * @return mixed
	 */
	public function wrapTable ($t) ;
}

Boot::registerClass(View::class, 'coopy.View');
