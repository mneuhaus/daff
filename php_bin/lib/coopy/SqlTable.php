<?php
/**
 */

namespace coopy;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Log;
use \haxe\ds\IntMap;
use \haxe\ds\StringMap;

class SqlTable implements RowStream, Meta, Table {
	/**
	 * @var IntMap
	 */
	public $cache;
	/**
	 * @var string[]|\Array_hx
	 */
	public $columnNames;
	/**
	 * @var SqlColumn[]|\Array_hx
	 */
	public $columns;
	/**
	 * @var SqlDatabase
	 */
	public $db;
	/**
	 * @var int
	 */
	public $h;
	/**
	 * @var SqlHelper
	 */
	public $helper;
	/**
	 * @var int[]|\Array_hx
	 */
	public $id2rid;
	/**
	 * @var SqlTableName
	 */
	public $name;
	/**
	 * @var string
	 */
	public $quotedTableName;

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param SqlHelper $helper
	 * 
	 * @return void
	 */
	public function __construct ($db, $name, $helper = null) {
		#coopy/SqlTable.hx:30: characters 9-21
		$this->db = $db;
		#coopy/SqlTable.hx:31: characters 9-25
		$this->name = $name;
		#coopy/SqlTable.hx:32: characters 9-29
		$this->helper = $helper;
		#coopy/SqlTable.hx:33: characters 9-55
		if ($helper === null) {
			#coopy/SqlTable.hx:33: characters 27-55
			$this->helper = $db->getHelper();
		}
		#coopy/SqlTable.hx:34: characters 9-48
		$this->cache = new IntMap();
		#coopy/SqlTable.hx:35: characters 9-15
		$this->h = -1;
		#coopy/SqlTable.hx:36: characters 9-22
		$this->id2rid = null;
		#coopy/SqlTable.hx:37: characters 9-21
		$this->getColumns();
	}

	/**
	 * @param ColumnChange[]|\Array_hx $columns
	 * 
	 * @return bool
	 */
	public function alterColumns ($columns) {
		#coopy/SqlTable.hx:176: characters 9-59
		$result = $this->helper->alterColumns($this->db, $this->name, $columns);
		#coopy/SqlTable.hx:177: characters 9-28
		$this->columns = null;
		#coopy/SqlTable.hx:178: characters 9-22
		return $result;
	}

	/**
	 * @param CompareFlags $flags
	 * 
	 * @return bool
	 */
	public function applyFlags ($flags) {
		#coopy/SqlTable.hx:226: characters 9-21
		return false;
	}

	/**
	 * @return Table
	 */
	public function asTable () {
		#coopy/SqlTable.hx:197: characters 9-21
		$pct = 3;
		#coopy/SqlTable.hx:198: characters 9-21
		$this->getColumns();
		#coopy/SqlTable.hx:199: characters 9-36
		$w = $this->columnNames->length;
		#coopy/SqlTable.hx:200: characters 9-43
		$mt = new SimpleTable($w + 1, $pct);
		#coopy/SqlTable.hx:201: characters 9-28
		$mt->setCell(0, 0, "@");
		#coopy/SqlTable.hx:202: characters 9-31
		$mt->setCell(0, 1, "type");
		#coopy/SqlTable.hx:203: characters 9-30
		$mt->setCell(0, 2, "key");
		#coopy/SqlTable.hx:204: characters 19-23
		$_g = 0;
		#coopy/SqlTable.hx:204: characters 23-24
		$_g1 = $w;
		#coopy/SqlTable.hx:204: lines 204-209
		while ($_g < $_g1) {
			#coopy/SqlTable.hx:204: characters 19-24
			$x = $_g++;
			#coopy/SqlTable.hx:205: characters 13-25
			$i = $x + 1;
			#coopy/SqlTable.hx:206: characters 13-43
			$mt->setCell($i, 0, ($this->columnNames->arr[$x] ?? null));
			#coopy/SqlTable.hx:207: characters 13-50
			$mt->setCell($i, 1, ($this->columns->arr[$x] ?? null)->type_value);
			#coopy/SqlTable.hx:208: characters 13-64
			$mt->setCell($i, 2, (($this->columns->arr[$x] ?? null)->primary ? "primary" : ""));
		}
		#coopy/SqlTable.hx:210: characters 9-18
		return $mt;
	}

