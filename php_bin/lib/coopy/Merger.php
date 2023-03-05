<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\IntMap;

/**
 *
 * Merge changes made in one table into another, given knowledge
 * of a common ancestor.
 *
 */
class Merger {
	/**
	 * @var IntMap
	 */
	public $column_mix_local;
	/**
	 * @var IntMap
	 */
	public $column_mix_remote;
	/**
	 * @var Ordering
	 */
	public $column_order;
	/**
	 * @var Unit[]|\Array_hx
	 */
	public $column_units;
	/**
	 * @var ConflictInfo[]|\Array_hx
	 */
	public $conflict_infos;
	/**
	 * @var int
	 */
	public $conflicts;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var Table
	 */
	public $local;
	/**
	 * @var Ordering
	 */
	public $order;
	/**
	 * @var Table
	 */
	public $parent;
	/**
	 * @var Table
	 */
	public $remote;
	/**
	 * @var IntMap
	 */
	public $row_mix_local;
	/**
	 * @var IntMap
	 */
	public $row_mix_remote;
	/**
	 * @var Unit[]|\Array_hx
	 */
	public $units;

	/**
	 * @param View $view
	 * @param mixed $pcell
	 * @param mixed $lcell
	 * @param mixed $rcell
	 * 
	 * @return mixed
	 */
	public static function makeConflictedCell ($view, $pcell, $lcell, $rcell) {
		#coopy/Merger.hx:208: lines 208-213
		return $view->toDatum("((( " . ($view->toString($pcell)??'null') . " ))) " . ($view->toString($lcell)??'null') . " /// " . ($view->toString($rcell)??'null'));
	}

	/**
	 *
	 * Constructor.
	 *
	 * @param parent the common ancestor
	 * @param local the reference table into which changes will be merged
	 * @param remote the table we are pulling changes from
	 *
	 * 
	 * @param Table $parent
	 * @param Table $local
	 * @param Table $remote
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($parent, $local, $remote, $flags) {
		#coopy/Merger.hx:41: characters 9-29
		$this->parent = $parent;
		#coopy/Merger.hx:42: characters 9-27
		$this->local = $local;
		#coopy/Merger.hx:43: characters 9-29
		$this->remote = $remote;
		#coopy/Merger.hx:44: characters 9-27
		$this->flags = $flags;
	}

	/**
	 * @param int $row
	 * @param int $col
	 * @param View $view
	 * @param mixed $pcell
	 * @param mixed $lcell
	 * @param mixed $rcell
	 * 
	 * @return void
	 */
	public function addConflictInfo ($row, $col, $view, $pcell, $lcell, $rcell) {
		#coopy/Merger.hx:197: lines 197-201
		$_this = $this->conflict_infos;
		#coopy/Merger.hx:199: characters 46-66
		$x = $view->toString($pcell);
		#coopy/Merger.hx:200: characters 46-66
		$x1 = $view->toString($lcell);
		#coopy/Merger.hx:197: lines 197-201
		$x2 = new ConflictInfo($row, $col, $x, $x1, $view->toString($rcell));
		$_this->arr[$_this->length++] = $x2;
	}

