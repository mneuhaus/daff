<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Take a table that may include meta-data and spit it into a regular table and a
 * meta-data table.
 *
 */
class CombinedTable implements Table {
	/**
	 * @var CombinedTableBody
	 */
	public $body;
	/**
	 * @var Table
	 */
	public $core;
	/**
	 * @var int
	 */
	public $dx;
	/**
	 * @var int
	 */
	public $dy;
	/**
	 * @var CombinedTableHead
	 */
	public $head;
	/**
	 * @var Meta
	 */
	public $meta;
	/**
	 * @var Table
	 */
	public $t;

	/**
	 *
	 * Constructor.
	 * @param t the table to wrap
	 *
	 * 
	 * @param Table $t
	 * 
	 * @return void
	 */
	public function __construct ($t) {
		#coopy/CombinedTable.hx:34: characters 9-19
		$this->t = $t;
		#coopy/CombinedTable.hx:35: characters 9-15
		$this->dx = 0;
		#coopy/CombinedTable.hx:36: characters 9-15
		$this->dy = 0;
		#coopy/CombinedTable.hx:37: characters 9-17
		$this->core = $t;
		#coopy/CombinedTable.hx:38: characters 9-20
		$this->head = null;
		#coopy/CombinedTable.hx:39: characters 9-44
		if (($t->get_width() < 1) || ($t->get_height() < 1)) {
			#coopy/CombinedTable.hx:39: characters 38-44
			return;
		}
		#coopy/CombinedTable.hx:40: characters 9-33
		$v = $t->getCellView();
		#coopy/CombinedTable.hx:41: characters 9-53
		if ($v->toString($t->getCell(0, 0)) !== "@@") {
			#coopy/CombinedTable.hx:41: characters 47-53
			return;
		}
		#coopy/CombinedTable.hx:42: characters 9-15
		$this->dx = 1;
		#coopy/CombinedTable.hx:43: characters 9-15
		$this->dy = 0;
		#coopy/CombinedTable.hx:44: characters 19-23
		$_g = 0;
		#coopy/CombinedTable.hx:44: characters 23-31
		$_g1 = $t->get_height();
		#coopy/CombinedTable.hx:44: lines 44-50
		while ($_g < $_g1) {
			#coopy/CombinedTable.hx:44: characters 19-31
			$y = $_g++;
			#coopy/CombinedTable.hx:45: characters 13-50
			$txt = $v->toString($t->getCell(0, $y));
			#coopy/CombinedTable.hx:46: lines 46-48
			if (($txt === null) || ($txt === "") || ($txt === "null")) {
				#coopy/CombinedTable.hx:47: characters 17-22
				break;
			}
			#coopy/CombinedTable.hx:49: characters 13-17
			$this->dy++;
		}
		#coopy/CombinedTable.hx:51: characters 9-54
		$this->head = new CombinedTableHead($this, $this->dx, $this->dy);
		#coopy/CombinedTable.hx:52: characters 9-54
		$this->body = new CombinedTableBody($this, $this->dx, $this->dy);
		#coopy/CombinedTable.hx:53: characters 9-25
		$this->core = $this->body;
		#coopy/CombinedTable.hx:54: characters 9-36
		$this->meta = new SimpleMeta($this->head);
	}

	/**
	 * @return Table
	 */
	public function all () {
		#coopy/CombinedTable.hx:24: characters 9-17
		return $this->t;
	}

	/**
	 * @return void
	 */
	public function clear () {
		#coopy/CombinedTable.hx:97: characters 9-21
		$this->core->clear();
	}

	/**
	 * @return Table
	 */
	public function clone () {
		#coopy/CombinedTable.hx:117: characters 9-28
		return $this->core->clone();
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/CombinedTable.hx:121: characters 9-26
		return $this->t->create();
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/CombinedTable.hx:73: characters 9-33
		return $this->core->getCell($x, $y);
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/CombinedTable.hx:85: characters 9-31
		return $this->t->getCellView();
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/CombinedTable.hx:113: characters 9-20
		return null;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/CombinedTable.hx:125: characters 9-20
		return $this->meta;
	}

	/**
	 * @return Table
	 */
	public function getTable () {
		#coopy/CombinedTable.hx:58: characters 9-20
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/CombinedTable.hx:69: characters 9-27
		return $this->core->get_height();
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/CombinedTable.hx:65: characters 9-26
		return $this->core->get_width();
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/CombinedTable.hx:105: characters 9-54
		return $this->core->insertOrDeleteColumns($fate, $wfate);
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/CombinedTable.hx:101: characters 9-51
		return $this->core->insertOrDeleteRows($fate, $hfate);
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/CombinedTable.hx:89: characters 9-34
		return $this->core->isResizable();
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/CombinedTable.hx:93: characters 9-32
		return $this->core->resize($h, $w);
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param mixed $c
	 * 
	 * @return void
	 */
	public function setCell ($x, $y, $c) {
		#coopy/CombinedTable.hx:77: characters 9-28
		$this->core->setCell($x, $y, $c);
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/CombinedTable.hx:81: characters 9-47
		return SimpleTable::tableToString($this);
	}

	/**
	 * @return bool
	 */
	public function trimBlank () {
		#coopy/CombinedTable.hx:109: characters 9-32
		return $this->core->trimBlank();
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(CombinedTable::class, 'coopy.CombinedTable');
Boot::registerGetters('coopy\\CombinedTable', [
	'width' => true,
	'height' => true
]);
