<?php
/**
 */

namespace coopy;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Log;
use \php\_Boot\HxString;

/**
 *
 * Read and write CSV format. You don't need to use this to use daff!
 * Feel free to use your own.
 *
 */
class Csv {
	/**
	 * @var int
	 */
	public $cursor;
	/**
	 * @var string
	 */
	public $delim;
	/**
	 * @var string
	 */
	public $discovered_eol;
	/**
	 * @var bool
	 */
	public $has_structure;
	/**
	 * @var string
	 */
	public $preferred_eol;
	/**
	 * @var bool
	 */
	public $row_ended;

	/**
	 *
	 * Constructor.
	 *
	 * @param delim cell delimiter to use, defaults to a comma
	 *
	 * 
	 * @param string $delim
	 * @param string $eol
	 * 
	 * @return void
	 */
	public function __construct ($delim = ",", $eol = null) {
		#coopy/Csv.hx:29: lines 29-35
		if ($delim === null) {
			$delim = ",";
		}
		#coopy/Csv.hx:30: characters 9-19
		$this->cursor = 0;
		#coopy/Csv.hx:31: characters 9-26
		$this->row_ended = false;
		#coopy/Csv.hx:32: characters 9-45
		$this->delim = ($delim === null ? "," : $delim);
		#coopy/Csv.hx:33: characters 9-35
		$this->discovered_eol = null;
		#coopy/Csv.hx:34: characters 9-33
		$this->preferred_eol = $eol;
	}

	/**
	 *
	 * Return the EOL sequence discovered the last time
	 * a CSV file/string was parsed.
	 *
	 * @return one of "\n", "\r", "\n\r", "\r\n", null
	 *
	 * 
	 * @return string
	 */
	public function getDiscoveredEol () {
		#coopy/Csv.hx:328: characters 9-30
		return $this->discovered_eol;
	}

	/**
	 *
	 * Create a table from a string in CSV format.
	 *
	 * @param txt the table encoded as a CSV-format string
	 * @return the decoded table
	 *
	 * 
	 * @param string $txt
	 * 
	 * @return Table
	 */
	public function makeTable ($txt) {
		#coopy/Csv.hx:207: characters 9-40
		$tab = new SimpleTable(0, 0);
		#coopy/Csv.hx:208: characters 9-28
		$this->parseTable($txt, $tab);
		#coopy/Csv.hx:209: characters 9-19
		return $tab;
	}

	/**
	 *
	 * Parse a string in CSV format representing a cell.
	 *
	 * @param txt the cell encoded as a CSV-format string
	 * @return the decoded content of the cell
	 *
	 * 
	 * @param string $txt
	 * 
	 * @return string
	 */
	public function parseCell ($txt) {
		#coopy/Csv.hx:313: characters 9-19
		$this->cursor = 0;
		#coopy/Csv.hx:314: characters 9-26
		$this->row_ended = false;
		#coopy/Csv.hx:315: characters 9-30
		$this->has_structure = false;
		#coopy/Csv.hx:316: characters 9-34
		return $this->parseCellPart($txt);
	}

