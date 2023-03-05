<?php
/**
 */

namespace coopy;

use \php\Boot;
use \php\_Boot\HxString;
use \haxe\ds\IntMap;
use \haxe\ds\StringMap;

/**
 *
 * Apply a tabular diff as a patch.
 *
 */
class HighlightPatch implements Row {
	/**
	 * @var string[]|\Array_hx
	 */
	public $actions;
	/**
	 * @var CellInfo
	 */
	public $cellInfo;
	/**
	 * @var HighlightPatchUnit[]|\Array_hx
	 */
	public $cmods;
	/**
	 * @var int[]|\Array_hx
	 */
	public $colPermutation;
	/**
	 * @var int[]|\Array_hx
	 */
	public $colPermutationRev;
	/**
	 * @var Csv
	 */
	public $csv;
	/**
	 * @var int
	 */
	public $currentRow;
	/**
	 * @var IntMap
	 */
	public $destInPatchCol;
	/**
	 * @var bool
	 */
	public $finished_columns;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var bool
	 */
	public $haveDroppedColumns;
	/**
	 * @var IntMap
	 */
	public $header;
	/**
	 * @var StringMap
	 */
	public $headerMove;
	/**
	 * @var StringMap
	 */
	public $headerPost;
	/**
	 * @var StringMap
	 */
	public $headerPre;
	/**
	 * @var StringMap
	 */
	public $headerRename;
	/**
	 * @var int
	 */
	public $headerRow;
	/**
	 * @var IndexPair[]|\Array_hx
	 */
	public $indexes;
	/**
	 * @var int
	 */
	public $lastSourceRow;
	/**
	 * @var Meta
	 */
	public $meta;
	/**
	 * @var bool
	 */
	public $meta_change;
	/**
	 * @var IntMap
	 */
	public $modifier;
	/**
	 * @var HighlightPatchUnit[]|\Array_hx
	 */
	public $mods;
	/**
	 * @var StringMap
	 */
	public $next_meta;
	/**
	 * @var Table
	 */
	public $patch;
	/**
	 * @var IntMap
	 */
	public $patchInDestCol;
	/**
	 * @var IntMap
	 */
	public $patchInSourceCol;
	/**
	 * @var IntMap
	 */
	public $patchInSourceRow;
	/**
	 * @var int
	 */
	public $payloadCol;
	/**
	 * @var int
	 */
	public $payloadTop;
	/**
	 * @var int
	 */
	public $preambleRow;
	/**
	 * @var StringMap
	 */
	public $prev_meta;
	/**
	 * @var bool
	 */
	public $process_meta;
	/**
	 * @var int
	 */
	public $rcOffset;
	/**
	 * @var CellInfo
	 */
	public $rowInfo;
	/**
	 * @var int[]|\Array_hx
	 */
	public $rowPermutation;
	/**
	 * @var int[]|\Array_hx
	 */
	public $rowPermutationRev;
	/**
	 * @var Table
	 */
	public $source;
	/**
	 * @var IntMap
	 */
	public $sourceInPatchCol;
	/**
	 * @var View
	 */
	public $sourceView;
	/**
	 * @var View
	 */
	public $view;

