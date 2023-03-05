<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

/**
 *
 * This implementation is unoptimized, it is expected to be replace with a native class.
 *
 */
class SimpleMeta implements Meta {
	/**
	 * @var bool
	 */
	public $has_properties;
	/**
	 * @var StringMap
	 */
	public $keys;
	/**
	 * @var bool
	 */
	public $may_be_nested;
	/**
	 * @var StringMap
	 */
	public $metadata;
	/**
	 * @var StringMap
	 */
	public $name2col;
	/**
	 * @var StringMap
	 */
	public $name2row;
	/**
	 * @var bool
	 */
	public $row_active;
	/**
	 * @var RowChange[]|\Array_hx
	 */
	public $row_change_cache;
	/**
	 * @var Table
	 */
	public $t;

	/**
	 * @param Table $t
	 * @param bool $has_properties
	 * @param bool $may_be_nested
	 * 
	 * @return void
	 */
	public function __construct ($t, $has_properties = true, $may_be_nested = false) {
		#coopy/SimpleMeta.hx:25: lines 25-35
		if ($has_properties === null) {
			$has_properties = true;
		}
		if ($may_be_nested === null) {
			$may_be_nested = false;
		}
		#coopy/SimpleMeta.hx:26: characters 9-19
		$this->t = $t;
		#coopy/SimpleMeta.hx:27: characters 9-20
		$this->rowChange();
		#coopy/SimpleMeta.hx:28: characters 9-20
		$this->colChange();
		#coopy/SimpleMeta.hx:29: characters 9-45
		$this->has_properties = $has_properties;
		#coopy/SimpleMeta.hx:30: characters 9-43
		$this->may_be_nested = $may_be_nested;
		#coopy/SimpleMeta.hx:31: characters 9-29
		$this->metadata = null;
		#coopy/SimpleMeta.hx:32: characters 9-25
		$this->keys = null;
		#coopy/SimpleMeta.hx:33: characters 9-27
		$this->row_active = false;
		#coopy/SimpleMeta.hx:34: characters 9-32
		$this->row_change_cache = null;
	}

	/**
	 * @param string $column
	 * @param string $property
	 * @param mixed $val
	 * 
	 * @return void
	 */
	public function addMetaData ($column, $property, $val) {
		#coopy/SimpleMeta.hx:142: lines 142-145
		if ($this->metadata === null) {
			#coopy/SimpleMeta.hx:143: characters 13-61
			$this->metadata = new StringMap();
			#coopy/SimpleMeta.hx:144: characters 13-42
			$this->keys = new StringMap();
		}
		#coopy/SimpleMeta.hx:146: lines 146-148
		if (!\array_key_exists($column, $this->metadata->data)) {
			#coopy/SimpleMeta.hx:147: characters 13-59
			$this1 = $this->metadata;
			$value = new StringMap();
			$this1->data[$column] = $value;
		}
		#coopy/SimpleMeta.hx:149: characters 9-42
		$props = ($this->metadata->data[$column] ?? null);
		#coopy/SimpleMeta.hx:150: characters 9-32
		$props->data[$property] = $val;
		#coopy/SimpleMeta.hx:151: characters 9-32
		$this->keys->data[$property] = true;
	}

