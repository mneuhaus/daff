<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\IntMap;

class SparseSheet {
	/**
	 * @var int
	 */
	public $h;
	/**
	 * @var IntMap
	 */
	public $row;
	/**
	 * @var int
	 */
	public $w;
	/**
	 * @var mixed
	 */
	public $zero;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/SparseSheet.hx:15: characters 9-18
		$this->h = $this->w = 0;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function get ($x, $y) {
		#coopy/SparseSheet.hx:30: characters 9-46
		$cursor = ($this->row->data[$y] ?? null);
		#coopy/SparseSheet.hx:31: characters 9-38
		if ($cursor === null) {
			#coopy/SparseSheet.hx:31: characters 27-38
			return $this->zero;
		}
		#coopy/SparseSheet.hx:32: characters 9-43
		$val = ($cursor->data[$x] ?? null);
		#coopy/SparseSheet.hx:33: characters 9-35
		if ($val === null) {
			#coopy/SparseSheet.hx:33: characters 24-35
			return $this->zero;
		}
		#coopy/SparseSheet.hx:34: characters 9-19
		return $val;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * @param mixed $zero
	 * 
	 * @return void
	 */
	public function nonDestructiveResize ($w, $h, $zero) {
		#coopy/SparseSheet.hx:24: characters 9-19
		$this->w = $w;
		#coopy/SparseSheet.hx:25: characters 9-19
		$this->h = $h;
		#coopy/SparseSheet.hx:26: characters 9-25
		$this->zero = $zero;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * @param mixed $zero
	 * 
	 * @return void
	 */
	public function resize ($w, $h, $zero) {
		#coopy/SparseSheet.hx:19: characters 9-40
		$this->row = new IntMap();
		#coopy/SparseSheet.hx:20: characters 9-39
		$this->nonDestructiveResize($w, $h, $zero);
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param mixed $val
	 * 
	 * @return void
	 */
	public function set ($x, $y, $val) {
		#coopy/SparseSheet.hx:38: characters 9-46
		$cursor = ($this->row->data[$y] ?? null);
		#coopy/SparseSheet.hx:39: lines 39-42
		if ($cursor === null) {
			#coopy/SparseSheet.hx:40: characters 13-38
			$cursor = new IntMap();
			#coopy/SparseSheet.hx:41: characters 13-30
			$this->row->data[$y] = $cursor;
		}
		#coopy/SparseSheet.hx:43: characters 9-26
		$cursor->data[$x] = $val;
	}
}

Boot::registerClass(SparseSheet::class, 'coopy.SparseSheet');
