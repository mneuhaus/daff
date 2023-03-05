<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\IntMap;

/**
 *
 * A basic table implementation. Each supported language should
 * have an optimized native implementation that you can use instead.
 * See the `Table` interface for documentation.
 *
 */
class SimpleTable implements Table {
	/**
	 * @var IntMap
	 */
	public $data;
	/**
	 * @var int
	 */
	public $h;
	/**
	 * @var Meta
	 */
	public $meta;
	/**
	 * @var int
	 */
	public $w;

	/**
	 *
	 * Compare the content of two tables.
	 *
	 * @param tab1 the first table
	 * @param tab2 the second table
	 * @return true if the tables are identical
	 *
	 * 
	 * @param Table $tab1
	 * @param Table $tab2
	 * 
	 * @return bool
	 */
	public static function tableIsSimilar ($tab1, $tab2) {
		#coopy/SimpleTable.hx:115: lines 115-120
		if (($tab1->get_height() === -1) || ($tab2->get_height() === -1)) {
			#coopy/SimpleTable.hx:117: characters 13-44
			$txt1 = SimpleTable::tableToString($tab1);
			#coopy/SimpleTable.hx:118: characters 13-44
			$txt2 = SimpleTable::tableToString($tab2);
			#coopy/SimpleTable.hx:119: characters 13-32
			return $txt1 === $txt2;
		}
		#coopy/SimpleTable.hx:121: characters 9-49
		if ($tab1->get_width() !== $tab2->get_width()) {
			#coopy/SimpleTable.hx:121: characters 37-49
			return false;
		}
		#coopy/SimpleTable.hx:122: characters 9-51
		if ($tab1->get_height() !== $tab2->get_height()) {
			#coopy/SimpleTable.hx:122: characters 39-51
			return false;
		}
		#coopy/SimpleTable.hx:123: characters 9-36
		$v = $tab1->getCellView();
		#coopy/SimpleTable.hx:124: characters 19-23
		$_g = 0;
		#coopy/SimpleTable.hx:124: characters 23-34
		$_g1 = $tab1->get_height();
		#coopy/SimpleTable.hx:124: lines 124-128
		while ($_g < $_g1) {
			#coopy/SimpleTable.hx:124: characters 19-34
			$i = $_g++;
			#coopy/SimpleTable.hx:125: characters 23-27
			$_g2 = 0;
			#coopy/SimpleTable.hx:125: characters 27-37
			$_g3 = $tab1->get_width();
			#coopy/SimpleTable.hx:125: lines 125-127
			while ($_g2 < $_g3) {
				#coopy/SimpleTable.hx:125: characters 23-37
				$j = $_g2++;
				#coopy/SimpleTable.hx:126: characters 17-81
				if (!$v->equals($tab1->getCell($j, $i), $tab2->getCell($j, $i))) {
					#coopy/SimpleTable.hx:126: characters 69-81
					return false;
				}
			}
		}
		#coopy/SimpleTable.hx:129: characters 9-20
		return true;
	}