	/**
	 * @param string $txt
	 * 
	 * @return string
	 */
	public function parseCellPart ($txt) {
		#coopy/Csv.hx:214: characters 9-35
		if ($txt === null) {
			#coopy/Csv.hx:214: characters 24-35
			return null;
		}
		#coopy/Csv.hx:215: characters 9-26
		$this->row_ended = false;
		#coopy/Csv.hx:216: characters 9-53
		$first_non_underscore = mb_strlen($txt);
		#coopy/Csv.hx:217: characters 9-38
		$last_processed = 0;
		#coopy/Csv.hx:218: characters 9-36
		$quoting = false;
		#coopy/Csv.hx:219: characters 9-29
		$quote = 0;
		#coopy/Csv.hx:220: characters 9-34
		$result = "";
		#coopy/Csv.hx:221: characters 9-33
		$start = $this->cursor;
		#coopy/Csv.hx:222: characters 19-25
		$_g = $this->cursor;
		#coopy/Csv.hx:222: characters 28-40
		$_g1 = mb_strlen($txt);
		#coopy/Csv.hx:222: lines 222-288
		while ($_g < $_g1) {
			#coopy/Csv.hx:222: characters 19-40
			$i = $_g++;
			#coopy/Csv.hx:223: characters 13-45
			$ch = HxString::charCodeAt($txt, $i);
			#coopy/Csv.hx:224: characters 13-31
			$last_processed = $i;
			#coopy/Csv.hx:225: lines 225-227
			if (($ch !== 95) && ($i < $first_non_underscore)) {
				#coopy/Csv.hx:226: characters 17-41
				$first_non_underscore = $i;
			}
			#coopy/Csv.hx:228: lines 228-286
			if ($this->has_structure) {
				#coopy/Csv.hx:229: lines 229-281
				if (!$quoting) {
					#coopy/Csv.hx:230: lines 230-247
					if ($ch === HxString::charCodeAt($this->delim, 0)) {
						#coopy/Csv.hx:231: lines 231-233
						if (mb_strlen($this->delim) === 1) {
							#coopy/Csv.hx:232: characters 29-34
							break;
						}
						#coopy/Csv.hx:234: lines 234-246
						if (($i + mb_strlen($this->delim)) <= mb_strlen($txt)) {
							#coopy/Csv.hx:235: characters 29-46
							$match = true;
							#coopy/Csv.hx:236: characters 39-43
							$_g2 = 1;
							#coopy/Csv.hx:236: characters 43-55
							$_g3 = mb_strlen($this->delim);
							#coopy/Csv.hx:236: lines 236-241
							while ($_g2 < $_g3) {
								#coopy/Csv.hx:236: characters 39-55
								$j = $_g2++;
								#coopy/Csv.hx:237: characters 37-52
								$index = $i + $j;
								#coopy/Csv.hx:237: lines 237-240
								if ((($index < 0 ? "" : \mb_substr($txt, $index, 1))) !== (($j < 0 ? "" : \mb_substr($this->delim, $j, 1)))) {
									#coopy/Csv.hx:238: characters 37-50
									$match = false;
									#coopy/Csv.hx:239: characters 37-42
									break;
								}
							}
							#coopy/Csv.hx:242: lines 242-245
							if ($match) {
								#coopy/Csv.hx:243: characters 33-63
								$last_processed += mb_strlen($this->delim) - 1;
								#coopy/Csv.hx:244: characters 33-38
								break;
							}
						}
					}
					#coopy/Csv.hx:248: lines 248-266
					if (($ch === 13) || ($ch === 10)) {
						#coopy/Csv.hx:249: characters 25-66
						$ch2 = HxString::charCodeAt($txt, $i + 1);
						#coopy/Csv.hx:250: lines 250-260
						if ($ch2 !== null) {
							#coopy/Csv.hx:251: lines 251-259
							if ($ch2 !== $ch) {
								#coopy/Csv.hx:252: lines 252-258
								if (($ch2 === 13) || ($ch2 === 10)) {
									#coopy/Csv.hx:253: lines 253-256
									if ($this->discovered_eol === null) {
										#coopy/Csv.hx:254: lines 254-255
										$this->discovered_eol = (\mb_chr($ch)??'null') . (\mb_chr($ch2)??'null');
									}
									#coopy/Csv.hx:257: characters 37-53
									++$last_processed;
								}
							}
						}
						#coopy/Csv.hx:261: lines 261-263
						if ($this->discovered_eol === null) {
							#coopy/Csv.hx:262: characters 29-69
							$this->discovered_eol = \mb_chr($ch);
						}
						#coopy/Csv.hx:264: characters 25-41
						$this->row_ended = true;
						#coopy/Csv.hx:265: characters 25-30
						break;
					}
					#coopy/Csv.hx:267: lines 267-278
					if ($ch === 34) {
						#coopy/Csv.hx:268: lines 268-277
						if ($i === $this->cursor) {
							#coopy/Csv.hx:269: characters 29-43
							$quoting = true;
							#coopy/Csv.hx:270: characters 29-39
							$quote = $ch;
							#coopy/Csv.hx:271: lines 271-273
							if ($i !== $start) {
								#coopy/Csv.hx:272: characters 33-66
								$result = ($result??'null') . (\mb_chr($ch)??'null');
							}
							#coopy/Csv.hx:274: characters 29-37
							continue;
						} else if ($ch === $quote) {
							#coopy/Csv.hx:276: characters 29-43
							$quoting = true;
						}
					}
					#coopy/Csv.hx:279: characters 21-54
					$result = ($result??'null') . (\mb_chr($ch)??'null');
					#coopy/Csv.hx:280: characters 21-29
					continue;
				}
				#coopy/Csv.hx:282: lines 282-285
				if ($ch === $quote) {
					#coopy/Csv.hx:283: characters 21-36
					$quoting = false;
					#coopy/Csv.hx:284: characters 21-29
					continue;
				}
			}
			#coopy/Csv.hx:287: characters 13-46
			$result = ($result??'null') . (\mb_chr($ch)??'null');
		}
		#coopy/Csv.hx:289: characters 9-32
		$this->cursor = $last_processed;
		#coopy/Csv.hx:290: lines 290-300
		if ($quote === 0) {
			#coopy/Csv.hx:291: lines 291-293
			if ($result === "NULL") {
				#coopy/Csv.hx:292: characters 17-28
				return null;
			}
			#coopy/Csv.hx:294: lines 294-299
			if ($first_non_underscore > $start) {
				#coopy/Csv.hx:295: characters 17-60
				$del = $first_non_underscore - $start;
				#coopy/Csv.hx:296: lines 296-298
				if (\mb_substr($result, $del, null) === "NULL") {
					#coopy/Csv.hx:297: characters 28-44
					return \mb_substr($result, 1, null);
				}
			}
		}
		#coopy/Csv.hx:301: characters 9-22
		return $result;
	}