	/**
	 * @param ColumnChange[]|\Array_hx $columns
	 * 
	 * @return bool
	 */
	public function alterColumns ($columns) {
		#coopy/SimpleMeta.hx:83: characters 22-43
		$this1 = [];
		$target_data = $this1;
		#coopy/SimpleMeta.hx:84: characters 9-23
		$wfate = 0;
		#coopy/SimpleMeta.hx:85: lines 85-88
		if ($this->has_properties) {
			#coopy/SimpleMeta.hx:86: characters 13-34
			$target_data["@"] = $wfate;
			#coopy/SimpleMeta.hx:87: characters 13-20
			++$wfate;
		}
		#coopy/SimpleMeta.hx:89: characters 19-23
		$_g = 0;
		#coopy/SimpleMeta.hx:89: characters 23-39
		$_g1 = $columns->length;
		#coopy/SimpleMeta.hx:89: lines 89-95
		while ($_g < $_g1) {
			#coopy/SimpleMeta.hx:89: characters 19-39
			$i = $_g++;
			#coopy/SimpleMeta.hx:90: characters 13-34
			$col = ($columns->arr[$i] ?? null);
			#coopy/SimpleMeta.hx:91: lines 91-93
			if ($col->prevName !== null) {
				#coopy/SimpleMeta.hx:92: characters 17-47
				$target_data[$col->prevName] = $wfate;
			}
			#coopy/SimpleMeta.hx:94: characters 13-40
			if ($col->name !== null) {
				#coopy/SimpleMeta.hx:94: characters 33-40
				++$wfate;
			}
		}
		#coopy/SimpleMeta.hx:96: characters 9-37
		$fate = new \Array_hx();
		#coopy/SimpleMeta.hx:97: characters 19-23
		$_g = 0;
		#coopy/SimpleMeta.hx:97: characters 23-32
		$_g1 = $this->t->get_width();
		#coopy/SimpleMeta.hx:97: lines 97-104
		while ($_g < $_g1) {
			#coopy/SimpleMeta.hx:97: characters 19-32
			$i = $_g++;
			#coopy/SimpleMeta.hx:98: characters 13-30
			$targeti = -1;
			#coopy/SimpleMeta.hx:99: characters 13-39
			$name = $this->t->getCell($i, 0);
			#coopy/SimpleMeta.hx:100: lines 100-102
			if (\array_key_exists($name, $target_data)) {
				#coopy/SimpleMeta.hx:101: characters 17-24
				$targeti = ($target_data[$name] ?? null);
			}
			#coopy/SimpleMeta.hx:103: characters 13-31
			$fate->arr[$fate->length++] = $targeti;
		}
		#coopy/SimpleMeta.hx:105: characters 9-44
		$this->t->insertOrDeleteColumns($fate, $wfate);
		#coopy/SimpleMeta.hx:106: characters 9-44
		$start = ($this->has_properties ? 1 : 0);
		#coopy/SimpleMeta.hx:107: characters 9-24
		$at = $start;
		#coopy/SimpleMeta.hx:108: characters 19-23
		$_g = 0;
		#coopy/SimpleMeta.hx:108: characters 23-39
		$_g1 = $columns->length;
		#coopy/SimpleMeta.hx:108: lines 108-116
		while ($_g < $_g1) {
			#coopy/SimpleMeta.hx:108: characters 19-39
			$i = $_g++;
			#coopy/SimpleMeta.hx:109: characters 13-34
			$col = ($columns->arr[$i] ?? null);
			#coopy/SimpleMeta.hx:110: lines 110-114
			if ($col->name !== null) {
				#coopy/SimpleMeta.hx:111: lines 111-113
				if ($col->name !== $col->prevName) {
					#coopy/SimpleMeta.hx:112: characters 21-45
					$this->t->setCell($at, 0, $col->name);
				}
			}
			#coopy/SimpleMeta.hx:115: characters 13-37
			if ($col->name !== null) {
				#coopy/SimpleMeta.hx:115: characters 33-37
				++$at;
			}
		}
		#coopy/SimpleMeta.hx:117: characters 9-41
		if (!$this->has_properties) {
			#coopy/SimpleMeta.hx:117: characters 30-41
			return true;
		}
		#coopy/SimpleMeta.hx:118: characters 9-20
		$this->colChange();
		#coopy/SimpleMeta.hx:119: characters 9-11
		$at = $start;
		#coopy/SimpleMeta.hx:120: characters 19-23
		$_g = 0;
		#coopy/SimpleMeta.hx:120: characters 23-39
		$_g1 = $columns->length;
		#coopy/SimpleMeta.hx:120: lines 120-128
		while ($_g < $_g1) {
			#coopy/SimpleMeta.hx:120: characters 19-39
			$i = $_g++;
			#coopy/SimpleMeta.hx:121: characters 13-34
			$col = ($columns->arr[$i] ?? null);
			#coopy/SimpleMeta.hx:122: lines 122-126
			if ($col->name !== null) {
				#coopy/SimpleMeta.hx:123: lines 123-125
				$_g2 = 0;
				$_g3 = $col->props;
				while ($_g2 < $_g3->length) {
					#coopy/SimpleMeta.hx:123: characters 22-26
					$prop = ($_g3->arr[$_g2] ?? null);
					#coopy/SimpleMeta.hx:123: lines 123-125
					++$_g2;
					#coopy/SimpleMeta.hx:124: characters 21-57
					$this->setCell($col->name, $prop->name, $prop->val);
				}
			}
			#coopy/SimpleMeta.hx:127: characters 13-37
			if ($col->name !== null) {
				#coopy/SimpleMeta.hx:127: characters 33-37
				++$at;
			}
		}
		#coopy/SimpleMeta.hx:129: characters 9-20
		return true;
	}