	/**
	 *
	 * Render the table as a string
	 *
	 * @param tab the table
	 * @return a text version of the table
	 *
	 * 
	 * @param Table $tab
	 * 
	 * @return string
	 */
	public static function tableToString ($tab) {
		#coopy/SimpleTable.hx:71: characters 9-34
		$meta = $tab->getMeta();
		#coopy/SimpleTable.hx:72: lines 72-93
		if ($meta !== null) {
			#coopy/SimpleTable.hx:73: characters 13-46
			$stream = $meta->getRowStream();
			#coopy/SimpleTable.hx:74: lines 74-92
			if ($stream !== null) {
				#coopy/SimpleTable.hx:75: characters 17-37
				$x = "";
				#coopy/SimpleTable.hx:76: characters 17-50
				$cols = $stream->fetchColumns();
				#coopy/SimpleTable.hx:77: characters 27-31
				$_g = 0;
				#coopy/SimpleTable.hx:77: characters 31-42
				$_g1 = $cols->length;
				#coopy/SimpleTable.hx:77: lines 77-80
				while ($_g < $_g1) {
					#coopy/SimpleTable.hx:77: characters 27-42
					$i = $_g++;
					#coopy/SimpleTable.hx:78: characters 21-38
					if ($i > 0) {
						#coopy/SimpleTable.hx:78: characters 30-38
						$x = ($x??'null') . ",";
					}
					#coopy/SimpleTable.hx:79: characters 21-33
					$x = ($x??'null') . (($cols->arr[$i] ?? null)??'null');
				}
				#coopy/SimpleTable.hx:81: characters 17-26
				$x = ($x??'null') . "\x0A";
				#coopy/SimpleTable.hx:82: characters 17-67
				$row = $stream->fetchRow();
				#coopy/SimpleTable.hx:83: lines 83-90
				while ($row !== null) {
					#coopy/SimpleTable.hx:84: characters 31-35
					$_g = 0;
					#coopy/SimpleTable.hx:84: characters 35-46
					$_g1 = $cols->length;
					#coopy/SimpleTable.hx:84: lines 84-87
					while ($_g < $_g1) {
						#coopy/SimpleTable.hx:84: characters 31-46
						$i = $_g++;
						#coopy/SimpleTable.hx:85: characters 25-42
						if ($i > 0) {
							#coopy/SimpleTable.hx:85: characters 34-42
							$x = ($x??'null') . ",";
						}
						#coopy/SimpleTable.hx:86: characters 25-42
						$x = ($x??'null') . \Std::string(($row->data[($cols->arr[$i] ?? null)] ?? null));
					}
					#coopy/SimpleTable.hx:88: characters 21-30
					$x = ($x??'null') . "\x0A";
					#coopy/SimpleTable.hx:89: characters 21-44
					$row = $stream->fetchRow();
				}
				#coopy/SimpleTable.hx:91: characters 17-25
				return $x;
			}
		}
		#coopy/SimpleTable.hx:94: characters 9-29
		$x = "";
		#coopy/SimpleTable.hx:95: characters 19-23
		$_g = 0;
		#coopy/SimpleTable.hx:95: characters 23-33
		$_g1 = $tab->get_height();
		#coopy/SimpleTable.hx:95: lines 95-101
		while ($_g < $_g1) {
			#coopy/SimpleTable.hx:95: characters 19-33
			$i = $_g++;
			#coopy/SimpleTable.hx:96: characters 23-27
			$_g2 = 0;
			#coopy/SimpleTable.hx:96: characters 27-36
			$_g3 = $tab->get_width();
			#coopy/SimpleTable.hx:96: lines 96-99
			while ($_g2 < $_g3) {
				#coopy/SimpleTable.hx:96: characters 23-36
				$j = $_g2++;
				#coopy/SimpleTable.hx:97: characters 17-34
				if ($j > 0) {
					#coopy/SimpleTable.hx:97: characters 26-34
					$x = ($x??'null') . ",";
				}
				#coopy/SimpleTable.hx:98: characters 17-38
				$x = ($x??'null') . \Std::string($tab->getCell($j, $i));
			}
			#coopy/SimpleTable.hx:100: characters 13-22
			$x = ($x??'null') . "\x0A";
		}
		#coopy/SimpleTable.hx:102: characters 9-17
		return $x;
	}

	/**
	 *
	 * Constructor.
	 * @param w the desired width of the table
	 * @param h the desired height of the table
	 *
	 * 
	 * @param int $w
	 * @param int $h
	 * 
	 * @return void
	 */
	public function __construct ($w, $h) {
		#coopy/SimpleTable.hx:29: characters 9-38
		$this->data = new IntMap();
		#coopy/SimpleTable.hx:30: characters 9-19
		$this->w = $w;
		#coopy/SimpleTable.hx:31: characters 9-19
		$this->h = $h;
		#coopy/SimpleTable.hx:32: characters 9-25
		$this->meta = null;
	}

	/**
	 * @return void
	 */
	public function clear () {
		#coopy/SimpleTable.hx:147: characters 9-38
		$this->data = new IntMap();
	}

