<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

class SqlTables implements Table {
	/**
	 * @var SqlDatabase
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
	 * @param SqlDatabase $db
	 * @param CompareFlags $flags
	 * @param string $role
	 * 
	 * @return void
	 */
	public function __construct ($db, $flags, $role) {
		#coopy/SqlTables.hx:14: characters 9-21
		$this->db = $db;
		#coopy/SqlTables.hx:15: characters 9-42
		$helper = $this->db->getHelper();
		#coopy/SqlTables.hx:16: characters 9-46
		$names = $helper->getTableNames($db);
		#coopy/SqlTables.hx:17: characters 9-49
		$allowed = null;
		#coopy/SqlTables.hx:18: characters 9-40
		$count = $names->length;
		#coopy/SqlTables.hx:19: lines 19-30
		if ($flags->tables !== null) {
			#coopy/SqlTables.hx:20: characters 13-47
			$allowed = new StringMap();
			#coopy/SqlTables.hx:21: lines 21-23
			$_g = 0;
			$_g1 = $flags->tables;
			while ($_g < $_g1->length) {
				#coopy/SqlTables.hx:21: characters 18-22
				$name = ($_g1->arr[$_g] ?? null);
				#coopy/SqlTables.hx:21: lines 21-23
				++$_g;
				#coopy/SqlTables.hx:22: characters 17-90
				$key = $flags->getNameByRole($name, $role);
				$value = $flags->getCanonicalName($name);
				$allowed->data[$key] = $value;
			}
			#coopy/SqlTables.hx:24: characters 13-22
			$count = 0;
			#coopy/SqlTables.hx:25: lines 25-29
			$_g = 0;
			while ($_g < $names->length) {
				#coopy/SqlTables.hx:25: characters 18-22
				$name = ($names->arr[$_g] ?? null);
				#coopy/SqlTables.hx:25: lines 25-29
				++$_g;
				#coopy/SqlTables.hx:26: lines 26-28
				if (\array_key_exists($name, $allowed->data)) {
					#coopy/SqlTables.hx:27: characters 21-28
					++$count;
				}
			}
		}
		#coopy/SqlTables.hx:31: characters 9-39
		$this->t = new SimpleTable(2, $count + 1);
		#coopy/SqlTables.hx:32: characters 9-30
		$this->t->setCell(0, 0, "name");
		#coopy/SqlTables.hx:33: characters 9-31
		$this->t->setCell(1, 0, "table");
		#coopy/SqlTables.hx:34: characters 9-33
		$v = $this->t->getCellView();
		#coopy/SqlTables.hx:35: characters 9-20
		$at = 1;
		#coopy/SqlTables.hx:36: lines 36-45
		$_g = 0;
		while ($_g < $names->length) {
			#coopy/SqlTables.hx:36: characters 14-18
			$name = ($names->arr[$_g] ?? null);
			#coopy/SqlTables.hx:36: lines 36-45
			++$_g;
			#coopy/SqlTables.hx:37: characters 13-30
			$cname = $name;
			#coopy/SqlTables.hx:38: lines 38-41
			if ($allowed !== null) {
				#coopy/SqlTables.hx:39: characters 17-52
				if (!\array_key_exists($name, $allowed->data)) {
					#coopy/SqlTables.hx:39: characters 44-52
					continue;
				}
				#coopy/SqlTables.hx:40: characters 17-42
				$cname = ($allowed->data[$name] ?? null);
			}
			#coopy/SqlTables.hx:42: characters 13-34
			$this->t->setCell(0, $at, $cname);
			#coopy/SqlTables.hx:43: characters 13-82
			$this->t->setCell(1, $at, $v->wrapTable(new SqlTable($db, new SqlTableName($name))));
			#coopy/SqlTables.hx:44: characters 13-17
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
		#coopy/SqlTables.hx:98: characters 9-20
		return null;
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/SqlTables.hx:102: characters 9-20
		return null;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/SqlTables.hx:52: characters 9-30
		return $this->t->getCell($x, $y);
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/SqlTables.hx:59: characters 9-31
		return $this->t->getCellView();
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/SqlTables.hx:94: characters 9-20
		return null;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/SqlTables.hx:106: characters 9-46
		return new SimpleMeta($this, true, true);
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/SqlTables.hx:90: characters 9-24
		return $this->t->get_height();
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/SqlTables.hx:86: characters 9-23
		return $this->t->get_width();
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/SqlTables.hx:78: characters 9-21
		return false;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/SqlTables.hx:74: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/SqlTables.hx:63: characters 9-21
		return false;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/SqlTables.hx:67: characters 9-21
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
		#coopy/SqlTables.hx:82: characters 9-21
		return false;
	}
}

Boot::registerClass(SqlTables::class, 'coopy.SqlTables');
Boot::registerGetters('coopy\\SqlTables', [
	'width' => true,
	'height' => true
]);