	/**
	 *
	 * Constructor.
	 *
	 * @param source the table to patch
	 * @param patch the tabular diff to use as a patch
	 *
	 * 
	 * @param Table $source
	 * @param Table $patch
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($source, $patch, $flags = null) {
		#coopy/HighlightPatch.hx:77: characters 9-29
		$this->source = $source;
		#coopy/HighlightPatch.hx:78: characters 9-27
		$this->patch = $patch;
		#coopy/HighlightPatch.hx:79: characters 9-27
		$this->flags = $flags;
		#coopy/HighlightPatch.hx:80: characters 9-57
		if ($flags === null) {
			#coopy/HighlightPatch.hx:80: characters 26-57
			$this->flags = new CompareFlags();
		}
		#coopy/HighlightPatch.hx:81: characters 9-35
		$this->view = $patch->getCellView();
		#coopy/HighlightPatch.hx:82: characters 9-42
		$this->sourceView = $source->getCellView();
		#coopy/HighlightPatch.hx:83: characters 9-32
		$this->meta = $source->getMeta();
	}

	/**
	 *
	 * Apply the patch.
	 *
	 * @return true on success
	 *
	 * 
	 * @return bool
	 */
	public function apply () {
		#coopy/HighlightPatch.hx:134: characters 9-16
		$this->reset();
		#coopy/HighlightPatch.hx:135: characters 9-39
		if ($this->patch->get_width() < 2) {
			#coopy/HighlightPatch.hx:135: characters 28-39
			return true;
		}
		#coopy/HighlightPatch.hx:136: characters 9-40
		if ($this->patch->get_height() < 1) {
			#coopy/HighlightPatch.hx:136: characters 29-40
			return true;
		}
		#coopy/HighlightPatch.hx:137: characters 9-32
		$this->payloadCol = 1 + $this->rcOffset;
		#coopy/HighlightPatch.hx:138: characters 9-33
		$this->payloadTop = $this->patch->get_width();
		#coopy/HighlightPatch.hx:139: characters 9-80
		$corner = $this->patch->getCellView()->toString($this->patch->getCell(0, 0));
		#coopy/HighlightPatch.hx:140: characters 9-43
		$this->rcOffset = ($corner === "@:@" ? 1 : 0);
		#coopy/HighlightPatch.hx:141: characters 19-23
		$_g = 0;
		#coopy/HighlightPatch.hx:141: characters 23-35
		$_g1 = $this->patch->get_height();
		#coopy/HighlightPatch.hx:141: lines 141-144
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:141: characters 19-35
			$r = $_g++;
			#coopy/HighlightPatch.hx:142: characters 13-73
			$str = $this->view->toString($this->patch->getCell($this->rcOffset, $r));
			#coopy/HighlightPatch.hx:143: characters 13-45
			$_this = $this->actions;
			$_this->arr[$_this->length++] = ($str !== null ? $str : "");
		}
		#coopy/HighlightPatch.hx:145: characters 9-43
		$this->preambleRow = $this->headerRow = $this->rcOffset;
		#coopy/HighlightPatch.hx:146: characters 19-23
		$_g = 0;
		#coopy/HighlightPatch.hx:146: characters 23-35
		$_g1 = $this->patch->get_height();
		#coopy/HighlightPatch.hx:146: lines 146-148
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:146: characters 19-35
			$r = $_g++;
			#coopy/HighlightPatch.hx:147: characters 13-24
			$this->applyRow($r);
		}
		#coopy/HighlightPatch.hx:149: characters 9-24
		$this->finishColumns();
		#coopy/HighlightPatch.hx:150: characters 9-21
		$this->finishRows();
		#coopy/HighlightPatch.hx:151: characters 9-20
		return true;
	}

	/**
	 * @param string $code
	 * 
	 * @return void
	 */
	public function applyAction ($code) {
		#coopy/HighlightPatch.hx:444: lines 444-447
		if ($this->useMetaForRowChanges()) {
			#coopy/HighlightPatch.hx:445: characters 13-38
			$this->applyActionExternal($code);
			#coopy/HighlightPatch.hx:446: characters 13-19
			return;
		}
		#coopy/HighlightPatch.hx:448: characters 9-65
		$mod = new HighlightPatchUnit();
		#coopy/HighlightPatch.hx:449: characters 9-24
		$mod->code = $code;
		#coopy/HighlightPatch.hx:450: characters 9-34
		$mod->add = $code === "+++";
		#coopy/HighlightPatch.hx:451: characters 9-34
		$mod->rem = $code === "---";
		#coopy/HighlightPatch.hx:452: characters 9-36
		$mod->update = $code === "->";
		#coopy/HighlightPatch.hx:453: characters 9-26
		$this->needSourceIndex();
		#coopy/HighlightPatch.hx:454: lines 454-456
		if ($this->lastSourceRow === -1) {
			#coopy/HighlightPatch.hx:455: characters 13-39
			$this->lastSourceRow = $this->lookUp(-1);
		}
		#coopy/HighlightPatch.hx:457: characters 9-42
		$mod->sourcePrevRow = $this->lastSourceRow;
		#coopy/HighlightPatch.hx:458: characters 9-54
		$nextAct = ($this->actions->arr[$this->currentRow + 1] ?? null);
		#coopy/HighlightPatch.hx:459: lines 459-461
		if (($nextAct !== "+++") && ($nextAct !== "...")) {
			#coopy/HighlightPatch.hx:460: characters 13-42
			$mod->sourceNextRow = $this->lookUp(1);
		}
		#coopy/HighlightPatch.hx:462: lines 462-475
		if ($mod->add) {
			#coopy/HighlightPatch.hx:463: lines 463-470
			if (($this->actions->arr[$this->currentRow - 1] ?? null) !== "+++") {
				#coopy/HighlightPatch.hx:464: lines 464-469
				if (($this->actions->arr[$this->currentRow - 1] ?? null) === "@@") {
					#coopy/HighlightPatch.hx:465: characters 21-42
					$mod->sourcePrevRow = 0;
					#coopy/HighlightPatch.hx:466: characters 21-38
					$this->lastSourceRow = 0;
				} else {
					#coopy/HighlightPatch.hx:468: characters 21-51
					$mod->sourcePrevRow = $this->lookUp(-1);
				}
			}
			#coopy/HighlightPatch.hx:471: characters 13-46
			$mod->sourceRow = $mod->sourcePrevRow;
			#coopy/HighlightPatch.hx:472: characters 13-59
			if ($mod->sourceRow !== -1) {
				#coopy/HighlightPatch.hx:472: characters 36-59
				$mod->sourceRowOffset = 1;
			}
		} else {
			#coopy/HighlightPatch.hx:474: characters 13-53
			$mod->sourceRow = $this->lastSourceRow = $this->lookUp();
		}
		#coopy/HighlightPatch.hx:476: lines 476-478
		if (($this->actions->arr[$this->currentRow + 1] ?? null) === "") {
			#coopy/HighlightPatch.hx:477: characters 13-46
			$this->lastSourceRow = $mod->sourceNextRow;
		}
		#coopy/HighlightPatch.hx:479: characters 9-34
		$mod->patchRow = $this->currentRow;
		#coopy/HighlightPatch.hx:480: lines 480-482
		if ($code === "@@") {
			#coopy/HighlightPatch.hx:481: characters 13-30
			$mod->sourceRow = 0;
		}
		#coopy/HighlightPatch.hx:483: characters 9-23
		$_this = $this->mods;
		$_this->arr[$_this->length++] = $mod;
	}

	/**
	 * @param string $code
	 * 
	 * @return void
	 */
	public function applyActionExternal ($code) {
		#coopy/HighlightPatch.hx:393: characters 9-31
		if ($code === "@@") {
			#coopy/HighlightPatch.hx:393: characters 25-31
			return;
		}
		#coopy/HighlightPatch.hx:395: characters 9-34
		$rc = new RowChange();
		#coopy/HighlightPatch.hx:396: characters 9-25
		$rc->action = $code;
		#coopy/HighlightPatch.hx:398: characters 9-19
		$this->checkAct();
		#coopy/HighlightPatch.hx:399: characters 9-61
		if ($code !== "+++") {
			#coopy/HighlightPatch.hx:399: characters 26-61
			$rc->cond = new StringMap();
		}
		#coopy/HighlightPatch.hx:400: characters 9-60
		if ($code !== "---") {
			#coopy/HighlightPatch.hx:400: characters 26-60
			$rc->val = new StringMap();
		}
		#coopy/HighlightPatch.hx:401: characters 9-33
		$have_column = false;
		#coopy/HighlightPatch.hx:402: characters 19-29
		$_g = $this->payloadCol;
		#coopy/HighlightPatch.hx:402: characters 32-42
		$_g1 = $this->payloadTop;
		#coopy/HighlightPatch.hx:402: lines 402-434
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:402: characters 19-42
			$i = $_g++;
			#coopy/HighlightPatch.hx:403: characters 13-39
			$prev_name = ($this->header->data[$i] ?? null);
			#coopy/HighlightPatch.hx:404: characters 13-34
			$name = $prev_name;
			#coopy/HighlightPatch.hx:405: lines 405-407
			if (\array_key_exists($prev_name, $this->headerRename->data)) {
				#coopy/HighlightPatch.hx:406: characters 24-51
				$name = ($this->headerRename->data[$prev_name] ?? null);
			}
			#coopy/HighlightPatch.hx:408: characters 13-49
			$cact = ($this->modifier->data[$i] ?? null);
			#coopy/HighlightPatch.hx:409: characters 13-38
			if ($cact === "...") {
				#coopy/HighlightPatch.hx:409: characters 30-38
				continue;
			}
			#coopy/HighlightPatch.hx:410: characters 13-49
			if (($name === null) || ($name === "")) {
				#coopy/HighlightPatch.hx:410: characters 41-49
				continue;
			}
			#coopy/HighlightPatch.hx:411: characters 13-64
			$txt = $this->csv->parseCell($this->getStringNull($i));
			#coopy/HighlightPatch.hx:412: characters 13-33
			$updated = false;
			#coopy/HighlightPatch.hx:413: lines 413-416
			if ($this->rowInfo->updated) {
				#coopy/HighlightPatch.hx:414: characters 17-34
				$this->getPreString($txt);
				#coopy/HighlightPatch.hx:415: characters 17-43
				$updated = $this->cellInfo->updated;
			}
			#coopy/HighlightPatch.hx:417: lines 417-423
			if (($cact === "+++") && ($code !== "---")) {
				#coopy/HighlightPatch.hx:418: lines 418-422
				if (($txt !== null) && ($txt !== "")) {
					#coopy/HighlightPatch.hx:419: characters 21-73
					if ($rc->val === null) {
						#coopy/HighlightPatch.hx:419: characters 39-73
						$rc->val = new StringMap();
					}
					#coopy/HighlightPatch.hx:420: characters 21-41
					$rc->val->data[$name] = $txt;
					#coopy/HighlightPatch.hx:421: characters 21-39
					$have_column = true;
				}
			}
			#coopy/HighlightPatch.hx:424: lines 424-433
			if ($updated) {
				#coopy/HighlightPatch.hx:425: characters 17-65
				$this1 = $rc->cond;
				$value = $this->csv->parseCell($this->cellInfo->lvalue);
				$this1->data[$name] = $value;
				#coopy/HighlightPatch.hx:426: characters 17-64
				$this2 = $rc->val;
				$value1 = $this->csv->parseCell($this->cellInfo->rvalue);
				$this2->data[$name] = $value1;
			} else if ($code === "+++") {
				#coopy/HighlightPatch.hx:428: characters 17-54
				if ($cact !== "---") {
					#coopy/HighlightPatch.hx:428: characters 34-54
					$rc->val->data[$name] = $txt;
				}
			} else if (($cact !== "+++") && ($cact !== "---")) {
				#coopy/HighlightPatch.hx:431: characters 21-42
				$rc->cond->data[$name] = $txt;
			}
		}
		#coopy/HighlightPatch.hx:435: lines 435-438
		if ($rc->action === "+") {
			#coopy/HighlightPatch.hx:436: characters 13-37
			if (!$have_column) {
				#coopy/HighlightPatch.hx:436: characters 31-37
				return;
			}
			#coopy/HighlightPatch.hx:437: characters 13-29
			$rc->action = "->";
		}
		#coopy/HighlightPatch.hx:439: characters 9-27
		$this->meta->changeRow($rc);
	}

	/**
	 * @return void
	 */
	public function applyHeader () {
		#coopy/HighlightPatch.hx:320: characters 19-29
		$_g = $this->payloadCol;
		#coopy/HighlightPatch.hx:320: characters 32-42
		$_g1 = $this->payloadTop;
		#coopy/HighlightPatch.hx:320: lines 320-351
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:320: characters 19-42
			$i = $_g++;
			#coopy/HighlightPatch.hx:321: characters 13-46
			$name = $this->getString($i);
			#coopy/HighlightPatch.hx:322: lines 322-326
			if ($name === "...") {
				#coopy/HighlightPatch.hx:323: characters 17-38
				$this->modifier->data[$i] = "...";
				#coopy/HighlightPatch.hx:324: characters 17-42
				$this->haveDroppedColumns = true;
				#coopy/HighlightPatch.hx:325: characters 17-25
				continue;
			}
			#coopy/HighlightPatch.hx:327: characters 13-48
			$mod = ($this->modifier->data[$i] ?? null);
			#coopy/HighlightPatch.hx:328: characters 13-37
			$move = false;
			#coopy/HighlightPatch.hx:329: lines 329-334
			if ($mod !== null) {
				#coopy/HighlightPatch.hx:330: lines 330-333
				if (HxString::charCodeAt($mod, 0) === 58) {
					#coopy/HighlightPatch.hx:331: characters 21-32
					$move = true;
					#coopy/HighlightPatch.hx:332: characters 27-51
					$mod = \mb_substr($mod, 1, mb_strlen($mod));
				}
			}
			#coopy/HighlightPatch.hx:335: characters 13-31
			$this->header->data[$i] = $name;
			#coopy/HighlightPatch.hx:336: lines 336-344
			if ($mod !== null) {
				#coopy/HighlightPatch.hx:337: lines 337-343
				if (HxString::charCodeAt($mod, 0) === 40) {
					#coopy/HighlightPatch.hx:338: characters 21-64
					$prev_name = \mb_substr($mod, 1, mb_strlen($mod) - 2);
					#coopy/HighlightPatch.hx:339: characters 21-47
					$this->headerPre->data[$prev_name] = $i;
					#coopy/HighlightPatch.hx:340: characters 21-43
					$this->headerPost->data[$name] = $i;
					#coopy/HighlightPatch.hx:341: characters 21-53
					$this->headerRename->data[$prev_name] = $name;
					#coopy/HighlightPatch.hx:342: characters 21-29
					continue;
				}
			}
			#coopy/HighlightPatch.hx:345: characters 13-50
			if ($mod !== "+++") {
				#coopy/HighlightPatch.hx:345: characters 29-50
				$this->headerPre->data[$name] = $i;
			}
			#coopy/HighlightPatch.hx:346: characters 13-51
			if ($mod !== "---") {
				#coopy/HighlightPatch.hx:346: characters 29-51
				$this->headerPost->data[$name] = $i;
			}
			#coopy/HighlightPatch.hx:347: lines 347-350
			if ($move) {
				#coopy/HighlightPatch.hx:348: characters 17-73
				if ($this->headerMove === null) {
					#coopy/HighlightPatch.hx:348: characters 39-73
					$this->headerMove = new StringMap();
				}
				#coopy/HighlightPatch.hx:349: characters 17-39
				$this->headerMove->data[$name] = 1;
			}
		}
		#coopy/HighlightPatch.hx:352: lines 352-356
		if (!$this->useMetaForRowChanges()) {
			#coopy/HighlightPatch.hx:353: lines 353-355
			if ($this->source->get_height() === 0) {
				#coopy/HighlightPatch.hx:354: characters 17-35
				$this->applyAction("+++");
			}
		}
	}

	/**
	 * @return void
	 */
	public function applyMeta () {
		#coopy/HighlightPatch.hx:312: characters 19-29
		$_g = $this->payloadCol;
		#coopy/HighlightPatch.hx:312: characters 32-42
		$_g1 = $this->payloadTop;
		#coopy/HighlightPatch.hx:312: lines 312-316
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:312: characters 19-42
			$i = $_g++;
			#coopy/HighlightPatch.hx:313: characters 13-46
			$name = $this->getString($i);
			#coopy/HighlightPatch.hx:314: characters 13-37
			if ($name === "") {
				#coopy/HighlightPatch.hx:314: characters 29-37
				continue;
			}
			#coopy/HighlightPatch.hx:315: characters 13-33
			$this->modifier->data[$i] = $name;
		}
	}

	/**
	 * @param string $code
	 * 
	 * @return void
	 */
	public function applyMetaRow ($code) {
		#coopy/HighlightPatch.hx:216: characters 9-28
		$this->needSourceColumns();
		#coopy/HighlightPatch.hx:218: characters 9-37
		$codes = HxString::split($code, "@");
		#coopy/HighlightPatch.hx:219: characters 9-28
		$prop_name = "";
		#coopy/HighlightPatch.hx:220: lines 220-222
		if ($codes->length > 1) {
			#coopy/HighlightPatch.hx:221: characters 13-46
			$prop_name = ($codes->arr[$codes->length - 2] ?? null);
		}
		#coopy/HighlightPatch.hx:223: lines 223-225
		if ($codes->length > 0) {
			#coopy/HighlightPatch.hx:224: characters 13-41
			$code = ($codes->arr[$codes->length - 1] ?? null);
		}
		#coopy/HighlightPatch.hx:226: characters 9-81
		if ($this->prev_meta === null) {
			#coopy/HighlightPatch.hx:226: characters 30-81
			$this->prev_meta = new StringMap();
		}
		#coopy/HighlightPatch.hx:227: characters 9-81
		if ($this->next_meta === null) {
			#coopy/HighlightPatch.hx:227: characters 30-81
			$this->next_meta = new StringMap();
		}
		#coopy/HighlightPatch.hx:228: characters 19-29
		$_g = $this->payloadCol;
		#coopy/HighlightPatch.hx:228: characters 32-42
		$_g1 = $this->payloadTop;
		#coopy/HighlightPatch.hx:228: lines 228-244
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:228: characters 19-42
			$i = $_g++;
			#coopy/HighlightPatch.hx:229: characters 13-44
			$txt = $this->getDatum($i);
			#coopy/HighlightPatch.hx:230: characters 13-31
			$idx_patch = $i;
			#coopy/HighlightPatch.hx:231: characters 13-101
			$idx_src = (\array_key_exists($idx_patch, $this->patchInSourceCol->data) ? ($this->patchInSourceCol->data[$idx_patch] ?? null) : -1);
			#coopy/HighlightPatch.hx:232: characters 13-43
			$prev_name = null;
			#coopy/HighlightPatch.hx:233: characters 13-38
			$name = null;
			#coopy/HighlightPatch.hx:234: characters 13-67
			if ($idx_src !== -1) {
				#coopy/HighlightPatch.hx:234: characters 30-67
				$prev_name = $this->source->getCell($idx_src, 0);
			}
			#coopy/HighlightPatch.hx:235: characters 13-71
			if (\array_key_exists($idx_patch, $this->header->data)) {
				#coopy/HighlightPatch.hx:235: characters 50-71
				$name = ($this->header->data[$idx_patch] ?? null);
			}
			#coopy/HighlightPatch.hx:236: characters 13-69
			DiffRender::examineCell(0, 0, $this->view, $txt, "", $code, "", $this->cellInfo);
			#coopy/HighlightPatch.hx:237: lines 237-243
			if ($this->cellInfo->updated) {
				#coopy/HighlightPatch.hx:238: characters 17-75
				$this->setMetaProp($this->prev_meta, $prev_name, $prop_name, $this->cellInfo->lvalue);
				#coopy/HighlightPatch.hx:239: characters 17-70
				$this->setMetaProp($this->next_meta, $name, $prop_name, $this->cellInfo->rvalue);
			} else {
				#coopy/HighlightPatch.hx:241: characters 17-74
				$this->setMetaProp($this->prev_meta, $prev_name, $prop_name, $this->cellInfo->value);
				#coopy/HighlightPatch.hx:242: characters 17-69
				$this->setMetaProp($this->next_meta, $name, $prop_name, $this->cellInfo->value);
			}
		}
	}

	/**
	 * @param int $r
	 * 
	 * @return void
	 */
	public function applyRow ($r) {
		#coopy/HighlightPatch.hx:248: characters 9-23
		$this->currentRow = $r;
		#coopy/HighlightPatch.hx:249: characters 9-40
		$code = ($this->actions->arr[$r] ?? null);
		#coopy/HighlightPatch.hx:250: characters 9-26
		$done = false;
		#coopy/HighlightPatch.hx:251: lines 251-278
		if (($r === 0) && ($this->rcOffset > 0)) {
			#coopy/HighlightPatch.hx:253: characters 13-24
			$done = true;
		} else if ($code === "@@") {
			#coopy/HighlightPatch.hx:255: characters 13-40
			$this->preambleRow = $this->headerRow = $r;
			#coopy/HighlightPatch.hx:256: characters 13-26
			$this->applyHeader();
			#coopy/HighlightPatch.hx:257: characters 13-30
			$this->applyAction("@@");
			#coopy/HighlightPatch.hx:258: characters 13-24
			$done = true;
		} else if ($code === "!") {
			#coopy/HighlightPatch.hx:260: characters 13-40
			$this->preambleRow = $this->headerRow = $r;
			#coopy/HighlightPatch.hx:261: characters 13-24
			$this->applyMeta();
			#coopy/HighlightPatch.hx:262: characters 13-24
			$done = true;
		} else if (HxString::indexOf($code, "@") === 0) {
			#coopy/HighlightPatch.hx:264: characters 13-94
			$this->flags->addWarning("cannot usefully apply diffs with metadata yet: '" . ($code??'null') . "'");
			#coopy/HighlightPatch.hx:265: characters 13-28
			$this->preambleRow = $r;
			#coopy/HighlightPatch.hx:266: characters 13-31
			$this->applyMetaRow($code);
			#coopy/HighlightPatch.hx:267: lines 267-275
			if ($this->process_meta) {
				#coopy/HighlightPatch.hx:268: characters 17-45
				$codes = HxString::split($code, "@");
				#coopy/HighlightPatch.hx:269: lines 269-271
				if ($codes->length > 0) {
					#coopy/HighlightPatch.hx:270: characters 21-49
					$code = ($codes->arr[$codes->length - 1] ?? null);
				}
			} else {
				#coopy/HighlightPatch.hx:273: characters 17-35
				$this->meta_change = true;
				#coopy/HighlightPatch.hx:274: characters 17-28
				$done = true;
			}
			#coopy/HighlightPatch.hx:276: characters 13-31
			$this->meta_change = true;
			#coopy/HighlightPatch.hx:277: characters 13-24
			$done = true;
		}
		#coopy/HighlightPatch.hx:279: characters 9-33
		if ($this->process_meta) {
			#coopy/HighlightPatch.hx:279: characters 27-33
			return;
		}
		#coopy/HighlightPatch.hx:280: lines 280-293
		if (!$done) {
			#coopy/HighlightPatch.hx:281: characters 13-28
			$this->finishColumns();
			#coopy/HighlightPatch.hx:282: lines 282-292
			if ($code === "+++") {
				#coopy/HighlightPatch.hx:283: characters 17-34
				$this->applyAction($code);
			} else if ($code === "---") {
				#coopy/HighlightPatch.hx:285: characters 17-34
				$this->applyAction($code);
			} else if (($code === "+") || ($code === ":")) {
				#coopy/HighlightPatch.hx:287: characters 17-34
				$this->applyAction($code);
			} else if (HxString::indexOf($code, "->") >= 0) {
				#coopy/HighlightPatch.hx:289: characters 17-34
				$this->applyAction("->");
			} else {
				#coopy/HighlightPatch.hx:291: characters 17-35
				$this->lastSourceRow = -1;
			}
		}
	}

	/**
	 * @return void
	 */
	public function checkAct () {
		#coopy/HighlightPatch.hx:487: characters 9-48
		$act = $this->getString($this->rcOffset);
		#coopy/HighlightPatch.hx:489: lines 489-491
		if ($this->rowInfo->value !== $act) {
			#coopy/HighlightPatch.hx:490: characters 13-67
			DiffRender::examineCell(0, 0, $this->view, $act, "", $act, "", $this->rowInfo);
		}
	}

	/**
	 * @param HighlightPatchUnit[]|\Array_hx $mods
	 * @param int[]|\Array_hx $permutation
	 * @param int[]|\Array_hx $permutationRev
	 * @param int $dim
	 * 
	 * @return void
	 */
	public function computeOrdering ($mods, $permutation, $permutationRev, $dim) {
		#coopy/HighlightPatch.hx:606: characters 9-57
		$to_unit = new IntMap();
		#coopy/HighlightPatch.hx:607: characters 9-59
		$from_unit = new IntMap();
		#coopy/HighlightPatch.hx:608: characters 9-64
		$meta_from_unit = new IntMap();
		#coopy/HighlightPatch.hx:609: characters 9-26
		$ct = 0;
		#coopy/HighlightPatch.hx:610: lines 610-628
		$_g = 0;
		while ($_g < $mods->length) {
			#coopy/HighlightPatch.hx:610: characters 14-17
			$mod = ($mods->arr[$_g] ?? null);
			#coopy/HighlightPatch.hx:610: lines 610-628
			++$_g;
			#coopy/HighlightPatch.hx:611: characters 13-43
			if ($mod->add || $mod->rem) {
				#coopy/HighlightPatch.hx:611: characters 35-43
				continue;
			}
			#coopy/HighlightPatch.hx:612: characters 13-42
			if ($mod->sourceRow < 0) {
				#coopy/HighlightPatch.hx:612: characters 34-42
				continue;
			}
			#coopy/HighlightPatch.hx:613: lines 613-619
			if ($mod->sourcePrevRow >= 0) {
				#coopy/HighlightPatch.hx:614: characters 17-59
				$v = $mod->sourceRow;
				$to_unit->data[$mod->sourcePrevRow] = $v;
				#coopy/HighlightPatch.hx:615: characters 17-61
				$v1 = $mod->sourcePrevRow;
				$from_unit->data[$mod->sourceRow] = $v1;
				#coopy/HighlightPatch.hx:616: lines 616-618
				if (($mod->sourcePrevRow + 1) !== $mod->sourceRow) {
					#coopy/HighlightPatch.hx:617: characters 21-25
					++$ct;
				}
			}
			#coopy/HighlightPatch.hx:620: lines 620-627
			if ($mod->sourceNextRow >= 0) {
				#coopy/HighlightPatch.hx:622: characters 17-59
				$v2 = $mod->sourceNextRow;
				$to_unit->data[$mod->sourceRow] = $v2;
				#coopy/HighlightPatch.hx:623: characters 17-61
				$v3 = $mod->sourceRow;
				$from_unit->data[$mod->sourceNextRow] = $v3;
				#coopy/HighlightPatch.hx:624: lines 624-626
				if (($mod->sourceRow + 1) !== $mod->sourceNextRow) {
					#coopy/HighlightPatch.hx:625: characters 21-25
					++$ct;
				}
			}
		}
		#coopy/HighlightPatch.hx:630: lines 630-671
		if ($ct > 0) {
			#coopy/HighlightPatch.hx:631: characters 13-43
			$cursor = null;
			#coopy/HighlightPatch.hx:632: characters 13-44
			$logical = null;
			#coopy/HighlightPatch.hx:633: characters 13-42
			$starts = new \Array_hx();
			#coopy/HighlightPatch.hx:634: characters 23-27
			$_g = 0;
			#coopy/HighlightPatch.hx:634: characters 27-30
			$_g1 = $dim;
			#coopy/HighlightPatch.hx:634: lines 634-641
			while ($_g < $_g1) {
				#coopy/HighlightPatch.hx:634: characters 23-30
				$i = $_g++;
				#coopy/HighlightPatch.hx:635: characters 17-50
				$u = ($from_unit->data[$i] ?? null);
				#coopy/HighlightPatch.hx:636: lines 636-640
				if ($u !== null) {
					#coopy/HighlightPatch.hx:637: characters 21-42
					$meta_from_unit->data[$u] = $i;
				} else {
					#coopy/HighlightPatch.hx:639: characters 21-35
					$starts->arr[$starts->length++] = $i;
				}
			}
			#coopy/HighlightPatch.hx:642: characters 13-58
			$used = new IntMap();
			#coopy/HighlightPatch.hx:643: characters 13-31
			$len = 0;
			#coopy/HighlightPatch.hx:644: characters 23-27
			$_g = 0;
			#coopy/HighlightPatch.hx:644: characters 27-30
			$_g1 = $dim;
			#coopy/HighlightPatch.hx:644: lines 644-664
			while ($_g < $_g1) {
				#coopy/HighlightPatch.hx:644: characters 23-30
				$i = $_g++;
				#coopy/HighlightPatch.hx:645: lines 645-649
				if (($logical !== null) && \array_key_exists($logical, $meta_from_unit->data)) {
					#coopy/HighlightPatch.hx:646: characters 21-53
					$cursor = ($meta_from_unit->data[$logical] ?? null);
				} else {
					#coopy/HighlightPatch.hx:648: characters 21-34
					$cursor = null;
				}
				#coopy/HighlightPatch.hx:650: lines 650-654
				if ($cursor === null) {
					#coopy/HighlightPatch.hx:651: characters 35-49
					if ($starts->length > 0) {
						$starts->length--;
					}
					#coopy/HighlightPatch.hx:651: characters 21-50
					$v = \array_shift($starts->arr);
					#coopy/HighlightPatch.hx:652: characters 21-31
					$cursor = $v;
					#coopy/HighlightPatch.hx:653: characters 21-32
					$logical = $v;
				}
				#coopy/HighlightPatch.hx:655: lines 655-657
				if ($cursor === null) {
					#coopy/HighlightPatch.hx:656: characters 21-31
					$cursor = 0;
				}
				#coopy/HighlightPatch.hx:658: lines 658-660
				while (\array_key_exists($cursor, $used->data)) {
					#coopy/HighlightPatch.hx:659: characters 21-48
					$cursor = ($cursor + 1) % $dim;
				}
				#coopy/HighlightPatch.hx:661: characters 17-33
				$logical = $cursor;
				#coopy/HighlightPatch.hx:662: characters 17-44
				$permutationRev->arr[$permutationRev->length++] = $cursor;
				#coopy/HighlightPatch.hx:663: characters 17-33
				$used->data[$cursor] = 1;
			}
			#coopy/HighlightPatch.hx:665: characters 23-27
			$_g = 0;
			#coopy/HighlightPatch.hx:665: characters 27-48
			$_g1 = $permutationRev->length;
			#coopy/HighlightPatch.hx:665: lines 665-667
			while ($_g < $_g1) {
				#coopy/HighlightPatch.hx:665: characters 23-48
				$i = $_g++;
				#coopy/HighlightPatch.hx:666: characters 17-36
				$permutation->offsetSet($i, -1);
			}
			#coopy/HighlightPatch.hx:668: characters 23-27
			$_g = 0;
			#coopy/HighlightPatch.hx:668: characters 27-45
			$_g1 = $permutation->length;
			#coopy/HighlightPatch.hx:668: lines 668-670
			while ($_g < $_g1) {
				#coopy/HighlightPatch.hx:668: characters 23-45
				$i = $_g++;
				#coopy/HighlightPatch.hx:669: characters 17-51
				$permutation->offsetSet(($permutationRev->arr[$i] ?? null), $i);
			}
		}
	}

	/**
	 * @return void
	 */
	public function fillInNewColumns () {
		#coopy/HighlightPatch.hx:681: lines 681-699
		$_g = 0;
		$_g1 = $this->cmods;
		while ($_g < $_g1->length) {
			#coopy/HighlightPatch.hx:681: characters 14-18
			$cmod = ($_g1->arr[$_g] ?? null);
			#coopy/HighlightPatch.hx:681: lines 681-699
			++$_g;
			#coopy/HighlightPatch.hx:682: lines 682-698
			if (!$cmod->rem) {
				#coopy/HighlightPatch.hx:683: lines 683-697
				if ($cmod->add) {
					#coopy/HighlightPatch.hx:684: lines 684-692
					$_g2 = 0;
					$_g3 = $this->mods;
					while ($_g2 < $_g3->length) {
						#coopy/HighlightPatch.hx:684: characters 26-29
						$mod = ($_g3->arr[$_g2] ?? null);
						#coopy/HighlightPatch.hx:684: lines 684-692
						++$_g2;
						#coopy/HighlightPatch.hx:685: lines 685-691
						if (($mod->patchRow !== -1) && ($mod->destRow !== -1)) {
							#coopy/HighlightPatch.hx:686: lines 686-687
							$d = $this->patch->getCell($cmod->patchRow, $mod->patchRow);
							#coopy/HighlightPatch.hx:688: lines 688-690
							$this->source->setCell($cmod->destRow, $mod->destRow, $d);
						}
					}
					#coopy/HighlightPatch.hx:693: characters 21-66
					$hdr = ($this->header->data[$cmod->patchRow] ?? null);
					#coopy/HighlightPatch.hx:694: lines 694-696
					$this->source->setCell($cmod->destRow, 0, $this->view->toDatum($hdr));
				}
			}
		}
	}

	/**
	 * @return void
	 */
	public function finishColumns () {
		#coopy/HighlightPatch.hx:788: characters 9-37
		if ($this->finished_columns) {
			#coopy/HighlightPatch.hx:788: characters 31-37
			return;
		}
		#coopy/HighlightPatch.hx:789: characters 9-32
		$this->finished_columns = true;
		#coopy/HighlightPatch.hx:790: characters 9-28
		$this->needSourceColumns();
		#coopy/HighlightPatch.hx:791: characters 19-29
		$_g = $this->payloadCol;
		#coopy/HighlightPatch.hx:791: characters 32-42
		$_g1 = $this->payloadTop;
		#coopy/HighlightPatch.hx:791: lines 791-828
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:791: characters 19-42
			$i = $_g++;
			#coopy/HighlightPatch.hx:792: characters 13-48
			$act = ($this->modifier->data[$i] ?? null);
			#coopy/HighlightPatch.hx:793: characters 13-46
			$hdr = ($this->header->data[$i] ?? null);
			#coopy/HighlightPatch.hx:794: characters 13-36
			if ($act === null) {
				#coopy/HighlightPatch.hx:794: characters 28-36
				$act = "";
			}
			#coopy/HighlightPatch.hx:795: lines 795-827
			if ($act === "---") {
				#coopy/HighlightPatch.hx:796: characters 17-35
				$at = -1;
				#coopy/HighlightPatch.hx:797: characters 17-77
				if (\array_key_exists($i, $this->patchInSourceCol->data)) {
					#coopy/HighlightPatch.hx:797: characters 54-77
					$at = ($this->patchInSourceCol->data[$i] ?? null);
				}
				#coopy/HighlightPatch.hx:798: characters 17-73
				$mod = new HighlightPatchUnit();
				#coopy/HighlightPatch.hx:799: characters 17-31
				$mod->code = $act;
				#coopy/HighlightPatch.hx:800: characters 17-31
				$mod->rem = true;
				#coopy/HighlightPatch.hx:801: characters 17-35
				$mod->sourceRow = $at;
				#coopy/HighlightPatch.hx:802: characters 17-33
				$mod->patchRow = $i;
				#coopy/HighlightPatch.hx:803: characters 17-32
				$_this = $this->cmods;
				$_this->arr[$_this->length++] = $mod;
			} else if ($act === "+++") {
				#coopy/HighlightPatch.hx:805: characters 17-73
				$mod1 = new HighlightPatchUnit();
				#coopy/HighlightPatch.hx:806: characters 17-31
				$mod1->code = $act;
				#coopy/HighlightPatch.hx:807: characters 17-31
				$mod1->add = true;
				#coopy/HighlightPatch.hx:808: characters 17-37
				$prev = -1;
				#coopy/HighlightPatch.hx:809: characters 17-41
				$cont = false;
				#coopy/HighlightPatch.hx:810: characters 17-35
				$mod1->sourceRow = -1;
				#coopy/HighlightPatch.hx:811: lines 811-813
				if ($this->cmods->length > 0) {
					#coopy/HighlightPatch.hx:812: characters 21-68
					$mod1->sourceRow = ($this->cmods->arr[$this->cmods->length - 1] ?? null)->sourceRow;
				}
				#coopy/HighlightPatch.hx:814: lines 814-816
				if ($mod1->sourceRow !== -1) {
					#coopy/HighlightPatch.hx:815: characters 21-44
					$mod1->sourceRowOffset = 1;
				}
				#coopy/HighlightPatch.hx:817: characters 17-33
				$mod1->patchRow = $i;
				#coopy/HighlightPatch.hx:818: characters 17-32
				$_this1 = $this->cmods;
				$_this1->arr[$_this1->length++] = $mod1;
			} else if ($act !== "...") {
				#coopy/HighlightPatch.hx:820: characters 17-35
				$at1 = -1;
				#coopy/HighlightPatch.hx:821: characters 17-77
				if (\array_key_exists($i, $this->patchInSourceCol->data)) {
					#coopy/HighlightPatch.hx:821: characters 54-77
					$at1 = ($this->patchInSourceCol->data[$i] ?? null);
				}
				#coopy/HighlightPatch.hx:822: characters 17-73
				$mod2 = new HighlightPatchUnit();
				#coopy/HighlightPatch.hx:823: characters 17-31
				$mod2->code = $act;
				#coopy/HighlightPatch.hx:824: characters 17-33
				$mod2->patchRow = $i;
				#coopy/HighlightPatch.hx:825: characters 17-35
				$mod2->sourceRow = $at1;
				#coopy/HighlightPatch.hx:826: characters 17-32
				$_this2 = $this->cmods;
				$_this2->arr[$_this2->length++] = $mod2;
			}
		}
		#coopy/HighlightPatch.hx:829: characters 9-27
		$at = -1;
		#coopy/HighlightPatch.hx:830: characters 9-28
		$rat = -1;
		#coopy/HighlightPatch.hx:831: characters 19-23
		$_g = 0;
		#coopy/HighlightPatch.hx:831: characters 23-37
		$_g1 = $this->cmods->length - 1;
		#coopy/HighlightPatch.hx:831: lines 831-843
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:831: characters 19-37
			$i = $_g++;
			#coopy/HighlightPatch.hx:832: characters 13-48
			$icode = ($this->cmods->arr[$i] ?? null)->code;
			#coopy/HighlightPatch.hx:833: lines 833-835
			if (($icode !== "+++") && ($icode !== "---")) {
				#coopy/HighlightPatch.hx:834: characters 17-40
				$at = ($this->cmods->arr[$i] ?? null)->sourceRow;
			}
			#coopy/HighlightPatch.hx:836: characters 13-42
			$this->cmods[$i + 1]->sourcePrevRow = $at;
			#coopy/HighlightPatch.hx:837: characters 13-43
			$j = $this->cmods->length - 1 - $i;
			#coopy/HighlightPatch.hx:838: characters 13-48
			$jcode = ($this->cmods->arr[$j] ?? null)->code;
			#coopy/HighlightPatch.hx:839: lines 839-841
			if (($jcode !== "+++") && ($jcode !== "---")) {
				#coopy/HighlightPatch.hx:840: characters 17-41
				$rat = ($this->cmods->arr[$j] ?? null)->sourceRow;
			}
			#coopy/HighlightPatch.hx:842: characters 13-43
			$this->cmods[$j - 1]->sourceNextRow = $rat;
		}
		#coopy/HighlightPatch.hx:844: characters 9-50
		$fate = new \Array_hx();
		#coopy/HighlightPatch.hx:845: characters 9-25
		$this->permuteColumns();
		#coopy/HighlightPatch.hx:846: lines 846-857
		if ($this->headerMove !== null) {
			#coopy/HighlightPatch.hx:847: lines 847-856
			if ($this->colPermutation->length > 0) {
				#coopy/HighlightPatch.hx:848: lines 848-852
				$_g = 0;
				$_g1 = $this->cmods;
				while ($_g < $_g1->length) {
					#coopy/HighlightPatch.hx:848: characters 22-25
					$mod = ($_g1->arr[$_g] ?? null);
					#coopy/HighlightPatch.hx:848: lines 848-852
					++$_g;
					#coopy/HighlightPatch.hx:849: lines 849-851
					if ($mod->sourceRow >= 0) {
						#coopy/HighlightPatch.hx:850: characters 25-70
						$mod->sourceRow = ($this->colPermutation->arr[$mod->sourceRow] ?? null);
					}
				}
				#coopy/HighlightPatch.hx:853: lines 853-855
				if (!$this->useMetaForColumnChanges()) {
					#coopy/HighlightPatch.hx:854: characters 21-87
					$this->source->insertOrDeleteColumns($this->colPermutation, $this->colPermutation->length);
				}
			}
		}
		#coopy/HighlightPatch.hx:859: characters 9-62
		$len = $this->processMods($this->cmods, $fate, $this->source->get_width());
		#coopy/HighlightPatch.hx:861: lines 861-864
		if (!$this->useMetaForColumnChanges()) {
			#coopy/HighlightPatch.hx:862: characters 13-51
			$this->source->insertOrDeleteColumns($fate, $len);
			#coopy/HighlightPatch.hx:863: characters 13-19
			return;
		}
		#coopy/HighlightPatch.hx:866: characters 9-29
		$changed = false;
		#coopy/HighlightPatch.hx:867: lines 867-872
		$_g = 0;
		$_g1 = $this->cmods;
		while ($_g < $_g1->length) {
			#coopy/HighlightPatch.hx:867: characters 14-17
			$mod = ($_g1->arr[$_g] ?? null);
			#coopy/HighlightPatch.hx:867: lines 867-872
			++$_g;
			#coopy/HighlightPatch.hx:868: lines 868-871
			if ($mod->code !== "") {
				#coopy/HighlightPatch.hx:869: characters 17-31
				$changed = true;
				#coopy/HighlightPatch.hx:870: characters 17-22
				break;
			}
		}
		#coopy/HighlightPatch.hx:873: characters 9-29
		if (!$changed) {
			#coopy/HighlightPatch.hx:873: characters 23-29
			return;
		}
		#coopy/HighlightPatch.hx:875: characters 9-49
		$columns = new \Array_hx();
		#coopy/HighlightPatch.hx:876: characters 9-41
		$target = new IntMap();
		#coopy/HighlightPatch.hx:877: lines 877-879
		$inc = function ($x) {
			#coopy/HighlightPatch.hx:878: characters 20-33
			if ($x < 0) {
				#coopy/HighlightPatch.hx:878: characters 26-27
				return $x;
			} else {
				#coopy/HighlightPatch.hx:878: characters 28-33
				return $x + 1;
			}
		};
		#coopy/HighlightPatch.hx:880: characters 19-23
		$_g = 0;
		#coopy/HighlightPatch.hx:880: characters 23-34
		$_g1 = $fate->length;
		#coopy/HighlightPatch.hx:880: lines 880-882
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:880: characters 19-34
			$i = $_g++;
			#coopy/HighlightPatch.hx:881: characters 13-39
			$value = $inc(($fate->arr[$i] ?? null));
			$target->data[$i] = $value;
		}
		#coopy/HighlightPatch.hx:883: characters 9-28
		$this->needSourceColumns();
		#coopy/HighlightPatch.hx:884: characters 9-26
		$this->needDestColumns();
		#coopy/HighlightPatch.hx:885: characters 27-31
		$_g = 1;
		#coopy/HighlightPatch.hx:885: characters 31-42
		$_g1 = $this->patch->get_width();
		#coopy/HighlightPatch.hx:885: lines 885-902
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:885: characters 27-42
			$idx_patch = $_g++;
			#coopy/HighlightPatch.hx:886: characters 13-45
			$change = new ColumnChange();
			#coopy/HighlightPatch.hx:887: characters 13-101
			$idx_src = (\array_key_exists($idx_patch, $this->patchInSourceCol->data) ? ($this->patchInSourceCol->data[$idx_patch] ?? null) : -1);
			#coopy/HighlightPatch.hx:888: characters 13-43
			$prev_name = null;
			#coopy/HighlightPatch.hx:889: characters 13-38
			$name = null;
			#coopy/HighlightPatch.hx:890: characters 13-67
			if ($idx_src !== -1) {
				#coopy/HighlightPatch.hx:890: characters 30-67
				$prev_name = $this->source->getCell($idx_src, 0);
			}
			#coopy/HighlightPatch.hx:891: lines 891-893
			if (($this->modifier->data[$idx_patch] ?? null) !== "---") {
				#coopy/HighlightPatch.hx:892: characters 17-75
				if (\array_key_exists($idx_patch, $this->header->data)) {
					#coopy/HighlightPatch.hx:892: characters 54-75
					$name = ($this->header->data[$idx_patch] ?? null);
				}
			}
			#coopy/HighlightPatch.hx:894: characters 13-40
			$change->prevName = $prev_name;
			#coopy/HighlightPatch.hx:895: characters 13-31
			$change->name = $name;
			#coopy/HighlightPatch.hx:896: lines 896-900
			if ($this->next_meta !== null) {
				#coopy/HighlightPatch.hx:897: lines 897-899
				if (\array_key_exists($name, $this->next_meta->data)) {
					#coopy/HighlightPatch.hx:898: characters 21-55
					$change->props = ($this->next_meta->data[$name] ?? null);
				}
			}
			#coopy/HighlightPatch.hx:901: characters 13-33
			$columns->arr[$columns->length++] = $change;
		}
		#coopy/HighlightPatch.hx:903: characters 9-35
		$this->meta->alterColumns($columns);
	}

	/**
	 * @return void
	 */
	public function finishRows () {
		#coopy/HighlightPatch.hx:703: lines 703-706
		if ($this->useMetaForRowChanges()) {
			#coopy/HighlightPatch.hx:705: characters 13-19
			return;
		}
		#coopy/HighlightPatch.hx:708: lines 708-716
		if ($this->source->get_width() === 0) {
			#coopy/HighlightPatch.hx:710: lines 710-714
			if ($this->source->get_height() !== 0) {
				#coopy/HighlightPatch.hx:713: characters 17-35
				$this->source->resize(0, 0);
			}
			#coopy/HighlightPatch.hx:715: characters 13-19
			return;
		}
		#coopy/HighlightPatch.hx:718: characters 9-50
		$fate = new \Array_hx();
		#coopy/HighlightPatch.hx:719: characters 9-22
		$this->permuteRows();
		#coopy/HighlightPatch.hx:720: lines 720-726
		if ($this->rowPermutation->length > 0) {
			#coopy/HighlightPatch.hx:721: lines 721-725
			$_g = 0;
			$_g1 = $this->mods;
			while ($_g < $_g1->length) {
				#coopy/HighlightPatch.hx:721: characters 18-21
				$mod = ($_g1->arr[$_g] ?? null);
				#coopy/HighlightPatch.hx:721: lines 721-725
				++$_g;
				#coopy/HighlightPatch.hx:722: lines 722-724
				if ($mod->sourceRow >= 0) {
					#coopy/HighlightPatch.hx:723: characters 21-34
					$mod->sourceRow = ($this->rowPermutation->arr[$mod->sourceRow] ?? null);
				}
			}
		}
		#coopy/HighlightPatch.hx:728: lines 728-730
		if ($this->rowPermutation->length > 0) {
			#coopy/HighlightPatch.hx:729: characters 13-76
			$this->source->insertOrDeleteRows($this->rowPermutation, $this->rowPermutation->length);
		}
		#coopy/HighlightPatch.hx:732: characters 9-62
		$len = $this->processMods($this->mods, $fate, $this->source->get_height());
		#coopy/HighlightPatch.hx:733: characters 9-44
		$this->source->insertOrDeleteRows($fate, $len);
		#coopy/HighlightPatch.hx:735: characters 9-26
		$this->needDestColumns();
		#coopy/HighlightPatch.hx:736: lines 736-768
		$_g = 0;
		$_g1 = $this->mods;
		while ($_g < $_g1->length) {
			#coopy/HighlightPatch.hx:736: characters 14-17
			$mod = ($_g1->arr[$_g] ?? null);
			#coopy/HighlightPatch.hx:736: lines 736-768
			++$_g;
			#coopy/HighlightPatch.hx:737: lines 737-767
			if (!$mod->rem) {
				#coopy/HighlightPatch.hx:738: lines 738-766
				if ($mod->add) {
					#coopy/HighlightPatch.hx:739: characters 31-41
					$data = \array_values($this->headerPost->data);
					$c_current = 0;
					$c_length = \count($data);
					$c_data = $data;
					while ($c_current < $c_length) {
						#coopy/HighlightPatch.hx:739: lines 739-746
						$c = $c_data[$c_current++];
						#coopy/HighlightPatch.hx:740: characters 25-72
						$offset = ($this->patchInDestCol->data[$c] ?? null);
						#coopy/HighlightPatch.hx:741: lines 741-745
						if (($offset !== null) && ($offset >= 0)) {
							#coopy/HighlightPatch.hx:742: lines 742-744
							$this->source->setCell($offset, $mod->destRow, $this->patch->getCell($c, $mod->patchRow));
						}
					}
				} else if ($mod->update) {
					#coopy/HighlightPatch.hx:749: characters 21-31
					$this->currentRow = $mod->patchRow;
					#coopy/HighlightPatch.hx:750: characters 21-31
					$this->checkAct();
					#coopy/HighlightPatch.hx:751: characters 21-51
					if (!$this->rowInfo->updated) {
						#coopy/HighlightPatch.hx:751: characters 43-51
						continue;
					}
					#coopy/HighlightPatch.hx:752: characters 31-40
					$data1 = \array_values($this->headerPre->data);
					$c_current1 = 0;
					$c_length1 = \count($data1);
					$c_data1 = $data1;
					while ($c_current1 < $c_length1) {
						#coopy/HighlightPatch.hx:752: lines 752-765
						$c1 = $c_data1[$c_current1++];
						#coopy/HighlightPatch.hx:754: characters 25-89
						$txt = $this->view->toString($this->patch->getCell($c1, $mod->patchRow));
						#coopy/HighlightPatch.hx:755: characters 25-90
						DiffRender::examineCell(0, 0, $this->view, $txt, "", $this->rowInfo->value, "", $this->cellInfo);
						#coopy/HighlightPatch.hx:756: characters 25-56
						if (!$this->cellInfo->updated) {
							#coopy/HighlightPatch.hx:756: characters 48-56
							continue;
						}
						#coopy/HighlightPatch.hx:757: characters 25-58
						if ($this->cellInfo->conflicted) {
							#coopy/HighlightPatch.hx:757: characters 50-58
							continue;
						}
						#coopy/HighlightPatch.hx:758: characters 25-88
						$d = $this->view->toDatum($this->csv->parseCell($this->cellInfo->rvalue));
						#coopy/HighlightPatch.hx:759: characters 25-60
						$offset1 = ($this->patchInDestCol->data[$c1] ?? null);
						#coopy/HighlightPatch.hx:760: lines 760-764
						if (($offset1 !== null) && ($offset1 >= 0)) {
							#coopy/HighlightPatch.hx:761: lines 761-763
							$this->source->setCell(($this->patchInDestCol->data[$c1] ?? null), $mod->destRow, $d);
						}
					}
				}
			}
		}
		#coopy/HighlightPatch.hx:770: characters 9-27
		$this->fillInNewColumns();
		#coopy/HighlightPatch.hx:771: characters 19-23
		$_g = 0;
		#coopy/HighlightPatch.hx:771: characters 23-35
		$_g1 = $this->source->get_width();
		#coopy/HighlightPatch.hx:771: lines 771-776
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:771: characters 19-35
			$i = $_g++;
			#coopy/HighlightPatch.hx:772: characters 13-68
			$name = $this->view->toString($this->source->getCell($i, 0));
			#coopy/HighlightPatch.hx:773: characters 13-61
			$next_name = ($this->headerRename->data[$name] ?? null);
			#coopy/HighlightPatch.hx:774: characters 13-42
			if ($next_name === null) {
				#coopy/HighlightPatch.hx:774: characters 34-42
				continue;
			}
			#coopy/HighlightPatch.hx:775: characters 13-56
			$this->source->setCell($i, 0, $this->view->toDatum($next_name));
		}
	}

	/**
	 * @param int $c
	 * 
	 * @return mixed
	 */
	public function getDatum ($c) {
		#coopy/HighlightPatch.hx:297: characters 9-43
		return $this->patch->getCell($c, $this->currentRow);
	}

	/**
	 * @param string $txt
	 * 
	 * @return string
	 */
	public function getPreString ($txt) {
		#coopy/HighlightPatch.hx:495: characters 9-19
		$this->checkAct();
		#coopy/HighlightPatch.hx:497: characters 9-41
		if (!$this->rowInfo->updated) {
			#coopy/HighlightPatch.hx:497: characters 31-41
			return $txt;
		}
		#coopy/HighlightPatch.hx:498: characters 9-74
		DiffRender::examineCell(0, 0, $this->view, $txt, "", $this->rowInfo->value, "", $this->cellInfo);
		#coopy/HighlightPatch.hx:499: characters 9-42
		if (!$this->cellInfo->updated) {
			#coopy/HighlightPatch.hx:499: characters 32-42
			return $txt;
		}
		#coopy/HighlightPatch.hx:500: characters 9-31
		return $this->cellInfo->lvalue;
	}

	/**
	 *
	 * Get the content in a given column of the patch on the active row.
	 * This is present for generating keys internally, you don't need it.
	 *
	 * @param c the column to look in
	 * @return the content of column `c` on the active row
	 *
	 * 
	 * @param int $c
	 * 
	 * @return string
	 */
	public function getRowString ($c) {
		#coopy/HighlightPatch.hx:513: characters 9-54
		$at = ($this->sourceInPatchCol->data[$c] ?? null);
		#coopy/HighlightPatch.hx:514: characters 9-43
		if ($at === null) {
			#coopy/HighlightPatch.hx:514: characters 25-43
			return "NOT_FOUND";
		}
		#coopy/HighlightPatch.hx:515: characters 9-43
		return $this->getPreString($this->getString($at));
	}

	/**
	 * @param int $c
	 * 
	 * @return string
	 */
	public function getString ($c) {
		#coopy/HighlightPatch.hx:301: characters 9-42
		return $this->view->toString($this->getDatum($c));
	}

	/**
	 * @param int $c
	 * 
	 * @return string
	 */
	public function getStringNull ($c) {
		#coopy/HighlightPatch.hx:305: characters 9-29
		$d = $this->getDatum($c);
		#coopy/HighlightPatch.hx:306: characters 9-33
		if ($d === null) {
			#coopy/HighlightPatch.hx:306: characters 22-33
			return null;
		}
		#coopy/HighlightPatch.hx:307: characters 9-32
		return $this->view->toString($d);
	}

	/**
	 * @return bool
	 */
	public function isPreamble () {
		#coopy/HighlightPatch.hx:519: characters 9-41
		return $this->currentRow <= $this->preambleRow;
	}

	/**
	 * @param int $del
	 * 
	 * @return int
	 */
	public function lookUp ($del = 0) {
		#coopy/HighlightPatch.hx:359: lines 359-390
		if ($del === null) {
			$del = 0;
		}
		#coopy/HighlightPatch.hx:360: lines 360-362
		if (\array_key_exists($this->currentRow + $del, $this->patchInSourceRow->data)) {
			#coopy/HighlightPatch.hx:361: characters 20-56
			return ($this->patchInSourceRow->data[$this->currentRow + $del] ?? null);
		}
		#coopy/HighlightPatch.hx:363: characters 9-31
		$result = -1;
		#coopy/HighlightPatch.hx:364: characters 9-26
		$this->currentRow += $del;
		#coopy/HighlightPatch.hx:365: lines 365-386
		if (($this->currentRow >= 0) && ($this->currentRow < $this->patch->get_height())) {
			#coopy/HighlightPatch.hx:366: lines 366-385
			$_g = 0;
			$_g1 = $this->indexes;
			while ($_g < $_g1->length) {
				#coopy/HighlightPatch.hx:366: characters 18-21
				$idx = ($_g1->arr[$_g] ?? null);
				#coopy/HighlightPatch.hx:366: lines 366-385
				++$_g;
				#coopy/HighlightPatch.hx:367: characters 17-67
				$match = $idx->queryByContent($this);
				#coopy/HighlightPatch.hx:368: characters 17-48
				if ($match->spot_a === 0) {
					#coopy/HighlightPatch.hx:368: characters 40-48
					continue;
				}
				#coopy/HighlightPatch.hx:369: lines 369-372
				if ($match->spot_a === 1) {
					#coopy/HighlightPatch.hx:370: characters 21-50
					$result = ($match->item_a->lst->arr[0] ?? null);
					#coopy/HighlightPatch.hx:371: characters 21-26
					break;
				}
				#coopy/HighlightPatch.hx:373: lines 373-384
				if ($this->currentRow > 0) {
					#coopy/HighlightPatch.hx:374: characters 21-79
					$prev = ($this->patchInSourceRow->data[$this->currentRow - 1] ?? null);
					#coopy/HighlightPatch.hx:375: lines 375-383
					if ($prev !== null) {
						#coopy/HighlightPatch.hx:376: characters 25-70
						$lst = $match->item_a->lst;
						#coopy/HighlightPatch.hx:377: lines 377-382
						$_g2 = 0;
						while ($_g2 < $lst->length) {
							#coopy/HighlightPatch.hx:377: characters 30-33
							$row = ($lst->arr[$_g2] ?? null);
							#coopy/HighlightPatch.hx:377: lines 377-382
							++$_g2;
							#coopy/HighlightPatch.hx:378: lines 378-381
							if ($row === ($prev + 1)) {
								#coopy/HighlightPatch.hx:379: characters 33-45
								$result = $row;
								#coopy/HighlightPatch.hx:380: characters 33-38
								break;
							}
						}
					}
				}
			}
		}
		#coopy/HighlightPatch.hx:387: characters 9-46
		$this->patchInSourceRow->data[$this->currentRow] = $result;
		#coopy/HighlightPatch.hx:388: characters 9-26
		$this->currentRow -= $del;
		#coopy/HighlightPatch.hx:389: characters 9-22
		return $result;
	}

	/**
	 * @return void
	 */
	public function needDestColumns () {
		#coopy/HighlightPatch.hx:171: characters 9-41
		if ($this->patchInDestCol !== null) {
			#coopy/HighlightPatch.hx:171: characters 35-41
			return;
		}
		#coopy/HighlightPatch.hx:173: characters 9-44
		$this->patchInDestCol = new IntMap();
		#coopy/HighlightPatch.hx:174: characters 9-44
		$this->destInPatchCol = new IntMap();
		#coopy/HighlightPatch.hx:177: lines 177-182
		$_g = 0;
		$_g1 = $this->cmods;
		while ($_g < $_g1->length) {
			#coopy/HighlightPatch.hx:177: characters 14-18
			$cmod = ($_g1->arr[$_g] ?? null);
			#coopy/HighlightPatch.hx:177: lines 177-182
			++$_g;
			#coopy/HighlightPatch.hx:178: lines 178-181
			if ($cmod->patchRow !== -1) {
				#coopy/HighlightPatch.hx:179: characters 17-63
				$this->patchInDestCol->data[$cmod->patchRow] = $cmod->destRow;
				#coopy/HighlightPatch.hx:180: characters 17-63
				$this->destInPatchCol->data[$cmod->destRow] = $cmod->patchRow;
			}
		}
	}

	/**
	 * @return void
	 */
	public function needSourceColumns () {
		#coopy/HighlightPatch.hx:155: characters 9-43
		if ($this->sourceInPatchCol !== null) {
			#coopy/HighlightPatch.hx:155: characters 37-43
			return;
		}
		#coopy/HighlightPatch.hx:156: characters 9-46
		$this->sourceInPatchCol = new IntMap();
		#coopy/HighlightPatch.hx:157: characters 9-46
		$this->patchInSourceCol = new IntMap();
		#coopy/HighlightPatch.hx:160: characters 9-46
		$av = $this->source->getCellView();
		#coopy/HighlightPatch.hx:161: characters 19-23
		$_g = 0;
		#coopy/HighlightPatch.hx:161: characters 23-35
		$_g1 = $this->source->get_width();
		#coopy/HighlightPatch.hx:161: lines 161-167
		while ($_g < $_g1) {
			#coopy/HighlightPatch.hx:161: characters 19-35
			$i = $_g++;
			#coopy/HighlightPatch.hx:162: characters 13-66
			$name = $av->toString($this->source->getCell($i, 0));
			#coopy/HighlightPatch.hx:163: characters 13-54
			$at = ($this->headerPre->data[$name] ?? null);
			#coopy/HighlightPatch.hx:164: characters 13-37
			if ($at === null) {
				#coopy/HighlightPatch.hx:164: characters 29-37
				continue;
			}
			#coopy/HighlightPatch.hx:165: characters 13-39
			$this->sourceInPatchCol->data[$i] = $at;
			#coopy/HighlightPatch.hx:166: characters 13-39
			$this->patchInSourceCol->data[$at] = $i;
		}
	}

	/**
	 * @return void
	 */
	public function needSourceIndex () {
		#coopy/HighlightPatch.hx:186: characters 9-34
		if ($this->indexes !== null) {
			#coopy/HighlightPatch.hx:186: characters 28-34
			return;
		}
		#coopy/HighlightPatch.hx:187: characters 9-71
		$state = new TableComparisonState();
		#coopy/HighlightPatch.hx:188: characters 9-25
		$state->a = $this->source;
		#coopy/HighlightPatch.hx:189: characters 9-25
		$state->b = $this->source;
		#coopy/HighlightPatch.hx:190: characters 9-59
		$comp = new CompareTable($state);
		#coopy/HighlightPatch.hx:191: characters 9-28
		$comp->storeIndexes();
		#coopy/HighlightPatch.hx:192: characters 9-19
		$comp->run();
		#coopy/HighlightPatch.hx:193: characters 9-21
		$comp->align();
		#coopy/HighlightPatch.hx:194: characters 9-36
		$this->indexes = $comp->getIndexes();
		#coopy/HighlightPatch.hx:195: characters 9-28
		$this->needSourceColumns();
	}

	/**
	 * @return void
	 */
	public function permuteColumns () {
		#coopy/HighlightPatch.hx:780: characters 9-39
		if ($this->headerMove === null) {
			#coopy/HighlightPatch.hx:780: characters 33-39
			return;
		}
		#coopy/HighlightPatch.hx:781: characters 9-42
		$this->colPermutation = new \Array_hx();
		#coopy/HighlightPatch.hx:782: characters 9-45
		$this->colPermutationRev = new \Array_hx();
		#coopy/HighlightPatch.hx:783: characters 9-77
		$this->computeOrdering($this->cmods, $this->colPermutation, $this->colPermutationRev, $this->source->get_width());
		#coopy/HighlightPatch.hx:784: characters 9-45
		if ($this->colPermutation->length === 0) {
			#coopy/HighlightPatch.hx:784: characters 39-45
			return;
		}
	}

	/**
	 * @return void
	 */
	public function permuteRows () {
		#coopy/HighlightPatch.hx:675: characters 9-42
		$this->rowPermutation = new \Array_hx();
		#coopy/HighlightPatch.hx:676: characters 9-45
		$this->rowPermutationRev = new \Array_hx();
		#coopy/HighlightPatch.hx:677: characters 9-77
		$this->computeOrdering($this->mods, $this->rowPermutation, $this->rowPermutationRev, $this->source->get_height());
	}

	/**
	 * @return void
	 */
	public function processMeta () {
		#coopy/HighlightPatch.hx:123: characters 9-28
		$this->process_meta = true;
	}

	/**
	 * @param HighlightPatchUnit[]|\Array_hx $rmods
	 * @param int[]|\Array_hx $fate
	 * @param int $len
	 * 
	 * @return int
	 */
	public function processMods ($rmods, $fate, $len) {
		#coopy/HighlightPatch.hx:539: characters 9-29
		\usort($rmods->arr, Boot::getInstanceClosure($this, 'sortMods'));
		#coopy/HighlightPatch.hx:540: characters 9-30
		$offset = 0;
		#coopy/HighlightPatch.hx:541: characters 9-29
		$last = -1;
		#coopy/HighlightPatch.hx:542: characters 9-30
		$target = 0;
		#coopy/HighlightPatch.hx:543: lines 543-547
		if ($rmods->length > 0) {
			#coopy/HighlightPatch.hx:544: lines 544-546
			if (($rmods->arr[0] ?? null)->sourcePrevRow === -1) {
				#coopy/HighlightPatch.hx:545: characters 17-25
				$last = 0;
			}
		}
		#coopy/HighlightPatch.hx:548: lines 548-578
		$_g = 0;
		while ($_g < $rmods->length) {
			#coopy/HighlightPatch.hx:548: characters 14-17
			$mod = ($rmods->arr[$_g] ?? null);
			#coopy/HighlightPatch.hx:548: lines 548-578
			++$_g;
			#coopy/HighlightPatch.hx:549: lines 549-555
			if ($last !== -1) {
				#coopy/HighlightPatch.hx:550: characters 27-31
				$_g1 = $last;
				#coopy/HighlightPatch.hx:550: characters 34-69
				$_g2 = $mod->sourceRow + $mod->sourceRowOffset;
				#coopy/HighlightPatch.hx:550: lines 550-554
				while ($_g1 < $_g2) {
					#coopy/HighlightPatch.hx:550: characters 27-69
					$i = $_g1++;
					#coopy/HighlightPatch.hx:551: characters 21-40
					$fate->arr[$fate->length++] = $i + $offset;
					#coopy/HighlightPatch.hx:552: characters 21-29
					++$target;
					#coopy/HighlightPatch.hx:553: characters 21-27
					++$last;
				}
			}
			#coopy/HighlightPatch.hx:556: lines 556-565
			if ($mod->rem) {
				#coopy/HighlightPatch.hx:557: characters 17-30
				$fate->arr[$fate->length++] = -1;
				#coopy/HighlightPatch.hx:558: characters 17-25
				--$offset;
			} else if ($mod->add) {
				#coopy/HighlightPatch.hx:560: characters 17-37
				$mod->destRow = $target;
				#coopy/HighlightPatch.hx:561: characters 17-25
				++$target;
				#coopy/HighlightPatch.hx:562: characters 17-25
				++$offset;
			} else {
				#coopy/HighlightPatch.hx:564: characters 17-37
				$mod->destRow = $target;
			}
			#coopy/HighlightPatch.hx:566: lines 566-577
			if ($mod->sourceRow >= 0) {
				#coopy/HighlightPatch.hx:567: characters 17-57
				$last = $mod->sourceRow + $mod->sourceRowOffset;
				#coopy/HighlightPatch.hx:568: characters 17-36
				if ($mod->rem) {
					#coopy/HighlightPatch.hx:568: characters 30-36
					++$last;
				}
			} else if ($mod->add && ($mod->sourceNextRow !== -1)) {
				#coopy/HighlightPatch.hx:571: characters 21-65
				$last = $mod->sourceNextRow + $mod->sourceRowOffset;
			} else if ($mod->rem || $mod->add) {
				#coopy/HighlightPatch.hx:574: characters 25-34
				$last = -1;
			}
		}
		#coopy/HighlightPatch.hx:579: lines 579-585
		if ($last !== -1) {
			#coopy/HighlightPatch.hx:580: characters 23-27
			$_g = $last;
			#coopy/HighlightPatch.hx:580: characters 30-33
			$_g1 = $len;
			#coopy/HighlightPatch.hx:580: lines 580-584
			while ($_g < $_g1) {
				#coopy/HighlightPatch.hx:580: characters 23-33
				$i = $_g++;
				#coopy/HighlightPatch.hx:581: characters 17-36
				$fate->arr[$fate->length++] = $i + $offset;
				#coopy/HighlightPatch.hx:582: characters 17-25
				++$target;
				#coopy/HighlightPatch.hx:583: characters 17-23
				++$last;
			}
		}
		#coopy/HighlightPatch.hx:586: characters 9-26
		return $len + $offset;
	}

	/**
	 * @return void
	 */
	public function reset () {
		#coopy/HighlightPatch.hx:87: characters 9-39
		$this->header = new IntMap();
		#coopy/HighlightPatch.hx:88: characters 9-42
		$this->headerPre = new StringMap();
		#coopy/HighlightPatch.hx:89: characters 9-43
		$this->headerPost = new StringMap();
		#coopy/HighlightPatch.hx:90: characters 9-48
		$this->headerRename = new StringMap();
		#coopy/HighlightPatch.hx:91: characters 9-26
		$this->headerMove = null;
		#coopy/HighlightPatch.hx:92: characters 9-41
		$this->modifier = new IntMap();
		#coopy/HighlightPatch.hx:93: characters 9-47
		$this->mods = new \Array_hx();
		#coopy/HighlightPatch.hx:94: characters 9-48
		$this->cmods = new \Array_hx();
		#coopy/HighlightPatch.hx:95: characters 9-24
		$this->csv = new Csv();
		#coopy/HighlightPatch.hx:96: characters 9-21
		$this->rcOffset = 0;
		#coopy/HighlightPatch.hx:97: characters 9-24
		$this->currentRow = -1;
		#coopy/HighlightPatch.hx:98: characters 9-33
		$this->rowInfo = new CellInfo();
		#coopy/HighlightPatch.hx:99: characters 9-34
		$this->cellInfo = new CellInfo();
		#coopy/HighlightPatch.hx:101: characters 9-68
		$this->sourceInPatchCol = $this->patchInSourceCol = $this->patchInDestCol = null;
		#coopy/HighlightPatch.hx:102: characters 9-46
		$this->patchInSourceRow = new IntMap();
		#coopy/HighlightPatch.hx:103: characters 9-23
		$this->indexes = null;
		#coopy/HighlightPatch.hx:104: characters 9-27
		$this->lastSourceRow = -1;
		#coopy/HighlightPatch.hx:105: characters 9-38
		$this->actions = new \Array_hx();
		#coopy/HighlightPatch.hx:106: characters 9-30
		$this->rowPermutation = null;
		#coopy/HighlightPatch.hx:107: characters 9-33
		$this->rowPermutationRev = null;
		#coopy/HighlightPatch.hx:108: characters 9-30
		$this->colPermutation = null;
		#coopy/HighlightPatch.hx:109: characters 9-33
		$this->colPermutationRev = null;
		#coopy/HighlightPatch.hx:110: characters 9-35
		$this->haveDroppedColumns = false;
		#coopy/HighlightPatch.hx:111: characters 9-22
		$this->headerRow = 0;
		#coopy/HighlightPatch.hx:112: characters 9-24
		$this->preambleRow = 0;
		#coopy/HighlightPatch.hx:114: characters 9-28
		$this->meta_change = false;
		#coopy/HighlightPatch.hx:115: characters 9-29
		$this->process_meta = false;
		#coopy/HighlightPatch.hx:116: characters 9-25
		$this->prev_meta = null;
		#coopy/HighlightPatch.hx:117: characters 9-25
		$this->next_meta = null;
		#coopy/HighlightPatch.hx:119: characters 9-33
		$this->finished_columns = false;
	}

	/**
	 * @param StringMap $target
	 * @param string $column_name
	 * @param string $prop_name
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setMetaProp ($target, $column_name, $prop_name, $value) {
		#coopy/HighlightPatch.hx:202: characters 9-38
		if ($column_name === null) {
			#coopy/HighlightPatch.hx:202: characters 32-38
			return;
		}
		#coopy/HighlightPatch.hx:203: characters 9-36
		if ($prop_name === null) {
			#coopy/HighlightPatch.hx:203: characters 30-36
			return;
		}
		#coopy/HighlightPatch.hx:204: lines 204-206
		if (!\array_key_exists($column_name, $target->data)) {
			#coopy/HighlightPatch.hx:205: characters 13-64
			$value1 = new \Array_hx();
			$target->data[$column_name] = $value1;
		}
		#coopy/HighlightPatch.hx:207: characters 9-43
		$change = new PropertyChange();
		#coopy/HighlightPatch.hx:208: characters 9-36
		$change->prevName = $prop_name;
		#coopy/HighlightPatch.hx:209: characters 9-32
		$change->name = $prop_name;
		#coopy/HighlightPatch.hx:210: characters 9-36
		if ($value === "") {
			#coopy/HighlightPatch.hx:210: characters 24-36
			$value = null;
		}
		#coopy/HighlightPatch.hx:211: characters 9-27
		$change->val = $value;
		#coopy/HighlightPatch.hx:212: characters 9-45
		$_this = ($target->data[$column_name] ?? null);
		$_this->arr[$_this->length++] = $change;
	}

	/**
	 * @param HighlightPatchUnit $a
	 * @param HighlightPatchUnit $b
	 * 
	 * @return int
	 */
	public function sortMods ($a, $b) {
		#coopy/HighlightPatch.hx:525: characters 9-51
		if (($b->code === "@@") && ($a->code !== "@@")) {
			#coopy/HighlightPatch.hx:525: characters 43-51
			return 1;
		}
		#coopy/HighlightPatch.hx:526: characters 9-52
		if (($a->code === "@@") && ($b->code !== "@@")) {
			#coopy/HighlightPatch.hx:526: characters 43-52
			return -1;
		}
		#coopy/HighlightPatch.hx:527: characters 9-69
		if (($a->sourceRow === -1) && !$a->add && ($b->sourceRow !== -1)) {
			#coopy/HighlightPatch.hx:527: characters 61-69
			return 1;
		}
		#coopy/HighlightPatch.hx:528: characters 9-70
		if (($a->sourceRow !== -1) && !$b->add && ($b->sourceRow === -1)) {
			#coopy/HighlightPatch.hx:528: characters 61-70
			return -1;
		}
		#coopy/HighlightPatch.hx:529: characters 9-82
		if (($a->sourceRow + $a->sourceRowOffset) > ($b->sourceRow + $b->sourceRowOffset)) {
			#coopy/HighlightPatch.hx:529: characters 74-82
			return 1;
		}
		#coopy/HighlightPatch.hx:530: characters 9-83
		if (($a->sourceRow + $a->sourceRowOffset) < ($b->sourceRow + $b->sourceRowOffset)) {
			#coopy/HighlightPatch.hx:530: characters 74-83
			return -1;
		}
		#coopy/HighlightPatch.hx:531: characters 9-44
		if ($a->patchRow > $b->patchRow) {
			#coopy/HighlightPatch.hx:531: characters 36-44
			return 1;
		}
		#coopy/HighlightPatch.hx:532: characters 9-45
		if ($a->patchRow < $b->patchRow) {
			#coopy/HighlightPatch.hx:532: characters 36-45
			return -1;
		}
		#coopy/HighlightPatch.hx:532: characters 47-55
		return 0;
	}

	/**
	 * @return bool
	 */
	public function useMetaForColumnChanges () {
		#coopy/HighlightPatch.hx:590: characters 9-37
		if ($this->meta === null) {
			#coopy/HighlightPatch.hx:590: characters 25-37
			return false;
		}
		#coopy/HighlightPatch.hx:591: characters 9-42
		return $this->meta->useForColumnChanges();
	}

	/**
	 * @return bool
	 */
	public function useMetaForRowChanges () {
		#coopy/HighlightPatch.hx:595: characters 9-37
		if ($this->meta === null) {
			#coopy/HighlightPatch.hx:595: characters 25-37
			return false;
		}
		#coopy/HighlightPatch.hx:596: characters 9-39
		return $this->meta->useForRowChanges();
	}
}

Boot::registerClass(HighlightPatch::class, 'coopy.HighlightPatch');