	/**
	 * @return Table
	 */
	public function clone () {
		#coopy/SimpleTable.hx:236: characters 38-43
		$result = $this->get_width();
		#coopy/SimpleTable.hx:236: characters 9-52
		$result1 = new SimpleTable($result, $this->get_height());
		#coopy/SimpleTable.hx:237: characters 19-23
		$_g = 0;
		#coopy/SimpleTable.hx:237: characters 23-29
		$_g1 = $this->get_height();
		#coopy/SimpleTable.hx:237: lines 237-241
		while ($_g < $_g1) {
			#coopy/SimpleTable.hx:237: characters 19-29
			$i = $_g++;
			#coopy/SimpleTable.hx:238: characters 23-27
			$_g2 = 0;
			#coopy/SimpleTable.hx:238: characters 27-32
			$_g3 = $this->get_width();
			#coopy/SimpleTable.hx:238: lines 238-240
			while ($_g2 < $_g3) {
				#coopy/SimpleTable.hx:238: characters 23-32
				$j = $_g2++;
				#coopy/SimpleTable.hx:239: characters 17-49
				$result1->setCell($j, $i, $this->getCell($j, $i));
			}
		}
		#coopy/SimpleTable.hx:242: lines 242-244
		if ($this->meta !== null) {
			#coopy/SimpleTable.hx:243: characters 13-49
			$result1->meta = $this->meta->cloneMeta($result1);
		}
		#coopy/SimpleTable.hx:245: characters 9-22
		return $result1;
	}

	/**
	 * @return Table
	 */
	public function create () {
		#coopy/SimpleTable.hx:249: characters 32-37
		$tmp = $this->get_width();
		#coopy/SimpleTable.hx:249: characters 9-45
		return new SimpleTable($tmp, $this->get_height());
	}

	/**
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) {
		#coopy/SimpleTable.hx:51: characters 16-31
		return ($this->data->data[$x + $y * $this->w] ?? null);
	}

	/**
	 * @return View
	 */
	public function getCellView () {
		#coopy/SimpleTable.hx:133: characters 9-32
		return new SimpleView();
	}

	/**
	 * @return mixed
	 */
	public function getData () {
		#coopy/SimpleTable.hx:232: characters 9-20
		return null;
	}

	/**
	 * @return Meta
	 */
	public function getMeta () {
		#coopy/SimpleTable.hx:257: characters 9-20
		return $this->meta;
	}

	/**
	 * @return Table
	 */
	public function getTable () {
		#coopy/SimpleTable.hx:36: characters 9-20
		return $this;
	}

	/**
	 * @return int
	 */
	public function get_height () {
		#coopy/SimpleTable.hx:47: characters 9-17
		return $this->h;
	}

	/**
	 * @return int
	 */
	public function get_width () {
		#coopy/SimpleTable.hx:43: characters 9-17
		return $this->w;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) {
		#coopy/SimpleTable.hx:169: characters 9-63
		$data2 = new IntMap();
		#coopy/SimpleTable.hx:170: characters 19-23
		$_g = 0;
		#coopy/SimpleTable.hx:170: characters 23-34
		$_g1 = $fate->length;
		#coopy/SimpleTable.hx:170: lines 170-180
		while ($_g < $_g1) {
			#coopy/SimpleTable.hx:170: characters 19-34
			$i = $_g++;
			#coopy/SimpleTable.hx:171: characters 13-35
			$j = ($fate->arr[$i] ?? null);
			#coopy/SimpleTable.hx:172: lines 172-179
			if ($j !== -1) {
				#coopy/SimpleTable.hx:173: characters 27-31
				$_g2 = 0;
				#coopy/SimpleTable.hx:173: characters 31-32
				$_g3 = $this->h;
				#coopy/SimpleTable.hx:173: lines 173-178
				while ($_g2 < $_g3) {
					#coopy/SimpleTable.hx:173: characters 27-32
					$r = $_g2++;
					#coopy/SimpleTable.hx:174: characters 21-43
					$idx = $r * $this->w + $i;
					#coopy/SimpleTable.hx:175: lines 175-177
					if (\array_key_exists($idx, $this->data->data)) {
						#coopy/SimpleTable.hx:176: characters 25-59
						$value = ($this->data->data[$idx] ?? null);
						$data2->data[$r * $wfate + $j] = $value;
					}
				}
			}
		}
		#coopy/SimpleTable.hx:181: characters 9-18
		$this->w = $wfate;
		#coopy/SimpleTable.hx:182: characters 9-21
		$this->data = $data2;
		#coopy/SimpleTable.hx:183: characters 9-20
		return true;
	}

