<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Body of a table that has embedded meta-data.
 *
 */
class CombinedTableBody implements Table {
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
	 * @var Table
	 */
	public $meta;
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
		#coopy/CombinedTableBody.hx:26: characters 9-29
		$this->parent = $parent;
		#coopy/CombinedTableBody.hx:27: characters 9-21
		$this->dx = $dx;
		#coopy/CombinedTableBody.hx:28: characters 9-21
		$this->dy = $dy;
		#coopy/CombinedTableBody.hx:29: characters 9-27
		$this->all = $parent->all();
	}

	/**
	 * @return void
	 */
	public function clear () {
		#coopy/CombinedTableBody.hx:82: characters 9-20
		$this->all->clear();
		#coopy/CombinedTableBody.hx:83: characters 9-15
		$this->dx = 0;
		#coopy/CombinedTableBody.hx:84: characters 9-15
		$this->dy = 0;
	}

	/**
	 * @return Table
	 */
	public function clone () {
		#coopy/CombinedTableBody.hx:123: characters 9-46
		return new CombinedTable($this->all->clone());
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/CombinedTableBody.hx:127: characters 9-47
		return new CombinedTable($this->all->create());
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/CombinedTableBody.hx:48: lines 48-53
		if ($y === 0) {
			#coopy/CombinedTableBody.hx:49: lines 49-51
			if ($this->meta === null) {
				#coopy/CombinedTableBody.hx:50: characters 17-50
				$this->meta = $this->parent->getMeta()->asTable();
			}
			#coopy/CombinedTableBody.hx:52: characters 13-40
			return $this->meta->getCell($x + $this->dx, 0);
		}
		#coopy/CombinedTableBody.hx:54: characters 9-40
		return $this->all->getCell($x + $this->dx, $y + $this->dy - 1);
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/CombinedTableBody.hx:70: characters 9-33
		return $this->all->getCellView();
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/CombinedTableBody.hx:119: characters 9-20
		return null;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/CombinedTableBody.hx:131: characters 9-32
		return $this->parent->getMeta();
	}

	/**
	 * @return Table
	 */
	public function getTable () {
		#coopy/CombinedTableBody.hx:33: characters 9-20
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/CombinedTableBody.hx:44: characters 9-31
		return $this->all->get_height() - $this->dy + 1;
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/CombinedTableBody.hx:40: characters 9-27
		return $this->all->get_width() - 1;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/CombinedTableBody.hx:104: characters 9-38
		$fate2 = new \Array_hx();
		#coopy/CombinedTableBody.hx:105: characters 19-23
		$_g = 0;
		#coopy/CombinedTableBody.hx:105: characters 23-29
		$_g1 = $this->dx + 1;
		#coopy/CombinedTableBody.hx:105: lines 105-107
		while ($_g < $_g1) {
			#coopy/CombinedTableBody.hx:105: characters 19-29
			$x = $_g++;
			#coopy/CombinedTableBody.hx:106: characters 13-26
			$fate2->arr[$fate2->length++] = $x;
		}
		#coopy/CombinedTableBody.hx:108: lines 108-110
		$_g = 0;
		while ($_g < $fate->length) {
			#coopy/CombinedTableBody.hx:108: characters 14-15
			$f = ($fate->arr[$_g] ?? null);
			#coopy/CombinedTableBody.hx:108: lines 108-110
			++$_g;
			#coopy/CombinedTableBody.hx:109: characters 13-42
			$fate2->arr[$fate2->length++] = ($f >= 0 ? $f + $this->dx + 1 : $f);
		}
		#coopy/CombinedTableBody.hx:111: characters 9-57
		return $this->all->insertOrDeleteColumns($fate2, $wfate + $this->dx);
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/CombinedTableBody.hx:88: characters 9-38
		$fate2 = new \Array_hx();
		#coopy/CombinedTableBody.hx:89: characters 19-23
		$_g = 0;
		#coopy/CombinedTableBody.hx:89: characters 23-25
		$_g1 = $this->dy;
		#coopy/CombinedTableBody.hx:89: lines 89-91
		while ($_g < $_g1) {
			#coopy/CombinedTableBody.hx:89: characters 19-25
			$y = $_g++;
			#coopy/CombinedTableBody.hx:90: characters 13-26
			$fate2->arr[$fate2->length++] = $y;
		}
		#coopy/CombinedTableBody.hx:92: characters 9-24
		$hdr = true;
		#coopy/CombinedTableBody.hx:93: lines 93-99
		$_g = 0;
		while ($_g < $fate->length) {
			#coopy/CombinedTableBody.hx:93: characters 14-15
			$f = ($fate->arr[$_g] ?? null);
			#coopy/CombinedTableBody.hx:93: lines 93-99
			++$_g;
			#coopy/CombinedTableBody.hx:94: lines 94-97
			if ($hdr) {
				#coopy/CombinedTableBody.hx:95: characters 17-28
				$hdr = false;
				#coopy/CombinedTableBody.hx:96: characters 17-25
				continue;
			}
			#coopy/CombinedTableBody.hx:98: characters 13-42
			$fate2->arr[$fate2->length++] = ($f >= 0 ? $f + $this->dy - 1 : $f);
		}
		#coopy/CombinedTableBody.hx:100: characters 9-56
		return $this->all->insertOrDeleteRows($fate2, $hfate + $this->dy - 1);
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/CombinedTableBody.hx:74: characters 9-33
		return $this->all->isResizable();
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/CombinedTableBody.hx:78: characters 9-36
		return $this->all->resize($w + 1, $h + $this->dy);
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param mixed $c
	 * 
	 * @return void
	 */
	public function setCell ($x, $y, $c) {
		#coopy/CombinedTableBody.hx:58: lines 58-61
		if ($y === 0) {
			#coopy/CombinedTableBody.hx:59: characters 13-34
			$this->all->setCell($x + $this->dx, 0, $c);
			#coopy/CombinedTableBody.hx:60: characters 13-19
			return;
		}
		#coopy/CombinedTableBody.hx:62: characters 9-35
		$this->all->setCell($x + $this->dx, $y + $this->dy - 1, $c);
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/CombinedTableBody.hx:66: characters 9-47
		return SimpleTable::tableToString($this);
	}

	/**
	 * @return bool
	 */
	public function trimBlank () {
		#coopy/CombinedTableBody.hx:115: characters 9-31
		return $this->all->trimBlank();
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(CombinedTableBody::class, 'coopy.CombinedTableBody');
Boot::registerGetters('coopy\\CombinedTableBody', [
	'width' => true,
	'height' => true
]);
