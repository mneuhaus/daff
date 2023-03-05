<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * A list of instances of a given row in a table.
 *
 */
class IndexItem {
	/**
	 * @var int[]|\Array_hx
	 */
	public $lst;

	/**
	 * @return void
	 */
	public function __construct () {
	}

	/**
	 *
	 * Add an extra instance to the list.
	 *
	 * @param i the row number
	 * @return the number of instances seen
	 *
	 * 
	 * @param int $i
	 * 
	 * @return int
	 */
	public function add ($i) {
		#coopy/IndexItem.hx:27: characters 9-46
		if ($this->lst === null) {
			#coopy/IndexItem.hx:27: characters 24-46
			$this->lst = new \Array_hx();
		}
		#coopy/IndexItem.hx:28: characters 9-20
		$_this = $this->lst;
		$_this->arr[$_this->length++] = $i;
		#coopy/IndexItem.hx:29: characters 9-26
		return $this->lst->length;
	}

	/**
	 *
	 * @return the full list of rows seen
	 *
	 * 
	 * @return int[]|\Array_hx
	 */
	public function asList () {
		#coopy/IndexItem.hx:56: characters 9-19
		return $this->lst;
	}

	/**
	 *
	 * @return the number of instances seen
	 *
	 * 
	 * @return int
	 */
	public function length () {
		#coopy/IndexItem.hx:38: characters 9-26
		return $this->lst->length;
	}

	/**
	 *
	 * @return the row number of the first instance seen
	 *
	 * 
	 * @return int
	 */
	public function value () {
		#coopy/IndexItem.hx:47: characters 9-22
		return ($this->lst->arr[0] ?? null);
	}
}

Boot::registerClass(IndexItem::class, 'coopy.IndexItem');
