<?php
/**
 */

namespace coopy;

use \php\Boot;
use \php\_Boot\HxString;

/**
 *
 * Convert a tabular diff into html form.  Typically called as `render(table).html()`.
 *
 */
class DiffRender {
	/**
	 * @var bool
	 */
	public $open;
	/**
	 * @var bool
	 */
	public $pretty_arrows;
	/**
	 * @var bool
	 */
	public $quote_html;
	/**
	 * @var string
	 */
	public $section;
	/**
	 * @var string
	 */
	public $td_close;
	/**
	 * @var string
	 */
	public $td_open;
	/**
	 * @var string[]|\Array_hx
	 */
	public $text_to_insert;

	/**
	 *
	 * Combine information about a single cell given row and column
	 * header information.  Usually `renderCell` will be much easier
	 * to use, this method is deprecated.
	 *
	 * 
	 * @param int $x
	 * @param int $y
	 * @param View $view
	 * @param mixed $raw
	 * @param string $vcol
	 * @param string $vrow
	 * @param string $vcorner
	 * @param CellInfo $cell
	 * @param int $offset
	 * 
	 * @return void
	 */
	public static function examineCell ($x, $y, $view, $raw, $vcol, $vrow, $vcorner, $cell, $offset = 0) {
		#coopy/DiffRender.hx:142: lines 142-255
		if ($offset === null) {
			$offset = 0;
		}
		#coopy/DiffRender.hx:143: characters 9-39
		$nested = $view->isHash($raw);
		#coopy/DiffRender.hx:144: characters 9-27
		$cell->category = "";
		#coopy/DiffRender.hx:145: characters 9-36
		$cell->category_given_tr = "";
		#coopy/DiffRender.hx:146: characters 9-28
		$cell->separator = "";
		#coopy/DiffRender.hx:147: characters 9-35
		$cell->pretty_separator = "";
		#coopy/DiffRender.hx:148: characters 9-32
		$cell->conflicted = false;
		#coopy/DiffRender.hx:149: characters 9-29
		$cell->updated = false;
		#coopy/DiffRender.hx:150: characters 9-67
		$cell->meta = $cell->pvalue = $cell->lvalue = $cell->rvalue = null;
		#coopy/DiffRender.hx:151: characters 9-25
		$cell->value = $raw;
		#coopy/DiffRender.hx:152: characters 9-39
		$cell->pretty_value = $cell->value;
		#coopy/DiffRender.hx:153: characters 9-34
		if ($vrow === null) {
			#coopy/DiffRender.hx:153: characters 25-34
			$vrow = "";
		}
		#coopy/DiffRender.hx:154: characters 9-34
		if ($vcol === null) {
			#coopy/DiffRender.hx:154: characters 25-34
			$vcol = "";
		}
		#coopy/DiffRender.hx:155: lines 155-162
		if ((mb_strlen($vrow) >= 3) && (\mb_substr($vrow, 0, 1) === "@") && (\mb_substr($vrow, 1, 1) !== "@")) {
			#coopy/DiffRender.hx:156: characters 13-43
			$idx = HxString::indexOf($vrow, "@", 1);
			#coopy/DiffRender.hx:157: lines 157-161
			if ($idx >= 0) {
				#coopy/DiffRender.hx:158: characters 17-49
				$cell->meta = \mb_substr($vrow, 1, $idx - 1);
				#coopy/DiffRender.hx:159: characters 24-54
				$vrow = \mb_substr($vrow, $idx + 1, mb_strlen($vrow));
				#coopy/DiffRender.hx:160: characters 17-39
				$cell->category = "meta";
			}
		}
		#coopy/DiffRender.hx:163: characters 9-43
		$removed_column = false;
		#coopy/DiffRender.hx:164: lines 164-166
		if ($vrow === ":") {
			#coopy/DiffRender.hx:165: characters 13-35
			$cell->category = "move";
		}
		#coopy/DiffRender.hx:167: lines 167-169
		if (($vrow === "") && ($offset === 1) && ($y === 0)) {
			#coopy/DiffRender.hx:168: characters 13-36
			$cell->category = "index";
		}
		#coopy/DiffRender.hx:170: lines 170-175
		if (HxString::indexOf($vcol, "+++") >= 0) {
			#coopy/DiffRender.hx:171: characters 13-59
			$cell->category_given_tr = $cell->category = "add";
		} else if (HxString::indexOf($vcol, "---") >= 0) {
			#coopy/DiffRender.hx:173: characters 13-62
			$cell->category_given_tr = $cell->category = "remove";
			#coopy/DiffRender.hx:174: characters 13-34
			$removed_column = true;
		}
		#coopy/DiffRender.hx:176: lines 176-251
		if ($vrow === "!") {
			#coopy/DiffRender.hx:177: characters 13-35
			$cell->category = "spec";
		} else if ($vrow === "@@") {
			#coopy/DiffRender.hx:179: characters 13-37
			$cell->category = "header";
		} else if ($vrow === "...") {
			#coopy/DiffRender.hx:181: characters 13-34
			$cell->category = "gap";
		} else if ($vrow === "+++") {
			#coopy/DiffRender.hx:183: lines 183-185
			if (!$removed_column) {
				#coopy/DiffRender.hx:184: characters 17-38
				$cell->category = "add";
			}
		} else if ($vrow === "---") {
			#coopy/DiffRender.hx:187: characters 13-37
			$cell->category = "remove";
		} else if (HxString::indexOf($vrow, "->") >= 0) {
			#coopy/DiffRender.hx:189: lines 189-250
			if (!$removed_column) {
				#coopy/DiffRender.hx:190: characters 17-62
				$tokens = HxString::split($vrow, "!");
				#coopy/DiffRender.hx:191: characters 17-42
				$full = $vrow;
				#coopy/DiffRender.hx:192: characters 17-47
				$part = ($tokens->arr[1] ?? null);
				#coopy/DiffRender.hx:193: characters 17-44
				if ($part === null) {
					#coopy/DiffRender.hx:193: characters 33-44
					$part = $full;
				}
				#coopy/DiffRender.hx:194: characters 17-53
				$str = $view->toString($cell->value);
				#coopy/DiffRender.hx:195: characters 17-40
				if ($str === null) {
					#coopy/DiffRender.hx:195: characters 32-40
					$str = "";
				}
				#coopy/DiffRender.hx:196: lines 196-249
				if ($nested || (HxString::indexOf($str, $part) >= 0)) {
					#coopy/DiffRender.hx:197: characters 21-49
					$cat = "modify";
					#coopy/DiffRender.hx:198: characters 21-36
					$div = $part;
					#coopy/DiffRender.hx:200: lines 200-210
					if ($part !== $full) {
						#coopy/DiffRender.hx:201: lines 201-205
						if ($nested) {
							#coopy/DiffRender.hx:202: characters 29-76
							$cell->conflicted = $view->hashExists($raw, "theirs");
						} else {
							#coopy/DiffRender.hx:204: characters 29-67
							$cell->conflicted = HxString::indexOf($str, $full) >= 0;
						}
						#coopy/DiffRender.hx:206: lines 206-209
						if ($cell->conflicted) {
							#coopy/DiffRender.hx:207: characters 29-39
							$div = $full;
							#coopy/DiffRender.hx:208: characters 29-45
							$cat = "conflict";
						}
					}
					#coopy/DiffRender.hx:211: characters 21-40
					$cell->updated = true;
					#coopy/DiffRender.hx:212: characters 21-41
					$cell->separator = $div;
					#coopy/DiffRender.hx:213: characters 21-48
					$cell->pretty_separator = $div;
					#coopy/DiffRender.hx:214: lines 214-231
					if ($nested) {
						#coopy/DiffRender.hx:215: lines 215-222
						if ($cell->conflicted) {
							#coopy/DiffRender.hx:216: characters 39-65
							$tokens1 = $view->hashGet($raw, "before");
							#coopy/DiffRender.hx:217: characters 39-63
							$tokens2 = $view->hashGet($raw, "ours");
							#coopy/DiffRender.hx:216: lines 216-218
							$tokens = \Array_hx::wrap([
								$tokens1,
								$tokens2,
								$view->hashGet($raw, "theirs"),
							]);
						} else {
							#coopy/DiffRender.hx:220: characters 39-65
							$tokens1 = $view->hashGet($raw, "before");
							#coopy/DiffRender.hx:220: lines 220-221
							$tokens = \Array_hx::wrap([
								$tokens1,
								$view->hashGet($raw, "after"),
							]);
						}
					} else {
						#coopy/DiffRender.hx:224: characters 25-77
						$cell->pretty_value = $view->toString($cell->pretty_value);
						#coopy/DiffRender.hx:225: characters 25-76
						if ($cell->pretty_value === null) {
							#coopy/DiffRender.hx:225: characters 54-76
							$cell->pretty_value = "";
						}
						#coopy/DiffRender.hx:226: lines 226-230
						if ($cell->pretty_value === $div) {
							#coopy/DiffRender.hx:227: characters 29-45
							$tokens = \Array_hx::wrap([
								"",
								"",
							]);
						} else {
							#coopy/DiffRender.hx:229: characters 29-66
							$tokens = HxString::split($cell->pretty_value, $div);
						}
					}
					#coopy/DiffRender.hx:232: characters 21-64
					$pretty_tokens = $tokens;
					#coopy/DiffRender.hx:233: lines 233-236
					if ($tokens->length >= 2) {
						#coopy/DiffRender.hx:234: characters 25-75
						$pretty_tokens->offsetSet(0, DiffRender::markSpaces(($tokens->arr[0] ?? null), ($tokens->arr[1] ?? null)));
						#coopy/DiffRender.hx:235: characters 25-75
						$pretty_tokens->offsetSet(1, DiffRender::markSpaces(($tokens->arr[1] ?? null), ($tokens->arr[0] ?? null)));
					}
					#coopy/DiffRender.hx:237: lines 237-241
					if ($tokens->length >= 3) {
						#coopy/DiffRender.hx:238: characters 25-61
						$ref = ($pretty_tokens->arr[0] ?? null);
						#coopy/DiffRender.hx:239: characters 25-69
						$pretty_tokens->offsetSet(0, DiffRender::markSpaces($ref, ($tokens->arr[2] ?? null)));
						#coopy/DiffRender.hx:240: characters 25-69
						$pretty_tokens->offsetSet(2, DiffRender::markSpaces(($tokens->arr[2] ?? null), $ref));
					}
					#coopy/DiffRender.hx:242: characters 21-70
					$cell->pretty_separator = \mb_chr(8594);
					#coopy/DiffRender.hx:243: characters 21-82
					$cell->pretty_value = $pretty_tokens->join($cell->pretty_separator);
					#coopy/DiffRender.hx:244: characters 21-65
					$cell->category_given_tr = $cell->category = $cat;
					#coopy/DiffRender.hx:245: characters 21-60
					$offset1 = ($cell->conflicted ? 1 : 0);
					#coopy/DiffRender.hx:246: characters 21-49
					$cell->lvalue = ($tokens->arr[$offset1] ?? null);
					#coopy/DiffRender.hx:247: characters 21-51
					$cell->rvalue = ($tokens->arr[$offset1 + 1] ?? null);
					#coopy/DiffRender.hx:248: characters 21-65
					if ($cell->conflicted) {
						#coopy/DiffRender.hx:248: characters 42-65
						$cell->pvalue = ($tokens->arr[0] ?? null);
					}
				}
			}
		}
		#coopy/DiffRender.hx:252: lines 252-254
		if (($x === 0) && ($offset > 0)) {
			#coopy/DiffRender.hx:253: characters 13-61
			$cell->category_given_tr = $cell->category = "index";
		}
	}