	/**
	 * @param RowChange $rc
	 * 
	 * @return bool
	 */
	public function changeRow ($rc) {
		#coopy/SqlTable.hx:182: lines 182-185
		if ($this->helper === null) {
			#coopy/SqlTable.hx:183: characters 13-18
			(Log::$trace)("No sql helper", new _HxAnon_SqlTable0("coopy/SqlTable.hx", 183, "coopy.SqlTable", "changeRow"));
			#coopy/SqlTable.hx:184: characters 13-25
			return false;
		}
		#coopy/SqlTable.hx:186: lines 186-192
		if ($rc->action === "+++") {
			#coopy/SqlTable.hx:187: characters 13-49
			return $this->helper->insert($this->db, $this->name, $rc->val);
		} else if ($rc->action === "---") {
			#coopy/SqlTable.hx:189: characters 13-50
			return $this->helper->delete($this->db, $this->name, $rc->cond);
		} else if ($rc->action === "->") {
			#coopy/SqlTable.hx:191: characters 13-57
			return $this->helper->update($this->db, $this->name, $rc->cond, $rc->val);
		}
		#coopy/SqlTable.hx:193: characters 9-21
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
		#coopy/SqlTable.hx:163: characters 9-20
		return null;
	}

	/**
	 * @param Table $table
	 * 
	 * @return Meta
	 */
	public function cloneMeta ($table = null) {
		#coopy/SqlTable.hx:222: characters 9-20
		return null;
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/SqlTable.hx:167: characters 9-20
		return null;
	}

	/**
	 * @return string[]|\Array_hx
	 */
	public function fetchColumns () {
		#coopy/SqlTable.hx:260: characters 9-21
		$this->getColumns();
		#coopy/SqlTable.hx:261: characters 9-27
		return $this->columnNames;
	}

	/**
	 * @return StringMap
	 */
	public function fetchRow () {
		#coopy/SqlTable.hx:248: lines 248-254
		if ($this->db->read()) {
			#coopy/SqlTable.hx:249: characters 13-49
			$row = new StringMap();
			#coopy/SqlTable.hx:250: characters 23-27
			$_g = 0;
			#coopy/SqlTable.hx:250: characters 27-45
			$_g1 = $this->columnNames->length;
			#coopy/SqlTable.hx:250: lines 250-252
			while ($_g < $_g1) {
				#coopy/SqlTable.hx:250: characters 23-45
				$i = $_g++;
				#coopy/SqlTable.hx:251: characters 17-48
				$k = ($this->columnNames->arr[$i] ?? null);
				$v = $this->db->get($i);
				$row->data[$k] = $v;
			}
			#coopy/SqlTable.hx:253: characters 13-23
			return $row;
		}
		#coopy/SqlTable.hx:255: characters 9-17
		$this->db->end();
		#coopy/SqlTable.hx:256: characters 9-20
		return null;
	}