	/**
	 * @param CompareFlags $flags
	 * 
	 * @return bool
	 */
	public function applyFlags ($flags) {
		#coopy/SimpleMeta.hx:213: characters 9-21
		return false;
	}

	/**
	 * @return Table
	 */
	public function asTable () {
		#coopy/SimpleMeta.hx:155: characters 9-55
		if ($this->has_properties && ($this->metadata === null)) {
			#coopy/SimpleMeta.hx:155: characters 47-55
			return $this->t;
		}
		#coopy/SimpleMeta.hx:156: characters 9-40
		if ($this->metadata === null) {
			#coopy/SimpleMeta.hx:156: characters 29-40
			return null;
		}
		#coopy/SimpleMeta.hx:157: characters 9-25
		$w = $this->t->get_width();
		#coopy/SimpleMeta.hx:158: characters 9-41
		$props = new \Array_hx();
		#coopy/SimpleMeta.hx:159: characters 19-30
		$data = \array_values(\array_map("strval", \array_keys($this->keys->data)));
		$k_current = 0;
		$k_length = \count($data);
		$k_data = $data;
		while ($k_current < $k_length) {
			#coopy/SimpleMeta.hx:159: characters 9-50
			$k = $k_data[$k_current++];
			#coopy/SimpleMeta.hx:159: characters 34-47
			$props->arr[$props->length++] = $k;
		}
		#coopy/SimpleMeta.hx:160: characters 9-36
		\usort($props->arr, Boot::getStaticClosure(\Reflect::class, 'compare'));
		#coopy/SimpleMeta.hx:161: characters 9-54
		$mt = new SimpleTable($w + 1, $props->length + 1);
		#coopy/SimpleMeta.hx:162: characters 9-28
		$mt->setCell(0, 0, "@");
		#coopy/SimpleMeta.hx:163: characters 19-23
		$_g = 0;
		#coopy/SimpleMeta.hx:163: characters 23-24
		$_g1 = $w;
		#coopy/SimpleMeta.hx:163: lines 163-173
		while ($_g < $_g1) {
			#coopy/SimpleMeta.hx:163: characters 19-24
			$x = $_g++;
			#coopy/SimpleMeta.hx:164: characters 13-39
			$name = $this->t->getCell($x, 0);
			#coopy/SimpleMeta.hx:165: characters 13-35
			$mt->setCell(1 + $x, 0, $name);
			#coopy/SimpleMeta.hx:166: characters 13-49
			if (!\array_key_exists($name, $this->metadata->data)) {
				#coopy/SimpleMeta.hx:166: characters 41-49
				continue;
			}
			#coopy/SimpleMeta.hx:167: characters 13-43
			$vals = ($this->metadata->data[$name] ?? null);
			#coopy/SimpleMeta.hx:168: characters 23-27
			$_g2 = 0;
			#coopy/SimpleMeta.hx:168: characters 27-41
			$_g3 = $props->length;
			#coopy/SimpleMeta.hx:168: lines 168-172
			while ($_g2 < $_g3) {
				#coopy/SimpleMeta.hx:168: characters 23-41
				$i = $_g2++;
				#coopy/SimpleMeta.hx:169: lines 169-171
				if (\array_key_exists(($props->arr[$i] ?? null), $vals->data)) {
					#coopy/SimpleMeta.hx:170: characters 21-59
					$mt->setCell(1 + $x, $i + 1, ($vals->data[($props->arr[$i] ?? null)] ?? null));
				}
			}
		}
		#coopy/SimpleMeta.hx:174: characters 19-23
		$_g = 0;
		#coopy/SimpleMeta.hx:174: characters 23-37
		$_g1 = $props->length;
		#coopy/SimpleMeta.hx:174: lines 174-176
		while ($_g < $_g1) {
			#coopy/SimpleMeta.hx:174: characters 19-37
			$y = $_g++;
			#coopy/SimpleMeta.hx:175: characters 13-39
			$mt->setCell(0, $y + 1, ($props->arr[$y] ?? null));
		}
		#coopy/SimpleMeta.hx:177: characters 9-18
		return $mt;
	}

