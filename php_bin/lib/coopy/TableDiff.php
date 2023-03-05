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
 * Build a highlighter diff of two/three tables.
 *
 */
class TableDiff {
	/**
	 * @var Table
	 */
	public $a;
	/**
	 * @var string
	 */
	public $act;
	/**
	 * @var int[]|\Array_hx
	 */
	public $active_column;
	/**
	 * @var int[]|\Array_hx
	 */
	public $active_row;
	/**
	 * @var Alignment
	 */
	public $align;
	/**
	 * @var bool
	 */
	public $allow_column;
	/**
	 * @var bool
	 */
	public $allow_delete;
	/**
	 * @var bool
	 */
	public $allow_insert;
	/**
	 * @var bool
	 */
	public $allow_update;
	/**
	 * @var Table
	 */
	public $b;
	/**
	 * @var CellBuilder
	 */
	public $builder;
	/**
	 * @var int
	 */
	public $col_deletes;
	/**
	 * @var int
	 */
	public $col_inserts;
	/**
	 * @var IntMap
	 */
	public $col_map;
	/**
	 * @var IntMap
	 */
	public $col_moves;
	/**
	 * @var int
	 */
	public $col_renames;
	/**
	 * @var int
	 */
	public $col_reorders;
	/**
	 * @var int
	 */
	public $col_updates;
	/**
	 * @var Unit[]|\Array_hx
	 */
	public $column_units;
	/**
	 * @var IntMap
	 */
	public $column_units_updated;
	/**
	 * @var string
	 */
	public $conflict_sep;
	/**
	 * @var bool
	 */
	public $diff_found;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var bool
	 */
	public $has_parent;
	/**
	 * @var bool
	 */
	public $have_addition;
	/**
	 * @var bool
	 */
	public $have_schema;
	/**
	 * @var IntMap
	 */
	public $is_index_a;
	/**
	 * @var IntMap
	 */
	public $is_index_b;
	/**
	 * @var IntMap
	 */
	public $is_index_p;
	/**
	 * @var bool
	 */
	public $nested;
	/**
	 * @var bool
	 */
	public $nesting_present;
	/**
	 * @var Ordering
	 */
	public $order;
	/**
	 * @var Table
	 */
	public $p;
	/**
	 * @var bool
	 */
	public $preserve_columns;
	/**
	 * @var bool
	 */
	public $publish;
	/**
	 * @var int
	 */
	public $ra_header;
	/**
	 * @var int
	 */
	public $rb_header;
	/**
	 * @var int
	 */
	public $row_deletes;
	/**
	 * @var int
	 */
	public $row_inserts;
	/**
	 * @var IntMap
	 */
	public $row_map;
	/**
	 * @var IntMap
	 */
	public $row_moves;
	/**
	 * @var int
	 */
	public $row_reorders;
	/**
	 * @var Unit[]|\Array_hx
	 */
	public $row_units;
	/**
	 * @var int
	 */
	public $row_updates;
	/**
	 * @var int
	 */
	public $rp_header;
	/**
	 * @var string[]|\Array_hx
	 */
	public $schema;
	/**
	 * @var bool
	 */
	public $schema_diff_found;
	/**
	 * @var string
	 */
	public $sep;
	/**
	 * @var bool
	 */
	public $show_rc_numbers;
	/**
	 * @var bool
	 */
	public $top_line_done;
	/**
	 * @var View
	 */
	public $v;

