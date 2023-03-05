<?php
/**
 */

namespace coopy;

use \php\Boot;

class ConflictInfo {
	/**
	 * @var int
	 */
	public $col;
	/**
	 * @var mixed
	 */
	public $lvalue;
	/**
	 * @var mixed
	 */
	public $pvalue;
	/**
	 * @var int
	 */
	public $row;
	/**
	 * @var mixed
	 */
	public $rvalue;

	/**
	 * @param int $row
	 * @param int $col
	 * @param mixed $pvalue
	 * @param mixed $lvalue
	 * @param mixed $rvalue
	 * 
	 * @return void
	 */
	public function __construct ($row, $col, $pvalue, $lvalue, $rvalue) {
		#coopy/ConflictInfo.hx:15: characters 9-23
		$this->row = $row;
		#coopy/ConflictInfo.hx:16: characters 9-23
		$this->col = $col;
		#coopy/ConflictInfo.hx:17: characters 9-29
		$this->pvalue = $pvalue;
		#coopy/ConflictInfo.hx:18: characters 9-29
		$this->lvalue = $lvalue;
		#coopy/ConflictInfo.hx:19: characters 9-29
		$this->rvalue = $rvalue;
	}
}

Boot::registerClass(ConflictInfo::class, 'coopy.ConflictInfo');