	/**
	 * @param RowChange $rc
	 * 
	 * @return bool
	 */
	public function changeRow ($rc) {
		#coopy/SimpleMeta.hx:208: characters 9-34
		$_this = $this->row_change_cache;
		$_this->arr[$_this->length++] = $rc;
		#coopy/SimpleMeta.hx:209: characters 9-21
		return false;
	}

	/**
	 * @param Table $table
	 * 
	 * @return Meta
	 */
	public function cloneMeta ($table = null) {
		#coopy/SimpleMeta.hx:181: characters 9-44
		$result = new SimpleMeta($table);
		#coopy/SimpleMeta.hx:182: lines 182-195
		if ($this->metadata !== null) {
			#coopy/SimpleMeta.hx:183: characters 13-24
			$result->keys = new StringMap();
			#coopy/SimpleMeta.hx:184: characters 23-34
			$data = \array_values(\array_map("strval", \array_keys($this->keys->data)));
			$k_current = 0;
			$k_length = \count($data);
			$k_data = $data;
			while ($k_current < $k_length) {
				#coopy/SimpleMeta.hx:184: characters 13-64
				$k = $k_data[$k_current++];
				#coopy/SimpleMeta.hx:184: characters 38-61
				$result->keys->data[$k] = true;
			}
			#coopy/SimpleMeta.hx:185: characters 13-28
			$result->metadata = new StringMap();
			#coopy/SimpleMeta.hx:186: characters 23-38
			$data = \array_values(\array_map("strval", \array_keys($this->metadata->data)));
			$k_current = 0;
			$k_length = \count($data);
			$k_data = $data;
			while ($k_current < $k_length) {
				#coopy/SimpleMeta.hx:186: lines 186-194
				$k = $k_data[$k_current++];
				#coopy/SimpleMeta.hx:187: characters 17-50
				if (!\array_key_exists($k, $this->metadata->data)) {
					#coopy/SimpleMeta.hx:187: characters 42-50
					continue;
				}
				#coopy/SimpleMeta.hx:188: characters 17-44
				$vals = ($this->metadata->data[$k] ?? null);
				#coopy/SimpleMeta.hx:189: characters 17-55
				$nvals = new StringMap();
				#coopy/SimpleMeta.hx:190: characters 27-38
				$data = \array_values(\array_map("strval", \array_keys($vals->data)));
				$p_current = 0;
				$p_length = \count($data);
				$p_data = $data;
				while ($p_current < $p_length) {
					#coopy/SimpleMeta.hx:190: lines 190-192
					$p = $p_data[$p_current++];
					#coopy/SimpleMeta.hx:191: characters 21-45
					$value = ($vals->data[$p] ?? null);
					$nvals->data[$p] = $value;
				}
				#coopy/SimpleMeta.hx:193: characters 17-45
				$result->metadata->data[$k] = $nvals;
			}
		}
		#coopy/SimpleMeta.hx:196: characters 9-22
		return $result;
	}

	/**
	 * @param string $key
	 * 
	 * @return int
	 */
	public function col ($key) {
		#coopy/SimpleMeta.hx:57: characters 9-34
		if ($this->t->get_height() < 1) {
			#coopy/SimpleMeta.hx:57: characters 25-34
			return -1;
		}
		#coopy/SimpleMeta.hx:58: lines 58-64
		if ($this->name2col === null) {
			#coopy/SimpleMeta.hx:59: characters 13-45
			$this->name2col = new StringMap();
			#coopy/SimpleMeta.hx:60: characters 13-29
			$w = $this->t->get_width();
			#coopy/SimpleMeta.hx:61: characters 23-27
			$_g = 0;
			#coopy/SimpleMeta.hx:61: characters 27-28
			$_g1 = $w;
			#coopy/SimpleMeta.hx:61: lines 61-63
			while ($_g < $_g1) {
				#coopy/SimpleMeta.hx:61: characters 23-28
				$c = $_g++;
				#coopy/SimpleMeta.hx:62: characters 17-47
				$this1 = $this->name2col;
				$key1 = $this->t->getCell($c, 0);
				$this1->data[$key1] = $c;
			}
		}
		#coopy/SimpleMeta.hx:65: characters 9-45
		if (!\array_key_exists($key, $this->name2col->data)) {
			#coopy/SimpleMeta.hx:65: characters 36-45
			return -1;
		}
		#coopy/SimpleMeta.hx:66: characters 16-33
		return ($this->name2col->data[$key] ?? null);
	}