	/**
	 *
	 * Parse a string in CSV format representing a table.
	 *
	 * @param txt the table encoded as a CSV-format string
	 * @param tab the table to store cells in
	 * @return true on success
	 *
	 * 
	 * @param string $txt
	 * @param Table $tab
	 * 
	 * @return bool
	 */
	public function parseTable ($txt, $tab) {
		#coopy/Csv.hx:156: characters 9-45
		if (!$tab->isResizable()) {
			#coopy/Csv.hx:156: characters 33-45
			return false;
		}
		#coopy/Csv.hx:157: characters 9-19
		$this->cursor = 0;
		#coopy/Csv.hx:158: characters 9-26
		$this->row_ended = false;
		#coopy/Csv.hx:159: characters 9-29
		$this->has_structure = true;
		#coopy/Csv.hx:160: characters 9-24
		$tab->resize(0, 0);
		#coopy/Csv.hx:161: characters 9-24
		$w = 0;
		#coopy/Csv.hx:162: characters 9-24
		$h = 0;
		#coopy/Csv.hx:163: characters 9-25
		$at = 0;
		#coopy/Csv.hx:164: characters 9-26
		$yat = 0;
		#coopy/Csv.hx:165: lines 165-193
		while ($this->cursor < mb_strlen($txt)) {
			#coopy/Csv.hx:166: characters 13-52
			$cell = $this->parseCellPart($txt);
			#coopy/Csv.hx:167: lines 167-170
			if ($yat >= $h) {
				#coopy/Csv.hx:168: characters 17-26
				$h = $yat + 1;
				#coopy/Csv.hx:169: characters 17-32
				$tab->resize($w, $h);
			}
			#coopy/Csv.hx:171: lines 171-185
			if ($at >= $w) {
				#coopy/Csv.hx:172: lines 172-184
				if ($yat > 0) {
					#coopy/Csv.hx:173: lines 173-180
					if (($cell !== "") && ($cell !== null)) {
						#coopy/Csv.hx:174: characters 25-51
						$context = "";
						#coopy/Csv.hx:175: characters 35-39
						$_g = 0;
						#coopy/Csv.hx:175: characters 39-40
						$_g1 = $w;
						#coopy/Csv.hx:175: lines 175-178
						while ($_g < $_g1) {
							#coopy/Csv.hx:175: characters 35-40
							$i = $_g++;
							#coopy/Csv.hx:176: characters 29-52
							if ($i > 0) {
								#coopy/Csv.hx:176: characters 38-52
								$context = ($context??'null') . ",";
							}
							#coopy/Csv.hx:177: characters 29-58
							$context = ($context??'null') . \Std::string($tab->getCell($i, $yat));
						}
						#coopy/Csv.hx:179: characters 25-30
						(Log::$trace)("Ignored overflowing row " . ($yat??'null') . " with cell '" . ($cell??'null') . "' after: " . ($context??'null'), new _HxAnon_Csv0("coopy/Csv.hx", 179, "coopy.Csv", "parseTable"));
					}
				} else {
					#coopy/Csv.hx:182: characters 21-29
					$w = $at + 1;
					#coopy/Csv.hx:183: characters 21-36
					$tab->resize($w, $h);
				}
			}
			#coopy/Csv.hx:186: characters 13-37
			$tab->setCell($at, $h - 1, $cell);
			#coopy/Csv.hx:187: characters 13-17
			++$at;
			#coopy/Csv.hx:188: lines 188-191
			if ($this->row_ended) {
				#coopy/Csv.hx:189: characters 17-23
				$at = 0;
				#coopy/Csv.hx:190: characters 17-22
				++$yat;
			}
			#coopy/Csv.hx:192: characters 13-21
			$this->cursor++;
		}
		#coopy/Csv.hx:194: characters 9-20
		return true;
	}

