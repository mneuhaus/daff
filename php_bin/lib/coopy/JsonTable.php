<?php
/**
 */

namespace coopy;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Log;
use \haxe\ds\IntMap;
use \haxe\ds\StringMap;

class JsonTable implements Meta, Table {
	/**
	 * @var string[]|\Array_hx
	 */
	public $columns;
	/**
	 * @var mixed
	 */
	public $data;
	/**
	 * @var int
	 */
	public $h;
	/**
	 * @var IntMap
	 */
	public $idx2col;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var StringMap[]|\Array_hx
	 */
	public $rows;
	/**
	 * @var int
	 */
	public $w;

	/**
	 * @param mixed $data
	 * @param string $name
	 * 
	 * @return void
	 */
	public function __construct ($data, $name) {
		#coopy/JsonTable.hx:17: characters 9-25
		$this->data = $data;
		#coopy/JsonTable.hx:18: characters 9-59
		$this->columns = \Reflect::field($data, "columns");
		#coopy/JsonTable.hx:19: characters 9-53
		$this->rows = \Reflect::field($data, "rows");
		#coopy/JsonTable.hx:20: characters 9-37
		$this->w = $this->columns->length;
		#coopy/JsonTable.hx:21: characters 9-34
		$this->h = $this->rows->length;
		#coopy/JsonTable.hx:22: characters 9-40
		$this->idx2col = new IntMap();
		#coopy/JsonTable.hx:23: characters 21-25
		$_g = 0;
		#coopy/JsonTable.hx:23: characters 25-44
		$_g1 = $this->columns->length;
		#coopy/JsonTable.hx:23: lines 23-25
		while ($_g < $_g1) {
			#coopy/JsonTable.hx:23: characters 21-44
			$idx = $_g++;
			#coopy/JsonTable.hx:24: characters 13-45
			$v = ($this->columns->arr[$idx] ?? null);
			$this->idx2col->data[$idx] = $v;
		}
		#coopy/JsonTable.hx:26: characters 9-25
		$this->name = $name;
	}

	/**
	 * @param ColumnChange[]|\Array_hx $columns
	 * 
	 * @return bool
	 */
	public function alterColumns ($columns) {
		#coopy/JsonTable.hx:106: characters 9-21
		return false;
	}

	/**
	 * @param CompareFlags $flags
	 * 
	 * @return bool
	 */
	public function applyFlags ($flags) {
		#coopy/JsonTable.hx:114: characters 9-21
		return false;
	}

	/**
	 * @return Table
	 */
	public function asTable () {
		#coopy/JsonTable.hx:118: characters 9-20
		return null;
	}

	/**
	 * @param RowChange $rc
	 * 
	 * @return bool
	 */
	public function changeRow ($rc) {
		#coopy/JsonTable.hx:110: characters 9-21
		return false;
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
		#coopy/JsonTable.hx:91: characters 9-20
		return null;
	}

	/**
	 * @param Table $table
	 * 
	 * @return Meta
	 */
	public function cloneMeta ($table = null) {
		#coopy/JsonTable.hx:122: characters 9-20
		return null;
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/JsonTable.hx:102: characters 9-20
		return null;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/JsonTable.hx:45: lines 45-47
		if ($y === 0) {
			#coopy/JsonTable.hx:46: characters 20-30
			return ($this->idx2col->data[$x] ?? null);
		}
		#coopy/JsonTable.hx:48: characters 9-52
		return \Reflect::field(($this->rows->arr[$y - 1] ?? null), ($this->idx2col->data[$x] ?? null));
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/JsonTable.hx:60: characters 9-32
		return new SimpleView();
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/JsonTable.hx:87: characters 9-20
		return null;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/JsonTable.hx:98: characters 9-20
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName () {
		#coopy/JsonTable.hx:146: characters 9-20
		return $this->name;
	}

	/**
	 * @return RowStream
	 */
	public function getRowStream () {
		#coopy/JsonTable.hx:134: characters 9-20
		return null;
	}

	/**
	 * @return Table
	 */
	public function getTable () {
		#coopy/JsonTable.hx:30: characters 9-20
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/JsonTable.hx:41: characters 9-19
		return $this->h + 1;
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/JsonTable.hx:37: characters 9-17
		return $this->w;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/JsonTable.hx:79: characters 9-21
		return false;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/JsonTable.hx:75: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isNested () {
		#coopy/JsonTable.hx:138: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/JsonTable.hx:64: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isSql () {
		#coopy/JsonTable.hx:142: characters 9-21
		return false;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/JsonTable.hx:68: characters 9-21
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
		#coopy/JsonTable.hx:52: characters 9-14
		(Log::$trace)("JsonTable is read-only", new _HxAnon_JsonTable0("coopy/JsonTable.hx", 52, "coopy.JsonTable", "setCell"));
	}

	/**
	 * @param Meta $meta
	 * 
	 * @return void
	 */
	public function setMeta ($meta) {
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/JsonTable.hx:56: characters 9-18
		return "";
	}

	/**
	 * @return bool
	 */
	public function trimBlank () {
		#coopy/JsonTable.hx:83: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function useForColumnChanges () {
		#coopy/JsonTable.hx:126: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function useForRowChanges () {
		#coopy/JsonTable.hx:130: characters 9-21
		return false;
	}

	public function __toString() {
		return $this->toString();
	}
}

class _HxAnon_JsonTable0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

Boot::registerClass(JsonTable::class, 'coopy.JsonTable');
Boot::registerGetters('coopy\\JsonTable', [
	'width' => true,
	'height' => true
]);
