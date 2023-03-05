<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

/**
 *
 * Experimental wrapper for reading tables expressed as json in following
 * format:
 *
 * {
 *   "names": ["sheet1", "sheet2"],
 *   "tables": {
 *     "sheet1": {
 *        "columns": ["col1", "col2", "col3"],
 *        "rows": [
 *            { "col1": 42, "col2": "x", "col3": null },
 *            { "col1": 24, "col2": "y", "col3": null },
 *            ...
 *        ]
 *     },
 *     "sheet2": {
 *        ...
 *     }
 *   }
 * }
 *
 *
 */
class JsonTables implements Table {
	/**
	 * @var mixed
	 */
	public $db;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var Table
	 */
	public $t;

	/**
	 * @param mixed $json
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($json, $flags) {
		#coopy/JsonTables.hx:37: characters 9-23
		$this->db = $json;
		#coopy/JsonTables.hx:38: characters 9-66
		$names = \Reflect::field($json, "names");
		#coopy/JsonTables.hx:39: characters 9-47
		$allowed = null;
		#coopy/JsonTables.hx:40: characters 9-40
		$count = $names->length;
		#coopy/JsonTables.hx:41: lines 41-52
		if (($flags !== null) && ($flags->tables !== null)) {
			#coopy/JsonTables.hx:42: characters 13-45
			$allowed = new StringMap();
			#coopy/JsonTables.hx:43: lines 43-45
			$_g = 0;
			$_g1 = $flags->tables;
			while ($_g < $_g1->length) {
				#coopy/JsonTables.hx:43: characters 18-22
				$name = ($_g1->arr[$_g] ?? null);
				#coopy/JsonTables.hx:43: lines 43-45
				++$_g;
				#coopy/JsonTables.hx:44: characters 17-39
				$allowed->data[$name] = true;
			}
			#coopy/JsonTables.hx:46: characters 13-22
			$count = 0;
			#coopy/JsonTables.hx:47: lines 47-51
			$_g = 0;
			while ($_g < $names->length) {
				#coopy/JsonTables.hx:47: characters 18-22
				$name = ($names->arr[$_g] ?? null);
				#coopy/JsonTables.hx:47: lines 47-51
				++$_g;
				#coopy/JsonTables.hx:48: lines 48-50
				if (\array_key_exists($name, $allowed->data)) {
					#coopy/JsonTables.hx:49: characters 21-28
					++$count;
				}
			}
		}
		#coopy/JsonTables.hx:53: characters 9-39
		$this->t = new SimpleTable(2, $count + 1);
		#coopy/JsonTables.hx:54: characters 9-30
		$this->t->setCell(0, 0, "name");
		#coopy/JsonTables.hx:55: characters 9-31
		$this->t->setCell(1, 0, "table");
		#coopy/JsonTables.hx:56: characters 9-33
		$v = $this->t->getCellView();
		#coopy/JsonTables.hx:57: characters 9-20
		$at = 1;
		#coopy/JsonTables.hx:58: lines 58-67
		$_g = 0;
		while ($_g < $names->length) {
			#coopy/JsonTables.hx:58: characters 14-18
			$name = ($names->arr[$_g] ?? null);
			#coopy/JsonTables.hx:58: lines 58-67
			++$_g;
			#coopy/JsonTables.hx:59: lines 59-61
			if ($allowed !== null) {
				#coopy/JsonTables.hx:60: characters 17-52
				if (!\array_key_exists($name, $allowed->data)) {
					#coopy/JsonTables.hx:60: characters 44-52
					continue;
				}
			}
			#coopy/JsonTables.hx:62: characters 13-33
			$this->t->setCell(0, $at, $name);
			#coopy/JsonTables.hx:63: characters 13-51
			$tab = \Reflect::field($this->db, "tables");
			#coopy/JsonTables.hx:64: characters 13-43
			$tab = \Reflect::field($tab, $name);
			#coopy/JsonTables.hx:65: characters 13-66
			$this->t->setCell(1, $at, $v->wrapTable(new JsonTable($tab, $name)));
			#coopy/JsonTables.hx:66: characters 13-17
			++$at;
		}
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
		#coopy/JsonTables.hx:120: characters 9-20
		return null;
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/JsonTables.hx:128: characters 9-20
		return null;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/JsonTables.hx:74: characters 9-30
		return $this->t->getCell($x, $y);
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/JsonTables.hx:81: characters 9-31
		return $this->t->getCellView();
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/JsonTables.hx:116: characters 9-20
		return null;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/JsonTables.hx:124: characters 9-46
		return new SimpleMeta($this, true, true);
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/JsonTables.hx:112: characters 9-24
		return $this->t->get_height();
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/JsonTables.hx:108: characters 9-23
		return $this->t->get_width();
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/JsonTables.hx:100: characters 9-21
		return false;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/JsonTables.hx:96: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/JsonTables.hx:85: characters 9-21
		return false;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/JsonTables.hx:89: characters 9-21
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
	}

	/**
	 * @return bool
	 */
	public function trimBlank () {
		#coopy/JsonTables.hx:104: characters 9-21
		return false;
	}
}

Boot::registerClass(JsonTables::class, 'coopy.JsonTables');
Boot::registerGetters('coopy\\JsonTables', [
	'width' => true,
	'height' => true
]);