	/**
	 *
	 * Render a single cell in CSV format.
	 *
	 * @param v a helper for interpreting the cell content
	 * @param d the cell content
	 * @param force_quote set if cell should always be quoted
	 * @return the cell in text format, quoted in a CSV-y way
	 *
	 * 
	 * @param View $v
	 * @param mixed $d
	 * @param bool $force_quote
	 * 
	 * @return string
	 */
	public function renderCell ($v, $d, $force_quote = false) {
		#coopy/Csv.hx:77: lines 77-144
		if ($force_quote === null) {
			$force_quote = false;
		}
		#coopy/Csv.hx:78: lines 78-80
		if ($d === null) {
			#coopy/Csv.hx:79: characters 13-26
			return "NULL";
		}
		#coopy/Csv.hx:81: characters 9-41
		$str = $v->toString($d);
		#coopy/Csv.hx:82: characters 9-45
		$need_quote = $force_quote;
		#coopy/Csv.hx:83: lines 83-89
		if (!$need_quote) {
			#coopy/Csv.hx:84: lines 84-88
			if (mb_strlen($str) > 0) {
				#coopy/Csv.hx:85: characters 21-70
				$tmp = null;
				if (\mb_substr($str, 0, 1) !== " ") {
					#coopy/Csv.hx:85: characters 41-65
					$index = mb_strlen($str) - 1;
					#coopy/Csv.hx:85: characters 21-70
					$tmp = (($index < 0 ? "" : \mb_substr($str, $index, 1))) === " ";
				} else {
					$tmp = true;
				}
				#coopy/Csv.hx:85: lines 85-87
				if ($tmp) {
					#coopy/Csv.hx:86: characters 21-38
					$need_quote = true;
				}
			}
		}
		#coopy/Csv.hx:90: lines 90-119
		if (!$need_quote) {
			#coopy/Csv.hx:91: characters 23-27
			$_g = 0;
			#coopy/Csv.hx:91: characters 27-37
			$_g1 = mb_strlen($str);
			#coopy/Csv.hx:91: lines 91-118
			while ($_g < $_g1) {
				#coopy/Csv.hx:91: characters 23-37
				$i = $_g++;
				#coopy/Csv.hx:92: characters 17-49
				$ch = ($i < 0 ? "" : \mb_substr($str, $i, 1));
				#coopy/Csv.hx:93: lines 93-96
				if (($ch === "\"") || ($ch === "\x0D") || ($ch === "\x0A") || ($ch === "\x09")) {
					#coopy/Csv.hx:94: characters 21-38
					$need_quote = true;
					#coopy/Csv.hx:95: characters 21-26
					break;
				}
				#coopy/Csv.hx:97: lines 97-117
				if ($ch === \mb_substr($this->delim, 0, 1)) {
					#coopy/Csv.hx:98: lines 98-101
					if (mb_strlen($this->delim) === 1) {
						#coopy/Csv.hx:99: characters 25-42
						$need_quote = true;
						#coopy/Csv.hx:100: characters 25-30
						break;
					}
					#coopy/Csv.hx:104: lines 104-116
					if (($i + mb_strlen($this->delim)) <= mb_strlen($str)) {
						#coopy/Csv.hx:105: characters 25-42
						$match = true;
						#coopy/Csv.hx:106: characters 35-39
						$_g2 = 1;
						#coopy/Csv.hx:106: characters 39-51
						$_g3 = mb_strlen($this->delim);
						#coopy/Csv.hx:106: lines 106-111
						while ($_g2 < $_g3) {
							#coopy/Csv.hx:106: characters 35-51
							$j = $_g2++;
							#coopy/Csv.hx:107: characters 33-48
							$index = $i + $j;
							#coopy/Csv.hx:107: lines 107-110
							if ((($index < 0 ? "" : \mb_substr($str, $index, 1))) !== (($j < 0 ? "" : \mb_substr($this->delim, $j, 1)))) {
								#coopy/Csv.hx:108: characters 33-46
								$match = false;
								#coopy/Csv.hx:109: characters 33-38
								break;
							}
						}
						#coopy/Csv.hx:112: lines 112-115
						if ($match) {
							#coopy/Csv.hx:113: characters 29-46
							$need_quote = true;
							#coopy/Csv.hx:114: characters 29-34
							break;
						}
					}
				}
			}
		}
		#coopy/Csv.hx:121: characters 9-34
		$result = "";
		#coopy/Csv.hx:122: characters 9-43
		if ($need_quote) {
			#coopy/Csv.hx:122: characters 27-40
			$result = ($result??'null') . "\"";
		}
		#coopy/Csv.hx:123: characters 9-36
		$line_buf = "";
		#coopy/Csv.hx:124: characters 19-23
		$_g = 0;
		#coopy/Csv.hx:124: characters 23-33
		$_g1 = mb_strlen($str);
		#coopy/Csv.hx:124: lines 124-138
		while ($_g < $_g1) {
			#coopy/Csv.hx:124: characters 19-33
			$i = $_g++;
			#coopy/Csv.hx:125: characters 13-45
			$ch = ($i < 0 ? "" : \mb_substr($str, $i, 1));
			#coopy/Csv.hx:126: lines 126-128
			if ($ch === "\"") {
				#coopy/Csv.hx:127: characters 17-30
				$result = ($result??'null') . "\"";
			}
			#coopy/Csv.hx:129: lines 129-137
			if (($ch !== "\x0D") && ($ch !== "\x0A")) {
				#coopy/Csv.hx:130: lines 130-133
				if (mb_strlen($line_buf) > 0) {
					#coopy/Csv.hx:131: characters 21-39
					$result = ($result??'null') . ($line_buf??'null');
					#coopy/Csv.hx:132: characters 21-34
					$line_buf = "";
				}
				#coopy/Csv.hx:134: characters 17-29
				$result = ($result??'null') . ($ch??'null');
			} else {
				#coopy/Csv.hx:136: characters 17-29
				$line_buf = ($line_buf??'null') . ($ch??'null');
			}
		}
		#coopy/Csv.hx:139: lines 139-141
		if (mb_strlen($line_buf) > 0) {
			#coopy/Csv.hx:140: characters 13-31
			$result = ($result??'null') . ($line_buf??'null');
		}
		#coopy/Csv.hx:142: characters 9-43
		if ($need_quote) {
			#coopy/Csv.hx:142: characters 27-40
			$result = ($result??'null') . "\"";
		}
		#coopy/Csv.hx:143: characters 9-22
		return $result;
	}