	/**
	 * @return string[]|\Array_hx
	 */
	public function getAllButPrimaryKey () {
		#coopy/SqlTable.hx:51: characters 9-21
		$this->getColumns();
		#coopy/SqlTable.hx:52: characters 9-42
		$result = new \Array_hx();
		#coopy/SqlTable.hx:53: lines 53-56
		$_g = 0;
		$_g1 = $this->columns;
		while ($_g < $_g1->length) {
			#coopy/SqlTable.hx:53: characters 14-17
			$col = ($_g1->arr[$_g] ?? null);
			#coopy/SqlTable.hx:53: lines 53-56
			++$_g;
			#coopy/SqlTable.hx:54: characters 13-45
			if ($col->isPrimaryKey()) {
				#coopy/SqlTable.hx:54: characters 37-45
				continue;
			}
			#coopy/SqlTable.hx:55: characters 13-39
			$x = $col->getName();
			$result->arr[$result->length++] = $x;
		}
		#coopy/SqlTable.hx:57: characters 9-22
		return $result;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/SqlTable.hx:76: lines 76-83
		if ($this->h >= 0) {
			#coopy/SqlTable.hx:77: characters 13-20
			--$y;
			#coopy/SqlTable.hx:78: lines 78-80
			if ($y >= 0) {
				#coopy/SqlTable.hx:79: characters 17-30
				$y = ($this->id2rid->arr[$y] ?? null);
			}
		} else if ($y === 0) {
			#coopy/SqlTable.hx:82: characters 13-19
			$y = -1;
		}
		#coopy/SqlTable.hx:84: lines 84-87
		if ($y < 0) {
			#coopy/SqlTable.hx:85: characters 13-25
			$this->getColumns();
			#coopy/SqlTable.hx:86: characters 13-35
			return ($this->columns->arr[$x] ?? null)->name;
		}
		#coopy/SqlTable.hx:88: characters 9-28
		$row = ($this->cache->data[$y] ?? null);
		#coopy/SqlTable.hx:89: lines 89-100
		if ($row === null) {
			#coopy/SqlTable.hx:90: characters 13-41
			$row = new IntMap();
			#coopy/SqlTable.hx:91: characters 13-25
			$this->getColumns();
			#coopy/SqlTable.hx:92: characters 13-44
			$this->db->beginRow($this->name, $y, $this->columnNames);
			#coopy/SqlTable.hx:93: lines 93-97
			while ($this->db->read()) {
				#coopy/SqlTable.hx:94: characters 27-31
				$_g = 0;
				#coopy/SqlTable.hx:94: characters 31-36
				$_g1 = $this->get_width();
				#coopy/SqlTable.hx:94: lines 94-96
				while ($_g < $_g1) {
					#coopy/SqlTable.hx:94: characters 27-36
					$i = $_g++;
					#coopy/SqlTable.hx:95: characters 21-39
					$v = $this->db->get($i);
					$row->data[$i] = $v;
				}
			}
			#coopy/SqlTable.hx:98: characters 13-21
			$this->db->end();
			#coopy/SqlTable.hx:99: characters 13-27
			$this->cache->data[$y] = $row;
		}
		#coopy/SqlTable.hx:101: characters 16-27
		return (($this->cache->data[$y] ?? null)->data[$x] ?? null);
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/SqlTable.hx:119: characters 9-32
		return new SimpleView();
	}

	/**
	 * @return string[]|\Array_hx
	 */
	public function getColumnNames () {
		#coopy/SqlTable.hx:61: characters 9-21
		$this->getColumns();
		#coopy/SqlTable.hx:62: characters 9-27
		return $this->columnNames;
	}

	/**
	 * @return void
	 */
	public function getColumns () {
		#coopy/SqlTable.hx:20: characters 9-34
		if ($this->columns !== null) {
			#coopy/SqlTable.hx:20: characters 28-34
			return;
		}
		#coopy/SqlTable.hx:21: characters 9-29
		if ($this->db === null) {
			#coopy/SqlTable.hx:21: characters 23-29
			return;
		}
		#coopy/SqlTable.hx:22: characters 9-38
		$this->columns = $this->db->getColumns($this->name);
		#coopy/SqlTable.hx:23: characters 9-42
		$this->columnNames = new \Array_hx();
		#coopy/SqlTable.hx:24: lines 24-26
		$_g = 0;
		$_g1 = $this->columns;
		while ($_g < $_g1->length) {
			#coopy/SqlTable.hx:24: characters 14-17
			$col = ($_g1->arr[$_g] ?? null);
			#coopy/SqlTable.hx:24: lines 24-26
			++$_g;
			#coopy/SqlTable.hx:25: characters 13-44
			$_this = $this->columnNames;
			$x = $col->getName();
			$_this->arr[$_this->length++] = $x;
		}
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/SqlTable.hx:159: characters 9-20
		return null;
	}

