<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Head of a table that has embedded meta-data.
 *
 */
class CombinedTableHead implements Table {
	/**
	 * @var Table
	 */
	public $all;
	/**
	 * @var int
	 */
	public $dx;
	/**
	 * @var int
	 */
	public $dy;
	/**
	 * @var CombinedTable
	 */
	public $parent;

	/**
	 *
	 * Constructor.
	 * @param parent the composite table
	 *
	 * 
	 * @param CombinedTable $parent
	 * @param int $dx
	 * @param int $dy
	 * 
	 * @return void
	 */
	public function __construct ($parent, $dx, $dy) {
		#coopy/CombinedTableHead.hx:25: characters 9-29
		$this->parent = $parent;
		#coopy/CombinedTableHead.hx:26: characters 9-21
		$this->dx = $dx;
		#coopy/CombinedTableHead.hx:27: characters 9-21
		$this->dy = $dy;
		#coopy/CombinedTableHead.hx:28: characters 9-32
		$this->all = $parent->all();
	}

	/**
	 * @return void
	 */
	public function clear () {
	}

	/**
	 * @return Table
	 */
	public function clone () {
		#coopy/CombinedTableHead.hx:95: characters 9-20
		return null;
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/CombinedTableHead.hx:99: characters 9-20
		return null;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/CombinedTableHead.hx:47: lines 47-51
		if ($x === 0) {
			#coopy/CombinedTableHead.hx:48: characters 13-35
			$v = $this->getCellView();
			#coopy/CombinedTableHead.hx:49: characters 13-52
			$txt = $v->toString($this->all->getCell($x, $y));
			#coopy/CombinedTableHead.hx:50: characters 13-68
			if (\mb_substr($txt, 0, 1) === "@") {
				#coopy/CombinedTableHead.hx:50: characters 44-68
				return \mb_substr($txt, 1, mb_strlen($txt));
			}
		}
		#coopy/CombinedTableHead.hx:52: characters 9-32
		return $this->all->getCell($x, $y);
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/CombinedTableHead.hx:64: characters 9-33
		return $this->all->getCellView();
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/CombinedTableHead.hx:91: characters 9-20
		return null;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/CombinedTableHead.hx:103: characters 9-20
		return null;
	}

	/**
	 * @return Table
	 */
	public function getTable () {
		#coopy/CombinedTableHead.hx:32: characters 9-20
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/CombinedTableHead.hx:43: characters 9-18
		return $this->dy;
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/CombinedTableHead.hx:39: characters 9-25
		return $this->all->get_width();
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/CombinedTableHead.hx:83: characters 9-53
		return $this->all->insertOrDeleteColumns($fate, $wfate);
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/CombinedTableHead.hx:79: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/CombinedTableHead.hx:68: characters 9-21
		return false;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/CombinedTableHead.hx:72: characters 9-21
		return false;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param mixed $c
	 * 
	 * @return void
	 */
	public function setCell ($x, $y, $c) {
		#coopy/CombinedTableHead.hx:56: characters 9-27
		$this->all->setCell($x, $y, $c);
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/CombinedTableHead.hx:60: characters 9-47
		return SimpleTable::tableToString($this);
	}

	/**
	 * @return bool
	 */
	public function trimBlank () {
		#coopy/CombinedTableHead.hx:87: characters 9-21
		return false;
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(CombinedTableHead::class, 'coopy.CombinedTableHead');
Boot::registerGetters('coopy\\CombinedTableHead', [
	'width' => true,
	'height' => true
]);