	/**
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) {
		#coopy/SimpleTable.hx:151: characters 9-63
		$data2 = new IntMap();
		#coopy/SimpleTable.hx:152: characters 19-23
		$_g = 0;
		#coopy/SimpleTable.hx:152: characters 23-34
		$_g1 = $fate->length;
		#coopy/SimpleTable.hx:152: lines 152-162
		while ($_g < $_g1) {
			#coopy/SimpleTable.hx:152: characters 19-34
			$i = $_g++;
			#coopy/SimpleTable.hx:153: characters 13-35
			$j = ($fate->arr[$i] ?? null);
			#coopy/SimpleTable.hx:154: lines 154-161
			if ($j !== -1) {
				#coopy/SimpleTable.hx:155: characters 27-31
				$_g2 = 0;
				#coopy/SimpleTable.hx:155: characters 31-32
				$_g3 = $this->w;
				#coopy/SimpleTable.hx:155: lines 155-160
				while ($_g2 < $_g3) {
					#coopy/SimpleTable.hx:155: characters 27-32
					$c = $_g2++;
					#coopy/SimpleTable.hx:156: characters 21-43
					$idx = $i * $this->w + $c;
					#coopy/SimpleTable.hx:157: lines 157-159
					if (\array_key_exists($idx, $this->data->data)) {
						#coopy/SimpleTable.hx:158: characters 25-55
						$key = $j * $this->w + $c;
						$value = ($this->data->data[$idx] ?? null);
						$data2->data[$key] = $value;
					}
				}
			}
		}
		#coopy/SimpleTable.hx:163: characters 9-18
		$this->h = $hfate;
		#coopy/SimpleTable.hx:164: characters 9-21
		$this->data = $data2;
		#coopy/SimpleTable.hx:165: characters 9-20
		return true;
	}

	/**
	 * @return bool
	 */
	public function isResizable () {
		#coopy/SimpleTable.hx:137: characters 9-20
		return true;
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) {
		#coopy/SimpleTable.hx:141: characters 9-19
		$this->w = $w;
		#coopy/SimpleTable.hx:142: characters 9-19
		$this->h = $h;
		#coopy/SimpleTable.hx:143: characters 9-20
		return true;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param mixed $c
	 * 
	 * @return void
	 */
	public function setCell ($x, $y, $c) {
		#coopy/SimpleTable.hx:55: characters 9-26
		$this->data->data[$x + $y * $this->w] = $c;
	}

	/**
	 * @param Meta $meta
	 * 
	 * @return void
	 */
	public function setMeta ($meta) {
		#coopy/SimpleTable.hx:253: characters 9-25
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/SimpleTable.hx:59: characters 9-35
		return SimpleTable::tableToString($this);
	}

	/**
	 * @return bool
	 */
	public function trimBlank () {
		#coopy/SimpleTable.hx:187: characters 9-30
		if ($this->h === 0) {
			#coopy/SimpleTable.hx:187: characters 19-30
			return true;
		}
		#coopy/SimpleTable.hx:188: characters 9-30
		$h_test = $this->h;
		#coopy/SimpleTable.hx:189: characters 9-34
		if ($h_test >= 3) {
			#coopy/SimpleTable.hx:189: characters 24-34
			$h_test = 3;
		}
		#coopy/SimpleTable.hx:190: characters 9-41
		$view = $this->getCellView();
		#coopy/SimpleTable.hx:191: characters 9-48
		$space = $view->toDatum("");
		#coopy/SimpleTable.hx:192: characters 9-32
		$more = true;
		#coopy/SimpleTable.hx:193: lines 193-202
		while ($more) {
			#coopy/SimpleTable.hx:194: characters 23-27
			$_g = 0;
			#coopy/SimpleTable.hx:194: characters 27-32
			$_g1 = $this->get_width();
			#coopy/SimpleTable.hx:194: lines 194-200
			while ($_g < $_g1) {
				#coopy/SimpleTable.hx:194: characters 23-32
				$i = $_g++;
				#coopy/SimpleTable.hx:195: characters 17-50
				$c = $this->getCell($i, $this->h - 1);
				#coopy/SimpleTable.hx:196: lines 196-199
				if (!($view->equals($c, $space) || ($c === null))) {
					#coopy/SimpleTable.hx:197: characters 21-33
					$more = false;
					#coopy/SimpleTable.hx:198: characters 21-26
					break;
				}
			}
			#coopy/SimpleTable.hx:201: characters 13-26
			if ($more) {
				#coopy/SimpleTable.hx:201: characters 23-26
				$this->h--;
			}
		}
		#coopy/SimpleTable.hx:203: characters 9-20
		$more = true;
		#coopy/SimpleTable.hx:204: characters 9-26
		$nw = $this->w;
		#coopy/SimpleTable.hx:205: lines 205-215
		while ($more) {
			#coopy/SimpleTable.hx:206: characters 13-28
			if ($this->w === 0) {
				#coopy/SimpleTable.hx:206: characters 23-28
				break;
			}
			#coopy/SimpleTable.hx:207: characters 23-27
			$_g = 0;
			#coopy/SimpleTable.hx:207: characters 27-33
			$_g1 = $h_test;
			#coopy/SimpleTable.hx:207: lines 207-213
			while ($_g < $_g1) {
				#coopy/SimpleTable.hx:207: characters 23-33
				$i = $_g++;
				#coopy/SimpleTable.hx:208: characters 17-51
				$c = $this->getCell($nw - 1, $i);
				#coopy/SimpleTable.hx:209: lines 209-212
				if (!($view->equals($c, $space) || ($c === null))) {
					#coopy/SimpleTable.hx:210: characters 21-33
					$more = false;
					#coopy/SimpleTable.hx:211: characters 21-26
					break;
				}
			}
			#coopy/SimpleTable.hx:214: characters 13-27
			if ($more) {
				#coopy/SimpleTable.hx:214: characters 23-27
				--$nw;
			}
		}
		#coopy/SimpleTable.hx:216: characters 9-31
		if ($nw === $this->w) {
			#coopy/SimpleTable.hx:216: characters 20-31
			return true;
		}
		#coopy/SimpleTable.hx:217: characters 9-63
		$data2 = new IntMap();
		#coopy/SimpleTable.hx:218: characters 19-23
		$_g = 0;
		#coopy/SimpleTable.hx:218: characters 23-25
		$_g1 = $nw;
		#coopy/SimpleTable.hx:218: lines 218-225
		while ($_g < $_g1) {
			#coopy/SimpleTable.hx:218: characters 19-25
			$i = $_g++;
			#coopy/SimpleTable.hx:219: characters 23-27
			$_g2 = 0;
			#coopy/SimpleTable.hx:219: characters 27-28
			$_g3 = $this->h;
			#coopy/SimpleTable.hx:219: lines 219-224
			while ($_g2 < $_g3) {
				#coopy/SimpleTable.hx:219: characters 23-28
				$r = $_g2++;
				#coopy/SimpleTable.hx:220: characters 17-39
				$idx = $r * $this->w + $i;
				#coopy/SimpleTable.hx:221: lines 221-223
				if (\array_key_exists($idx, $this->data->data)) {
					#coopy/SimpleTable.hx:222: characters 21-52
					$value = ($this->data->data[$idx] ?? null);
					$data2->data[$r * $nw + $i] = $value;
				}
			}
		}
		#coopy/SimpleTable.hx:226: characters 9-15
		$this->w = $nw;
		#coopy/SimpleTable.hx:227: characters 9-21
		$this->data = $data2;
		#coopy/SimpleTable.hx:228: characters 9-20
		return true;
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(SimpleTable::class, 'coopy.SimpleTable');
Boot::registerGetters('coopy\\SimpleTable', [
	'width' => true,
	'height' => true
]);
