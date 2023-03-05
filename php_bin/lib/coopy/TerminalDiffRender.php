<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

/**
 *
 * Decorate a diff being displayed on a console.  Colors, glyphs, any
 * other eye-candy we like.
 *
 */
class TerminalDiffRender {
	/**
	 * @var bool
	 */
	public $align_columns;
	/**
	 * @var StringMap
	 */
	public $codes;
	/**
	 * @var Csv
	 */
	public $csv;
	/**
	 * @var string
	 */
	public $delim;
	/**
	 * @var bool
	 */
	public $diff;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var Table
	 */
	public $t;
	/**
	 * @var bool
	 */
	public $use_glyphs;
	/**
	 * @var View
	 */
	public $v;
	/**
	 * @var bool
	 */
	public $wide_columns;

	/**
	 * @param CompareFlags $flags
	 * @param string $delim
	 * @param bool $diff
	 * 
	 * @return void
	 */
	public function __construct ($flags = null, $delim = null, $diff = true) {
		#coopy/TerminalDiffRender.hx:26: lines 26-42
		if ($diff === null) {
			$diff = true;
		}
		#coopy/TerminalDiffRender.hx:27: characters 9-29
		$this->align_columns = true;
		#coopy/TerminalDiffRender.hx:28: characters 9-29
		$this->wide_columns = false;
		#coopy/TerminalDiffRender.hx:29: characters 9-26
		$this->use_glyphs = true;
		#coopy/TerminalDiffRender.hx:30: characters 9-27
		$this->flags = $flags;
		#coopy/TerminalDiffRender.hx:31: lines 31-39
		if ($flags !== null) {
			#coopy/TerminalDiffRender.hx:32: lines 32-34
			if ($flags->padding_strategy === "dense") {
				#coopy/TerminalDiffRender.hx:33: characters 17-38
				$this->align_columns = false;
			}
			#coopy/TerminalDiffRender.hx:35: lines 35-37
			if ($flags->padding_strategy === "sparse") {
				#coopy/TerminalDiffRender.hx:36: characters 17-36
				$this->wide_columns = true;
			}
			#coopy/TerminalDiffRender.hx:38: characters 13-42
			$this->use_glyphs = $flags->use_glyphs;
		}
		#coopy/TerminalDiffRender.hx:40: characters 9-49
		$this->delim = ($delim !== null ? $delim : ",");
		#coopy/TerminalDiffRender.hx:41: characters 9-25
		$this->diff = $diff;
	}