	/**
	 *
	 * Go ahead and merge.
	 *
	 * @return the number of conflicts found during the merge
	 *
	 * 
	 * @return int
	 */
	public function apply () {
		#coopy/Merger.hx:112: characters 9-18
		$this->conflicts = 0;
		#coopy/Merger.hx:113: characters 9-23
		$this->conflict_infos = new \Array_hx();
		#coopy/Merger.hx:115: characters 9-75
		$ct = Coopy::compareTables3($this->parent, $this->local, $this->remote);
		#coopy/Merger.hx:116: characters 9-44
		$align = $ct->align();
		#coopy/Merger.hx:122: characters 9-14
		$this->order = $align->toOrder();
		#coopy/Merger.hx:123: characters 9-14
		$this->units = $this->order->getList();
		#coopy/Merger.hx:124: characters 9-21
		$this->column_order = $align->meta->toOrder();
		#coopy/Merger.hx:125: characters 9-21
		$this->column_units = $this->column_order->getList();
		#coopy/Merger.hx:127: characters 9-55
		$allow_insert = $this->flags->allowInsert();
		#coopy/Merger.hx:128: characters 9-55
		$allow_delete = $this->flags->allowDelete();
		#coopy/Merger.hx:129: characters 9-55
		$allow_update = $this->flags->allowUpdate();
		#coopy/Merger.hx:132: characters 9-41
		$view = $this->parent->getCellView();
		#coopy/Merger.hx:133: lines 133-153
		$_g = 0;
		$_g1 = $this->units;
		while ($_g < $_g1->length) {
			#coopy/Merger.hx:133: characters 14-17
			$row = ($_g1->arr[$_g] ?? null);
			#coopy/Merger.hx:133: lines 133-153
			++$_g;
			#coopy/Merger.hx:134: lines 134-152
			if (($row->l >= 0) && ($row->r >= 0) && ($row->p >= 0)) {
				#coopy/Merger.hx:135: lines 135-151
				$_g2 = 0;
				$_g3 = $this->column_units;
				while ($_g2 < $_g3->length) {
					#coopy/Merger.hx:135: characters 22-25
					$col = ($_g3->arr[$_g2] ?? null);
					#coopy/Merger.hx:135: lines 135-151
					++$_g2;
					#coopy/Merger.hx:136: lines 136-150
					if (($col->l >= 0) && ($col->r >= 0) && ($col->p >= 0)) {
						#coopy/Merger.hx:137: characters 25-65
						$pcell = $this->parent->getCell($col->p, $row->p);
						#coopy/Merger.hx:138: characters 25-65
						$rcell = $this->remote->getCell($col->r, $row->r);
						#coopy/Merger.hx:139: lines 139-149
						if (!$view->equals($pcell, $rcell)) {
							#coopy/Merger.hx:140: characters 29-68
							$lcell = $this->local->getCell($col->l, $row->l);
							#coopy/Merger.hx:141: lines 141-148
							if ($view->equals($pcell, $lcell)) {
								#coopy/Merger.hx:142: characters 33-65
								$this->local->setCell($col->l, $row->l, $rcell);
							} else if (!$view->equals($rcell, $lcell)) {
								#coopy/Merger.hx:144: lines 144-145
								$this->local->setCell($col->l, $row->l, Merger::makeConflictedCell($view, $pcell, $lcell, $rcell));
								#coopy/Merger.hx:146: characters 33-44
								$this->conflicts++;
								#coopy/Merger.hx:147: characters 33-84
								$this->addConflictInfo($row->l, $col->l, $view, $pcell, $lcell, $rcell);
							}
						}
					}
				}
			}
		}
		#coopy/Merger.hx:156: characters 9-25
		$this->shuffleColumns();
		#coopy/Merger.hx:157: characters 9-22
		$this->shuffleRows();
		#coopy/Merger.hx:160: characters 19-43
		$data = \array_keys($this->column_mix_remote->data);
		$x_current = 0;
		$x_length = \count($data);
		$x_data = $data;
		while ($x_current < $x_length) {
			#coopy/Merger.hx:160: lines 160-171
			$x = $x_data[$x_current++];
			#coopy/Merger.hx:161: characters 13-47
			$x2 = ($this->column_mix_remote->data[$x] ?? null);
			#coopy/Merger.hx:162: lines 162-170
			$_g = 0;
			$_g1 = $this->units;
			while ($_g < $_g1->length) {
				#coopy/Merger.hx:162: characters 18-22
				$unit = ($_g1->arr[$_g] ?? null);
				#coopy/Merger.hx:162: lines 162-170
				++$_g;
				#coopy/Merger.hx:163: lines 163-169
				if (($unit->l >= 0) && ($unit->r >= 0)) {
					#coopy/Merger.hx:164: lines 164-165
					$this->local->setCell($x2, ($this->row_mix_local->data[$unit->l] ?? null), $this->remote->getCell($x, $unit->r));
				} else if (($unit->p < 0) && ($unit->r >= 0)) {
					#coopy/Merger.hx:167: lines 167-168
					$this->local->setCell($x2, ($this->row_mix_remote->data[$unit->r] ?? null), $this->remote->getCell($x, $unit->r));
				}
			}
		}
		#coopy/Merger.hx:174: characters 19-40
		$data = \array_keys($this->row_mix_remote->data);
		$y_current = 0;
		$y_length = \count($data);
		$y_data = $data;
		while ($y_current < $y_length) {
			#coopy/Merger.hx:174: lines 174-182
			$y = $y_data[$y_current++];
			#coopy/Merger.hx:175: characters 13-44
			$y2 = ($this->row_mix_remote->data[$y] ?? null);
			#coopy/Merger.hx:176: lines 176-181
			$_g = 0;
			$_g1 = $this->column_units;
			while ($_g < $_g1->length) {
				#coopy/Merger.hx:176: characters 18-22
				$unit = ($_g1->arr[$_g] ?? null);
				#coopy/Merger.hx:176: lines 176-181
				++$_g;
				#coopy/Merger.hx:177: lines 177-180
				if (($unit->l >= 0) && ($unit->r >= 0)) {
					#coopy/Merger.hx:178: lines 178-179
					$this->local->setCell(($this->column_mix_local->data[$unit->l] ?? null), $y2, $this->remote->getCell($unit->r, $y));
				}
			}
		}
		#coopy/Merger.hx:184: characters 9-25
		return $this->conflicts;
	}