	/**
	 *
	 * Convert a table to a string in CSV format.
	 *
	 * @param t the table to render
	 * @return the table as a string in CSV format
	 *
	 * 
	 * @param Table $t
	 * 
	 * @return string
	 */
	public function renderTable ($t) {
		#coopy/Csv.hx:46: characters 9-33
		$eol = $this->preferred_eol;
		#coopy/Csv.hx:47: lines 47-49
		if ($eol === null) {
			#coopy/Csv.hx:48: characters 13-25
			$eol = "\x0D\x0A";
		}
		#coopy/Csv.hx:50: characters 9-33
		$result = "";
		#coopy/Csv.hx:51: characters 9-40
		$v = $t->getCellView();
		#coopy/Csv.hx:52: characters 9-41
		$stream = new TableStream($t);
		#coopy/Csv.hx:53: characters 9-32
		$w = $stream->width();
		#coopy/Csv.hx:54: characters 9-40
		$txts = new \Array_hx();
		#coopy/Csv.hx:55: lines 55-63
		while ($stream->fetch()) {
			#coopy/Csv.hx:56: characters 23-27
			$_g = 0;
			#coopy/Csv.hx:56: characters 27-28
			$_g1 = $w;
			#coopy/Csv.hx:56: lines 56-61
			while ($_g < $_g1) {
				#coopy/Csv.hx:56: characters 23-28
				$x = $_g++;
				#coopy/Csv.hx:57: lines 57-59
				if ($x > 0) {
					#coopy/Csv.hx:58: characters 21-37
					$txts->arr[$txts->length++] = $this->delim;
				}
				#coopy/Csv.hx:60: characters 17-59
				$x1 = $this->renderCell($v, $stream->getCell($x));
				$txts->arr[$txts->length++] = $x1;
			}
			#coopy/Csv.hx:62: characters 13-27
			$txts->arr[$txts->length++] = $eol;
		}
		#coopy/Csv.hx:64: characters 9-29
		return $txts->join("");
	}

	/**
	 *
	 * Set the EOL sequence to use at end of rows.
	 * a CSV file/string was parsed.
	 *
	 * @param eol "\n" or "\r\n" - if it is something else
	 * I don't want to know.
	 *
	 * 
	 * @param string $eol
	 * 
	 * @return void
	 */
	public function setPreferredEol ($eol) {
		#coopy/Csv.hx:341: characters 9-28
		$this->preferred_eol = $eol;
	}
}

class _HxAnon_Csv0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

Boot::registerClass(Csv::class, 'coopy.Csv');