	/**
	 * @param string $sl
	 * @param string $sr
	 * 
	 * @return string
	 */
	public static function markSpaces ($sl, $sr) {
		#coopy/DiffRender.hx:258: characters 9-30
		if ($sl === $sr) {
			#coopy/DiffRender.hx:258: characters 21-30
			return $sl;
		}
		#coopy/DiffRender.hx:259: characters 9-44
		if (($sl === null) || ($sr === null)) {
			#coopy/DiffRender.hx:259: characters 35-44
			return $sl;
		}
		#coopy/DiffRender.hx:260: characters 9-59
		$slc = \StringTools::replace($sl, " ", "");
		#coopy/DiffRender.hx:261: characters 9-59
		$src = \StringTools::replace($sr, " ", "");
		#coopy/DiffRender.hx:262: characters 9-32
		if ($slc !== $src) {
			#coopy/DiffRender.hx:262: characters 23-32
			return $sl;
		}
		#coopy/DiffRender.hx:263: characters 9-43
		$slo = "";
		#coopy/DiffRender.hx:264: characters 9-26
		$il = 0;
		#coopy/DiffRender.hx:265: characters 9-26
		$ir = 0;
		#coopy/DiffRender.hx:266: lines 266-282
		while ($il < mb_strlen($sl)) {
			#coopy/DiffRender.hx:267: characters 13-45
			$cl = ($il < 0 ? "" : \mb_substr($sl, $il, 1));
			#coopy/DiffRender.hx:268: characters 13-34
			$cr = "";
			#coopy/DiffRender.hx:269: lines 269-271
			if ($ir < mb_strlen($sr)) {
				#coopy/DiffRender.hx:270: characters 22-35
				$cr = ($ir < 0 ? "" : \mb_substr($sr, $ir, 1));
			}
			#coopy/DiffRender.hx:272: lines 272-281
			if ($cl === $cr) {
				#coopy/DiffRender.hx:273: characters 17-26
				$slo = ($slo??'null') . ($cl??'null');
				#coopy/DiffRender.hx:274: characters 17-21
				++$il;
				#coopy/DiffRender.hx:275: characters 17-21
				++$ir;
			} else if ($cr === " ") {
				#coopy/DiffRender.hx:277: characters 17-21
				++$ir;
			} else {
				#coopy/DiffRender.hx:279: characters 17-49
				$slo = ($slo??'null') . (\mb_chr(9251)??'null');
				#coopy/DiffRender.hx:280: characters 17-21
				++$il;
			}
		}
		#coopy/DiffRender.hx:283: characters 9-19
		return $slo;
	}