	/**
	 *
	 * Constructor.
	 *
	 * @param align a pre-computed alignment of the tables involved
	 * @param flags options to control the appearance of the diff
	 *
	 * 
	 * @param Alignment $align
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($align, $flags) {
		#coopy/TableDiff.hx:90: characters 9-27
		$this->align = $align;
		#coopy/TableDiff.hx:91: characters 9-27
		$this->flags = $flags;
		#coopy/TableDiff.hx:92: characters 9-23
		$this->builder = null;
		#coopy/TableDiff.hx:93: characters 9-33
		$this->preserve_columns = false;
	}

	/**
	 * @param Table $output
	 * 
	 * @return void
	 */
	public function addHeader ($output) {
		#coopy/TableDiff.hx:559: lines 559-584
		if ($this->flags->always_show_header) {
			#coopy/TableDiff.hx:560: characters 13-42
			$at = $output->get_height();
			#coopy/TableDiff.hx:561: characters 13-54
			$output->resize($this->column_units->length + 1, $at + 1);
			#coopy/TableDiff.hx:562: characters 13-54
			$output->setCell(0, $at, $this->builder->marker("@@"));
			#coopy/TableDiff.hx:563: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:563: characters 27-46
			$_g1 = $this->column_units->length;
			#coopy/TableDiff.hx:563: lines 563-582
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:563: characters 23-46
				$j = $_g++;
				#coopy/TableDiff.hx:564: characters 17-52
				$cunit = ($this->column_units->arr[$j] ?? null);
				#coopy/TableDiff.hx:565: lines 565-580
				if ($cunit->r >= 0) {
					#coopy/TableDiff.hx:566: lines 566-569
					if ($this->b->get_height() !== 0) {
						#coopy/TableDiff.hx:567: lines 567-568
						$output->setCell($j + 1, $at, $this->b->getCell($cunit->r, $this->rb_header));
					}
				} else if ($cunit->l >= 0) {
					#coopy/TableDiff.hx:571: lines 571-574
					if ($this->a->get_height() !== 0) {
						#coopy/TableDiff.hx:572: lines 572-573
						$output->setCell($j + 1, $at, $this->a->getCell($cunit->l, $this->ra_header));
					}
				} else if ($cunit->lp() >= 0) {
					#coopy/TableDiff.hx:576: lines 576-579
					if ($this->p->get_height() !== 0) {
						#coopy/TableDiff.hx:577: lines 577-578
						$output->setCell($j + 1, $at, $this->p->getCell($cunit->lp(), $this->rp_header));
					}
				}
				#coopy/TableDiff.hx:581: characters 17-39
				$this->col_map->data[$j + 1] = $cunit;
			}
			#coopy/TableDiff.hx:583: characters 13-33
			$this->top_line_done = true;
		}
	}

	/**
	 * @param Table $output
	 * 
	 * @return bool
	 */
	public function addMeta ($output) {
		#coopy/TableDiff.hx:611: characters 9-52
		if (($this->a === null) && ($this->b === null) && ($this->p === null)) {
			#coopy/TableDiff.hx:611: characters 40-52
			return false;
		}
		#coopy/TableDiff.hx:612: characters 9-43
		if (!$this->flags->show_meta) {
			#coopy/TableDiff.hx:612: characters 31-43
			return false;
		}
		#coopy/TableDiff.hx:614: characters 9-46
		$a_meta = $this->getMetaTable($this->a);
		#coopy/TableDiff.hx:615: characters 9-46
		$b_meta = $this->getMetaTable($this->b);
		#coopy/TableDiff.hx:616: characters 9-46
		$p_meta = $this->getMetaTable($this->p);
		#coopy/TableDiff.hx:617: characters 9-47
		if (!$this->checkMeta($this->a, $a_meta)) {
			#coopy/TableDiff.hx:617: characters 35-47
			return false;
		}
		#coopy/TableDiff.hx:618: characters 9-47
		if (!$this->checkMeta($this->b, $b_meta)) {
			#coopy/TableDiff.hx:618: characters 35-47
			return false;
		}
		#coopy/TableDiff.hx:619: characters 9-47
		if (!$this->checkMeta($this->p, $p_meta)) {
			#coopy/TableDiff.hx:619: characters 35-47
			return false;
		}
		#coopy/TableDiff.hx:623: characters 9-46
		$meta_diff = new SimpleTable(0, 0);
		#coopy/TableDiff.hx:624: characters 9-45
		$meta_flags = new CompareFlags();
		#coopy/TableDiff.hx:625: characters 9-39
		$meta_flags->addPrimaryKey("@@");
		#coopy/TableDiff.hx:626: characters 9-38
		$meta_flags->addPrimaryKey("@");
		#coopy/TableDiff.hx:627: characters 9-52
		$meta_flags->unchanged_column_context = 65536;
		#coopy/TableDiff.hx:628: characters 9-41
		$meta_flags->unchanged_context = 0;
		#coopy/TableDiff.hx:629: characters 9-110
		$meta_align = Coopy::compareTables3(($a_meta === $p_meta ? null : $p_meta), $a_meta, $b_meta, $meta_flags)->align();
		#coopy/TableDiff.hx:630: characters 9-55
		$td = new TableDiff($meta_align, $meta_flags);
		#coopy/TableDiff.hx:631: characters 9-35
		$td->preserve_columns = true;
		#coopy/TableDiff.hx:632: characters 9-29
		$td->hilite($meta_diff);
		#coopy/TableDiff.hx:634: lines 634-659
		if ($td->hasDifference() || $td->hasSchemaDifference()) {
			#coopy/TableDiff.hx:635: characters 13-35
			$h = $output->get_height();
			#coopy/TableDiff.hx:636: characters 13-39
			$dh = $meta_diff->get_height();
			#coopy/TableDiff.hx:637: characters 13-55
			$offset = ($td->hasSchemaDifference() ? 2 : 1);
			#coopy/TableDiff.hx:638: characters 13-52
			$output->resize($output->get_width(), $h + $dh - $offset);
			#coopy/TableDiff.hx:639: characters 13-45
			$v = $meta_diff->getCellView();
			#coopy/TableDiff.hx:640: characters 23-29
			$_g = $offset;
			#coopy/TableDiff.hx:640: characters 32-34
			$_g1 = $dh;
			#coopy/TableDiff.hx:640: lines 640-648
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:640: characters 23-34
				$y = $_g++;
				#coopy/TableDiff.hx:641: characters 27-31
				$_g2 = 1;
				#coopy/TableDiff.hx:641: characters 31-46
				$_g3 = $meta_diff->get_width();
				#coopy/TableDiff.hx:641: lines 641-647
				while ($_g2 < $_g3) {
					#coopy/TableDiff.hx:641: characters 27-46
					$x = $_g2++;
					#coopy/TableDiff.hx:642: characters 21-52
					$c = $meta_diff->getCell($x, $y);
					#coopy/TableDiff.hx:643: lines 643-645
					if ($x === 1) {
						#coopy/TableDiff.hx:644: characters 25-91
						$c = "@" . ($v->toString($c)??'null') . "@" . ($v->toString($meta_diff->getCell(0, $y))??'null');
					}
					#coopy/TableDiff.hx:646: characters 21-53
					$output->setCell($x - 1, $h + $y - $offset, $c);
				}
			}
			#coopy/TableDiff.hx:649: lines 649-658
			if ($this->active_column !== null) {
				#coopy/TableDiff.hx:650: lines 650-657
				if ($td->active_column->length === $meta_diff->get_width()) {
					#coopy/TableDiff.hx:652: characters 31-35
					$_g = 1;
					#coopy/TableDiff.hx:652: characters 35-50
					$_g1 = $meta_diff->get_width();
					#coopy/TableDiff.hx:652: lines 652-656
					while ($_g < $_g1) {
						#coopy/TableDiff.hx:652: characters 31-50
						$i = $_g++;
						#coopy/TableDiff.hx:653: lines 653-655
						if (($td->active_column->arr[$i] ?? null) >= 0) {
							#coopy/TableDiff.hx:654: characters 29-51
							$this->active_column->offsetSet($i - 1, 1);
						}
					}
				}
			}
		}
		#coopy/TableDiff.hx:661: characters 9-21
		return false;
	}

	/**
	 * @param Table $output
	 * 
	 * @return int
	 */
	public function addRcNumbers ($output) {
		#coopy/TableDiff.hx:473: characters 9-31
		$admin_w = 1;
		#coopy/TableDiff.hx:474: lines 474-504
		if ($this->show_rc_numbers && !$this->flags->never_show_order) {
			#coopy/TableDiff.hx:475: characters 13-22
			++$admin_w;
			#coopy/TableDiff.hx:476: characters 13-56
			$target = new \Array_hx();
			#coopy/TableDiff.hx:477: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:477: characters 27-39
			$_g1 = $output->get_width();
			#coopy/TableDiff.hx:477: lines 477-479
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:477: characters 23-39
				$i = $_g++;
				#coopy/TableDiff.hx:478: characters 17-33
				$target->arr[$target->length++] = $i + 1;
			}
			#coopy/TableDiff.hx:480: characters 13-64
			$output->insertOrDeleteColumns($target, $output->get_width() + 1);
			#coopy/TableDiff.hx:482: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:482: characters 27-40
			$_g1 = $output->get_height();
			#coopy/TableDiff.hx:482: lines 482-489
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:482: characters 23-40
				$i = $_g++;
				#coopy/TableDiff.hx:483: characters 17-50
				$unit = ($this->row_map->data[$i] ?? null);
				#coopy/TableDiff.hx:484: lines 484-487
				if ($unit === null) {
					#coopy/TableDiff.hx:485: characters 21-43
					$output->setCell(0, $i, "");
					#coopy/TableDiff.hx:486: characters 21-29
					continue;
				}
				#coopy/TableDiff.hx:488: characters 17-61
				$output->setCell(0, $i, $this->builder->links($unit, true));
			}
			#coopy/TableDiff.hx:490: characters 13-38
			$target = new \Array_hx();
			#coopy/TableDiff.hx:491: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:491: characters 27-40
			$_g1 = $output->get_height();
			#coopy/TableDiff.hx:491: lines 491-493
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:491: characters 23-40
				$i = $_g++;
				#coopy/TableDiff.hx:492: characters 17-33
				$target->arr[$target->length++] = $i + 1;
			}
			#coopy/TableDiff.hx:494: characters 13-62
			$output->insertOrDeleteRows($target, $output->get_height() + 1);
			#coopy/TableDiff.hx:495: characters 23-27
			$_g = 1;
			#coopy/TableDiff.hx:495: characters 27-39
			$_g1 = $output->get_width();
			#coopy/TableDiff.hx:495: lines 495-502
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:495: characters 23-39
				$i = $_g++;
				#coopy/TableDiff.hx:496: characters 17-52
				$unit = ($this->col_map->data[$i - 1] ?? null);
				#coopy/TableDiff.hx:497: lines 497-500
				if ($unit === null) {
					#coopy/TableDiff.hx:498: characters 21-43
					$output->setCell($i, 0, "");
					#coopy/TableDiff.hx:499: characters 21-29
					continue;
				}
				#coopy/TableDiff.hx:501: characters 17-62
				$output->setCell($i, 0, $this->builder->links($unit, false));
			}
			#coopy/TableDiff.hx:503: characters 13-54
			$output->setCell(0, 0, $this->builder->marker("@:@"));
		}
		#coopy/TableDiff.hx:505: characters 9-23
		return $admin_w;
	}

	/**
	 * @param Table $output
	 * 
	 * @return void
	 */
	public function addSchema ($output) {
		#coopy/TableDiff.hx:547: lines 547-555
		if ($this->have_schema) {
			#coopy/TableDiff.hx:548: characters 13-42
			$at = $output->get_height();
			#coopy/TableDiff.hx:549: characters 13-54
			$output->resize($this->column_units->length + 1, $at + 1);
			#coopy/TableDiff.hx:550: characters 13-53
			$output->setCell(0, $at, $this->builder->marker("!"));
			#coopy/TableDiff.hx:551: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:551: characters 27-46
			$_g1 = $this->column_units->length;
			#coopy/TableDiff.hx:551: lines 551-553
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:551: characters 23-46
				$j = $_g++;
				#coopy/TableDiff.hx:552: characters 17-60
				$output->setCell($j + 1, $at, $this->v->toDatum(($this->schema->arr[$j] ?? null)));
			}
			#coopy/TableDiff.hx:554: characters 13-37
			$this->schema_diff_found = true;
		}
	}

	/**
	 * @param Table $t
	 * @param Table $meta
	 * 
	 * @return bool
	 */
	public function checkMeta ($t, $meta) {
		#coopy/TableDiff.hx:588: lines 588-590
		if ($meta === null) {
			#coopy/TableDiff.hx:589: characters 13-25
			return false;
		}
		#coopy/TableDiff.hx:591: lines 591-593
		if ($t === null) {
			#coopy/TableDiff.hx:592: characters 20-53
			if ($meta->get_width() === 1) {
				#coopy/TableDiff.hx:592: characters 38-52
				return $meta->get_height() === 1;
			} else {
				#coopy/TableDiff.hx:592: characters 20-53
				return false;
			}
		}
		#coopy/TableDiff.hx:594: characters 9-48
		if ($meta->get_width() !== ($t->get_width() + 1)) {
			#coopy/TableDiff.hx:594: characters 36-48
			return false;
		}
		#coopy/TableDiff.hx:595: characters 9-56
		if (($meta->get_width() === 0) || ($meta->get_height() === 0)) {
			#coopy/TableDiff.hx:595: characters 44-56
			return false;
		}
		#coopy/TableDiff.hx:596: characters 9-20
		return true;
	}

	/**
	 * @param View $v
	 * @param bool $have_ll
	 * @param mixed $ll
	 * @param bool $have_rr
	 * @param mixed $rr
	 * @param bool $have_pp
	 * @param mixed $pp
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed[]|\Array_hx
	 */
	public function checkNesting ($v, $have_ll, $ll, $have_rr, $rr, $have_pp, $pp, $x, $y) {
		#coopy/TableDiff.hx:717: characters 9-31
		$all_tables = true;
		#coopy/TableDiff.hx:718: characters 9-62
		if ($have_ll) {
			#coopy/TableDiff.hx:718: characters 49-62
			$all_tables = $all_tables && $v->isTable($ll);
		}
		#coopy/TableDiff.hx:719: characters 9-62
		if ($have_rr) {
			#coopy/TableDiff.hx:719: characters 49-62
			$all_tables = $all_tables && $v->isTable($rr);
		}
		#coopy/TableDiff.hx:720: characters 9-62
		if ($have_pp) {
			#coopy/TableDiff.hx:720: characters 49-62
			$all_tables = $all_tables && $v->isTable($pp);
		}
		#coopy/TableDiff.hx:721: characters 9-43
		if (!$all_tables) {
			#coopy/TableDiff.hx:721: characters 26-43
			return \Array_hx::wrap([
				$ll,
				$rr,
				$pp,
			]);
		}
		#coopy/TableDiff.hx:723: characters 9-37
		$ll_table = null;
		#coopy/TableDiff.hx:724: characters 9-37
		$rr_table = null;
		#coopy/TableDiff.hx:725: characters 9-37
		$pp_table = null;
		#coopy/TableDiff.hx:726: characters 9-47
		if ($have_ll) {
			#coopy/TableDiff.hx:726: characters 22-47
			$ll_table = $v->getTable($ll);
		}
		#coopy/TableDiff.hx:727: characters 9-47
		if ($have_rr) {
			#coopy/TableDiff.hx:727: characters 22-47
			$rr_table = $v->getTable($rr);
		}
		#coopy/TableDiff.hx:728: characters 9-47
		if ($have_pp) {
			#coopy/TableDiff.hx:728: characters 22-47
			$pp_table = $v->getTable($pp);
		}
		#coopy/TableDiff.hx:729: characters 9-29
		$compare = false;
		#coopy/TableDiff.hx:730: characters 9-47
		$comp = new TableComparisonState();
		#coopy/TableDiff.hx:731: characters 9-26
		$comp->a = $ll_table;
		#coopy/TableDiff.hx:732: characters 9-26
		$comp->b = $rr_table;
		#coopy/TableDiff.hx:733: characters 9-26
		$comp->p = $pp_table;
		#coopy/TableDiff.hx:734: characters 9-35
		$comp->compare_flags = $this->flags;
		#coopy/TableDiff.hx:735: characters 9-23
		$comp->getMeta();
		#coopy/TableDiff.hx:736: characters 9-24
		$key = null;
		#coopy/TableDiff.hx:737: lines 737-739
		if ($comp->a_meta !== null) {
			#coopy/TableDiff.hx:738: characters 13-40
			$key = $comp->a_meta->getName();
		}
		#coopy/TableDiff.hx:740: lines 740-742
		if (($key === null) && ($comp->b_meta !== null)) {
			#coopy/TableDiff.hx:741: characters 13-40
			$key = $comp->b_meta->getName();
		}
		#coopy/TableDiff.hx:743: lines 743-745
		if ($key === null) {
			#coopy/TableDiff.hx:744: characters 13-30
			$key = ($x??'null') . "_" . ($y??'null');
		}
		#coopy/TableDiff.hx:746: lines 746-754
		if ($this->align->comp !== null) {
			#coopy/TableDiff.hx:747: lines 747-753
			if ($this->align->comp->children === null) {
				#coopy/TableDiff.hx:748: characters 17-78
				$this->align->comp->children = new StringMap();
				#coopy/TableDiff.hx:749: characters 17-61
				$this->align->comp->child_order = new \Array_hx();
				#coopy/TableDiff.hx:750: characters 17-31
				$compare = true;
			} else {
				#coopy/TableDiff.hx:752: characters 17-59
				$compare = !\array_key_exists($key, $this->align->comp->children->data);
			}
		}
		#coopy/TableDiff.hx:755: lines 755-763
		if ($compare) {
			#coopy/TableDiff.hx:756: characters 13-35
			$this->nesting_present = true;
			#coopy/TableDiff.hx:757: characters 13-46
			$this->align->comp->children->data[$key] = $comp;
			#coopy/TableDiff.hx:758: characters 13-45
			$_this = $this->align->comp->child_order;
			$_this->arr[$_this->length++] = $key;
			#coopy/TableDiff.hx:759: characters 13-45
			$ct = new CompareTable($comp);
			#coopy/TableDiff.hx:760: characters 13-23
			$ct->align();
		} else {
			#coopy/TableDiff.hx:762: characters 20-48
			$comp = ($this->align->comp->children->data[$key] ?? null);
		}
		#coopy/TableDiff.hx:765: characters 9-36
		$ll_out = null;
		#coopy/TableDiff.hx:766: characters 9-36
		$rr_out = null;
		#coopy/TableDiff.hx:767: characters 9-36
		$pp_out = null;
		#coopy/TableDiff.hx:768: lines 768-782
		if ($comp->alignment->isMarkedAsIdentical() || ($have_ll && !$have_rr) || ($have_rr && !$have_ll)) {
			#coopy/TableDiff.hx:769: characters 13-37
			$ll_out = "[" . ($key??'null') . "]";
			#coopy/TableDiff.hx:770: characters 13-28
			$rr_out = $ll_out;
			#coopy/TableDiff.hx:771: characters 13-28
			$pp_out = $ll_out;
		} else {
			#coopy/TableDiff.hx:773: lines 773-775
			if ($ll !== null) {
				#coopy/TableDiff.hx:774: characters 17-43
				$ll_out = "[a." . ($key??'null') . "]";
			}
			#coopy/TableDiff.hx:776: lines 776-778
			if ($rr !== null) {
				#coopy/TableDiff.hx:777: characters 17-43
				$rr_out = "[b." . ($key??'null') . "]";
			}
			#coopy/TableDiff.hx:779: lines 779-781
			if ($pp !== null) {
				#coopy/TableDiff.hx:780: characters 17-43
				$pp_out = "[p." . ($key??'null') . "]";
			}
		}
		#coopy/TableDiff.hx:783: characters 9-40
		return \Array_hx::wrap([
			$ll_out,
			$rr_out,
			$pp_out,
		]);
	}

	/**
	 * @param int $w
	 * @param int $h
	 * 
	 * @return void
	 */
	public function checkRcNumbers ($w, $h) {
		#coopy/TableDiff.hx:460: lines 460-469
		if (!$this->show_rc_numbers) {
			#coopy/TableDiff.hx:461: lines 461-468
			if ($this->flags->always_show_order) {
				#coopy/TableDiff.hx:462: characters 17-39
				$this->show_rc_numbers = true;
			} else if ($this->flags->ordered) {
				#coopy/TableDiff.hx:464: characters 17-57
				$this->show_rc_numbers = $this->isReordered($this->row_map, $h);
				#coopy/TableDiff.hx:465: lines 465-467
				if (!$this->show_rc_numbers) {
					#coopy/TableDiff.hx:466: characters 21-61
					$this->show_rc_numbers = $this->isReordered($this->col_map, $w);
				}
			}
		}
	}

	/**
	 * @param int[]|\Array_hx $active
	 * 
	 * @return int
	 */
	public function countActive ($active) {
		#coopy/TableDiff.hx:229: characters 9-20
		$ct = 0;
		#coopy/TableDiff.hx:230: characters 9-34
		$showed_dummy = false;
		#coopy/TableDiff.hx:231: characters 19-23
		$_g = 0;
		#coopy/TableDiff.hx:231: characters 23-36
		$_g1 = $active->length;
		#coopy/TableDiff.hx:231: lines 231-238
		while ($_g < $_g1) {
			#coopy/TableDiff.hx:231: characters 19-36
			$i = $_g++;
			#coopy/TableDiff.hx:232: characters 13-39
			$publish = ($active->arr[$i] ?? null) > 0;
			#coopy/TableDiff.hx:233: characters 13-38
			$dummy = ($active->arr[$i] ?? null) === 3;
			#coopy/TableDiff.hx:234: characters 13-46
			if ($dummy && $showed_dummy) {
				#coopy/TableDiff.hx:234: characters 38-46
				continue;
			}
			#coopy/TableDiff.hx:235: characters 13-35
			if (!$publish) {
				#coopy/TableDiff.hx:235: characters 27-35
				continue;
			}
			#coopy/TableDiff.hx:236: characters 13-33
			$showed_dummy = $dummy;
			#coopy/TableDiff.hx:237: characters 13-17
			++$ct;
		}
		#coopy/TableDiff.hx:239: characters 9-18
		return $ct;
	}

	/**
	 * @param Table $output
	 * @param int $admin_w
	 * 
	 * @return void
	 */
	public function elideColumns ($output, $admin_w) {
		#coopy/TableDiff.hx:509: lines 509-543
		if ($this->active_column !== null) {
			#coopy/TableDiff.hx:510: characters 13-42
			$all_active = true;
			#coopy/TableDiff.hx:511: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:511: characters 27-47
			$_g1 = $this->active_column->length;
			#coopy/TableDiff.hx:511: lines 511-516
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:511: characters 23-47
				$i = $_g++;
				#coopy/TableDiff.hx:512: lines 512-515
				if (($this->active_column->arr[$i] ?? null) === 0) {
					#coopy/TableDiff.hx:513: characters 21-39
					$all_active = false;
					#coopy/TableDiff.hx:514: characters 21-26
					break;
				}
			}
			#coopy/TableDiff.hx:517: lines 517-542
			if (!$all_active) {
				#coopy/TableDiff.hx:518: characters 17-58
				$fate = new \Array_hx();
				#coopy/TableDiff.hx:519: characters 27-31
				$_g = 0;
				#coopy/TableDiff.hx:519: characters 31-38
				$_g1 = $admin_w;
				#coopy/TableDiff.hx:519: lines 519-521
				while ($_g < $_g1) {
					#coopy/TableDiff.hx:519: characters 27-38
					$i = $_g++;
					#coopy/TableDiff.hx:520: characters 21-33
					$fate->arr[$fate->length++] = $i;
				}
				#coopy/TableDiff.hx:522: characters 17-40
				$at = $admin_w;
				#coopy/TableDiff.hx:523: characters 17-34
				$ct = 0;
				#coopy/TableDiff.hx:524: characters 17-58
				$dots = new \Array_hx();
				#coopy/TableDiff.hx:525: characters 27-31
				$_g = 0;
				#coopy/TableDiff.hx:525: characters 31-51
				$_g1 = $this->active_column->length;
				#coopy/TableDiff.hx:525: lines 525-535
				while ($_g < $_g1) {
					#coopy/TableDiff.hx:525: characters 27-51
					$i = $_g++;
					#coopy/TableDiff.hx:526: characters 21-60
					$off = ($this->active_column->arr[$i] ?? null) === 0;
					#coopy/TableDiff.hx:527: characters 26-42
					if ($off) {
						#coopy/TableDiff.hx:527: characters 32-38
						++$ct;
					} else {
						#coopy/TableDiff.hx:527: characters 41-42
						$ct = 0;
					}
					#coopy/TableDiff.hx:528: lines 528-534
					if ($off && ($ct > 1)) {
						#coopy/TableDiff.hx:529: characters 25-38
						$fate->arr[$fate->length++] = -1;
					} else {
						#coopy/TableDiff.hx:531: characters 25-47
						if ($off) {
							#coopy/TableDiff.hx:531: characters 34-47
							$dots->arr[$dots->length++] = $at;
						}
						#coopy/TableDiff.hx:532: characters 25-38
						$fate->arr[$fate->length++] = $at;
						#coopy/TableDiff.hx:533: characters 25-29
						++$at;
					}
				}
				#coopy/TableDiff.hx:536: characters 17-54
				$output->insertOrDeleteColumns($fate, $at);
				#coopy/TableDiff.hx:537: lines 537-541
				$_g = 0;
				while ($_g < $dots->length) {
					#coopy/TableDiff.hx:537: characters 22-23
					$d = ($dots->arr[$_g] ?? null);
					#coopy/TableDiff.hx:537: lines 537-541
					++$_g;
					#coopy/TableDiff.hx:538: characters 31-35
					$_g1 = 0;
					#coopy/TableDiff.hx:538: characters 35-48
					$_g2 = $output->get_height();
					#coopy/TableDiff.hx:538: lines 538-540
					while ($_g1 < $_g2) {
						#coopy/TableDiff.hx:538: characters 31-48
						$j = $_g1++;
						#coopy/TableDiff.hx:539: characters 25-66
						$output->setCell($d, $j, $this->builder->marker("..."));
					}
				}
			}
		}
	}

	/**
	 * @return TableComparisonState
	 */
	public function getComparisonState () {
		#coopy/TableDiff.hx:1166: characters 9-37
		if ($this->align === null) {
			#coopy/TableDiff.hx:1166: characters 26-37
			return null;
		}
		#coopy/TableDiff.hx:1167: characters 9-26
		return $this->align->comp;
	}

	/**
	 * @param Table $t
	 * 
	 * @return Table
	 */
	public function getMetaTable ($t) {
		#coopy/TableDiff.hx:600: lines 600-604
		if ($t === null) {
			#coopy/TableDiff.hx:601: characters 13-47
			$result = new SimpleTable(1, 1);
			#coopy/TableDiff.hx:602: characters 13-36
			$result->setCell(0, 0, "@");
			#coopy/TableDiff.hx:603: characters 13-26
			return $result;
		}
		#coopy/TableDiff.hx:605: characters 9-32
		$meta = $t->getMeta();
		#coopy/TableDiff.hx:606: characters 9-36
		if ($meta === null) {
			#coopy/TableDiff.hx:606: characters 25-36
			return null;
		}
		#coopy/TableDiff.hx:607: characters 9-30
		return $meta->asTable();
	}

	/**
	 * @param Table $t
	 * @param Table $t2
	 * @param string $root
	 * 
	 * @return string
	 */
	public function getSeparator ($t, $t2, $root) {
		#coopy/TableDiff.hx:110: characters 9-33
		$sep = $root;
		#coopy/TableDiff.hx:111: characters 9-31
		$w = $t->get_width();
		#coopy/TableDiff.hx:112: characters 9-32
		$h = $t->get_height();
		#coopy/TableDiff.hx:113: characters 9-43
		$view = $t->getCellView();
		#coopy/TableDiff.hx:114: characters 19-23
		$_g = 0;
		#coopy/TableDiff.hx:114: characters 23-24
		$_g1 = $h;
		#coopy/TableDiff.hx:114: lines 114-122
		while ($_g < $_g1) {
			#coopy/TableDiff.hx:114: characters 19-24
			$y = $_g++;
			#coopy/TableDiff.hx:115: characters 23-27
			$_g2 = 0;
			#coopy/TableDiff.hx:115: characters 27-28
			$_g3 = $w;
			#coopy/TableDiff.hx:115: lines 115-121
			while ($_g2 < $_g3) {
				#coopy/TableDiff.hx:115: characters 23-28
				$x = $_g2++;
				#coopy/TableDiff.hx:116: characters 17-66
				$txt = $view->toString($t->getCell($x, $y));
				#coopy/TableDiff.hx:117: characters 17-40
				if ($txt === null) {
					#coopy/TableDiff.hx:117: characters 32-40
					continue;
				}
				#coopy/TableDiff.hx:118: lines 118-120
				while (HxString::indexOf($txt, $sep) >= 0) {
					#coopy/TableDiff.hx:119: characters 21-36
					$sep = "-" . ($sep??'null');
				}
			}
		}
		#coopy/TableDiff.hx:123: lines 123-135
		if ($t2 !== null) {
			#coopy/TableDiff.hx:124: characters 13-25
			$w = $t2->get_width();
			#coopy/TableDiff.hx:125: characters 13-26
			$h = $t2->get_height();
			#coopy/TableDiff.hx:126: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:126: characters 27-28
			$_g1 = $h;
			#coopy/TableDiff.hx:126: lines 126-134
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:126: characters 23-28
				$y = $_g++;
				#coopy/TableDiff.hx:127: characters 27-31
				$_g2 = 0;
				#coopy/TableDiff.hx:127: characters 31-32
				$_g3 = $w;
				#coopy/TableDiff.hx:127: lines 127-133
				while ($_g2 < $_g3) {
					#coopy/TableDiff.hx:127: characters 27-32
					$x = $_g2++;
					#coopy/TableDiff.hx:128: characters 21-71
					$txt = $view->toString($t2->getCell($x, $y));
					#coopy/TableDiff.hx:129: characters 21-44
					if ($txt === null) {
						#coopy/TableDiff.hx:129: characters 36-44
						continue;
					}
					#coopy/TableDiff.hx:130: lines 130-132
					while (HxString::indexOf($txt, $sep) >= 0) {
						#coopy/TableDiff.hx:131: characters 25-40
						$sep = "-" . ($sep??'null');
					}
				}
			}
		}
		#coopy/TableDiff.hx:136: characters 9-19
		return $sep;
	}

	/**
	 *
	 * Get statistics of the diff - number of rows deleted, updated,
	 * etc.
	 *
	 * 
	 * @return DiffSummary
	 */
	public function getSummary () {
		#coopy/TableDiff.hx:1177: characters 9-36
		$ds = new DiffSummary();
		#coopy/TableDiff.hx:1178: characters 9-37
		$ds->row_deletes = $this->row_deletes;
		#coopy/TableDiff.hx:1179: characters 9-37
		$ds->row_inserts = $this->row_inserts;
		#coopy/TableDiff.hx:1180: characters 9-37
		$ds->row_updates = $this->row_updates;
		#coopy/TableDiff.hx:1181: characters 9-39
		$ds->row_reorders = $this->row_reorders;
		#coopy/TableDiff.hx:1182: characters 9-37
		$ds->col_deletes = $this->col_deletes;
		#coopy/TableDiff.hx:1183: characters 9-37
		$ds->col_inserts = $this->col_inserts;
		#coopy/TableDiff.hx:1184: characters 9-37
		$ds->col_updates = $this->col_updates;
		#coopy/TableDiff.hx:1185: characters 9-37
		$ds->col_renames = $this->col_renames;
		#coopy/TableDiff.hx:1186: characters 9-39
		$ds->col_reorders = $this->col_reorders;
		#coopy/TableDiff.hx:1187: characters 9-68
		$ds->row_count_initial_with_header = $this->align->getSource()->get_height();
		#coopy/TableDiff.hx:1188: characters 9-66
		$ds->row_count_final_with_header = $this->align->getTarget()->get_height();
		#coopy/TableDiff.hx:1189: characters 9-86
		$ds->row_count_initial = $this->align->getSource()->get_height() - $this->align->getSourceHeader() - 1;
		#coopy/TableDiff.hx:1190: characters 9-84
		$ds->row_count_final = $this->align->getTarget()->get_height() - $this->align->getTargetHeader() - 1;
		#coopy/TableDiff.hx:1191: characters 9-55
		$ds->col_count_initial = $this->align->getSource()->get_width();
		#coopy/TableDiff.hx:1192: characters 9-53
		$ds->col_count_final = $this->align->getTarget()->get_width();
		#coopy/TableDiff.hx:1193: lines 1193-1195
		$ds->different = ($this->row_deletes + $this->row_inserts + $this->row_updates + $this->row_reorders + $this->col_deletes + $this->col_inserts + $this->col_updates + $this->col_renames + $this->col_reorders) > 0;
		#coopy/TableDiff.hx:1196: characters 9-18
		return $ds;
	}

	/**
	 *
	 * @return true if a difference was found during call to `hilite()`
	 *
	 * 
	 * @return bool
	 */
	public function hasDifference () {
		#coopy/TableDiff.hx:1149: characters 9-26
		return $this->diff_found;
	}

	/**
	 *
	 * @return true if a schema difference was found during call to `hilite()`
	 *
	 * 
	 * @return bool
	 */
	public function hasSchemaDifference () {
		#coopy/TableDiff.hx:1158: characters 9-33
		return $this->schema_diff_found;
	}

	/**
	 *
	 * Generate a highlighter diff.
	 * @param output the table in which to place the diff - it can then
	 * be converted to html using `DiffRender`
	 * @return true on success
	 *
	 * 
	 * @param Table $output
	 * 
	 * @return bool
	 */
	public function hilite ($output) {
		#coopy/TableDiff.hx:980: characters 9-39
		$output = Coopy::tablify($output);
		#coopy/TableDiff.hx:981: characters 9-36
		return $this->hiliteSingle($output);
	}

	/**
	 * @param Table $output
	 * 
	 * @return bool
	 */
	public function hiliteSingle ($output) {
		#coopy/TableDiff.hx:985: characters 9-48
		if (!$output->isResizable()) {
			#coopy/TableDiff.hx:985: characters 36-48
			return false;
		}
		#coopy/TableDiff.hx:986: lines 986-992
		if ($this->builder === null) {
			#coopy/TableDiff.hx:987: lines 987-991
			if ($this->flags->allow_nested_cells) {
				#coopy/TableDiff.hx:988: characters 17-50
				$this->builder = new NestedCellBuilder();
			} else {
				#coopy/TableDiff.hx:990: characters 17-53
				$this->builder = new FlatCellBuilder($this->flags);
			}
		}
		#coopy/TableDiff.hx:993: characters 9-27
		$output->resize(0, 0);
		#coopy/TableDiff.hx:994: characters 9-23
		$output->clear();
		#coopy/TableDiff.hx:996: characters 9-16
		$this->reset();
		#coopy/TableDiff.hx:997: characters 9-22
		$this->setupTables();
		#coopy/TableDiff.hx:998: characters 9-23
		$this->setupColumns();
		#coopy/TableDiff.hx:999: characters 9-21
		$this->setupMoves();
		#coopy/TableDiff.hx:1000: characters 9-23
		$this->scanActivity();
		#coopy/TableDiff.hx:1001: characters 9-21
		$this->scanSchema();
		#coopy/TableDiff.hx:1002: characters 9-26
		$this->addSchema($output);
		#coopy/TableDiff.hx:1003: characters 9-26
		$this->addHeader($output);
		#coopy/TableDiff.hx:1004: characters 9-24
		$this->addMeta($output);
		#coopy/TableDiff.hx:1009: lines 1009-1010
		$outer_reps_needed = ($this->flags->show_unchanged && $this->flags->show_unchanged_columns ? 1 : 2);
		#coopy/TableDiff.hx:1016: characters 9-30
		$outer_reps_needed = 2;
		#coopy/TableDiff.hx:1019: characters 9-49
		$output_height = $output->get_height();
		#coopy/TableDiff.hx:1020: characters 9-54
		$output_height_init = $output->get_height();
		#coopy/TableDiff.hx:1022: characters 21-25
		$_g = 0;
		#coopy/TableDiff.hx:1022: characters 25-42
		$_g1 = $outer_reps_needed;
		#coopy/TableDiff.hx:1022: lines 1022-1109
		while ($_g < $_g1) {
			#coopy/TableDiff.hx:1022: characters 21-42
			$out = $_g++;
			#coopy/TableDiff.hx:1023: lines 1023-1031
			if ($out === 1) {
				#coopy/TableDiff.hx:1024: characters 17-33
				$this->refineActivity();
				#coopy/TableDiff.hx:1025: characters 17-77
				$rows = $this->countActive($this->active_row) + $output_height_init;
				#coopy/TableDiff.hx:1026: characters 17-42
				if ($this->top_line_done) {
					#coopy/TableDiff.hx:1026: characters 36-42
					--$rows;
				}
				#coopy/TableDiff.hx:1027: characters 17-51
				$output_height = $output_height_init;
				#coopy/TableDiff.hx:1028: lines 1028-1030
				if ($rows > $output->get_height()) {
					#coopy/TableDiff.hx:1029: characters 21-62
					$output->resize($this->column_units->length + 1, $rows);
				}
			}
			#coopy/TableDiff.hx:1033: characters 13-45
			$showed_dummy = false;
			#coopy/TableDiff.hx:1034: characters 13-30
			$l = -1;
			#coopy/TableDiff.hx:1035: characters 13-30
			$r = -1;
			#coopy/TableDiff.hx:1036: characters 23-27
			$_g2 = 0;
			#coopy/TableDiff.hx:1036: characters 27-43
			$_g3 = $this->row_units->length;
			#coopy/TableDiff.hx:1036: lines 1036-1108
			while ($_g2 < $_g3) {
				#coopy/TableDiff.hx:1036: characters 23-43
				$i = $_g2++;
				#coopy/TableDiff.hx:1037: characters 17-48
				$unit = ($this->row_units->arr[$i] ?? null);
				#coopy/TableDiff.hx:1038: characters 17-46
				$reordered = false;
				#coopy/TableDiff.hx:1040: lines 1040-1045
				if ($this->flags->ordered) {
					#coopy/TableDiff.hx:1041: lines 1041-1043
					if (\array_key_exists($i, $this->row_moves->data)) {
						#coopy/TableDiff.hx:1042: characters 25-41
						$reordered = true;
					}
					#coopy/TableDiff.hx:1044: characters 21-58
					if ($reordered) {
						#coopy/TableDiff.hx:1044: characters 36-58
						$this->show_rc_numbers = true;
					}
				}
				#coopy/TableDiff.hx:1047: characters 17-51
				if (($unit->r < 0) && ($unit->l < 0)) {
					#coopy/TableDiff.hx:1047: characters 43-51
					continue;
				}
				#coopy/TableDiff.hx:1049: characters 17-73
				if (($unit->r === 0) && ($unit->lp() <= 0) && $this->top_line_done) {
					#coopy/TableDiff.hx:1049: characters 65-73
					continue;
				}
				#coopy/TableDiff.hx:1051: characters 17-47
				$this->publish = $this->flags->show_unchanged;
				#coopy/TableDiff.hx:1052: characters 17-42
				$dummy = false;
				#coopy/TableDiff.hx:1053: lines 1053-1059
				if ($out === 1) {
					#coopy/TableDiff.hx:1054: characters 21-58
					$value = ($this->active_row->arr[$i] ?? null);
					#coopy/TableDiff.hx:1055: characters 21-53
					$this->publish = ($value !== null) && ($value > 0);
					#coopy/TableDiff.hx:1056: characters 44-52
					$dummy = ($value !== null) && ($value === 3);
					#coopy/TableDiff.hx:1057: characters 21-54
					if ($dummy && $showed_dummy) {
						#coopy/TableDiff.hx:1057: characters 46-54
						continue;
					}
					#coopy/TableDiff.hx:1058: characters 21-43
					if (!$this->publish) {
						#coopy/TableDiff.hx:1058: characters 35-43
						continue;
					}
				}
				#coopy/TableDiff.hx:1061: characters 17-49
				if (!$dummy) {
					#coopy/TableDiff.hx:1061: characters 29-49
					$showed_dummy = false;
				}
				#coopy/TableDiff.hx:1063: characters 17-46
				$at = $output_height;
				#coopy/TableDiff.hx:1064: lines 1064-1069
				if ($this->publish) {
					#coopy/TableDiff.hx:1065: characters 21-36
					++$output_height;
					#coopy/TableDiff.hx:1066: lines 1066-1068
					if ($output->get_height() < $output_height) {
						#coopy/TableDiff.hx:1067: characters 25-75
						$output->resize($this->column_units->length + 1, $output_height);
					}
				}
				#coopy/TableDiff.hx:1070: lines 1070-1076
				if ($dummy) {
					#coopy/TableDiff.hx:1071: characters 31-35
					$_g4 = 0;
					#coopy/TableDiff.hx:1071: characters 35-58
					$_g5 = $this->column_units->length + 1;
					#coopy/TableDiff.hx:1071: lines 1071-1073
					while ($_g4 < $_g5) {
						#coopy/TableDiff.hx:1071: characters 31-58
						$j = $_g4++;
						#coopy/TableDiff.hx:1072: characters 25-62
						$output->setCell($j, $at, $this->v->toDatum("..."));
					}
					#coopy/TableDiff.hx:1074: characters 21-40
					$showed_dummy = true;
					#coopy/TableDiff.hx:1075: characters 21-29
					continue;
				}
				#coopy/TableDiff.hx:1078: characters 17-38
				$this->have_addition = false;
				#coopy/TableDiff.hx:1079: characters 17-41
				$skip = false;
				#coopy/TableDiff.hx:1081: characters 17-25
				$this->act = "";
				#coopy/TableDiff.hx:1082: lines 1082-1085
				if ($reordered) {
					#coopy/TableDiff.hx:1083: characters 21-30
					$this->act = ":";
					#coopy/TableDiff.hx:1084: characters 21-47
					if ($out === 0) {
						#coopy/TableDiff.hx:1084: characters 33-47
						$this->row_reorders++;
					}
				}
				#coopy/TableDiff.hx:1087: lines 1087-1091
				if (($unit->p < 0) && ($unit->l < 0) && ($unit->r >= 0)) {
					#coopy/TableDiff.hx:1088: characters 21-51
					if (!$this->allow_insert) {
						#coopy/TableDiff.hx:1088: characters 40-51
						$skip = true;
					}
					#coopy/TableDiff.hx:1089: characters 21-32
					$this->act = "+++";
					#coopy/TableDiff.hx:1090: characters 21-55
					if (($out === 0) && !$skip) {
						#coopy/TableDiff.hx:1090: characters 42-55
						$this->row_inserts++;
					}
				}
				#coopy/TableDiff.hx:1092: lines 1092-1096
				if ((($unit->p >= 0) || !$this->has_parent) && ($unit->l >= 0) && ($unit->r < 0)) {
					#coopy/TableDiff.hx:1093: characters 21-51
					if (!$this->allow_delete) {
						#coopy/TableDiff.hx:1093: characters 40-51
						$skip = true;
					}
					#coopy/TableDiff.hx:1094: characters 21-32
					$this->act = "---";
					#coopy/TableDiff.hx:1095: characters 21-55
					if (($out === 0) && !$skip) {
						#coopy/TableDiff.hx:1095: characters 42-55
						$this->row_deletes++;
					}
				}
				#coopy/TableDiff.hx:1098: lines 1098-1105
				if ($skip) {
					#coopy/TableDiff.hx:1099: lines 1099-1103
					if (!$this->publish) {
						#coopy/TableDiff.hx:1100: lines 1100-1102
						if ($this->active_row !== null) {
							#coopy/TableDiff.hx:1101: characters 29-47
							$this->active_row->offsetSet($i, -3);
						}
					}
					#coopy/TableDiff.hx:1104: characters 21-29
					continue;
				}
				#coopy/TableDiff.hx:1107: characters 17-46
				$this->scanRow($unit, $output, $at, $i, $out);
			}
		}
		#coopy/TableDiff.hx:1111: characters 9-51
		$this->checkRcNumbers($output->get_width(), $output->get_height());
		#coopy/TableDiff.hx:1113: characters 9-50
		$admin_w = $this->addRcNumbers($output);
		#coopy/TableDiff.hx:1114: characters 9-60
		if (!$this->preserve_columns) {
			#coopy/TableDiff.hx:1114: characters 32-60
			$this->elideColumns($output, $admin_w);
		}
		#coopy/TableDiff.hx:1116: characters 9-20
		return true;
	}

	/**
	 * @param Tables $output
	 * 
	 * @return bool
	 */
	public function hiliteWithNesting ($output) {
		#coopy/TableDiff.hx:1122: characters 9-39
		$base = $output->add("base");
		#coopy/TableDiff.hx:1123: characters 9-41
		$result = $this->hiliteSingle($base);
		#coopy/TableDiff.hx:1124: characters 9-34
		if (!$result) {
			#coopy/TableDiff.hx:1124: characters 22-34
			return false;
		}
		#coopy/TableDiff.hx:1125: characters 9-42
		if ($this->align->comp === null) {
			#coopy/TableDiff.hx:1125: characters 31-42
			return true;
		}
		#coopy/TableDiff.hx:1126: characters 9-44
		$order = $this->align->comp->child_order;
		#coopy/TableDiff.hx:1127: characters 9-37
		if ($order === null) {
			#coopy/TableDiff.hx:1127: characters 26-37
			return true;
		}
		#coopy/TableDiff.hx:1128: characters 9-33
		$output->alignment = $this->align;
		#coopy/TableDiff.hx:1129: lines 1129-1139
		$_g = 0;
		while ($_g < $order->length) {
			#coopy/TableDiff.hx:1129: characters 14-18
			$name = ($order->arr[$_g] ?? null);
			#coopy/TableDiff.hx:1129: lines 1129-1139
			++$_g;
			#coopy/TableDiff.hx:1130: characters 13-55
			$child = ($this->align->comp->children->data[$name] ?? null);
			#coopy/TableDiff.hx:1131: characters 13-45
			$alignment = $child->alignment;
			#coopy/TableDiff.hx:1132: lines 1132-1135
			if ($alignment->isMarkedAsIdentical()) {
				#coopy/TableDiff.hx:1133: characters 17-51
				$this->align->comp->children->data[$name] = null;
				#coopy/TableDiff.hx:1134: characters 17-25
				continue;
			}
			#coopy/TableDiff.hx:1136: characters 13-53
			$td = new TableDiff($alignment, $this->flags);
			#coopy/TableDiff.hx:1137: characters 13-49
			$child_output = $output->add($name);
			#coopy/TableDiff.hx:1138: characters 32-61
			$result = $result && $td->hiliteSingle($child_output);
		}
		#coopy/TableDiff.hx:1140: characters 9-22
		return $result;
	}

	/**
	 * @param View $v
	 * @param mixed $aa
	 * @param mixed $bb
	 * 
	 * @return bool
	 */
	public function isEqual ($v, $aa, $bb) {
		#coopy/TableDiff.hx:695: lines 695-705
		if ($this->flags->ignore_epsilon > 0) {
			#coopy/TableDiff.hx:696: characters 13-41
			$fa = \Std::parseFloat($aa);
			#coopy/TableDiff.hx:697: lines 697-704
			if (!\is_nan($fa)) {
				#coopy/TableDiff.hx:698: characters 17-45
				$fb = \Std::parseFloat($bb);
				#coopy/TableDiff.hx:699: lines 699-703
				if (!\is_nan($fb)) {
					#coopy/TableDiff.hx:700: lines 700-702
					if (\abs($fa - $fb) < $this->flags->ignore_epsilon) {
						#coopy/TableDiff.hx:701: characters 25-36
						return true;
					}
				}
			}
		}
		#coopy/TableDiff.hx:706: lines 706-708
		if ($this->flags->ignore_whitespace || $this->flags->ignore_case) {
			#coopy/TableDiff.hx:707: characters 13-66
			return $this->normalizeString($v, $aa) === $this->normalizeString($v, $bb);
		}
		#coopy/TableDiff.hx:709: characters 9-31
		return $v->equals($aa, $bb);
	}

	/**
	 * @return bool
	 */
	public function isNested () {
		#coopy/TableDiff.hx:1162: characters 9-31
		return $this->nesting_present;
	}

	/**
	 * @param IntMap $m
	 * @param int $ct
	 * 
	 * @return bool
	 */
	public function isReordered ($m, $ct) {
		#coopy/TableDiff.hx:140: characters 9-38
		$reordered = false;
		#coopy/TableDiff.hx:141: characters 9-26
		$l = -1;
		#coopy/TableDiff.hx:142: characters 9-26
		$r = -1;
		#coopy/TableDiff.hx:143: characters 19-23
		$_g = 0;
		#coopy/TableDiff.hx:143: characters 23-25
		$_g1 = $ct;
		#coopy/TableDiff.hx:143: lines 143-160
		while ($_g < $_g1) {
			#coopy/TableDiff.hx:143: characters 19-25
			$i = $_g++;
			#coopy/TableDiff.hx:144: characters 13-40
			$unit = ($m->data[$i] ?? null);
			#coopy/TableDiff.hx:145: characters 13-37
			if ($unit === null) {
				#coopy/TableDiff.hx:145: characters 29-37
				continue;
			}
			#coopy/TableDiff.hx:146: lines 146-152
			if ($unit->l >= 0) {
				#coopy/TableDiff.hx:147: lines 147-150
				if ($unit->l < $l) {
					#coopy/TableDiff.hx:148: characters 21-37
					$reordered = true;
					#coopy/TableDiff.hx:149: characters 21-26
					break;
				}
				#coopy/TableDiff.hx:151: characters 17-27
				$l = $unit->l;
			}
			#coopy/TableDiff.hx:153: lines 153-159
			if ($unit->r >= 0) {
				#coopy/TableDiff.hx:154: lines 154-157
				if ($unit->r < $r) {
					#coopy/TableDiff.hx:155: characters 21-37
					$reordered = true;
					#coopy/TableDiff.hx:156: characters 21-26
					break;
				}
				#coopy/TableDiff.hx:158: characters 17-27
				$r = $unit->r;
			}
		}
		#coopy/TableDiff.hx:161: characters 9-25
		return $reordered;
	}

	/**
	 * @param View $v
	 * @param mixed $str
	 * 
	 * @return string
	 */
	public function normalizeString ($v, $str) {
		#coopy/TableDiff.hx:678: characters 9-34
		if ($str === null) {
			#coopy/TableDiff.hx:678: characters 24-34
			return $str;
		}
		#coopy/TableDiff.hx:679: lines 679-681
		if (!($this->flags->ignore_whitespace || $this->flags->ignore_case)) {
			#coopy/TableDiff.hx:680: characters 13-23
			return $str;
		}
		#coopy/TableDiff.hx:682: characters 9-35
		$txt = $v->toString($str);
		#coopy/TableDiff.hx:683: lines 683-685
		if ($this->flags->ignore_whitespace) {
			#coopy/TableDiff.hx:684: characters 13-40
			$txt = \trim($txt);
		}
		#coopy/TableDiff.hx:686: lines 686-688
		if ($this->flags->ignore_case) {
			#coopy/TableDiff.hx:687: characters 13-36
			$txt = \mb_strtolower($txt);
		}
		#coopy/TableDiff.hx:689: characters 9-19
		return $txt;
	}

	/**
	 * @return void
	 */
	public function refineActivity () {
		#coopy/TableDiff.hx:665: characters 9-68
		$this->spreadContext($this->row_units, $this->flags->unchanged_context, $this->active_row);
		#coopy/TableDiff.hx:666: lines 666-667
		$this->spreadContext($this->column_units, $this->flags->unchanged_column_context, $this->active_column);
		#coopy/TableDiff.hx:668: lines 668-674
		if ($this->active_column !== null) {
			#coopy/TableDiff.hx:669: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:669: characters 27-46
			$_g1 = $this->column_units->length;
			#coopy/TableDiff.hx:669: lines 669-673
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:669: characters 23-46
				$i = $_g++;
				#coopy/TableDiff.hx:670: lines 670-672
				if (($this->active_column->arr[$i] ?? null) === 3) {
					#coopy/TableDiff.hx:671: characters 21-41
					$this->active_column->offsetSet($i, 0);
				}
			}
		}
	}

	/**
	 * @return void
	 */
	public function reset () {
		#coopy/TableDiff.hx:243: characters 9-27
		$this->has_parent = false;
		#coopy/TableDiff.hx:244: characters 9-46
		$this->rp_header = $this->ra_header = $this->rb_header = 0;
		#coopy/TableDiff.hx:245: characters 9-41
		$this->is_index_p = new IntMap();
		#coopy/TableDiff.hx:246: characters 9-41
		$this->is_index_a = new IntMap();
		#coopy/TableDiff.hx:247: characters 9-41
		$this->is_index_b = new IntMap();
		#coopy/TableDiff.hx:248: characters 9-38
		$this->row_map = new IntMap();
		#coopy/TableDiff.hx:249: characters 9-38
		$this->col_map = new IntMap();
		#coopy/TableDiff.hx:250: characters 9-32
		$this->show_rc_numbers = false;
		#coopy/TableDiff.hx:251: characters 9-25
		$this->row_moves = null;
		#coopy/TableDiff.hx:252: characters 9-25
		$this->col_moves = null;
		#coopy/TableDiff.hx:253: characters 9-73
		$this->allow_insert = $this->allow_delete = $this->allow_update = $this->allow_column = true;
		#coopy/TableDiff.hx:254: characters 9-17
		$this->sep = "";
		#coopy/TableDiff.hx:255: characters 9-26
		$this->conflict_sep = "";
		#coopy/TableDiff.hx:256: characters 9-30
		$this->top_line_done = false;
		#coopy/TableDiff.hx:257: characters 9-27
		$this->diff_found = false;
		#coopy/TableDiff.hx:258: characters 9-34
		$this->schema_diff_found = false;
		#coopy/TableDiff.hx:259: characters 9-24
		$this->row_deletes = 0;
		#coopy/TableDiff.hx:260: characters 9-24
		$this->row_inserts = 0;
		#coopy/TableDiff.hx:261: characters 9-24
		$this->row_updates = 0;
		#coopy/TableDiff.hx:262: characters 9-25
		$this->row_reorders = 0;
		#coopy/TableDiff.hx:263: characters 9-24
		$this->col_deletes = 0;
		#coopy/TableDiff.hx:264: characters 9-24
		$this->col_inserts = 0;
		#coopy/TableDiff.hx:265: characters 9-24
		$this->col_updates = 0;
		#coopy/TableDiff.hx:266: characters 9-24
		$this->col_renames = 0;
		#coopy/TableDiff.hx:267: characters 9-25
		$this->col_reorders = 0;
		#coopy/TableDiff.hx:268: characters 9-51
		$this->column_units_updated = new IntMap();
	}

	/**
	 * @return void
	 */
	public function scanActivity () {
		#coopy/TableDiff.hx:329: characters 9-38
		$this->active_row = new \Array_hx();
		#coopy/TableDiff.hx:330: characters 9-29
		$this->active_column = null;
		#coopy/TableDiff.hx:331: lines 331-336
		if (!$this->flags->show_unchanged) {
			#coopy/TableDiff.hx:332: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:332: characters 27-43
			$_g1 = $this->row_units->length;
			#coopy/TableDiff.hx:332: lines 332-335
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:332: characters 23-43
				$i = $_g++;
				#coopy/TableDiff.hx:334: characters 17-53
				$this->active_row->offsetSet($this->row_units->length - 1 - $i, 0);
			}
		}
		#coopy/TableDiff.hx:338: lines 338-348
		if (!$this->flags->show_unchanged_columns) {
			#coopy/TableDiff.hx:339: characters 13-45
			$this->active_column = new \Array_hx();
			#coopy/TableDiff.hx:340: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:340: characters 27-46
			$_g1 = $this->column_units->length;
			#coopy/TableDiff.hx:340: lines 340-347
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:340: characters 23-46
				$i = $_g++;
				#coopy/TableDiff.hx:341: characters 17-33
				$v = 0;
				#coopy/TableDiff.hx:342: characters 17-51
				$unit = ($this->column_units->arr[$i] ?? null);
				#coopy/TableDiff.hx:343: characters 17-63
				if (($unit->l >= 0) && ($this->is_index_a->data[$unit->l] ?? null)) {
					#coopy/TableDiff.hx:343: characters 58-63
					$v = 1;
				}
				#coopy/TableDiff.hx:344: characters 17-63
				if (($unit->r >= 0) && ($this->is_index_b->data[$unit->r] ?? null)) {
					#coopy/TableDiff.hx:344: characters 58-63
					$v = 1;
				}
				#coopy/TableDiff.hx:345: characters 17-63
				if (($unit->p >= 0) && ($this->is_index_p->data[$unit->p] ?? null)) {
					#coopy/TableDiff.hx:345: characters 58-63
					$v = 1;
				}
				#coopy/TableDiff.hx:346: characters 17-37
				$this->active_column->offsetSet($i, $v);
			}
		}
	}

	/**
	 *
	 * Generate diff for given l/r/p row unit #i.
	 *
	 * Relies on state of:
	 *   column_units, tables a/b/p, flags, view v,
	 *   allow_update, active_column, sep, conflict_sep, publish, active_row
	 *
	 * @param unit the index of the row to compare in each table
	 * @param output where to store the diff
	 * @param at the current row location in the output table
	 * @param i the index of the row unit
	 *
	 * 
	 * @param Unit $unit
	 * @param Table $output
	 * @param int $at
	 * @param int $i
	 * @param int $out
	 * 
	 * @return void
	 */
	public function scanRow ($unit, $output, $at, $i, $out) {
		#coopy/TableDiff.hx:801: characters 9-39
		$row_update = false;
		#coopy/TableDiff.hx:802: characters 19-23
		$_g = 0;
		#coopy/TableDiff.hx:802: characters 23-42
		$_g1 = $this->column_units->length;
		#coopy/TableDiff.hx:802: lines 802-954
		while ($_g < $_g1) {
			#coopy/TableDiff.hx:802: characters 19-42
			$j = $_g++;
			#coopy/TableDiff.hx:803: characters 13-48
			$cunit = ($this->column_units->arr[$j] ?? null);
			#coopy/TableDiff.hx:804: characters 13-37
			$pp = null;
			#coopy/TableDiff.hx:805: characters 13-37
			$ll = null;
			#coopy/TableDiff.hx:806: characters 13-37
			$rr = null;
			#coopy/TableDiff.hx:807: characters 13-37
			$dd = null;
			#coopy/TableDiff.hx:808: characters 13-40
			$dd_to = null;
			#coopy/TableDiff.hx:809: characters 13-43
			$have_dd_to = false;
			#coopy/TableDiff.hx:810: characters 13-44
			$dd_to_alt = null;
			#coopy/TableDiff.hx:811: characters 13-47
			$have_dd_to_alt = false;
			#coopy/TableDiff.hx:812: characters 13-40
			$have_pp = false;
			#coopy/TableDiff.hx:813: characters 13-40
			$have_ll = false;
			#coopy/TableDiff.hx:814: characters 13-40
			$have_rr = false;
			#coopy/TableDiff.hx:815: lines 815-818
			if (($cunit->p >= 0) && ($unit->p >= 0)) {
				#coopy/TableDiff.hx:816: characters 17-47
				$pp = $this->p->getCell($cunit->p, $unit->p);
				#coopy/TableDiff.hx:817: characters 17-31
				$have_pp = true;
			}
			#coopy/TableDiff.hx:819: lines 819-822
			if (($cunit->l >= 0) && ($unit->l >= 0)) {
				#coopy/TableDiff.hx:820: characters 17-47
				$ll = $this->a->getCell($cunit->l, $unit->l);
				#coopy/TableDiff.hx:821: characters 17-31
				$have_ll = true;
			}
			#coopy/TableDiff.hx:823: lines 823-835
			if (($cunit->r >= 0) && ($unit->r >= 0)) {
				#coopy/TableDiff.hx:824: characters 17-47
				$rr = $this->b->getCell($cunit->r, $unit->r);
				#coopy/TableDiff.hx:825: characters 17-31
				$have_rr = true;
				#coopy/TableDiff.hx:826: lines 826-834
				if ((($have_pp ? $cunit->p : $cunit->l)) < 0) {
					#coopy/TableDiff.hx:827: lines 827-833
					if ($rr !== null) {
						#coopy/TableDiff.hx:828: lines 828-832
						if ($this->v->toString($rr) !== "") {
							#coopy/TableDiff.hx:829: lines 829-831
							if ($this->allow_column) {
								#coopy/TableDiff.hx:830: characters 33-53
								$this->have_addition = true;
							}
						}
					}
				}
			}
			#coopy/TableDiff.hx:840: lines 840-849
			if ($this->nested) {
				#coopy/TableDiff.hx:841: lines 841-845
				$ndiff = $this->checkNesting($this->v, $have_ll, $ll, $have_rr, $rr, $have_pp, $pp, $i, $j);
				#coopy/TableDiff.hx:846: characters 17-30
				$ll = ($ndiff->arr[0] ?? null);
				#coopy/TableDiff.hx:847: characters 17-30
				$rr = ($ndiff->arr[1] ?? null);
				#coopy/TableDiff.hx:848: characters 17-30
				$pp = ($ndiff->arr[2] ?? null);
			}
			#coopy/TableDiff.hx:852: lines 852-888
			if ($have_pp) {
				#coopy/TableDiff.hx:853: lines 853-872
				if (!$have_rr) {
					#coopy/TableDiff.hx:854: characters 21-28
					$dd = $pp;
				} else if ($this->isEqual($this->v, $pp, $rr)) {
					#coopy/TableDiff.hx:858: characters 25-32
					$dd = $ll;
				} else {
					#coopy/TableDiff.hx:861: characters 25-32
					$dd = $pp;
					#coopy/TableDiff.hx:862: characters 25-35
					$dd_to = $rr;
					#coopy/TableDiff.hx:863: characters 25-42
					$have_dd_to = true;
					#coopy/TableDiff.hx:865: lines 865-870
					if (!$this->isEqual($this->v, $pp, $ll)) {
						#coopy/TableDiff.hx:866: lines 866-869
						if (!$this->isEqual($this->v, $pp, $rr)) {
							#coopy/TableDiff.hx:867: characters 33-47
							$dd_to_alt = $ll;
							#coopy/TableDiff.hx:868: characters 33-54
							$have_dd_to_alt = true;
						}
					}
				}
			} else if ($have_ll) {
				#coopy/TableDiff.hx:874: lines 874-885
				if (!$have_rr) {
					#coopy/TableDiff.hx:875: characters 21-28
					$dd = $ll;
				} else if ($this->isEqual($this->v, $ll, $rr)) {
					#coopy/TableDiff.hx:878: characters 25-32
					$dd = $ll;
				} else {
					#coopy/TableDiff.hx:881: characters 25-32
					$dd = $ll;
					#coopy/TableDiff.hx:882: characters 25-35
					$dd_to = $rr;
					#coopy/TableDiff.hx:883: characters 25-42
					$have_dd_to = true;
				}
			} else {
				#coopy/TableDiff.hx:887: characters 17-24
				$dd = $rr;
			}
			#coopy/TableDiff.hx:890: characters 13-37
			$cell = $dd;
			#coopy/TableDiff.hx:891: lines 891-938
			if ($have_dd_to && ((($dd !== null) && $this->allow_update) || $this->allow_column)) {
				#coopy/TableDiff.hx:892: lines 892-895
				if (!$row_update) {
					#coopy/TableDiff.hx:893: characters 21-46
					if ($out === 0) {
						#coopy/TableDiff.hx:893: characters 33-46
						$this->row_updates++;
					}
					#coopy/TableDiff.hx:894: characters 21-38
					$row_update = true;
				}
				#coopy/TableDiff.hx:896: lines 896-898
				if ($this->active_column !== null) {
					#coopy/TableDiff.hx:897: characters 21-41
					$this->active_column->offsetSet($j, 1);
				}
				#coopy/TableDiff.hx:900: lines 900-909
				if ($this->sep === "") {
					#coopy/TableDiff.hx:901: lines 901-908
					if ($this->builder->needSeparator()) {
						#coopy/TableDiff.hx:904: characters 25-53
						$this->sep = $this->getSeparator($this->a, $this->b, "->");
						#coopy/TableDiff.hx:905: characters 25-50
						$this->builder->setSeparator($this->sep);
					} else {
						#coopy/TableDiff.hx:907: characters 25-35
						$this->sep = "->";
					}
				}
				#coopy/TableDiff.hx:910: characters 17-48
				$is_conflict = false;
				#coopy/TableDiff.hx:911: lines 911-915
				if ($have_dd_to_alt) {
					#coopy/TableDiff.hx:912: lines 912-914
					if (!$this->isEqual($this->v, $dd_to, $dd_to_alt)) {
						#coopy/TableDiff.hx:913: characters 25-43
						$is_conflict = true;
					}
				}
				#coopy/TableDiff.hx:916: lines 916-933
				if (!$is_conflict) {
					#coopy/TableDiff.hx:917: characters 21-52
					$cell = $this->builder->update($dd, $dd_to);
					#coopy/TableDiff.hx:918: lines 918-920
					if (mb_strlen($this->sep) > mb_strlen($this->act)) {
						#coopy/TableDiff.hx:919: characters 25-34
						$this->act = $this->sep;
					}
				} else {
					#coopy/TableDiff.hx:922: lines 922-930
					if ($this->conflict_sep === "") {
						#coopy/TableDiff.hx:923: lines 923-929
						if ($this->builder->needSeparator()) {
							#coopy/TableDiff.hx:925: characters 29-71
							$this->conflict_sep = ($this->getSeparator($this->p, $this->a, "!")??'null') . ($this->sep??'null');
							#coopy/TableDiff.hx:926: characters 29-71
							$this->builder->setConflictSeparator($this->conflict_sep);
						} else {
							#coopy/TableDiff.hx:928: characters 29-49
							$this->conflict_sep = "!->";
						}
					}
					#coopy/TableDiff.hx:931: characters 21-64
					$cell = $this->builder->conflict($dd, $dd_to_alt, $dd_to);
					#coopy/TableDiff.hx:932: characters 21-39
					$this->act = $this->conflict_sep;
				}
				#coopy/TableDiff.hx:934: lines 934-937
				if (!\array_key_exists($j, $this->column_units_updated->data)) {
					#coopy/TableDiff.hx:935: characters 21-53
					$this->column_units_updated->data[$j] = true;
					#coopy/TableDiff.hx:936: characters 21-34
					$this->col_updates++;
				}
			}
			#coopy/TableDiff.hx:939: lines 939-941
			if (($this->act === "") && $this->have_addition) {
				#coopy/TableDiff.hx:940: characters 17-26
				$this->act = "+";
			}
			#coopy/TableDiff.hx:942: lines 942-948
			if ($this->act === "+++") {
				#coopy/TableDiff.hx:943: lines 943-947
				if ($have_rr) {
					#coopy/TableDiff.hx:944: lines 944-946
					if ($this->active_column !== null) {
						#coopy/TableDiff.hx:945: characters 25-45
						$this->active_column->offsetSet($j, 1);
					}
				}
			}
			#coopy/TableDiff.hx:949: lines 949-953
			if ($this->publish) {
				#coopy/TableDiff.hx:950: lines 950-952
				if (($this->active_column === null) || (($this->active_column->arr[$j] ?? null) > 0)) {
					#coopy/TableDiff.hx:951: characters 21-48
					$output->setCell($j + 1, $at, $cell);
				}
			}
		}
		#coopy/TableDiff.hx:956: lines 956-959
		if ($this->publish) {
			#coopy/TableDiff.hx:957: characters 13-53
			$output->setCell(0, $at, $this->builder->marker($this->act));
			#coopy/TableDiff.hx:958: characters 13-33
			$this->row_map->data[$at] = $unit;
		}
		#coopy/TableDiff.hx:961: lines 961-968
		if ($this->act !== "") {
			#coopy/TableDiff.hx:962: characters 13-30
			$this->diff_found = true;
			#coopy/TableDiff.hx:963: lines 963-967
			if (!$this->publish) {
				#coopy/TableDiff.hx:964: lines 964-966
				if ($this->active_row !== null) {
					#coopy/TableDiff.hx:965: characters 21-38
					$this->active_row->offsetSet($i, 1);
				}
			}
		}
	}

	/**
	 * @return void
	 */
	public function scanSchema () {
		#coopy/TableDiff.hx:393: characters 9-37
		$this->schema = new \Array_hx();
		#coopy/TableDiff.hx:394: characters 9-28
		$this->have_schema = false;
		#coopy/TableDiff.hx:395: characters 19-23
		$_g = 0;
		#coopy/TableDiff.hx:395: characters 23-42
		$_g1 = $this->column_units->length;
		#coopy/TableDiff.hx:395: lines 395-455
		while ($_g < $_g1) {
			#coopy/TableDiff.hx:395: characters 19-42
			$j = $_g++;
			#coopy/TableDiff.hx:396: characters 13-48
			$cunit = ($this->column_units->arr[$j] ?? null);
			#coopy/TableDiff.hx:397: characters 13-42
			$reordered = false;
			#coopy/TableDiff.hx:399: lines 399-404
			if ($this->flags->ordered) {
				#coopy/TableDiff.hx:400: lines 400-402
				if (\array_key_exists($j, $this->col_moves->data)) {
					#coopy/TableDiff.hx:401: characters 21-37
					$reordered = true;
				}
				#coopy/TableDiff.hx:403: characters 17-54
				if ($reordered) {
					#coopy/TableDiff.hx:403: characters 32-54
					$this->show_rc_numbers = true;
				}
			}
			#coopy/TableDiff.hx:406: characters 13-35
			$act = "";
			#coopy/TableDiff.hx:407: lines 407-418
			if (($cunit->r >= 0) && ($cunit->lp() === -1)) {
				#coopy/TableDiff.hx:408: characters 17-35
				$this->have_schema = true;
				#coopy/TableDiff.hx:409: characters 17-28
				$act = "+++";
				#coopy/TableDiff.hx:410: lines 410-414
				if ($this->active_column !== null) {
					#coopy/TableDiff.hx:411: lines 411-413
					if ($this->allow_column) {
						#coopy/TableDiff.hx:412: characters 25-45
						$this->active_column->offsetSet($j, 1);
					}
				}
				#coopy/TableDiff.hx:415: lines 415-417
				if ($this->allow_column) {
					#coopy/TableDiff.hx:416: characters 21-34
					$this->col_inserts++;
				}
			}
			#coopy/TableDiff.hx:419: lines 419-430
			if (($cunit->r < 0) && ($cunit->lp() >= 0)) {
				#coopy/TableDiff.hx:420: characters 17-35
				$this->have_schema = true;
				#coopy/TableDiff.hx:421: characters 17-28
				$act = "---";
				#coopy/TableDiff.hx:422: lines 422-426
				if ($this->active_column !== null) {
					#coopy/TableDiff.hx:423: lines 423-425
					if ($this->allow_column) {
						#coopy/TableDiff.hx:424: characters 25-45
						$this->active_column->offsetSet($j, 1);
					}
				}
				#coopy/TableDiff.hx:427: lines 427-429
				if ($this->allow_column) {
					#coopy/TableDiff.hx:428: characters 21-34
					$this->col_deletes++;
				}
			}
			#coopy/TableDiff.hx:431: lines 431-446
			if (($cunit->r >= 0) && ($cunit->lp() >= 0)) {
				#coopy/TableDiff.hx:432: lines 432-445
				if (($this->p->get_height() >= $this->rp_header) && ($this->b->get_height() >= $this->rb_header)) {
					#coopy/TableDiff.hx:433: characters 21-72
					$pp = $this->p->getCell($cunit->lp(), $this->rp_header);
					#coopy/TableDiff.hx:434: characters 21-69
					$bb = $this->b->getCell($cunit->r, $this->rb_header);
					#coopy/TableDiff.hx:435: lines 435-444
					if (!$this->isEqual($this->v, $pp, $bb)) {
						#coopy/TableDiff.hx:436: characters 25-43
						$this->have_schema = true;
						#coopy/TableDiff.hx:437: characters 25-34
						$act = "(";
						#coopy/TableDiff.hx:438: characters 25-46
						$act = ($act??'null') . ($this->v->toString($pp)??'null');
						#coopy/TableDiff.hx:439: characters 25-35
						$act = ($act??'null') . ")";
						#coopy/TableDiff.hx:440: lines 440-443
						if ($this->active_column !== null) {
							#coopy/TableDiff.hx:441: characters 29-49
							$this->active_column->offsetSet($j, 1);
							#coopy/TableDiff.hx:442: characters 29-42
							$this->col_renames++;
						}
					}
				}
			}
			#coopy/TableDiff.hx:447: lines 447-452
			if ($reordered) {
				#coopy/TableDiff.hx:448: characters 17-32
				$act = ":" . ($act??'null');
				#coopy/TableDiff.hx:449: characters 17-35
				$this->have_schema = true;
				#coopy/TableDiff.hx:450: characters 17-62
				if ($this->active_column !== null) {
					#coopy/TableDiff.hx:450: characters 42-62
					$this->active_column = null;
				}
				#coopy/TableDiff.hx:451: characters 17-31
				$this->col_reorders++;
			}
			#coopy/TableDiff.hx:454: characters 13-29
			$_this = $this->schema;
			$_this->arr[$_this->length++] = $act;
		}
	}

	/**
	 *
	 * If you wish to customize how diff cells are generated,
	 * call this prior to calling `hilite()`.
	 *
	 * @param builder hooks to generate custom cells
	 *
	 * 
	 * @param CellBuilder $builder
	 * 
	 * @return void
	 */
	public function setCellBuilder ($builder) {
		#coopy/TableDiff.hx:105: characters 9-31
		$this->builder = $builder;
	}

	/**
	 * @param StringMap $ignore
	 * @param IntMap $idx_ignore
	 * @param Table $tab
	 * @param int $r_header
	 * 
	 * @return void
	 */
	public function setIgnore ($ignore, $idx_ignore, $tab, $r_header) {
		#coopy/TableDiff.hx:218: characters 9-35
		$v = $tab->getCellView();
		#coopy/TableDiff.hx:219: lines 219-225
		if ($tab->get_height() >= $r_header) {
			#coopy/TableDiff.hx:220: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:220: characters 27-36
			$_g1 = $tab->get_width();
			#coopy/TableDiff.hx:220: lines 220-224
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:220: characters 23-36
				$i = $_g++;
				#coopy/TableDiff.hx:221: characters 17-64
				$name = $v->toString($tab->getCell($i, $r_header));
				#coopy/TableDiff.hx:222: characters 17-51
				if (!\array_key_exists($name, $ignore->data)) {
					#coopy/TableDiff.hx:222: characters 43-51
					continue;
				}
				#coopy/TableDiff.hx:223: characters 17-39
				$idx_ignore->data[$i] = true;
			}
		}
	}

	/**
	 * @return void
	 */
	public function setupColumns () {
		#coopy/TableDiff.hx:353: characters 9-60
		$column_order = $this->align->meta->toOrder();
		#coopy/TableDiff.hx:354: characters 9-46
		$this->column_units = $column_order->getList();
		#coopy/TableDiff.hx:356: characters 9-48
		$ignore = $this->flags->getIgnoredColumns();
		#coopy/TableDiff.hx:357: lines 357-374
		if ($ignore !== null) {
			#coopy/TableDiff.hx:358: characters 13-48
			$p_ignore = new IntMap();
			#coopy/TableDiff.hx:359: characters 13-48
			$a_ignore = new IntMap();
			#coopy/TableDiff.hx:360: characters 13-48
			$b_ignore = new IntMap();
			#coopy/TableDiff.hx:361: characters 13-51
			$this->setIgnore($ignore, $p_ignore, $this->p, $this->rp_header);
			#coopy/TableDiff.hx:362: characters 13-51
			$this->setIgnore($ignore, $a_ignore, $this->a, $this->ra_header);
			#coopy/TableDiff.hx:363: characters 13-51
			$this->setIgnore($ignore, $b_ignore, $this->b, $this->rb_header);
			#coopy/TableDiff.hx:365: characters 13-51
			$ncolumn_units = new \Array_hx();
			#coopy/TableDiff.hx:366: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:366: characters 27-46
			$_g1 = $this->column_units->length;
			#coopy/TableDiff.hx:366: lines 366-372
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:366: characters 23-46
				$j = $_g++;
				#coopy/TableDiff.hx:367: characters 17-52
				$cunit = ($this->column_units->arr[$j] ?? null);
				#coopy/TableDiff.hx:368: lines 368-370
				if (\array_key_exists($cunit->p, $p_ignore->data) || \array_key_exists($cunit->l, $a_ignore->data) || \array_key_exists($cunit->r, $b_ignore->data)) {
					#coopy/TableDiff.hx:370: characters 47-55
					continue;
				}
				#coopy/TableDiff.hx:371: characters 17-42
				$ncolumn_units->arr[$ncolumn_units->length++] = $cunit;
			}
			#coopy/TableDiff.hx:373: characters 13-41
			$this->column_units = $ncolumn_units;
		}
	}

	/**
	 * @return void
	 */
	public function setupMoves () {
		#coopy/TableDiff.hx:378: lines 378-389
		if ($this->flags->ordered) {
			#coopy/TableDiff.hx:379: characters 13-43
			$this->row_moves = new IntMap();
			#coopy/TableDiff.hx:380: characters 13-65
			$moves = Mover::moveUnits($this->row_units);
			#coopy/TableDiff.hx:381: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:381: characters 27-39
			$_g1 = $moves->length;
			#coopy/TableDiff.hx:381: lines 381-383
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:381: characters 23-39
				$i = $_g++;
				#coopy/TableDiff.hx:382: characters 17-40
				$this->row_moves->data[$moves[$i]] = $i;
			}
			#coopy/TableDiff.hx:384: characters 13-43
			$this->col_moves = new IntMap();
			#coopy/TableDiff.hx:385: characters 13-50
			$moves = Mover::moveUnits($this->column_units);
			#coopy/TableDiff.hx:386: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:386: characters 27-39
			$_g1 = $moves->length;
			#coopy/TableDiff.hx:386: lines 386-388
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:386: characters 23-39
				$i = $_g++;
				#coopy/TableDiff.hx:387: characters 17-40
				$this->col_moves->data[$moves[$i]] = $i;
			}
		}
	}

	/**
	 * @return void
	 */
	public function setupTables () {
		#coopy/TableDiff.hx:272: characters 9-32
		$this->order = $this->align->toOrder();
		#coopy/TableDiff.hx:273: characters 9-36
		$this->row_units = $this->order->getList();
		#coopy/TableDiff.hx:274: characters 9-47
		$this->has_parent = $this->align->reference !== null;
		#coopy/TableDiff.hx:275: lines 275-307
		if ($this->has_parent) {
			#coopy/TableDiff.hx:276: characters 13-34
			$this->p = $this->align->getSource();
			#coopy/TableDiff.hx:277: characters 13-44
			$this->a = $this->align->reference->getTarget();
			#coopy/TableDiff.hx:278: characters 13-34
			$this->b = $this->align->getTarget();
			#coopy/TableDiff.hx:279: characters 13-63
			$this->rp_header = $this->align->reference->meta->getSourceHeader();
			#coopy/TableDiff.hx:280: characters 13-63
			$this->ra_header = $this->align->reference->meta->getTargetHeader();
			#coopy/TableDiff.hx:281: characters 13-53
			$this->rb_header = $this->align->meta->getTargetHeader();
			#coopy/TableDiff.hx:282: lines 282-287
			if ($this->align->getIndexColumns() !== null) {
				#coopy/TableDiff.hx:283: lines 283-286
				$_g = 0;
				$_g1 = $this->align->getIndexColumns();
				while ($_g < $_g1->length) {
					#coopy/TableDiff.hx:283: characters 22-25
					$p2b = ($_g1->arr[$_g] ?? null);
					#coopy/TableDiff.hx:283: lines 283-286
					++$_g;
					#coopy/TableDiff.hx:284: characters 21-61
					if ($p2b->l >= 0) {
						#coopy/TableDiff.hx:284: characters 35-61
						$this->is_index_p->data[$p2b->l] = true;
					}
					#coopy/TableDiff.hx:285: characters 21-61
					if ($p2b->r >= 0) {
						#coopy/TableDiff.hx:285: characters 35-61
						$this->is_index_b->data[$p2b->r] = true;
					}
				}
			}
			#coopy/TableDiff.hx:288: lines 288-293
			if ($this->align->reference->getIndexColumns() !== null) {
				#coopy/TableDiff.hx:289: lines 289-292
				$_g = 0;
				$_g1 = $this->align->reference->getIndexColumns();
				while ($_g < $_g1->length) {
					#coopy/TableDiff.hx:289: characters 22-25
					$p2a = ($_g1->arr[$_g] ?? null);
					#coopy/TableDiff.hx:289: lines 289-292
					++$_g;
					#coopy/TableDiff.hx:290: characters 21-61
					if ($p2a->l >= 0) {
						#coopy/TableDiff.hx:290: characters 35-61
						$this->is_index_p->data[$p2a->l] = true;
					}
					#coopy/TableDiff.hx:291: characters 21-61
					if ($p2a->r >= 0) {
						#coopy/TableDiff.hx:291: characters 35-61
						$this->is_index_a->data[$p2a->r] = true;
					}
				}
			}
		} else {
			#coopy/TableDiff.hx:295: characters 13-34
			$this->a = $this->align->getSource();
			#coopy/TableDiff.hx:296: characters 13-34
			$this->b = $this->align->getTarget();
			#coopy/TableDiff.hx:297: characters 13-18
			$this->p = $this->a;
			#coopy/TableDiff.hx:298: characters 13-53
			$this->ra_header = $this->align->meta->getSourceHeader();
			#coopy/TableDiff.hx:299: characters 13-34
			$this->rp_header = $this->ra_header;
			#coopy/TableDiff.hx:300: characters 13-53
			$this->rb_header = $this->align->meta->getTargetHeader();
			#coopy/TableDiff.hx:301: lines 301-306
			if ($this->align->getIndexColumns() !== null) {
				#coopy/TableDiff.hx:302: lines 302-305
				$_g = 0;
				$_g1 = $this->align->getIndexColumns();
				while ($_g < $_g1->length) {
					#coopy/TableDiff.hx:302: characters 22-25
					$a2b = ($_g1->arr[$_g] ?? null);
					#coopy/TableDiff.hx:302: lines 302-305
					++$_g;
					#coopy/TableDiff.hx:303: characters 21-61
					if ($a2b->l >= 0) {
						#coopy/TableDiff.hx:303: characters 35-61
						$this->is_index_a->data[$a2b->l] = true;
					}
					#coopy/TableDiff.hx:304: characters 21-61
					if ($a2b->r >= 0) {
						#coopy/TableDiff.hx:304: characters 35-61
						$this->is_index_b->data[$a2b->r] = true;
					}
				}
			}
		}
		#coopy/TableDiff.hx:309: characters 9-43
		$this->allow_insert = $this->flags->allowInsert();
		#coopy/TableDiff.hx:310: characters 9-43
		$this->allow_delete = $this->flags->allowDelete();
		#coopy/TableDiff.hx:311: characters 9-43
		$this->allow_update = $this->flags->allowUpdate();
		#coopy/TableDiff.hx:312: characters 9-43
		$this->allow_column = $this->flags->allowColumn();
		#coopy/TableDiff.hx:314: characters 9-24
		$common = $this->a;
		#coopy/TableDiff.hx:315: characters 9-37
		if ($common === null) {
			#coopy/TableDiff.hx:315: characters 27-37
			$common = $this->b;
		}
		#coopy/TableDiff.hx:316: characters 9-37
		if ($common === null) {
			#coopy/TableDiff.hx:316: characters 27-37
			$common = $this->p;
		}
		#coopy/TableDiff.hx:317: characters 9-33
		$this->v = $common->getCellView();
		#coopy/TableDiff.hx:318: characters 9-27
		$this->builder->setView($this->v);
		#coopy/TableDiff.hx:320: characters 9-23
		$this->nested = false;
		#coopy/TableDiff.hx:321: characters 9-37
		$meta = $common->getMeta();
		#coopy/TableDiff.hx:322: lines 322-324
		if ($meta !== null) {
			#coopy/TableDiff.hx:323: characters 13-37
			$this->nested = $meta->isNested();
		}
		#coopy/TableDiff.hx:325: characters 9-32
		$this->nesting_present = false;
	}

	/**
	 * @param Unit[]|\Array_hx $units
	 * @param int $del
	 * @param int[]|\Array_hx $active
	 * 
	 * @return void
	 */
	public function spreadContext ($units, $del, $active) {
		#coopy/TableDiff.hx:168: lines 168-211
		if (($del > 0) && ($active !== null)) {
			#coopy/TableDiff.hx:170: characters 13-37
			$mark = -$del - 1;
			#coopy/TableDiff.hx:171: characters 13-33
			$skips = 0;
			#coopy/TableDiff.hx:172: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:172: characters 27-39
			$_g1 = $units->length;
			#coopy/TableDiff.hx:172: lines 172-188
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:172: characters 23-39
				$i = $_g++;
				#coopy/TableDiff.hx:173: lines 173-177
				if (($active->arr[$i] ?? null) === -3) {
					#coopy/TableDiff.hx:175: characters 21-28
					++$skips;
					#coopy/TableDiff.hx:176: characters 21-29
					continue;
				}
				#coopy/TableDiff.hx:178: lines 178-187
				if ((($active->arr[$i] ?? null) === 0) || (($active->arr[$i] ?? null) === 3)) {
					#coopy/TableDiff.hx:179: lines 179-183
					if (($i - $mark) <= ($del + $skips)) {
						#coopy/TableDiff.hx:180: characters 25-38
						$active->offsetSet($i, 2);
					} else if (($i - $mark) === ($del + 1 + $skips)) {
						#coopy/TableDiff.hx:182: characters 25-38
						$active->offsetSet($i, 3);
					}
				} else if (($active->arr[$i] ?? null) === 1) {
					#coopy/TableDiff.hx:185: characters 21-29
					$mark = $i;
					#coopy/TableDiff.hx:186: characters 21-30
					$skips = 0;
				}
			}
			#coopy/TableDiff.hx:191: characters 13-42
			$mark = $units->length + $del + 1;
			#coopy/TableDiff.hx:192: characters 13-22
			$skips = 0;
			#coopy/TableDiff.hx:193: characters 23-27
			$_g = 0;
			#coopy/TableDiff.hx:193: characters 27-39
			$_g1 = $units->length;
			#coopy/TableDiff.hx:193: lines 193-210
			while ($_g < $_g1) {
				#coopy/TableDiff.hx:193: characters 23-39
				$j = $_g++;
				#coopy/TableDiff.hx:194: characters 17-48
				$i = $units->length - 1 - $j;
				#coopy/TableDiff.hx:195: lines 195-199
				if (($active->arr[$i] ?? null) === -3) {
					#coopy/TableDiff.hx:197: characters 21-28
					++$skips;
					#coopy/TableDiff.hx:198: characters 21-29
					continue;
				}
				#coopy/TableDiff.hx:200: lines 200-209
				if ((($active->arr[$i] ?? null) === 0) || (($active->arr[$i] ?? null) === 3)) {
					#coopy/TableDiff.hx:201: lines 201-205
					if (($mark - $i) <= ($del + $skips)) {
						#coopy/TableDiff.hx:202: characters 25-38
						$active->offsetSet($i, 2);
					} else if (($mark - $i) === ($del + 1 + $skips)) {
						#coopy/TableDiff.hx:204: characters 25-38
						$active->offsetSet($i, 3);
					}
				} else if (($active->arr[$i] ?? null) === 1) {
					#coopy/TableDiff.hx:207: characters 21-29
					$mark = $i;
					#coopy/TableDiff.hx:208: characters 21-30
					$skips = 0;
				}
			}
		}
	}
}

Boot::registerClass(TableDiff::class, 'coopy.TableDiff');
