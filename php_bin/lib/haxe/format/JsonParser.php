<?php
/**
 */

namespace haxe\format;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Exception;

class JsonParser {
	/**
	 * @var int
	 */
	public $pos;
	/**
	 * @var mixed
	 */
	public $str;

	/**
	 * @param string $str
	 * 
	 * @return void
	 */
	public function __construct ($str) {
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:40: characters 3-17
		$this->str = $str;
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:41: characters 3-15
		$this->pos = 0;
	}

	/**
	 * @return mixed
	 */
	public function doParse () {
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:45: characters 3-27
		$result = $this->parseRec();
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:46: characters 3-9
		$c = null;
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:47: lines 47-54
		while (true) {
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:47: characters 33-43
			$s = $this->str;
			$pos = $this->pos++;
			$c = ($pos >= \strlen($s) ? 0 : \ord($s[$pos]));
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:47: lines 47-54
			if (!($c !== 0)) {
				break;
			}
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:48: lines 48-53
			if ($c === 9 || $c === 10 || $c === 13 || $c === 32) {
			} else {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:52: characters 6-19
				$this->invalidChar();
			}
		}
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:55: characters 3-16
		return $result;
	}

	/**
	 * @return void
	 */
	public function invalidChar () {
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:253: characters 3-8
		$this->pos--;
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:254: characters 27-47
		$s = $this->str;
		$pos = $this->pos;
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:254: characters 3-8
		throw Exception::thrown("Invalid char " . ((($pos >= \strlen($s) ? 0 : \ord($s[$pos])))??'null') . " at position " . ($this->pos??'null'));
	}

	/**
	 * @param int $start
	 * 
	 * @return void
	 */
	public function invalidNumber ($start) {
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:258: characters 3-8
		throw Exception::thrown("Invalid number at position " . ($start??'null') . ": " . (\substr($this->str, $start, $this->pos - $start)??'null'));
	}