	/**
	 *
	 * Extract information about a single cell.
	 * Useful if you are doing custom rendering.
	 *
	 * @param tab the table
	 * @param view a viewer for cells of the table
	 * @param x cell column
	 * @param y cell row
	 * @return details of what is in the cell
	 *
	 * 
	 * @param Table $tab
	 * @param View $view
	 * @param int $x
	 * @param int $y
	 * 
	 * @return CellInfo
	 */
	public static function renderCell ($tab, $view, $x, $y) {
		#coopy/DiffRender.hx:302: characters 9-46
		$cell = new CellInfo();
		#coopy/DiffRender.hx:303: characters 9-63
		$corner = $view->toString($tab->getCell(0, 0));
		#coopy/DiffRender.hx:304: characters 9-49
		$off = ($corner === "@:@" ? 1 : 0);
		#coopy/DiffRender.hx:306: lines 306-314
		DiffRender::examineCell($x, $y, $view, $tab->getCell($x, $y), $view->toString($tab->getCell($x, $off)), $view->toString($tab->getCell($off, $y)), $corner, $cell, $off);
		#coopy/DiffRender.hx:315: characters 9-20
		return $cell;
	}

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/DiffRender.hx:23: characters 9-45
		$this->text_to_insert = new \Array_hx();
		#coopy/DiffRender.hx:24: characters 9-21
		$this->open = false;
		#coopy/DiffRender.hx:25: characters 9-29
		$this->pretty_arrows = true;
		#coopy/DiffRender.hx:26: characters 9-26
		$this->quote_html = true;
	}

	/**
	 * @param string $mode
	 * 
	 * @return void
	 */
	public function beginRow ($mode) {
		#coopy/DiffRender.hx:68: characters 9-24
		$this->td_open = "<td";
		#coopy/DiffRender.hx:69: characters 9-27
		$this->td_close = "</td>";
		#coopy/DiffRender.hx:70: characters 9-37
		$row_class = "";
		#coopy/DiffRender.hx:71: lines 71-74
		if ($mode === "header") {
			#coopy/DiffRender.hx:72: characters 13-28
			$this->td_open = "<th";
			#coopy/DiffRender.hx:73: characters 13-31
			$this->td_close = "</th>";
		}
		#coopy/DiffRender.hx:75: characters 9-25
		$row_class = $mode;
		#coopy/DiffRender.hx:76: characters 9-34
		$tr = "<tr>";
		#coopy/DiffRender.hx:77: lines 77-79
		if ($row_class !== "") {
			#coopy/DiffRender.hx:78: characters 13-52
			$tr = "<tr class=\"" . ($row_class??'null') . "\">";
		}
		#coopy/DiffRender.hx:80: characters 9-19
		$this->insert($tr);
	}

	/**
	 * @return void
	 */
	public function beginTable () {
		#coopy/DiffRender.hx:48: characters 9-28
		$this->insert("<table>\x0A");
		#coopy/DiffRender.hx:49: characters 9-23
		$this->section = null;
	}

	/**
	 *
	 * Call this after rendering the table to add a header/footer
	 * and style sheet for a complete test page.
	 *
	 * 
	 * @return void
	 */
	public function completeHtml () {
		#coopy/DiffRender.hx:477: lines 477-482
		$this->text_to_insert->insert(0, "<!DOCTYPE html>\x0A<html>\x0A<head>\x0A<meta charset='utf-8'>\x0A<style TYPE='text/css'>\x0A");
		#coopy/DiffRender.hx:483: characters 9-45
		$this->text_to_insert->insert(1, $this->sampleCss());
		#coopy/DiffRender.hx:484: lines 484-488
		$this->text_to_insert->insert(2, "</style>\x0A</head>\x0A<body>\x0A<div class='highlighter'>\x0A");
		#coopy/DiffRender.hx:489: lines 489-492
		$_this = $this->text_to_insert;
		$_this->arr[$_this->length++] = "</div>\x0A</body>\x0A</html>\x0A";
	}

	/**
	 * @return void
	 */
	public function endRow () {
		#coopy/DiffRender.hx:98: characters 9-26
		$this->insert("</tr>\x0A");
	}

	/**
	 * @return void
	 */
	public function endTable () {
		#coopy/DiffRender.hx:103: characters 9-25
		$this->setSection(null);
		#coopy/DiffRender.hx:104: characters 9-29
		$this->insert("</table>\x0A");
	}

	/**
	 *
	 * @return the generated html, make sure to call `render(table)` first
	 * or it will be empty
	 *
	 * 
	 * @return string
	 */
	public function html () {
		#coopy/DiffRender.hx:114: characters 9-39
		return $this->text_to_insert->join("");
	}

	/**
	 * @param string $str
	 * 
	 * @return void
	 */
	public function insert ($str) {
		#coopy/DiffRender.hx:44: characters 9-33
		$_this = $this->text_to_insert;
		$_this->arr[$_this->length++] = $str;
	}

	/**
	 * @param string $txt
	 * @param string $mode
	 * 
	 * @return void
	 */
	public function insertCell ($txt, $mode) {
		#coopy/DiffRender.hx:84: characters 9-41
		$cell_decorate = "";
		#coopy/DiffRender.hx:85: lines 85-87
		if ($mode !== "") {
			#coopy/DiffRender.hx:86: characters 13-54
			$cell_decorate = " class=\"" . ($mode??'null') . "\"";
		}
		#coopy/DiffRender.hx:88: characters 9-42
		$this->insert(($this->td_open??'null') . ($cell_decorate??'null') . ">");
		#coopy/DiffRender.hx:89: lines 89-93
		if ($txt !== null) {
			#coopy/DiffRender.hx:90: characters 13-24
			$this->insert($txt);
		} else {
			#coopy/DiffRender.hx:92: characters 13-27
			$this->insert("null");
		}
		#coopy/DiffRender.hx:94: characters 9-25
		$this->insert($this->td_close);
	}

	/**
	 * @param bool $flag
	 * 
	 * @return void
	 */
	public function quoteHtml ($flag) {
		#coopy/DiffRender.hx:40: characters 9-26
		$this->quote_html = $flag;
	}

	/**
	 *
	 * Render a table as html - call `html()` or similar to get the result.
	 *
	 * @param tab the table to render
	 * @return self, so you can call render(table).html()
	 *
	 * 
	 * @param Table $tab
	 * 
	 * @return DiffRender
	 */
	public function render ($tab) {
		#coopy/DiffRender.hx:327: characters 9-33
		$tab = Coopy::tablify($tab);
		#coopy/DiffRender.hx:328: characters 9-53
		if (($tab->get_width() === 0) || ($tab->get_height() === 0)) {
			#coopy/DiffRender.hx:328: characters 42-53
			return $this;
		}
		#coopy/DiffRender.hx:329: characters 9-40
		$render = $this;
		#coopy/DiffRender.hx:330: characters 9-28
		$render->beginTable();
		#coopy/DiffRender.hx:331: characters 9-35
		$change_row = -1;
		#coopy/DiffRender.hx:332: characters 9-46
		$cell = new CellInfo();
		#coopy/DiffRender.hx:333: characters 9-38
		$view = $tab->getCellView();
		#coopy/DiffRender.hx:334: characters 9-63
		$corner = $view->toString($tab->getCell(0, 0));
		#coopy/DiffRender.hx:335: characters 9-49
		$off = ($corner === "@:@" ? 1 : 0);
		#coopy/DiffRender.hx:336: lines 336-338
		if ($off > 0) {
			#coopy/DiffRender.hx:337: characters 13-57
			if (($tab->get_width() <= 1) || ($tab->get_height() <= 1)) {
				#coopy/DiffRender.hx:337: characters 46-57
				return $this;
			}
		}
		#coopy/DiffRender.hx:339: characters 21-25
		$_g = 0;
		#coopy/DiffRender.hx:339: characters 25-35
		$_g1 = $tab->get_height();
		#coopy/DiffRender.hx:339: lines 339-375
		while ($_g < $_g1) {
			#coopy/DiffRender.hx:339: characters 21-35
			$row = $_g++;
			#coopy/DiffRender.hx:341: characters 13-37
			$open = false;
			#coopy/DiffRender.hx:343: characters 13-68
			$txt = $view->toString($tab->getCell($off, $row));
			#coopy/DiffRender.hx:344: characters 13-36
			if ($txt === null) {
				#coopy/DiffRender.hx:344: characters 28-36
				$txt = "";
			}
			#coopy/DiffRender.hx:345: characters 13-65
			DiffRender::examineCell($off, $row, $view, $txt, "", $txt, $corner, $cell, $off);
			#coopy/DiffRender.hx:346: characters 13-51
			$row_mode = $cell->category;
			#coopy/DiffRender.hx:347: lines 347-349
			if ($row_mode === "spec") {
				#coopy/DiffRender.hx:348: characters 17-33
				$change_row = $row;
			}
			#coopy/DiffRender.hx:350: lines 350-354
			if (($row_mode === "header") || ($row_mode === "spec") || ($row_mode === "index") || ($row_mode === "meta")) {
				#coopy/DiffRender.hx:351: characters 17-35
				$this->setSection("head");
			} else {
				#coopy/DiffRender.hx:353: characters 17-35
				$this->setSection("body");
			}
			#coopy/DiffRender.hx:356: characters 13-38
			$render->beginRow($row_mode);
			#coopy/DiffRender.hx:358: characters 23-27
			$_g2 = 0;
			#coopy/DiffRender.hx:358: characters 27-36
			$_g3 = $tab->get_width();
			#coopy/DiffRender.hx:358: lines 358-373
			while ($_g2 < $_g3) {
				#coopy/DiffRender.hx:358: characters 23-36
				$c = $_g2++;
				#coopy/DiffRender.hx:359: lines 359-367
				DiffRender::examineCell($c, $row, $view, $tab->getCell($c, $row), ($change_row >= 0 ? $view->toString($tab->getCell($c, $change_row)) : ""), $txt, $corner, $cell, $off);
				#coopy/DiffRender.hx:368: characters 17-70
				$val = ($this->pretty_arrows ? $cell->pretty_value : $cell->value);
				#coopy/DiffRender.hx:369: lines 369-371
				if ($this->quote_html) {
					#coopy/DiffRender.hx:370: characters 27-69
					$val = \htmlspecialchars($view->toString($val), (null ? \ENT_QUOTES | \ENT_HTML401 : \ENT_NOQUOTES));
				}
				#coopy/DiffRender.hx:372: characters 17-63
				$render->insertCell($val, $cell->category_given_tr);
			}
			#coopy/DiffRender.hx:374: characters 13-28
			$render->endRow();
		}
		#coopy/DiffRender.hx:376: characters 9-26
		$render->endTable();
		#coopy/DiffRender.hx:377: characters 9-20
		return $this;
	}

	/**
	 * @param Tables $tabs
	 * 
	 * @return DiffRender
	 */
	public function renderTables ($tabs) {
		#coopy/DiffRender.hx:381: characters 9-53
		$order = $tabs->getOrder();
		#coopy/DiffRender.hx:382: characters 9-23
		$start = 0;
		#coopy/DiffRender.hx:383: lines 383-386
		if (($order->length <= 1) || $tabs->hasInsDel()) {
			#coopy/DiffRender.hx:384: characters 13-31
			$this->render($tabs->one());
			#coopy/DiffRender.hx:385: characters 13-22
			$start = 1;
		}
		#coopy/DiffRender.hx:387: characters 19-24
		$_g = $start;
		#coopy/DiffRender.hx:387: characters 27-39
		$_g1 = $order->length;
		#coopy/DiffRender.hx:387: lines 387-395
		while ($_g < $_g1) {
			#coopy/DiffRender.hx:387: characters 19-39
			$i = $_g++;
			#coopy/DiffRender.hx:388: characters 13-33
			$name = ($order->arr[$i] ?? null);
			#coopy/DiffRender.hx:389: characters 13-46
			$tab = $tabs->get($name);
			#coopy/DiffRender.hx:390: characters 13-40
			if ($tab->get_height() <= 1) {
				#coopy/DiffRender.hx:390: characters 32-40
				continue;
			}
			#coopy/DiffRender.hx:391: characters 13-27
			$this->insert("<h3>");
			#coopy/DiffRender.hx:392: characters 13-25
			$this->insert($name);
			#coopy/DiffRender.hx:393: characters 13-30
			$this->insert("</h3>\x0A");
			#coopy/DiffRender.hx:394: characters 13-24
			$this->render($tab);
		}
		#coopy/DiffRender.hx:396: characters 9-20
		return $this;
	}

	/**
	 *
	 * @return sample css for the generated html
	 *
	 * 
	 * @return string
	 */
	public function sampleCss () {
		#coopy/DiffRender.hx:405: lines 405-467
		return ".highlighter .add { \x0A  background-color: #7fff7f;\x0A}\x0A\x0A.highlighter .remove { \x0A  background-color: #ff7f7f;\x0A}\x0A\x0A.highlighter td.modify { \x0A  background-color: #7f7fff;\x0A}\x0A\x0A.highlighter td.conflict { \x0A  background-color: #f00;\x0A}\x0A\x0A.highlighter .spec { \x0A  background-color: #aaa;\x0A}\x0A\x0A.highlighter .move { \x0A  background-color: #ffa;\x0A}\x0A\x0A.highlighter .null { \x0A  color: #888;\x0A}\x0A\x0A.highlighter table { \x0A  border-collapse:collapse;\x0A}\x0A\x0A.highlighter td, .highlighter th {\x0A  border: 1px solid #2D4068;\x0A  padding: 3px 7px 2px;\x0A}\x0A\x0A.highlighter th, .highlighter .header, .highlighter .meta {\x0A  background-color: #aaf;\x0A  font-weight: bold;\x0A  padding-bottom: 4px;\x0A  padding-top: 5px;\x0A  text-align:left;\x0A}\x0A\x0A.highlighter tr.header th {\x0A  border-bottom: 2px solid black;\x0A}\x0A\x0A.highlighter tr.index td, .highlighter .index, .highlighter tr.header th.index {\x0A  background-color: white;\x0A  border: none;\x0A}\x0A\x0A.highlighter .gap {\x0A  color: #888;\x0A}\x0A\x0A.highlighter td {\x0A  empty-cells: show;\x0A  white-space: pre-wrap;\x0A}\x0A";
	}

	/**
	 * @param string $str
	 * 
	 * @return void
	 */
	public function setSection ($str) {
		#coopy/DiffRender.hx:53: characters 9-33
		if ($str === $this->section) {
			#coopy/DiffRender.hx:53: characters 27-33
			return;
		}
		#coopy/DiffRender.hx:54: lines 54-58
		if ($this->section !== null) {
			#coopy/DiffRender.hx:55: characters 13-26
			$this->insert("</t");
			#coopy/DiffRender.hx:56: characters 13-28
			$this->insert($this->section);
			#coopy/DiffRender.hx:57: characters 13-26
			$this->insert(">\x0A");
		}
		#coopy/DiffRender.hx:59: characters 9-22
		$this->section = $str;
		#coopy/DiffRender.hx:60: lines 60-64
		if ($this->section !== null) {
			#coopy/DiffRender.hx:61: characters 13-25
			$this->insert("<t");
			#coopy/DiffRender.hx:62: characters 13-28
			$this->insert($this->section);
			#coopy/DiffRender.hx:63: characters 13-26
			$this->insert(">\x0A");
		}
	}

	/**
	 *
	 * @return the generated html
	 *
	 * 
	 * @return string
	 */
	public function toString () {
		#coopy/DiffRender.hx:123: characters 9-22
		return $this->html();
	}

	/**
	 *
	 * Call this if you want arrow separators `->` to be converted to prettier
	 * glyphs.
	 *
	 * 
	 * @param bool $flag
	 * 
	 * @return void
	 */
	public function usePrettyArrows ($flag) {
		#coopy/DiffRender.hx:36: characters 9-29
		$this->pretty_arrows = $flag;
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(DiffRender::class, 'coopy.DiffRender');