	/**
	 * @return void
	 */
	public function colChange () {
		#coopy/SimpleMeta.hx:53: characters 9-24
		$this->name2col = null;
	}

	/**
	 * @return string
	 */
	public function getName () {
		#coopy/SimpleMeta.hx:229: characters 9-20
		return null;
	}

	/**
	 * @return RowStream
	 */
	public function getRowStream () {
		#coopy/SimpleMeta.hx:217: characters 9-34
		return new TableStream($this->t);
	}

	/**
	 * @return bool
	 */
	public function isNested () {
		#coopy/SimpleMeta.hx:221: characters 9-29
		return $this->may_be_nested;
	}

	/**
	 * @return bool
	 */
	public function isSql () {
		#coopy/SimpleMeta.hx:225: characters 9-21
		return false;
	}

	/**
	 * @param string $key
	 * 
	 * @return int
	 */
	public function row ($key) {
		#coopy/SimpleMeta.hx:70: characters 9-33
		if ($this->t->get_width() < 1) {
			#coopy/SimpleMeta.hx:70: characters 24-33
			return -1;
		}
		#coopy/SimpleMeta.hx:71: lines 71-77
		if ($this->name2row === null) {
			#coopy/SimpleMeta.hx:72: characters 13-45
			$this->name2row = new StringMap();
			#coopy/SimpleMeta.hx:73: characters 13-30
			$h = $this->t->get_height();
			#coopy/SimpleMeta.hx:74: characters 23-27
			$_g = 1;
			#coopy/SimpleMeta.hx:74: characters 27-28
			$_g1 = $h;
			#coopy/SimpleMeta.hx:74: lines 74-76
			while ($_g < $_g1) {
				#coopy/SimpleMeta.hx:74: characters 23-28
				$r = $_g++;
				#coopy/SimpleMeta.hx:75: characters 17-47
				$this1 = $this->name2row;
				$key1 = $this->t->getCell(0, $r);
				$this1->data[$key1] = $r;
			}
		}
		#coopy/SimpleMeta.hx:78: characters 9-45
		if (!\array_key_exists($key, $this->name2row->data)) {
			#coopy/SimpleMeta.hx:78: characters 36-45
			return -1;
		}
		#coopy/SimpleMeta.hx:79: characters 16-33
		return ($this->name2row->data[$key] ?? null);
	}

	/**
	 * @return void
	 */
	public function rowChange () {
		#coopy/SimpleMeta.hx:49: characters 9-24
		$this->name2row = null;
	}

	/**
	 * @param string $c
	 * @param string $r
	 * @param mixed $val
	 * 
	 * @return bool
	 */
	public function setCell ($c, $r, $val) {
		#coopy/SimpleMeta.hx:133: characters 9-25
		$ri = $this->row($r);
		#coopy/SimpleMeta.hx:134: characters 9-33
		if ($ri === -1) {
			#coopy/SimpleMeta.hx:134: characters 21-33
			return false;
		}
		#coopy/SimpleMeta.hx:135: characters 9-25
		$ci = $this->col($c);
		#coopy/SimpleMeta.hx:136: characters 9-33
		if ($ci === -1) {
			#coopy/SimpleMeta.hx:136: characters 21-33
			return false;
		}
		#coopy/SimpleMeta.hx:137: characters 9-29
		$this->t->setCell($ci, $ri, $val);
		#coopy/SimpleMeta.hx:138: characters 9-20
		return true;
	}

	/**
	 *
	 * This sneaky method will divert any row-level modifications
	 * made during patching to a user-supplied array.
	 *
	 * 
	 * @param RowChange[]|\Array_hx $changes
	 * 
	 * @return void
	 */
	public function storeRowChanges ($changes) {
		#coopy/SimpleMeta.hx:44: characters 9-35
		$this->row_change_cache = $changes;
		#coopy/SimpleMeta.hx:45: characters 9-26
		$this->row_active = true;
	}

	/**
	 * @return bool
	 */
	public function useForColumnChanges () {
		#coopy/SimpleMeta.hx:200: characters 9-20
		return true;
	}

	/**
	 * @return bool
	 */
	public function useForRowChanges () {
		#coopy/SimpleMeta.hx:204: characters 9-26
		return $this->row_active;
	}
}

Boot::registerClass(SimpleMeta::class, 'coopy.SimpleMeta');
