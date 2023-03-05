<?php
/**
 */

namespace coopy;

use \php\Boot;
use \php\_Boot\HxString;

/**
 *
 * The row/column number for related content in the local table,
 * the remote table, and the parent table (if there is one).
 *
 */
class Unit {
	/**
	 * @var int
	 *
	 * The row/column number in the local table.
	 *
	 */
	public $l;
	/**
	 * @var int
	 *
	 * The row/column number in the parent table.
	 *
	 */
	public $p;
	/**
	 * @var int
	 *
	 * The row/column number in the remote table.
	 *
	 */
	public $r;

	/**
	 * @param int $i
	 * 
	 * @return string
	 */
	public static function describe ($i) {
		#coopy/Unit.hx:60: characters 16-39
		if ($i >= 0) {
			#coopy/Unit.hx:60: characters 25-33
			return "" . ($i??'null');
		} else {
			#coopy/Unit.hx:60: characters 36-39
			return "-";
		}
	}

	/**
	 *
	 * Constructor.
	 * @param l the row/column number in the local table (-1 means absent)
	 * @param r the row/column number in the remote table (-1 means absent)
	 * @param p the row/column number in the parent table (-1 means absent, -2 means there is no parent)
	 *
	 * 
	 * @param int $l
	 * @param int $r
	 * @param int $p
	 * 
	 * @return void
	 */
	public function __construct ($l = -2, $r = -2, $p = -2) {
		#coopy/Unit.hx:43: lines 43-47
		if ($l === null) {
			$l = -2;
		}
		if ($r === null) {
			$r = -2;
		}
		if ($p === null) {
			$p = -2;
		}
		#coopy/Unit.hx:44: characters 9-19
		$this->l = $l;
		#coopy/Unit.hx:45: characters 9-19
		$this->r = $r;
		#coopy/Unit.hx:46: characters 9-19
		$this->p = $p;
	}

	/**
	 * @param int $num
	 * 
	 * @return string
	 */
	public function base26 ($num) {
		#coopy/Unit.hx:106: characters 9-50
		$alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		#coopy/Unit.hx:107: characters 9-30
		if ($num < 0) {
			#coopy/Unit.hx:107: characters 20-30
			return "-";
		}
		#coopy/Unit.hx:108: characters 9-22
		$out = "";
		#coopy/Unit.hx:109: lines 109-112
		while (true) {
			#coopy/Unit.hx:110: characters 25-47
			$index = $num % 26;
			#coopy/Unit.hx:110: characters 13-47
			$out = ($out??'null') . (($index < 0 ? "" : \mb_substr($alpha, $index, 1))??'null');
			#coopy/Unit.hx:111: characters 13-43
			$num = (int)(\floor($num / 26)) - 1;
			#coopy/Unit.hx:109: lines 109-112
			if (!($num >= 0)) {
				break;
			}
		}
		#coopy/Unit.hx:113: characters 9-19
		return $out;
	}

	/**
	 *
	 * Read from a serialized version of the row/column numbers
	 * @param txt the string to read
	 * @return true on success
	 *
	 * 
	 * @param string $txt
	 * 
	 * @return bool
	 */
	public function fromString ($txt) {
		#coopy/Unit.hx:81: characters 9-19
		$txt = ($txt??'null') . "]";
		#coopy/Unit.hx:82: characters 9-26
		$at = 0;
		#coopy/Unit.hx:83: characters 19-23
		$_g = 0;
		#coopy/Unit.hx:83: characters 23-33
		$_g1 = mb_strlen($txt);
		#coopy/Unit.hx:83: lines 83-100
		while ($_g < $_g1) {
			#coopy/Unit.hx:83: characters 19-33
			$i = $_g++;
			#coopy/Unit.hx:84: characters 13-46
			$ch = HxString::charCodeAt($txt, $i);
			#coopy/Unit.hx:85: lines 85-99
			if (($ch >= 48) && ($ch <= 57)) {
				#coopy/Unit.hx:86: characters 17-25
				$at *= 10;
				#coopy/Unit.hx:87: characters 17-36
				$at += $ch - 48;
			} else if ($ch === 45) {
				#coopy/Unit.hx:89: characters 17-24
				$at = -1;
			} else if ($ch === 124) {
				#coopy/Unit.hx:91: characters 17-23
				$this->p = $at;
				#coopy/Unit.hx:92: characters 17-23
				$at = 0;
			} else if ($ch === 58) {
				#coopy/Unit.hx:94: characters 17-23
				$this->l = $at;
				#coopy/Unit.hx:95: characters 17-23
				$at = 0;
			} else if ($ch === 93) {
				#coopy/Unit.hx:97: characters 17-23
				$this->r = $at;
				#coopy/Unit.hx:98: characters 17-28
				return true;
			}
		}
		#coopy/Unit.hx:101: characters 9-21
		return false;
	}

	/**
	 *
	 * @return the row/column number in the parent table if present, otherwise in the local table
	 *
	 * 
	 * @return int
	 */
	public function lp () {
		#coopy/Unit.hx:55: characters 16-31
		if ($this->p === -2) {
			#coopy/Unit.hx:55: characters 26-27
			return $this->l;
		} else {
			#coopy/Unit.hx:55: characters 30-31
			return $this->p;
		}
	}

	/**
	 *
	 * @return as for toString(), but representing row/column numbers
	 * as A,B,C,D,...,AA,AB,AC,AD,....
	 *
	 * 
	 * @return string
	 */
	public function toBase26String () {
		#coopy/Unit.hx:123: characters 9-72
		if ($this->p >= -1) {
			#coopy/Unit.hx:123: characters 20-72
			return ($this->base26($this->p)??'null') . "|" . ($this->base26($this->l)??'null') . ":" . ($this->base26($this->r)??'null');
		}
		#coopy/Unit.hx:124: characters 9-43
		return ($this->base26($this->l)??'null') . ":" . ($this->base26($this->r)??'null');
	}

	/**
	 *
	 * @return a text serialization of the row/column numbers, as `LL:RR` when the parent is absent, and `PP|LL:RR` when the parent is present
	 *
	 * 
	 * @return string
	 */
	public function toString () {
		#coopy/Unit.hx:69: characters 9-78
		if ($this->p >= -1) {
			#coopy/Unit.hx:69: characters 20-78
			return (Unit::describe($this->p)??'null') . "|" . (Unit::describe($this->l)??'null') . ":" . (Unit::describe($this->r)??'null');
		}
		#coopy/Unit.hx:70: characters 9-47
		return (Unit::describe($this->l)??'null') . ":" . (Unit::describe($this->r)??'null');
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(Unit::class, 'coopy.Unit');