	/**
	 * @return SqlDatabase
	 */
	public function getDatabase () {
		#coopy/SqlTable.hx:230: characters 9-18
		return $this->db;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/SqlTable.hx:171: characters 9-20
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName () {
		#coopy/SqlTable.hx:265: characters 9-31
		return $this->name->toString();
	}

	/**
	 * @return string[]|\Array_hx
	 */
	public function getPrimaryKey () {
		#coopy/SqlTable.hx:41: characters 9-21
		$this->getColumns();
		#coopy/SqlTable.hx:42: characters 9-42
		$result = new \Array_hx();
		#coopy/SqlTable.hx:43: lines 43-46
		$_g = 0;
		$_g1 = $this->columns;
		while ($_g < $_g1->length) {
			#coopy/SqlTable.hx:43: characters 14-17
			$col = ($_g1->arr[$_g] ?? null);
			#coopy/SqlTable.hx:43: lines 43-46
			++$_g;
			#coopy/SqlTable.hx:44: characters 13-46
			if (!$col->isPrimaryKey()) {
				#coopy/SqlTable.hx:44: characters 38-46
				continue;
			}
			#coopy/SqlTable.hx:45: characters 13-39
			$x = $col->getName();
			$result->arr[$result->length++] = $x;
		}
		#coopy/SqlTable.hx:47: characters 9-22
		return $result;
	}

	/**
	 * @param string $name
	 * 
	 * @return string
	 */
	public function getQuotedColumnName ($name) {
		#coopy/SqlTable.hx:72: characters 9-44
		return $this->db->getQuotedColumnName($name);
	}

	/**
	 * @return string
	 */
	public function getQuotedTableName () {
		#coopy/SqlTable.hx:66: characters 9-58
		if ($this->quotedTableName !== null) {
			#coopy/SqlTable.hx:66: characters 36-58
			return $this->quotedTableName;
		}
		#coopy/SqlTable.hx:67: characters 9-54
		$this->quotedTableName = $this->db->getQuotedTableName($this->name);
		#coopy/SqlTable.hx:68: characters 9-31
		return $this->quotedTableName;
	}

	/**
	 * @return RowStream
	 */
	public function getRowStream () {
		#coopy/SqlTable.hx:234: characters 9-21
		$this->getColumns();
		#coopy/SqlTable.hx:235: characters 9-99
		$this->db->begin("SELECT * FROM " . ($this->getQuotedTableName()??'null') . " ORDER BY ?", \Array_hx::wrap([$this->db->rowid()]), $this->columnNames);
		#coopy/SqlTable.hx:236: characters 9-20
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/SqlTable.hx:154: characters 9-27
		if ($this->h >= 0) {
			#coopy/SqlTable.hx:154: characters 19-27
			return $this->h;
		}
		#coopy/SqlTable.hx:155: characters 9-18
		return -1;
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/SqlTable.hx:149: characters 9-21
		$this->getColumns();
		#coopy/SqlTable.hx:150: characters 9-30
		return $this->columns->length;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/SqlTable.hx:138: characters 9-21
		return false;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/SqlTable.hx:134: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isNested () {
		#coopy/SqlTable.hx:240: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/SqlTable.hx:123: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function isSql () {
		#coopy/SqlTable.hx:244: characters 9-20
		return true;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/SqlTable.hx:127: characters 9-21
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
		#coopy/SqlTable.hx:115: characters 9-14
		(Log::$trace)("SqlTable cannot set cells yet", new _HxAnon_SqlTable0("coopy/SqlTable.hx", 115, "coopy.SqlTable", "setCell"));
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param mixed $c
	 * 
	 * @return void
	 */
	public function setCellCache ($x, $y, $c) {
		#coopy/SqlTable.hx:105: characters 9-28
		$row = ($this->cache->data[$y] ?? null);
		#coopy/SqlTable.hx:106: lines 106-110
		if ($row === null) {
			#coopy/SqlTable.hx:107: characters 13-41
			$row = new IntMap();
			#coopy/SqlTable.hx:108: characters 13-25
			$this->getColumns();
			#coopy/SqlTable.hx:109: characters 13-27
			$this->cache->data[$y] = $row;
		}
		#coopy/SqlTable.hx:111: characters 9-19
		$v = $c;
		$row->data[$x] = $v;
	}

	/**
	 * @return bool
	 */
	public function trimBlank () {
		#coopy/SqlTable.hx:142: characters 9-21
		return false;
	}

	/**
	 * @return bool
	 */
	public function useForColumnChanges () {
		#coopy/SqlTable.hx:214: characters 9-20
		return true;
	}

	/**
	 * @return bool
	 */
	public function useForRowChanges () {
		#coopy/SqlTable.hx:218: characters 9-20
		return true;
	}
}

class _HxAnon_SqlTable0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

Boot::registerClass(SqlTable::class, 'coopy.SqlTable');
Boot::registerGetters('coopy\\SqlTable', [
	'width' => true,
	'height' => true
]);