	/**
	 *
	 * @param enable choose whether columns should be aligned by padding
	 *
	 * 
	 * @param bool $enable
	 * 
	 * @return void
	 */
	public function alignColumns ($enable) {
		#coopy/TerminalDiffRender.hx:51: characters 9-31
		$this->align_columns = $enable;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param bool $color
	 * 
	 * @return string
	 */
	public function getText ($x, $y, $color) {
		#coopy/TerminalDiffRender.hx:118: characters 9-44
		$val = $this->t->getCell($x, $y);
		#coopy/TerminalDiffRender.hx:119: characters 9-51
		$cell = DiffRender::renderCell($this->t, $this->v, $x, $y);
		#coopy/TerminalDiffRender.hx:120: lines 120-147
		if ($color && $this->diff) {
			#coopy/TerminalDiffRender.hx:121: characters 13-29
			$code = null;
			#coopy/TerminalDiffRender.hx:122: lines 122-124
			if ($cell->category !== null) {
				#coopy/TerminalDiffRender.hx:123: characters 24-44
				$code = ($this->codes->data[$cell->category] ?? null);
			}
			#coopy/TerminalDiffRender.hx:125: lines 125-128
			if ($cell->category_given_tr !== null) {
				#coopy/TerminalDiffRender.hx:126: characters 17-61
				$code_tr = ($this->codes->data[$cell->category_given_tr] ?? null);
				#coopy/TerminalDiffRender.hx:127: characters 17-50
				if ($code_tr !== null) {
					#coopy/TerminalDiffRender.hx:127: characters 36-50
					$code = $code_tr;
				}
			}
			#coopy/TerminalDiffRender.hx:129: lines 129-140
			if ($code !== null) {
				#coopy/TerminalDiffRender.hx:130: characters 17-85
				$separator = ($this->use_glyphs ? $cell->pretty_separator : $cell->separator);
				#coopy/TerminalDiffRender.hx:131: lines 131-139
				if ($cell->rvalue !== null) {
					#coopy/TerminalDiffRender.hx:132: characters 21-131
					$val = (($this->codes->data["remove"] ?? null)??'null') . ($cell->lvalue??'null') . (($this->codes->data["modify"] ?? null)??'null') . ($separator??'null') . (($this->codes->data["add"] ?? null)??'null') . ($cell->rvalue??'null') . (($this->codes->data["done"] ?? null)??'null');
					#coopy/TerminalDiffRender.hx:133: lines 133-135
					if ($cell->pvalue !== null) {
						#coopy/TerminalDiffRender.hx:134: characters 25-98
						$val = (($this->codes->data["conflict"] ?? null)??'null') . ($cell->pvalue??'null') . (($this->codes->data["modify"] ?? null)??'null') . ($separator??'null') . \Std::string($val);
					}
				} else {
					#coopy/TerminalDiffRender.hx:137: characters 40-57
					$val = ($this->use_glyphs ? $cell->pretty_value : $cell->value);
					#coopy/TerminalDiffRender.hx:138: characters 21-53
					$val = ($code??'null') . \Std::string($val) . (($this->codes->data["done"] ?? null)??'null');
				}
			}
		} else if ($color && !$this->diff) {
			#coopy/TerminalDiffRender.hx:142: lines 142-144
			if ($y === 0) {
				#coopy/TerminalDiffRender.hx:143: characters 17-60
				$val = (($this->codes->data["header"] ?? null)??'null') . \Std::string($val) . (($this->codes->data["done"] ?? null)??'null');
			}
		} else {
			#coopy/TerminalDiffRender.hx:146: characters 32-49
			$val = ($this->use_glyphs ? $cell->pretty_value : $cell->value);
		}
		#coopy/TerminalDiffRender.hx:148: characters 9-37
		return $this->csv->renderCell($this->v, $val);
	}

	/**
	 * @param Table $t
	 * 
	 * @return int[]|\Array_hx
	 */
	public function pickSizes ($t) {
		#coopy/TerminalDiffRender.hx:152: characters 9-31
		$w = $t->get_width();
		#coopy/TerminalDiffRender.hx:153: characters 9-32
		$h = $t->get_height();
		#coopy/TerminalDiffRender.hx:154: characters 9-40
		$v = $t->getCellView();
		#coopy/TerminalDiffRender.hx:155: characters 9-30
		$csv = new Csv();
		#coopy/TerminalDiffRender.hx:156: characters 9-38
		$sizes = new \Array_hx();
		#coopy/TerminalDiffRender.hx:157: characters 9-22
		$row = -1;
		#coopy/TerminalDiffRender.hx:158: characters 9-25
		$total = $w - 1;
		#coopy/TerminalDiffRender.hx:159: characters 19-23
		$_g = 0;
		#coopy/TerminalDiffRender.hx:159: characters 23-24
		$_g1 = $w;
		#coopy/TerminalDiffRender.hx:159: lines 159-201
		while ($_g < $_g1) {
			#coopy/TerminalDiffRender.hx:159: characters 19-24
			$x = $_g++;
			#coopy/TerminalDiffRender.hx:160: characters 13-31
			$m = 0;
			#coopy/TerminalDiffRender.hx:161: characters 13-32
			$m2 = 0;
			#coopy/TerminalDiffRender.hx:162: characters 13-32
			$mmax = 0;
			#coopy/TerminalDiffRender.hx:163: characters 13-36
			$mmostmax = 0;
			#coopy/TerminalDiffRender.hx:164: characters 13-33
			$mmin = -1;
			#coopy/TerminalDiffRender.hx:165: characters 23-27
			$_g2 = 0;
			#coopy/TerminalDiffRender.hx:165: characters 27-28
			$_g3 = $h;
			#coopy/TerminalDiffRender.hx:165: lines 165-180
			while ($_g2 < $_g3) {
				#coopy/TerminalDiffRender.hx:165: characters 23-28
				$y = $_g2++;
				#coopy/TerminalDiffRender.hx:166: characters 17-46
				$txt = $this->getText($x, $y, false);
				#coopy/TerminalDiffRender.hx:167: lines 167-169
				if (($txt === "@@") && ($row === -1) && $this->diff) {
					#coopy/TerminalDiffRender.hx:168: characters 21-28
					$row = $y;
				}
				#coopy/TerminalDiffRender.hx:170: lines 170-172
				if (($row === -1) && !$this->diff) {
					#coopy/TerminalDiffRender.hx:171: characters 21-28
					$row = $y;
				}
				#coopy/TerminalDiffRender.hx:173: characters 17-38
				$len = mb_strlen($txt);
				#coopy/TerminalDiffRender.hx:174: lines 174-176
				if ($y === $row) {
					#coopy/TerminalDiffRender.hx:175: characters 21-31
					$mmin = $len;
				}
				#coopy/TerminalDiffRender.hx:177: characters 17-25
				$m += $len;
				#coopy/TerminalDiffRender.hx:178: characters 17-30
				$m2 += $len * $len;
				#coopy/TerminalDiffRender.hx:179: characters 17-41
				if ($len > $mmax) {
					#coopy/TerminalDiffRender.hx:179: characters 31-41
					$mmax = $len;
				}
			}
			#coopy/TerminalDiffRender.hx:181: characters 13-28
			$mean = $m / $h;
			#coopy/TerminalDiffRender.hx:182: characters 13-54
			$stddev = \sqrt($m2 / $h - $mean * $mean);
			#coopy/TerminalDiffRender.hx:183: characters 13-51
			$most = (int)(($mean + $stddev * 2 + 0.5));
			#coopy/TerminalDiffRender.hx:184: characters 23-27
			$_g4 = 0;
			#coopy/TerminalDiffRender.hx:184: characters 27-28
			$_g5 = $h;
			#coopy/TerminalDiffRender.hx:184: lines 184-190
			while ($_g4 < $_g5) {
				#coopy/TerminalDiffRender.hx:184: characters 23-28
				$y1 = $_g4++;
				#coopy/TerminalDiffRender.hx:185: characters 17-46
				$txt1 = $this->getText($x, $y1, false);
				#coopy/TerminalDiffRender.hx:186: characters 17-38
				$len1 = mb_strlen($txt1);
				#coopy/TerminalDiffRender.hx:187: lines 187-189
				if ($len1 <= $most) {
					#coopy/TerminalDiffRender.hx:188: characters 21-53
					if ($len1 > $mmostmax) {
						#coopy/TerminalDiffRender.hx:188: characters 39-53
						$mmostmax = $len1;
					}
				}
			}
			#coopy/TerminalDiffRender.hx:191: characters 13-29
			$full = $mmax;
			#coopy/TerminalDiffRender.hx:192: characters 13-28
			$most = $mmostmax;
			#coopy/TerminalDiffRender.hx:193: lines 193-195
			if ($mmin !== -1) {
				#coopy/TerminalDiffRender.hx:194: characters 17-43
				if ($most < $mmin) {
					#coopy/TerminalDiffRender.hx:194: characters 32-43
					$most = $mmin;
				}
			}
			#coopy/TerminalDiffRender.hx:196: lines 196-198
			if ($this->wide_columns) {
				#coopy/TerminalDiffRender.hx:197: characters 17-28
				$most = $full;
			}
			#coopy/TerminalDiffRender.hx:199: characters 13-29
			$sizes->arr[$sizes->length++] = $most;
			#coopy/TerminalDiffRender.hx:200: characters 13-26
			$total += $most;
		}
		#coopy/TerminalDiffRender.hx:202: lines 202-204
		if (($total > 130) && !$this->wide_columns) {
			#coopy/TerminalDiffRender.hx:203: characters 13-24
			return null;
		}
		#coopy/TerminalDiffRender.hx:205: characters 9-21
		return $sizes;
	}

	/**
	 *
	 * Generate a string with appropriate ANSI colors for a given diff.
	 *
	 * @param t a tabular diff (perhaps generated by `TableDiff.hilite`)
	 * @return the diff in text form, with inserted color codes
	 *
	 * 
	 * @param Table $t
	 * 
	 * @return string
	 */
	public function render ($t) {
		#coopy/TerminalDiffRender.hx:63: characters 9-25
		$this->csv = new Csv();
		#coopy/TerminalDiffRender.hx:64: characters 9-33
		$result = "";
		#coopy/TerminalDiffRender.hx:65: characters 9-31
		$w = $t->get_width();
		#coopy/TerminalDiffRender.hx:66: characters 9-32
		$h = $t->get_height();
		#coopy/TerminalDiffRender.hx:67: characters 9-19
		$this->t = $t;
		#coopy/TerminalDiffRender.hx:68: characters 9-28
		$this->v = $t->getCellView();
		#coopy/TerminalDiffRender.hx:70: characters 9-41
		$this->codes = new StringMap();
		#coopy/TerminalDiffRender.hx:71: characters 9-40
		$this->codes->data["header"] = "\x1B[0;1m";
		#coopy/TerminalDiffRender.hx:72: characters 9-38
		$this->codes->data["minor"] = "\x1B[33m";
		#coopy/TerminalDiffRender.hx:73: characters 9-36
		$this->codes->data["done"] = "\x1B[0m";
		#coopy/TerminalDiffRender.hx:74: characters 9-38
		$this->codes->data["meta"] = "\x1B[0;1m";
		#coopy/TerminalDiffRender.hx:75: characters 9-39
		$this->codes->data["spec"] = "\x1B[35;1m";
		#coopy/TerminalDiffRender.hx:76: characters 9-38
		$this->codes->data["add"] = "\x1B[32;1m";
		#coopy/TerminalDiffRender.hx:77: characters 9-43
		$this->codes->data["conflict"] = "\x1B[33;1m";
		#coopy/TerminalDiffRender.hx:78: characters 9-41
		$this->codes->data["modify"] = "\x1B[34;1m";
		#coopy/TerminalDiffRender.hx:79: characters 9-41
		$this->codes->data["remove"] = "\x1B[31;1m";
		#coopy/TerminalDiffRender.hx:81: characters 9-26
		$sizes = null;
		#coopy/TerminalDiffRender.hx:82: characters 9-48
		if ($this->align_columns) {
			#coopy/TerminalDiffRender.hx:82: characters 28-48
			$sizes = $this->pickSizes($t);
		}
		#coopy/TerminalDiffRender.hx:84: characters 9-40
		$txts = new \Array_hx();
		#coopy/TerminalDiffRender.hx:85: characters 19-23
		$_g = 0;
		#coopy/TerminalDiffRender.hx:85: characters 23-24
		$_g1 = $h;
		#coopy/TerminalDiffRender.hx:85: lines 85-109
		while ($_g < $_g1) {
			#coopy/TerminalDiffRender.hx:85: characters 19-24
			$y = $_g++;
			#coopy/TerminalDiffRender.hx:86: characters 13-28
			$target = 0;
			#coopy/TerminalDiffRender.hx:87: characters 13-24
			$at = 0;
			#coopy/TerminalDiffRender.hx:88: characters 23-27
			$_g2 = 0;
			#coopy/TerminalDiffRender.hx:88: characters 27-28
			$_g3 = $w;
			#coopy/TerminalDiffRender.hx:88: lines 88-107
			while ($_g2 < $_g3) {
				#coopy/TerminalDiffRender.hx:88: characters 23-28
				$x = $_g2++;
				#coopy/TerminalDiffRender.hx:89: lines 89-95
				if ($sizes !== null) {
					#coopy/TerminalDiffRender.hx:90: characters 21-44
					$spaces = $target - $at;
					#coopy/TerminalDiffRender.hx:91: characters 31-35
					$_g4 = 0;
					#coopy/TerminalDiffRender.hx:91: characters 35-41
					$_g5 = $spaces;
					#coopy/TerminalDiffRender.hx:91: lines 91-94
					while ($_g4 < $_g5) {
						#coopy/TerminalDiffRender.hx:91: characters 31-41
						$i = $_g4++;
						#coopy/TerminalDiffRender.hx:92: characters 25-39
						$txts->arr[$txts->length++] = " ";
						#coopy/TerminalDiffRender.hx:93: characters 25-29
						++$at;
					}
				}
				#coopy/TerminalDiffRender.hx:96: lines 96-100
				if ($x > 0) {
					#coopy/TerminalDiffRender.hx:97: characters 21-46
					$x1 = ($this->codes->data["minor"] ?? null);
					$txts->arr[$txts->length++] = $x1;
					#coopy/TerminalDiffRender.hx:98: characters 21-37
					$txts->arr[$txts->length++] = $this->delim;
					#coopy/TerminalDiffRender.hx:99: characters 21-45
					$x2 = ($this->codes->data["done"] ?? null);
					$txts->arr[$txts->length++] = $x2;
				}
				#coopy/TerminalDiffRender.hx:101: characters 17-45
				$x3 = $this->getText($x, $y, true);
				$txts->arr[$txts->length++] = $x3;
				#coopy/TerminalDiffRender.hx:102: lines 102-106
				if ($sizes !== null) {
					#coopy/TerminalDiffRender.hx:103: characters 21-50
					$bit = $this->getText($x, $y, false);
					#coopy/TerminalDiffRender.hx:104: characters 21-37
					$at += mb_strlen($bit);
					#coopy/TerminalDiffRender.hx:105: characters 21-39
					$target += ($sizes->arr[$x] ?? null);
				}
			}
			#coopy/TerminalDiffRender.hx:108: characters 13-30
			$txts->arr[$txts->length++] = "\x0D\x0A";
		}
		#coopy/TerminalDiffRender.hx:110: characters 9-22
		$this->t = null;
		#coopy/TerminalDiffRender.hx:111: characters 9-17
		$this->v = null;
		#coopy/TerminalDiffRender.hx:112: characters 9-19
		$this->csv = null;
		#coopy/TerminalDiffRender.hx:113: characters 9-21
		$this->codes = null;
		#coopy/TerminalDiffRender.hx:114: characters 9-29
		return $txts->join("");
	}
}

Boot::registerClass(TerminalDiffRender::class, 'coopy.TerminalDiffRender');