	/**
	 * @return mixed
	 */
	public function parseRec () {
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:59: lines 59-137
		while (true) {
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:60: characters 12-22
			$s = $this->str;
			$pos = $this->pos++;
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:60: characters 4-23
			$c = ($pos >= \strlen($s) ? 0 : \ord($s[$pos]));
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:61: lines 61-136
			if ($c === 9 || $c === 10 || $c === 13 || $c === 32) {
			} else if ($c === 34) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:131: characters 6-26
				return $this->parseString();
			} else if ($c === 45 || $c === 48 || $c === 49 || $c === 50 || $c === 51 || $c === 52 || $c === 53 || $c === 54 || $c === 55 || $c === 56 || $c === 57) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:133: characters 13-27
				$c1 = $c;
				$start = $this->pos - 1;
				$minus = $c1 === 45;
				$digit = !$minus;
				$zero = $c1 === 48;
				$point = false;
				$e = false;
				$pm = false;
				$end = false;
				while (true) {
					$s1 = $this->str;
					$pos1 = $this->pos++;
					$c1 = ($pos1 >= \strlen($s1) ? 0 : \ord($s1[$pos1]));
					if ($c1 === 43 || $c1 === 45) {
						if (!$e || $pm) {
							$this->invalidNumber($start);
						}
						$digit = false;
						$pm = true;
					} else if ($c1 === 46) {
						if ($minus || $point || $e) {
							$this->invalidNumber($start);
						}
						$digit = false;
						$point = true;
					} else if ($c1 === 48) {
						if ($zero && !$point) {
							$this->invalidNumber($start);
						}
						if ($minus) {
							$minus = false;
							$zero = true;
						}
						$digit = true;
					} else if ($c1 === 49 || $c1 === 50 || $c1 === 51 || $c1 === 52 || $c1 === 53 || $c1 === 54 || $c1 === 55 || $c1 === 56 || $c1 === 57) {
						if ($zero && !$point) {
							$this->invalidNumber($start);
						}
						if ($minus) {
							$minus = false;
						}
						$digit = true;
						$zero = false;
					} else if ($c1 === 69 || $c1 === 101) {
						if ($minus || $zero || $e) {
							$this->invalidNumber($start);
						}
						$digit = false;
						$e = true;
					} else {
						if (!$digit) {
							$this->invalidNumber($start);
						}
						$this->pos--;
						$end = true;
					}
					if ($end) {
						break;
					}
				}
				$f = \Std::parseFloat(\substr($this->str, $start, $this->pos - $start));
				$i = (int)($f);
				if (Boot::equal($i, $f)) {
					return $i;
				} else {
					return $f;
				}
			} else if ($c === 91) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:91: characters 6-44
				$arr = new \Array_hx();
				$comma = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:92: lines 92-108
				while (true) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:93: characters 15-25
					$s2 = $this->str;
					$pos2 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:93: characters 7-26
					$c2 = ($pos2 >= \strlen($s2) ? 0 : \ord($s2[$pos2]));
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:94: lines 94-107
					if ($c2 === 9 || $c2 === 10 || $c2 === 13 || $c2 === 32) {
					} else if ($c2 === 44) {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:101: characters 9-52
						if ($comma) {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:101: characters 20-33
							$comma = false;
						} else {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:101: characters 39-52
							$this->invalidChar();
						}
					} else if ($c2 === 93) {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:98: characters 9-42
						if ($comma === false) {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:98: characters 29-42
							$this->invalidChar();
						}
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:99: characters 9-19
						return $arr;
					} else {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:103: characters 9-33
						if ($comma) {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:103: characters 20-33
							$this->invalidChar();
						}
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:104: characters 9-14
						$this->pos--;
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:105: characters 9-29
						$x = $this->parseRec();
						$arr->arr[$arr->length++] = $x;
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:106: characters 9-21
						$comma = true;
					}
				}
			} else if ($c === 102) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:117: characters 6-21
				$save = $this->pos;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-110
				$tmp = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-84
				$tmp1 = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-58
				$tmp2 = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-20
				$s3 = $this->str;
				$pos3 = $this->pos++;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-58
				if ((($pos3 >= \strlen($s3) ? 0 : \ord($s3[$pos3]))) === 97) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 36-46
					$s4 = $this->str;
					$pos4 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-58
					$tmp2 = (($pos4 >= \strlen($s4) ? 0 : \ord($s4[$pos4]))) !== 108;
				} else {
					$tmp2 = true;
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-84
				if (!$tmp2) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 62-72
					$s5 = $this->str;
					$pos5 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-84
					$tmp1 = (($pos5 >= \strlen($s5) ? 0 : \ord($s5[$pos5]))) !== 115;
				} else {
					$tmp1 = true;
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-110
				if (!$tmp1) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 88-98
					$s6 = $this->str;
					$pos6 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: characters 10-110
					$tmp = (($pos6 >= \strlen($s6) ? 0 : \ord($s6[$pos6]))) !== 101;
				} else {
					$tmp = true;
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:118: lines 118-121
				if ($tmp) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:119: characters 7-17
					$this->pos = $save;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:120: characters 7-20
					$this->invalidChar();
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:122: characters 6-18
				return false;
			} else if ($c === 110) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:124: characters 6-21
				$save1 = $this->pos;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 10-84
				$tmp3 = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 10-58
				$tmp4 = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 10-20
				$s7 = $this->str;
				$pos7 = $this->pos++;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 10-58
				if ((($pos7 >= \strlen($s7) ? 0 : \ord($s7[$pos7]))) === 117) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 36-46
					$s8 = $this->str;
					$pos8 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 10-58
					$tmp4 = (($pos8 >= \strlen($s8) ? 0 : \ord($s8[$pos8]))) !== 108;
				} else {
					$tmp4 = true;
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 10-84
				if (!$tmp4) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 62-72
					$s9 = $this->str;
					$pos9 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: characters 10-84
					$tmp3 = (($pos9 >= \strlen($s9) ? 0 : \ord($s9[$pos9]))) !== 108;
				} else {
					$tmp3 = true;
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:125: lines 125-128
				if ($tmp3) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:126: characters 7-17
					$this->pos = $save1;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:127: characters 7-20
					$this->invalidChar();
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:129: characters 6-17
				return null;
			} else if ($c === 116) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:110: characters 6-21
				$save2 = $this->pos;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 10-84
				$tmp5 = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 10-58
				$tmp6 = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 10-20
				$s10 = $this->str;
				$pos10 = $this->pos++;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 10-58
				if ((($pos10 >= \strlen($s10) ? 0 : \ord($s10[$pos10]))) === 114) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 36-46
					$s11 = $this->str;
					$pos11 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 10-58
					$tmp6 = (($pos11 >= \strlen($s11) ? 0 : \ord($s11[$pos11]))) !== 117;
				} else {
					$tmp6 = true;
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 10-84
				if (!$tmp6) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 62-72
					$s12 = $this->str;
					$pos12 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: characters 10-84
					$tmp5 = (($pos12 >= \strlen($s12) ? 0 : \ord($s12[$pos12]))) !== 101;
				} else {
					$tmp5 = true;
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:111: lines 111-114
				if ($tmp5) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:112: characters 7-17
					$this->pos = $save2;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:113: characters 7-20
					$this->invalidChar();
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:115: characters 6-17
				return true;
			} else if ($c === 123) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:65: characters 6-58
				$obj = new HxAnon();
				$field = null;
				$comma1 = null;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:66: lines 66-89
				while (true) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:67: characters 15-25
					$s13 = $this->str;
					$pos13 = $this->pos++;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:67: characters 7-26
					$c3 = ($pos13 >= \strlen($s13) ? 0 : \ord($s13[$pos13]));
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:68: lines 68-88
					if ($c3 === 9 || $c3 === 10 || $c3 === 13 || $c3 === 32) {
					} else if ($c3 === 34) {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:84: characters 9-50
						if (($field !== null) || $comma1) {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:84: characters 37-50
							$this->invalidChar();
						}
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:85: characters 9-30
						$field = $this->parseString();
					} else if ($c3 === 44) {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:82: characters 9-52
						if ($comma1) {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:82: characters 20-33
							$comma1 = false;
						} else {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:82: characters 39-52
							$this->invalidChar();
						}
					} else if ($c3 === 58) {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:76: lines 76-77
						if ($field === null) {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:77: characters 10-23
							$this->invalidChar();
						}
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:78: characters 9-49
						\Reflect::setField($obj, $field, $this->parseRec());
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:79: characters 9-21
						$field = null;
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:80: characters 9-21
						$comma1 = true;
					} else if ($c3 === 125) {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:72: lines 72-73
						if (($field !== null) || ($comma1 === false)) {
							#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:73: characters 10-23
							$this->invalidChar();
						}
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:74: characters 9-19
						return $obj;
					} else {
						#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:87: characters 9-22
						$this->invalidChar();
					}
				}
			} else {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:135: characters 6-19
				$this->invalidChar();
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function parseString () {
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:141: characters 3-19
		$start = $this->pos;
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:142: characters 3-31
		$buf = null;
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:143: lines 143-188
		while (true) {
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:144: characters 12-22
			$s = $this->str;
			$pos = $this->pos++;
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:144: characters 4-23
			$c = ($pos >= \strlen($s) ? 0 : \ord($s[$pos]));
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:145: lines 145-146
			if ($c === 34) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:146: characters 5-10
				break;
			}
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:147: lines 147-187
			if ($c === 92) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:148: lines 148-150
				if ($buf === null) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:149: characters 6-14
					$buf = "";
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:151: characters 11-50
				$buf = ($buf . \substr($this->str, $start, $this->pos - $start - 1));
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:152: characters 9-19
				$s1 = $this->str;
				$pos1 = $this->pos++;
				$c = ($pos1 >= \strlen($s1) ? 0 : \ord($s1[$pos1]));
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:153: lines 153-172
				if ($c === 34 || $c === 47 || $c === 92) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:165: characters 13-49
					$buf = ($buf . \mb_chr($c));
				} else if ($c === 98) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:161: characters 13-46
					$buf = ($buf . \chr(8));
				} else if ($c === 102) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:163: characters 13-47
					$buf = ($buf . \chr(12));
				} else if ($c === 110) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:157: characters 7-37
					$buf = ($buf . "\x0A");
				} else if ($c === 114) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:155: characters 7-37
					$buf = ($buf . "\x0D");
				} else if ($c === 116) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:159: characters 7-37
					$buf = ($buf . "\x09");
				} else if ($c === 117) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:167: characters 7-56
					$uc = \Std::parseInt("0x" . (\substr($this->str, $this->pos, 4)??'null'));
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:168: characters 7-15
					$this->pos += 4;
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:169: characters 13-50
					$buf = ($buf . \mb_chr($uc));
				} else {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:171: characters 7-12
					throw Exception::thrown("Invalid escape sequence \\" . (\mb_chr($c)??'null') . " at position " . ($this->pos - 1));
				}
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:173: characters 5-16
				$start = $this->pos;
			} else if ($c >= 128) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:177: characters 5-10
				$this->pos++;
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:178: lines 178-185
				if ($c >= 252) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:179: characters 6-14
					$this->pos += 4;
				} else if ($c >= 248) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:181: characters 6-14
					$this->pos += 3;
				} else if ($c >= 240) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:183: characters 6-14
					$this->pos += 2;
				} else if ($c >= 224) {
					#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:185: characters 6-11
					$this->pos++;
				}
			} else if ($c === 0) {
				#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:187: characters 5-10
				throw Exception::thrown("Unclosed string");
			}
		}
		#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:189: lines 189-193
		if ($buf === null) {
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:190: characters 11-45
			return \substr($this->str, $start, $this->pos - $start - 1);
		} else {
			#/usr/local/lib/haxe/std/php/_std/haxe/format/JsonParser.hx:192: characters 11-50
			return ($buf . \substr($this->str, $start, $this->pos - $start - 1));
		}
	}
}

Boot::registerClass(JsonParser::class, 'haxe.format.JsonParser');