	/**
	 * @return ConflictInfo[]|\Array_hx
	 */
	public function getConflictInfos () {
		#coopy/Merger.hx:188: characters 9-30
		return $this->conflict_infos;
	}

	/**
	 * @return void
	 */
	public function shuffleColumns () {
		#coopy/Merger.hx:87: characters 9-46
		$this->column_mix_local = new IntMap();
		#coopy/Merger.hx:88: characters 9-47
		$this->column_mix_remote = new IntMap();
		#coopy/Merger.hx:89: characters 9-37
		$fate = new \Array_hx();
		#coopy/Merger.hx:90: lines 90-91
		$wfate = $this->shuffleDimension($this->column_units, $this->local->get_width(), $fate, $this->column_mix_local, $this->column_mix_remote);
		#coopy/Merger.hx:92: characters 9-48
		$this->local->insertOrDeleteColumns($fate, $wfate);
	}

	/**
	 * @param Unit[]|\Array_hx $dim_units
	 * @param int $len
	 * @param int[]|\Array_hx $fate
	 * @param IntMap $cl
	 * @param IntMap $cr
	 * 
	 * @return int
	 */
	public function shuffleDimension ($dim_units, $len, $fate, $cl, $cr) {
		#coopy/Merger.hx:51: characters 9-20
		$at = 0;
		#coopy/Merger.hx:52: lines 52-74
		$_g = 0;
		while ($_g < $dim_units->length) {
			#coopy/Merger.hx:52: characters 14-19
			$cunit = ($dim_units->arr[$_g] ?? null);
			#coopy/Merger.hx:52: lines 52-74
			++$_g;
			#coopy/Merger.hx:53: lines 53-73
			if ($cunit->p < 0) {
				#coopy/Merger.hx:54: lines 54-63
				if ($cunit->l < 0) {
					#coopy/Merger.hx:55: lines 55-59
					if ($cunit->r >= 0) {
						#coopy/Merger.hx:57: characters 25-41
						$cr->data[$cunit->r] = $at;
						#coopy/Merger.hx:58: characters 25-29
						++$at;
					}
				} else {
					#coopy/Merger.hx:61: characters 21-37
					$cl->data[$cunit->l] = $at;
					#coopy/Merger.hx:62: characters 21-25
					++$at;
				}
			} else if ($cunit->l >= 0) {
				#coopy/Merger.hx:66: lines 66-71
				if ($cunit->r >= 0) {
					#coopy/Merger.hx:69: characters 25-41
					$cl->data[$cunit->l] = $at;
					#coopy/Merger.hx:70: characters 25-29
					++$at;
				}
			}
		}
		#coopy/Merger.hx:75: characters 19-23
		$_g = 0;
		#coopy/Merger.hx:75: characters 23-26
		$_g1 = $len;
		#coopy/Merger.hx:75: lines 75-82
		while ($_g < $_g1) {
			#coopy/Merger.hx:75: characters 19-26
			$x = $_g++;
			#coopy/Merger.hx:76: characters 13-33
			$idx = ($cl->data[$x] ?? null);
			#coopy/Merger.hx:77: lines 77-81
			if ($idx === null) {
				#coopy/Merger.hx:78: characters 17-30
				$fate->arr[$fate->length++] = -1;
			} else {
				#coopy/Merger.hx:80: characters 17-31
				$fate->arr[$fate->length++] = $idx;
			}
		}
		#coopy/Merger.hx:83: characters 9-18
		return $at;
	}

	/**
	 * @return void
	 */
	public function shuffleRows () {
		#coopy/Merger.hx:96: characters 9-43
		$this->row_mix_local = new IntMap();
		#coopy/Merger.hx:97: characters 9-44
		$this->row_mix_remote = new IntMap();
		#coopy/Merger.hx:98: characters 9-37
		$fate = new \Array_hx();
		#coopy/Merger.hx:99: lines 99-100
		$hfate = $this->shuffleDimension($this->units, $this->local->get_height(), $fate, $this->row_mix_local, $this->row_mix_remote);
		#coopy/Merger.hx:101: characters 9-45
		$this->local->insertOrDeleteRows($fate, $hfate);
	}
}

Boot::registerClass(Merger::class, 'coopy.Merger');
