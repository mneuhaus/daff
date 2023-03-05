<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\IntMap;
use \haxe\ds\StringMap;

/**
 *
 * Run a comparison between tables.  Normally you'll
 * call `coopy.Coopy.compareTables` to start off such a comparison.
 *
 */
class CompareTable {
	/**
	 * @var TableComparisonState
	 */
	public $comp;
	/**
	 * @var IndexPair[]|\Array_hx
	 */
	public $indexes;

	/**
	 *
	 * @param comp the state of the comparison, including the tables to
	 * be compared, and whether the comparison has run to completion.
	 *
	 * 
	 * @param TableComparisonState $comp
	 * 
	 * @return void
	 */
	public function __construct ($comp) {
		#coopy/CompareTable.hx:25: characters 9-25
		$this->comp = $comp;
		#coopy/CompareTable.hx:26: lines 26-30
		if ($comp->compare_flags !== null) {
			#coopy/CompareTable.hx:27: lines 27-29
			if ($comp->compare_flags->parent !== null) {
				#coopy/CompareTable.hx:28: characters 17-51
				$comp->p = $comp->compare_flags->parent;
			}
		}
	}

	/**
	 *
	 * Access a summary of how the tables align with each other.
	 * Runs the comparison to completion if it hasn't already been
	 * finished.
	 *
	 * @return the alignment between tables
	 *
	 * 
	 * @return Alignment
	 */
	public function align () {
		#coopy/CompareTable.hx:62: lines 62-64
		while (!$this->comp->completed) {
			#coopy/CompareTable.hx:63: characters 13-18
			$this->run();
		}
		#coopy/CompareTable.hx:65: characters 9-53
		$alignment = new Alignment();
		#coopy/CompareTable.hx:66: characters 9-29
		$this->alignCore($alignment);
		#coopy/CompareTable.hx:68: characters 9-30
		$alignment->comp = $this->comp;
		#coopy/CompareTable.hx:69: characters 9-35
		$this->comp->alignment = $alignment;
		#coopy/CompareTable.hx:70: characters 9-25
		return $alignment;
	}

	/**
	 * @param Alignment $align
	 * @param Table $a
	 * @param Table $b
	 * 
	 * @return void
	 */
	public function alignColumns ($align, $a, $b) {
		#coopy/CompareTable.hx:411: characters 9-37
		$align->range($a->get_width(), $b->get_width());
		#coopy/CompareTable.hx:412: characters 9-26
		$align->tables($a, $b);
		#coopy/CompareTable.hx:413: characters 9-32
		$align->setRowlike(false);
		#coopy/CompareTable.hx:415: characters 9-28
		$slop = 5;
		#coopy/CompareTable.hx:417: characters 9-41
		$va = $a->getCellView();
		#coopy/CompareTable.hx:418: characters 9-41
		$vb = $b->getCellView();
		#coopy/CompareTable.hx:419: characters 9-31
		$ra_best = 0;
		#coopy/CompareTable.hx:420: characters 9-31
		$rb_best = 0;
		#coopy/CompareTable.hx:421: characters 9-32
		$ct_best = -1;
		#coopy/CompareTable.hx:422: characters 9-46
		$ma_best = null;
		#coopy/CompareTable.hx:423: characters 9-46
		$mb_best = null;
		#coopy/CompareTable.hx:424: characters 9-33
		$ra_header = 0;
		#coopy/CompareTable.hx:425: characters 9-33
		$rb_header = 0;
		#coopy/CompareTable.hx:426: characters 9-34
		$ra_uniques = 0;
		#coopy/CompareTable.hx:427: characters 9-34
		$rb_uniques = 0;
		#coopy/CompareTable.hx:428: characters 20-24
		$_g = 0;
		#coopy/CompareTable.hx:428: characters 24-28
		$_g1 = $slop;
		#coopy/CompareTable.hx:428: lines 428-486
		while ($_g < $_g1) {
			#coopy/CompareTable.hx:428: characters 20-28
			$ra = $_g++;
			#coopy/CompareTable.hx:429: characters 24-28
			$_g2 = 0;
			#coopy/CompareTable.hx:429: characters 28-32
			$_g3 = $slop;
			#coopy/CompareTable.hx:429: lines 429-485
			while ($_g2 < $_g3) {
				#coopy/CompareTable.hx:429: characters 24-32
				$rb = $_g2++;
				#coopy/CompareTable.hx:430: characters 17-66
				$ma = new StringMap();
				#coopy/CompareTable.hx:431: characters 17-66
				$mb = new StringMap();
				#coopy/CompareTable.hx:432: characters 17-34
				$ct = 0;
				#coopy/CompareTable.hx:433: characters 17-39
				$uniques = 0;
				#coopy/CompareTable.hx:434: lines 434-449
				if ($ra < $a->get_height()) {
					#coopy/CompareTable.hx:435: characters 32-36
					$_g4 = 0;
					#coopy/CompareTable.hx:435: characters 36-43
					$_g5 = $a->get_width();
					#coopy/CompareTable.hx:435: lines 435-444
					while ($_g4 < $_g5) {
						#coopy/CompareTable.hx:435: characters 32-43
						$ca = $_g4++;
						#coopy/CompareTable.hx:436: characters 25-74
						$key = $va->toString($a->getCell($ca, $ra));
						#coopy/CompareTable.hx:437: lines 437-443
						if (\array_key_exists($key, $ma->data)) {
							#coopy/CompareTable.hx:438: characters 29-43
							$ma->data[$key] = -1;
							#coopy/CompareTable.hx:439: characters 29-38
							--$uniques;
						} else {
							#coopy/CompareTable.hx:441: characters 25-39
							$ma->data[$key] = $ca;
							#coopy/CompareTable.hx:442: characters 25-34
							++$uniques;
						}
					}
					#coopy/CompareTable.hx:445: lines 445-448
					if ($uniques > $ra_uniques) {
						#coopy/CompareTable.hx:446: characters 25-34
						$ra_header = $ra;
						#coopy/CompareTable.hx:447: characters 25-35
						$ra_uniques = $uniques;
					}
				}
				#coopy/CompareTable.hx:450: characters 17-24
				$uniques = 0;
				#coopy/CompareTable.hx:451: lines 451-466
				if ($rb < $b->get_height()) {
					#coopy/CompareTable.hx:452: characters 32-36
					$_g6 = 0;
					#coopy/CompareTable.hx:452: characters 36-43
					$_g7 = $b->get_width();
					#coopy/CompareTable.hx:452: lines 452-461
					while ($_g6 < $_g7) {
						#coopy/CompareTable.hx:452: characters 32-43
						$cb = $_g6++;
						#coopy/CompareTable.hx:453: characters 25-74
						$key1 = $vb->toString($b->getCell($cb, $rb));
						#coopy/CompareTable.hx:454: lines 454-460
						if (\array_key_exists($key1, $mb->data)) {
							#coopy/CompareTable.hx:455: characters 29-43
							$mb->data[$key1] = -1;
							#coopy/CompareTable.hx:456: characters 29-38
							--$uniques;
						} else {
							#coopy/CompareTable.hx:458: characters 29-43
							$mb->data[$key1] = $cb;
							#coopy/CompareTable.hx:459: characters 29-38
							++$uniques;
						}
					}
					#coopy/CompareTable.hx:462: lines 462-465
					if ($uniques > $rb_uniques) {
						#coopy/CompareTable.hx:463: characters 25-34
						$rb_header = $rb;
						#coopy/CompareTable.hx:464: characters 25-35
						$rb_uniques = $uniques;
					}
				}
				#coopy/CompareTable.hx:468: characters 29-38
				$data = \array_values(\array_map("strval", \array_keys($ma->data)));
				$key_current = 0;
				$key_length = \count($data);
				$key_data = $data;
				while ($key_current < $key_length) {
					#coopy/CompareTable.hx:468: lines 468-476
					$key2 = $key_data[$key_current++];
					#coopy/CompareTable.hx:469: characters 21-48
					$i0 = ($ma->data[$key2] ?? null);
					#coopy/CompareTable.hx:470: characters 21-54
					$i1 = ($mb->data[$key2] ?? null);
					#coopy/CompareTable.hx:471: lines 471-475
					if ($i1 !== null) {
						#coopy/CompareTable.hx:472: lines 472-474
						if (($i1 >= 0) && ($i0 >= 0)) {
							#coopy/CompareTable.hx:473: characters 29-33
							++$ct;
						}
					}
				}
				#coopy/CompareTable.hx:478: lines 478-484
				if ($ct > $ct_best) {
					#coopy/CompareTable.hx:479: characters 21-28
					$ct_best = $ct;
					#coopy/CompareTable.hx:480: characters 21-28
					$ma_best = $ma;
					#coopy/CompareTable.hx:481: characters 21-28
					$mb_best = $mb;
					#coopy/CompareTable.hx:482: characters 21-28
					$ra_best = $ra;
					#coopy/CompareTable.hx:483: characters 21-28
					$rb_best = $rb;
				}
			}
		}
		#coopy/CompareTable.hx:488: lines 488-495
		if ($ma_best === null) {
			#coopy/CompareTable.hx:489: lines 489-493
			if (($a->get_height() > 0) && ($b->get_height() === 0)) {
				#coopy/CompareTable.hx:490: characters 17-36
				$align->headers(0, -1);
			} else if (($a->get_height() === 0) && ($b->get_height() > 0)) {
				#coopy/CompareTable.hx:492: characters 17-36
				$align->headers(-1, 0);
			}
			#coopy/CompareTable.hx:494: characters 13-19
			return;
		}
		#coopy/CompareTable.hx:496: characters 21-35
		$data = \array_values(\array_map("strval", \array_keys($ma_best->data)));
		$key_current = 0;
		$key_length = \count($data);
		$key_data = $data;
		while ($key_current < $key_length) {
			#coopy/CompareTable.hx:496: lines 496-506
			$key = $key_data[$key_current++];
			#coopy/CompareTable.hx:497: characters 13-51
			$i0 = ($ma_best->data[$key] ?? null);
			#coopy/CompareTable.hx:498: characters 13-51
			$i1 = ($mb_best->data[$key] ?? null);
			#coopy/CompareTable.hx:499: lines 499-505
			if (($i0 !== null) && ($i1 !== null)) {
				#coopy/CompareTable.hx:500: characters 17-34
				$align->link($i0, $i1);
			} else if ($i0 !== null) {
				#coopy/CompareTable.hx:502: characters 17-34
				$align->link($i0, -1);
			} else if ($i1 !== null) {
				#coopy/CompareTable.hx:504: characters 17-34
				$align->link(-1, $i1);
			}
		}
		#coopy/CompareTable.hx:507: characters 21-35
		$data = \array_values(\array_map("strval", \array_keys($mb_best->data)));
		$key_current = 0;
		$key_length = \count($data);
		$key_data = $data;
		while ($key_current < $key_length) {
			#coopy/CompareTable.hx:507: lines 507-513
			$key = $key_data[$key_current++];
			#coopy/CompareTable.hx:508: characters 13-51
			$i0 = ($ma_best->data[$key] ?? null);
			#coopy/CompareTable.hx:509: characters 13-51
			$i1 = ($mb_best->data[$key] ?? null);
			#coopy/CompareTable.hx:510: lines 510-512
			if (($i0 === null) && ($i1 !== null)) {
				#coopy/CompareTable.hx:511: characters 17-34
				$align->link(-1, $i1);
			}
		}
		#coopy/CompareTable.hx:514: characters 9-43
		$align->headers($ra_header, $rb_header);
	}

	/**
	 * @param Alignment $align
	 * 
	 * @return void
	 */
	public function alignCore ($align) {
		#coopy/CompareTable.hx:84: lines 84-107
		if ($this->useSql()) {
			#coopy/CompareTable.hx:85: characters 13-40
			$tab1 = null;
			#coopy/CompareTable.hx:86: characters 13-40
			$tab2 = null;
			#coopy/CompareTable.hx:87: characters 13-40
			$tab3 = null;
			#coopy/CompareTable.hx:88: lines 88-96
			if ($this->comp->p === null) {
				#coopy/CompareTable.hx:89: characters 17-35
				$tab1 = $this->comp->a;
				#coopy/CompareTable.hx:90: characters 17-35
				$tab2 = $this->comp->b;
			} else {
				#coopy/CompareTable.hx:92: characters 17-50
				$align->reference = new Alignment();
				#coopy/CompareTable.hx:93: characters 17-35
				$tab1 = $this->comp->p;
				#coopy/CompareTable.hx:94: characters 17-35
				$tab2 = $this->comp->b;
				#coopy/CompareTable.hx:95: characters 17-35
				$tab3 = $this->comp->a;
			}
			#coopy/CompareTable.hx:97: characters 13-41
			$db = null;
			#coopy/CompareTable.hx:98: characters 13-52
			if ($tab1 !== null) {
				#coopy/CompareTable.hx:98: characters 29-52
				$db = $tab1->getDatabase();
			}
			#coopy/CompareTable.hx:99: characters 13-64
			if (($db === null) && ($tab2 !== null)) {
				#coopy/CompareTable.hx:99: characters 41-64
				$db = $tab2->getDatabase();
			}
			#coopy/CompareTable.hx:100: characters 13-64
			if (($db === null) && ($tab3 !== null)) {
				#coopy/CompareTable.hx:100: characters 41-64
				$db = $tab3->getDatabase();
			}
			#coopy/CompareTable.hx:101: characters 13-81
			$sc = new SqlCompare($db, $tab1, $tab2, $tab3, $align, $this->comp->compare_flags);
			#coopy/CompareTable.hx:102: characters 13-23
			$sc->apply();
			#coopy/CompareTable.hx:103: lines 103-105
			if ($this->comp->p !== null) {
				#coopy/CompareTable.hx:104: characters 17-60
				$align->meta->reference = $align->reference->meta;
			}
			#coopy/CompareTable.hx:106: characters 13-19
			return;
		}
		#coopy/CompareTable.hx:108: lines 108-111
		if ($this->comp->p === null) {
			#coopy/CompareTable.hx:109: characters 13-44
			$this->alignCore2($align, $this->comp->a, $this->comp->b);
			#coopy/CompareTable.hx:110: characters 13-19
			return;
		}
		#coopy/CompareTable.hx:112: characters 9-42
		$align->reference = new Alignment();
		#coopy/CompareTable.hx:113: characters 9-40
		$this->alignCore2($align, $this->comp->p, $this->comp->b);
		#coopy/CompareTable.hx:114: characters 9-50
		$this->alignCore2($align->reference, $this->comp->p, $this->comp->a);
		#coopy/CompareTable.hx:115: characters 9-52
		$align->meta->reference = $align->reference->meta;
	}

	/**
	 * @param Alignment $align
	 * @param Table $a
	 * @param Table $b
	 * 
	 * @return void
	 */
	public function alignCore2 ($align, $a, $b) {
		#coopy/CompareTable.hx:121: lines 121-123
		if ($align->meta === null) {
			#coopy/CompareTable.hx:122: characters 13-23
			$align->meta = new Alignment();
		}
		#coopy/CompareTable.hx:124: characters 9-37
		$this->alignColumns($align->meta, $a, $b);
		#coopy/CompareTable.hx:125: characters 9-60
		$column_order = $align->meta->toOrder();
		#coopy/CompareTable.hx:127: characters 9-39
		$align->range($a->get_height(), $b->get_height());
		#coopy/CompareTable.hx:128: characters 9-26
		$align->tables($a, $b);
		#coopy/CompareTable.hx:129: characters 9-31
		$align->setRowlike(true);
		#coopy/CompareTable.hx:131: characters 9-31
		$w = $a->get_width();
		#coopy/CompareTable.hx:132: characters 9-33
		$ha = $a->get_height();
		#coopy/CompareTable.hx:133: characters 9-33
		$hb = $b->get_height();
		#coopy/CompareTable.hx:135: characters 9-41
		$av = $a->getCellView();
		#coopy/CompareTable.hx:137: characters 9-40
		$ids = null;
		#coopy/CompareTable.hx:138: characters 9-46
		$ignore = null;
		#coopy/CompareTable.hx:139: characters 9-35
		$ordered = true;
		#coopy/CompareTable.hx:140: lines 140-144
		if ($this->comp->compare_flags !== null) {
			#coopy/CompareTable.hx:141: characters 13-16
			$ids = $this->comp->compare_flags->ids;
			#coopy/CompareTable.hx:142: characters 13-19
			$ignore = $this->comp->compare_flags->getIgnoredColumns();
			#coopy/CompareTable.hx:143: characters 13-20
			$ordered = $this->comp->compare_flags->ordered;
		}
		#coopy/CompareTable.hx:146: characters 9-60
		$common_units = new \Array_hx();
		#coopy/CompareTable.hx:147: characters 9-55
		$ra_header = $align->getSourceHeader();
		#coopy/CompareTable.hx:148: characters 9-55
		$rb_header = $align->getSourceHeader();
		#coopy/CompareTable.hx:149: lines 149-163
		$_g = 0;
		$_g1 = $column_order->getList();
		while ($_g < $_g1->length) {
			#coopy/CompareTable.hx:149: characters 14-18
			$unit = ($_g1->arr[$_g] ?? null);
			#coopy/CompareTable.hx:149: lines 149-163
			++$_g;
			#coopy/CompareTable.hx:150: lines 150-162
			if (($unit->l >= 0) && ($unit->r >= 0) && ($unit->p !== -1)) {
				#coopy/CompareTable.hx:151: lines 151-160
				if ($ignore !== null) {
					#coopy/CompareTable.hx:152: lines 152-155
					if (($unit->l >= 0) && ($ra_header >= 0) && ($ra_header < $a->get_height())) {
						#coopy/CompareTable.hx:153: characters 25-77
						$name = $av->toString($a->getCell($unit->l, $ra_header));
						#coopy/CompareTable.hx:154: characters 25-58
						if (\array_key_exists($name, $ignore->data)) {
							#coopy/CompareTable.hx:154: characters 50-58
							continue;
						}
					}
					#coopy/CompareTable.hx:156: lines 156-159
					if (($unit->r >= 0) && ($rb_header >= 0) && ($rb_header < $b->get_height())) {
						#coopy/CompareTable.hx:157: characters 25-77
						$name1 = $av->toString($b->getCell($unit->r, $rb_header));
						#coopy/CompareTable.hx:158: characters 25-58
						if (\array_key_exists($name1, $ignore->data)) {
							#coopy/CompareTable.hx:158: characters 50-58
							continue;
						}
					}
				}
				#coopy/CompareTable.hx:161: characters 17-40
				$common_units->arr[$common_units->length++] = $unit;
			}
		}
		#coopy/CompareTable.hx:165: characters 9-42
		$index_top = null;
		#coopy/CompareTable.hx:166: characters 9-35
		$pending_ct = $ha;
		#coopy/CompareTable.hx:167: characters 9-43
		$reverse_pending_ct = $hb;
		#coopy/CompareTable.hx:168: characters 9-54
		$used = new IntMap();
		#coopy/CompareTable.hx:169: characters 9-62
		$used_reverse = new IntMap();
		#coopy/CompareTable.hx:170: lines 170-324
		if ($ids !== null) {
			#coopy/CompareTable.hx:174: characters 13-22
			$index_top = new IndexPair($this->comp->compare_flags);
			#coopy/CompareTable.hx:175: characters 30-52
			$this1 = [];
			$ids_as_map_data = $this1;
			#coopy/CompareTable.hx:176: lines 176-178
			$_g = 0;
			while ($_g < $ids->length) {
				#coopy/CompareTable.hx:176: characters 18-20
				$id = ($ids->arr[$_g] ?? null);
				#coopy/CompareTable.hx:176: lines 176-178
				++$_g;
				#coopy/CompareTable.hx:177: characters 17-38
				$ids_as_map_data[$id] = true;
			}
			#coopy/CompareTable.hx:179: lines 179-186
			$_g = 0;
			while ($_g < $common_units->length) {
				#coopy/CompareTable.hx:179: characters 18-22
				$unit = ($common_units->arr[$_g] ?? null);
				#coopy/CompareTable.hx:179: lines 179-186
				++$_g;
				#coopy/CompareTable.hx:180: characters 17-59
				$na = $av->toString($a->getCell($unit->l, 0));
				#coopy/CompareTable.hx:181: characters 17-59
				$nb = $av->toString($b->getCell($unit->r, 0));
				#coopy/CompareTable.hx:182: lines 182-185
				if (\array_key_exists($na, $ids_as_map_data) || \array_key_exists($nb, $ids_as_map_data)) {
					#coopy/CompareTable.hx:183: characters 21-56
					$index_top->addColumns($unit->l, $unit->r);
					#coopy/CompareTable.hx:184: characters 21-48
					$align->addIndexColumns($unit);
				}
			}
			#coopy/CompareTable.hx:187: characters 13-41
			$index_top->indexTables($a, $b, 1);
			#coopy/CompareTable.hx:188: lines 188-190
			if ($this->indexes !== null) {
				#coopy/CompareTable.hx:189: characters 17-40
				$_this = $this->indexes;
				$_this->arr[$_this->length++] = $index_top;
			}
			#coopy/CompareTable.hx:191: characters 23-27
			$_g = 0;
			#coopy/CompareTable.hx:191: characters 27-29
			$_g1 = $ha;
			#coopy/CompareTable.hx:191: lines 191-201
			while ($_g < $_g1) {
				#coopy/CompareTable.hx:191: characters 23-29
				$j = $_g++;
				#coopy/CompareTable.hx:192: characters 17-65
				$cross = $index_top->queryLocal($j);
				#coopy/CompareTable.hx:193: characters 17-49
				$spot_a = $cross->spot_a;
				#coopy/CompareTable.hx:194: characters 17-49
				$spot_b = $cross->spot_b;
				#coopy/CompareTable.hx:195: characters 17-53
				if (($spot_a !== 1) || ($spot_b !== 1)) {
					#coopy/CompareTable.hx:195: characters 45-53
					continue;
				}
				#coopy/CompareTable.hx:196: characters 17-47
				$jb = ($cross->item_b->lst->arr[0] ?? null);
				#coopy/CompareTable.hx:197: characters 17-33
				$align->link($j, $jb);
				#coopy/CompareTable.hx:198: characters 17-31
				$used->data[$jb] = 1;
				#coopy/CompareTable.hx:199: characters 17-66
				if (!\array_key_exists($j, $used_reverse->data)) {
					#coopy/CompareTable.hx:199: characters 46-66
					--$reverse_pending_ct;
				}
				#coopy/CompareTable.hx:200: characters 17-38
				$used_reverse->data[$j] = 1;
			}
		} else {
			#coopy/CompareTable.hx:208: characters 13-29
			$N = 5;
			#coopy/CompareTable.hx:209: characters 13-57
			$columns = new \Array_hx();
			#coopy/CompareTable.hx:210: lines 210-248
			if ($common_units->length > $N) {
				#coopy/CompareTable.hx:211: characters 17-80
				$columns_eval = new \Array_hx();
				#coopy/CompareTable.hx:212: characters 27-31
				$_g = 0;
				#coopy/CompareTable.hx:212: characters 31-50
				$_g1 = $common_units->length;
				#coopy/CompareTable.hx:212: lines 212-233
				while ($_g < $_g1) {
					#coopy/CompareTable.hx:212: characters 27-50
					$i = $_g++;
					#coopy/CompareTable.hx:213: characters 21-37
					$ct = 0;
					#coopy/CompareTable.hx:214: characters 48-69
					$this1 = [];
					$mem_data = $this1;
					#coopy/CompareTable.hx:215: characters 49-70
					$this2 = [];
					$mem2_data = $this2;
					#coopy/CompareTable.hx:216: characters 21-53
					$ca = ($common_units->arr[$i] ?? null)->l;
					#coopy/CompareTable.hx:217: characters 21-53
					$cb = ($common_units->arr[$i] ?? null)->r;
					#coopy/CompareTable.hx:218: characters 31-35
					$_g2 = 0;
					#coopy/CompareTable.hx:218: characters 35-37
					$_g3 = $ha;
					#coopy/CompareTable.hx:218: lines 218-224
					while ($_g2 < $_g3) {
						#coopy/CompareTable.hx:218: characters 31-37
						$j = $_g2++;
						#coopy/CompareTable.hx:219: characters 25-72
						$key = $av->toString($a->getCell($ca, $j));
						#coopy/CompareTable.hx:220: lines 220-223
						if (!\array_key_exists($key, $mem_data)) {
							#coopy/CompareTable.hx:221: characters 29-43
							$mem_data[$key] = 1;
							#coopy/CompareTable.hx:222: characters 29-33
							++$ct;
						}
					}
					#coopy/CompareTable.hx:225: characters 31-35
					$_g4 = 0;
					#coopy/CompareTable.hx:225: characters 35-37
					$_g5 = $hb;
					#coopy/CompareTable.hx:225: lines 225-231
					while ($_g4 < $_g5) {
						#coopy/CompareTable.hx:225: characters 31-37
						$j1 = $_g4++;
						#coopy/CompareTable.hx:226: characters 25-72
						$key1 = $av->toString($b->getCell($cb, $j1));
						#coopy/CompareTable.hx:227: lines 227-230
						if (!\array_key_exists($key1, $mem2_data)) {
							#coopy/CompareTable.hx:228: characters 29-44
							$mem2_data[$key1] = 1;
							#coopy/CompareTable.hx:229: characters 29-33
							++$ct;
						}
					}
					#coopy/CompareTable.hx:232: characters 21-46
					$columns_eval->arr[$columns_eval->length++] = \Array_hx::wrap([
						$i,
						$ct,
					]);
				}
				#coopy/CompareTable.hx:234: lines 234-240
				$sorter = function ($a, $b) {
					#coopy/CompareTable.hx:235: characters 21-44
					if (($a->arr[1] ?? null) < ($b->arr[1] ?? null)) {
						#coopy/CompareTable.hx:235: characters 36-44
						return 1;
					}
					#coopy/CompareTable.hx:236: characters 21-45
					if (($a->arr[1] ?? null) > ($b->arr[1] ?? null)) {
						#coopy/CompareTable.hx:236: characters 36-45
						return -1;
					}
					#coopy/CompareTable.hx:237: characters 21-44
					if (($a->arr[0] ?? null) > ($b->arr[0] ?? null)) {
						#coopy/CompareTable.hx:237: characters 36-44
						return 1;
					}
					#coopy/CompareTable.hx:238: characters 21-45
					if (($a->arr[0] ?? null) < ($b->arr[0] ?? null)) {
						#coopy/CompareTable.hx:238: characters 36-45
						return -1;
					}
					#coopy/CompareTable.hx:239: characters 21-29
					return 0;
				};
				#coopy/CompareTable.hx:241: characters 17-42
				\usort($columns_eval->arr, $sorter);
				#coopy/CompareTable.hx:242: characters 40-94
				$_g = new \Array_hx();
				$_g_current = 0;
				$_g_array = $columns_eval;
				while ($_g_current < $_g_array->length) {
					$x = ($_g_array->arr[$_g_current++] ?? null);
					$x1 = ($x->arr[0] ?? null);
					$_g->arr[$_g->length++] = $x1;
				}
				#coopy/CompareTable.hx:242: characters 17-24
				$columns = \Lambda::array($_g);
				#coopy/CompareTable.hx:243: characters 17-24
				$columns = $columns->slice(0, $N);
			} else {
				#coopy/CompareTable.hx:245: characters 27-31
				$_g = 0;
				#coopy/CompareTable.hx:245: characters 31-50
				$_g1 = $common_units->length;
				#coopy/CompareTable.hx:245: lines 245-247
				while ($_g < $_g1) {
					#coopy/CompareTable.hx:245: characters 27-50
					$i = $_g++;
					#coopy/CompareTable.hx:246: characters 21-36
					$columns->arr[$columns->length++] = $i;
				}
			}
			#coopy/CompareTable.hx:250: characters 13-68
			$top = (int)(\floor((2 ** $columns->length) + 0.5));
			#coopy/CompareTable.hx:252: characters 13-61
			$pending = new IntMap();
			#coopy/CompareTable.hx:253: characters 23-27
			$_g = 0;
			#coopy/CompareTable.hx:253: characters 27-29
			$_g1 = $ha;
			#coopy/CompareTable.hx:253: lines 253-255
			while ($_g < $_g1) {
				#coopy/CompareTable.hx:253: characters 23-29
				$j = $_g++;
				#coopy/CompareTable.hx:254: characters 17-33
				$pending->data[$j] = $j;
			}
			#coopy/CompareTable.hx:257: characters 13-68
			$added_columns = new IntMap();
			#coopy/CompareTable.hx:258: characters 13-36
			$index_ct = 0;
			#coopy/CompareTable.hx:260: characters 23-27
			$_g = 0;
			#coopy/CompareTable.hx:260: characters 27-30
			$_g1 = $top;
			#coopy/CompareTable.hx:260: lines 260-323
			while ($_g < $_g1) {
				#coopy/CompareTable.hx:260: characters 23-30
				$k = $_g++;
				#coopy/CompareTable.hx:261: characters 17-35
				if ($k === 0) {
					#coopy/CompareTable.hx:261: characters 27-35
					continue;
				}
				#coopy/CompareTable.hx:262: characters 17-43
				if ($pending_ct === 0) {
					#coopy/CompareTable.hx:262: characters 38-43
					break;
				}
				#coopy/CompareTable.hx:263: characters 17-68
				$active_columns = new \Array_hx();
				#coopy/CompareTable.hx:264: characters 17-34
				$kk = $k;
				#coopy/CompareTable.hx:265: characters 17-34
				$at = 0;
				#coopy/CompareTable.hx:266: lines 266-272
				while ($kk > 0) {
					#coopy/CompareTable.hx:267: lines 267-269
					if (($kk % 2) === 1) {
						#coopy/CompareTable.hx:268: characters 45-56
						$columns1 = ($columns->arr[$at] ?? null);
						#coopy/CompareTable.hx:268: characters 25-57
						$active_columns->arr[$active_columns->length++] = $columns1;
					}
					#coopy/CompareTable.hx:270: characters 21-29
					$kk >>= 1;
					#coopy/CompareTable.hx:271: characters 21-25
					++$at;
				}
				#coopy/CompareTable.hx:274: characters 17-75
				$index = new IndexPair($this->comp->compare_flags);
				#coopy/CompareTable.hx:275: characters 27-31
				$_g2 = 0;
				#coopy/CompareTable.hx:275: characters 31-52
				$_g3 = $active_columns->length;
				#coopy/CompareTable.hx:275: lines 275-283
				while ($_g2 < $_g3) {
					#coopy/CompareTable.hx:275: characters 27-52
					$k1 = $_g2++;
					#coopy/CompareTable.hx:276: characters 21-55
					$col = ($active_columns->arr[$k1] ?? null);
					#coopy/CompareTable.hx:277: characters 21-57
					$unit = ($common_units->arr[$col] ?? null);
					#coopy/CompareTable.hx:278: characters 21-52
					$index->addColumns($unit->l, $unit->r);
					#coopy/CompareTable.hx:279: lines 279-282
					if (!\array_key_exists($col, $added_columns->data)) {
						#coopy/CompareTable.hx:280: characters 25-52
						$align->addIndexColumns($unit);
						#coopy/CompareTable.hx:281: characters 25-52
						$added_columns->data[$col] = true;
					}
				}
				#coopy/CompareTable.hx:284: characters 17-41
				$index->indexTables($a, $b, 1);
				#coopy/CompareTable.hx:285: characters 17-48
				if ($k === ($top - 1)) {
					#coopy/CompareTable.hx:285: characters 31-40
					$index_top = $index;
				}
				#coopy/CompareTable.hx:287: characters 17-40
				$h = $a->get_height();
				#coopy/CompareTable.hx:288: characters 17-45
				if ($b->get_height() > $h) {
					#coopy/CompareTable.hx:288: characters 33-34
					$h = $b->get_height();
				}
				#coopy/CompareTable.hx:289: characters 17-31
				if ($h < 1) {
					#coopy/CompareTable.hx:289: characters 26-27
					$h = 1;
				}
				#coopy/CompareTable.hx:290: characters 17-62
				$wide_top_freq = $index->getTopFreq();
				#coopy/CompareTable.hx:291: characters 17-51
				$ratio = $wide_top_freq;
				#coopy/CompareTable.hx:292: characters 17-32
				$ratio /= $h + 20;
				#coopy/CompareTable.hx:293: lines 293-297
				if ($ratio >= 0.1) {
					#coopy/CompareTable.hx:295: characters 21-56
					if (($index_ct > 0) || ($k < ($top - 1))) {
						#coopy/CompareTable.hx:295: characters 48-56
						continue;
					}
				}
				#coopy/CompareTable.hx:299: characters 17-27
				++$index_ct;
				#coopy/CompareTable.hx:300: lines 300-302
				if ($this->indexes !== null) {
					#coopy/CompareTable.hx:301: characters 21-40
					$_this = $this->indexes;
					$_this->arr[$_this->length++] = $index;
				}
				#coopy/CompareTable.hx:304: characters 17-59
				$fixed = new \Array_hx();
				#coopy/CompareTable.hx:305: characters 27-41
				$data = \array_keys($pending->data);
				$j_current = 0;
				$j_length = \count($data);
				$j_data = $data;
				while ($j_current < $j_length) {
					#coopy/CompareTable.hx:305: lines 305-318
					$j = $j_data[$j_current++];
					#coopy/CompareTable.hx:306: characters 21-65
					$cross = $index->queryLocal($j);
					#coopy/CompareTable.hx:307: characters 21-53
					$spot_a = $cross->spot_a;
					#coopy/CompareTable.hx:308: characters 21-53
					$spot_b = $cross->spot_b;
					#coopy/CompareTable.hx:309: characters 21-57
					if (($spot_a !== 1) || ($spot_b !== 1)) {
						#coopy/CompareTable.hx:309: characters 49-57
						continue;
					}
					#coopy/CompareTable.hx:310: characters 21-52
					$val = ($cross->item_b->lst->arr[0] ?? null);
					#coopy/CompareTable.hx:311: lines 311-317
					if (!\array_key_exists($val, $used->data)) {
						#coopy/CompareTable.hx:312: characters 25-38
						$fixed->arr[$fixed->length++] = $j;
						#coopy/CompareTable.hx:313: characters 25-42
						$align->link($j, $val);
						#coopy/CompareTable.hx:314: characters 25-40
						$used->data[$val] = 1;
						#coopy/CompareTable.hx:315: characters 25-74
						if (!\array_key_exists($j, $used_reverse->data)) {
							#coopy/CompareTable.hx:315: characters 54-74
							--$reverse_pending_ct;
						}
						#coopy/CompareTable.hx:316: characters 25-46
						$used_reverse->data[$j] = 1;
					}
				}
				#coopy/CompareTable.hx:319: characters 27-31
				$_g4 = 0;
				#coopy/CompareTable.hx:319: characters 31-43
				$_g5 = $fixed->length;
				#coopy/CompareTable.hx:319: lines 319-322
				while ($_g4 < $_g5) {
					#coopy/CompareTable.hx:319: characters 27-43
					$j1 = $_g4++;
					#coopy/CompareTable.hx:320: characters 21-45
					$pending->remove(($fixed->arr[$j1] ?? null));
					#coopy/CompareTable.hx:321: characters 21-33
					--$pending_ct;
				}
			}
		}
		#coopy/CompareTable.hx:325: lines 325-391
		if ($index_top !== null) {
			#coopy/CompareTable.hx:329: characters 13-34
			$offset = 0;
			#coopy/CompareTable.hx:330: characters 13-33
			$scale = 1;
			#coopy/CompareTable.hx:331: characters 25-29
			$_g = 0;
			#coopy/CompareTable.hx:331: lines 331-359
			while ($_g < 2) {
				#coopy/CompareTable.hx:331: characters 25-30
				$sgn = $_g++;
				#coopy/CompareTable.hx:332: lines 332-356
				if ($pending_ct > 0) {
					#coopy/CompareTable.hx:333: characters 21-47
					$xb = null;
					#coopy/CompareTable.hx:334: characters 21-53
					if (($scale === -1) && ($hb > 0)) {
						#coopy/CompareTable.hx:334: characters 44-46
						$xb = $hb - 1;
					}
					#coopy/CompareTable.hx:335: characters 33-37
					$_g1 = 0;
					#coopy/CompareTable.hx:335: characters 37-39
					$_g2 = $ha;
					#coopy/CompareTable.hx:335: lines 335-355
					while ($_g1 < $_g2) {
						#coopy/CompareTable.hx:335: characters 33-39
						$xa0 = $_g1++;
						#coopy/CompareTable.hx:336: characters 25-59
						$xa = $xa0 * $scale + $offset;
						#coopy/CompareTable.hx:337: characters 25-61
						$xb2 = $align->a2b($xa);
						#coopy/CompareTable.hx:338: lines 338-342
						if ($xb2 !== null) {
							#coopy/CompareTable.hx:339: characters 29-31
							$xb = $xb2 + $scale;
							#coopy/CompareTable.hx:340: characters 29-52
							if (($xb >= $hb) || ($xb < 0)) {
								#coopy/CompareTable.hx:340: characters 47-52
								break;
							}
							#coopy/CompareTable.hx:341: characters 29-37
							continue;
						}
						#coopy/CompareTable.hx:343: characters 25-47
						if ($xb === null) {
							#coopy/CompareTable.hx:343: characters 39-47
							continue;
						}
						#coopy/CompareTable.hx:344: characters 25-57
						$ka = $index_top->localKey($xa);
						#coopy/CompareTable.hx:345: characters 25-58
						$kb = $index_top->remoteKey($xb);
						#coopy/CompareTable.hx:346: characters 25-45
						if ($ka !== $kb) {
							#coopy/CompareTable.hx:346: characters 37-45
							continue;
						}
						#coopy/CompareTable.hx:347: characters 25-54
						if (\array_key_exists($xb, $used->data)) {
							#coopy/CompareTable.hx:347: characters 46-54
							continue;
						}
						#coopy/CompareTable.hx:348: characters 25-42
						$align->link($xa, $xb);
						#coopy/CompareTable.hx:349: characters 25-39
						$used->data[$xb] = 1;
						#coopy/CompareTable.hx:350: characters 25-47
						$used_reverse->data[$xa] = 1;
						#coopy/CompareTable.hx:351: characters 25-37
						--$pending_ct;
						#coopy/CompareTable.hx:352: characters 25-34
						$xb += $scale;
						#coopy/CompareTable.hx:353: characters 25-48
						if (($xb >= $hb) || ($xb < 0)) {
							#coopy/CompareTable.hx:353: characters 43-48
							break;
						}
						#coopy/CompareTable.hx:354: characters 25-49
						if ($pending_ct === 0) {
							#coopy/CompareTable.hx:354: characters 44-49
							break;
						}
					}
				}
				#coopy/CompareTable.hx:357: characters 17-23
				$offset = $ha - 1;
				#coopy/CompareTable.hx:358: characters 17-22
				$scale = -1;
			}
			#coopy/CompareTable.hx:360: characters 13-19
			$offset = 0;
			#coopy/CompareTable.hx:361: characters 13-18
			$scale = 1;
			#coopy/CompareTable.hx:362: characters 25-29
			$_g = 0;
			#coopy/CompareTable.hx:362: lines 362-390
			while ($_g < 2) {
				#coopy/CompareTable.hx:362: characters 25-30
				$sgn = $_g++;
				#coopy/CompareTable.hx:363: lines 363-387
				if ($reverse_pending_ct > 0) {
					#coopy/CompareTable.hx:364: characters 21-47
					$xa = null;
					#coopy/CompareTable.hx:365: characters 21-53
					if (($scale === -1) && ($ha > 0)) {
						#coopy/CompareTable.hx:365: characters 44-46
						$xa = $ha - 1;
					}
					#coopy/CompareTable.hx:366: characters 33-37
					$_g1 = 0;
					#coopy/CompareTable.hx:366: characters 37-39
					$_g2 = $hb;
					#coopy/CompareTable.hx:366: lines 366-386
					while ($_g1 < $_g2) {
						#coopy/CompareTable.hx:366: characters 33-39
						$xb0 = $_g1++;
						#coopy/CompareTable.hx:367: characters 25-59
						$xb = $xb0 * $scale + $offset;
						#coopy/CompareTable.hx:368: characters 25-61
						$xa2 = $align->b2a($xb);
						#coopy/CompareTable.hx:369: lines 369-373
						if ($xa2 !== null) {
							#coopy/CompareTable.hx:370: characters 29-31
							$xa = $xa2 + $scale;
							#coopy/CompareTable.hx:371: characters 29-52
							if (($xa >= $ha) || ($xa < 0)) {
								#coopy/CompareTable.hx:371: characters 47-52
								break;
							}
							#coopy/CompareTable.hx:372: characters 29-37
							continue;
						}
						#coopy/CompareTable.hx:374: characters 25-47
						if ($xa === null) {
							#coopy/CompareTable.hx:374: characters 39-47
							continue;
						}
						#coopy/CompareTable.hx:375: characters 25-57
						$ka = $index_top->localKey($xa);
						#coopy/CompareTable.hx:376: characters 25-58
						$kb = $index_top->remoteKey($xb);
						#coopy/CompareTable.hx:377: characters 25-45
						if ($ka !== $kb) {
							#coopy/CompareTable.hx:377: characters 37-45
							continue;
						}
						#coopy/CompareTable.hx:378: characters 25-62
						if (\array_key_exists($xa, $used_reverse->data)) {
							#coopy/CompareTable.hx:378: characters 54-62
							continue;
						}
						#coopy/CompareTable.hx:379: characters 25-42
						$align->link($xa, $xb);
						#coopy/CompareTable.hx:380: characters 25-39
						$used->data[$xb] = 1;
						#coopy/CompareTable.hx:381: characters 25-47
						$used_reverse->data[$xa] = 1;
						#coopy/CompareTable.hx:382: characters 25-45
						--$reverse_pending_ct;
						#coopy/CompareTable.hx:383: characters 25-34
						$xa += $scale;
						#coopy/CompareTable.hx:384: characters 25-48
						if (($xa >= $ha) || ($xa < 0)) {
							#coopy/CompareTable.hx:384: characters 43-48
							break;
						}
						#coopy/CompareTable.hx:385: characters 25-57
						if ($reverse_pending_ct === 0) {
							#coopy/CompareTable.hx:385: characters 52-57
							break;
						}
					}
				}
				#coopy/CompareTable.hx:388: characters 17-23
				$offset = $hb - 1;
				#coopy/CompareTable.hx:389: characters 17-22
				$scale = -1;
			}
		}
		#coopy/CompareTable.hx:393: characters 19-23
		$_g = 1;
		#coopy/CompareTable.hx:393: characters 23-25
		$_g1 = $ha;
		#coopy/CompareTable.hx:393: lines 393-397
		while ($_g < $_g1) {
			#coopy/CompareTable.hx:393: characters 19-25
			$i = $_g++;
			#coopy/CompareTable.hx:394: lines 394-396
			if (!\array_key_exists($i, $used_reverse->data)) {
				#coopy/CompareTable.hx:395: characters 17-33
				$align->link($i, -1);
			}
		}
		#coopy/CompareTable.hx:398: characters 19-23
		$_g = 1;
		#coopy/CompareTable.hx:398: characters 23-25
		$_g1 = $hb;
		#coopy/CompareTable.hx:398: lines 398-402
		while ($_g < $_g1) {
			#coopy/CompareTable.hx:398: characters 19-25
			$i = $_g++;
			#coopy/CompareTable.hx:399: lines 399-401
			if (!\array_key_exists($i, $used->data)) {
				#coopy/CompareTable.hx:400: characters 17-33
				$align->link(-1, $i);
			}
		}
		#coopy/CompareTable.hx:404: lines 404-407
		if (($ha > 0) && ($hb > 0)) {
			#coopy/CompareTable.hx:405: characters 13-28
			$align->link(0, 0);
			#coopy/CompareTable.hx:406: characters 13-31
			$align->headers(0, 0);
		}
	}

	/**
	 * @return bool
	 */
	public function compareCore () {
		#coopy/CompareTable.hx:595: characters 9-41
		if ($this->comp->completed) {
			#coopy/CompareTable.hx:595: characters 29-41
			return false;
		}
		#coopy/CompareTable.hx:596: lines 596-598
		if (!$this->comp->is_equal_known) {
			#coopy/CompareTable.hx:597: characters 13-33
			return $this->testIsEqual();
		}
		#coopy/CompareTable.hx:599: lines 599-601
		if (!$this->comp->has_same_columns_known) {
			#coopy/CompareTable.hx:600: characters 13-40
			return $this->testHasSameColumns();
		}
		#coopy/CompareTable.hx:602: characters 9-30
		$this->comp->completed = true;
		#coopy/CompareTable.hx:603: characters 9-21
		return false;
	}

	/**
	 *
	 * @return the state of the comparison (the tables involved, if the
	 * comparison has completed, etc)
	 *
	 * 
	 * @return TableComparisonState
	 */
	public function getComparisonState () {
		#coopy/CompareTable.hx:80: characters 9-20
		return $this->comp;
	}

	/**
	 *
	 * Access the indexes generated during the comparison.
	 * The `storeIndexes()` method must be called before the
	 * comparison.
	 *
	 * @return the indexes generated during the comparison after
	 * the `storeIndexes()` method was called, or null if it
	 * was never called.
	 *
	 * 
	 * @return IndexPair[]|\Array_hx
	 */
	public function getIndexes () {
		#coopy/CompareTable.hx:630: characters 9-23
		return $this->indexes;
	}

	/**
	 * @param Table $a
	 * @param Table $b
	 * 
	 * @return bool
	 */
	public function hasSameColumns2 ($a, $b) {
		#coopy/CompareTable.hx:531: lines 531-533
		if ($a->get_width() !== $b->get_width()) {
			#coopy/CompareTable.hx:532: characters 13-25
			return false;
		}
		#coopy/CompareTable.hx:534: lines 534-536
		if (($a->get_height() === 0) || ($b->get_height() === 0)) {
			#coopy/CompareTable.hx:535: characters 13-24
			return true;
		}
		#coopy/CompareTable.hx:540: characters 9-41
		$av = $a->getCellView();
		#coopy/CompareTable.hx:541: characters 19-23
		$_g = 0;
		#coopy/CompareTable.hx:541: characters 23-30
		$_g1 = $a->get_width();
		#coopy/CompareTable.hx:541: lines 541-550
		while ($_g < $_g1) {
			#coopy/CompareTable.hx:541: characters 19-30
			$i = $_g++;
			#coopy/CompareTable.hx:542: characters 23-28
			$_g2 = $i + 1;
			#coopy/CompareTable.hx:542: characters 31-38
			$_g3 = $a->get_width();
			#coopy/CompareTable.hx:542: lines 542-546
			while ($_g2 < $_g3) {
				#coopy/CompareTable.hx:542: characters 23-38
				$j = $_g2++;
				#coopy/CompareTable.hx:543: lines 543-545
				if ($av->equals($a->getCell($i, 0), $a->getCell($j, 0))) {
					#coopy/CompareTable.hx:544: characters 21-33
					return false;
				}
			}
			#coopy/CompareTable.hx:547: lines 547-549
			if (!$av->equals($a->getCell($i, 0), $b->getCell($i, 0))) {
				#coopy/CompareTable.hx:548: characters 17-29
				return false;
			}
		}
		#coopy/CompareTable.hx:552: characters 9-20
		return true;
	}

	/**
	 * @param Table $a
	 * @param Table $b
	 * 
	 * @return bool
	 */
	public function isEqual2 ($a, $b) {
		#coopy/CompareTable.hx:580: lines 580-582
		if (($a->get_width() !== $b->get_width()) || ($a->get_height() !== $b->get_height())) {
			#coopy/CompareTable.hx:581: characters 13-25
			return false;
		}
		#coopy/CompareTable.hx:583: characters 9-41
		$av = $a->getCellView();
		#coopy/CompareTable.hx:584: characters 19-23
		$_g = 0;
		#coopy/CompareTable.hx:584: characters 23-31
		$_g1 = $a->get_height();
		#coopy/CompareTable.hx:584: lines 584-590
		while ($_g < $_g1) {
			#coopy/CompareTable.hx:584: characters 19-31
			$i = $_g++;
			#coopy/CompareTable.hx:585: characters 23-27
			$_g2 = 0;
			#coopy/CompareTable.hx:585: characters 27-34
			$_g3 = $a->get_width();
			#coopy/CompareTable.hx:585: lines 585-589
			while ($_g2 < $_g3) {
				#coopy/CompareTable.hx:585: characters 23-34
				$j = $_g2++;
				#coopy/CompareTable.hx:586: lines 586-588
				if (!$av->equals($a->getCell($j, $i), $b->getCell($j, $i))) {
					#coopy/CompareTable.hx:587: characters 21-33
					return false;
				}
			}
		}
		#coopy/CompareTable.hx:591: characters 9-20
		return true;
	}

	/**
	 *
	 * Run or continue the comparison.
	 *
	 * @return true if `run()` needs to be called again to do more work
	 *
	 * 
	 * @return bool
	 */
	public function run () {
		#coopy/CompareTable.hx:41: lines 41-44
		if ($this->useSql()) {
			#coopy/CompareTable.hx:42: characters 13-34
			$this->comp->completed = true;
			#coopy/CompareTable.hx:43: characters 13-25
			return false;
		}
		#coopy/CompareTable.hx:45: characters 9-41
		$more = $this->compareCore();
		#coopy/CompareTable.hx:46: lines 46-48
		while ($more && $this->comp->run_to_completion) {
			#coopy/CompareTable.hx:47: characters 13-33
			$more = $this->compareCore();
		}
		#coopy/CompareTable.hx:49: characters 9-21
		return !$more;
	}

	/**
	 *
	 * During a comparison, we generate a set of indexes that help
	 * relate the tables to each other.  Normally these will be
	 * discarded as soon as possible in order to save memory.
	 * If you'd like the indexes kept, call this method.
	 *
	 * 
	 * @return void
	 */
	public function storeIndexes () {
		#coopy/CompareTable.hx:615: characters 9-41
		$this->indexes = new \Array_hx();
	}

	/**
	 * @return bool
	 */
	public function testHasSameColumns () {
		#coopy/CompareTable.hx:518: characters 9-32
		$p = $this->comp->p;
		#coopy/CompareTable.hx:519: characters 9-32
		$a = $this->comp->a;
		#coopy/CompareTable.hx:520: characters 9-32
		$b = $this->comp->b;
		#coopy/CompareTable.hx:521: characters 9-46
		$eq = $this->hasSameColumns2($a, $b);
		#coopy/CompareTable.hx:522: lines 522-524
		if ($eq && ($p !== null)) {
			#coopy/CompareTable.hx:523: characters 13-38
			$eq = $this->hasSameColumns2($p, $a);
		}
		#coopy/CompareTable.hx:525: characters 9-35
		$this->comp->has_same_columns = $eq;
		#coopy/CompareTable.hx:526: characters 9-43
		$this->comp->has_same_columns_known = true;
		#coopy/CompareTable.hx:527: characters 9-20
		return true;
	}

	/**
	 * @return bool
	 */
	public function testIsEqual () {
		#coopy/CompareTable.hx:556: characters 9-32
		$p = $this->comp->p;
		#coopy/CompareTable.hx:557: characters 9-32
		$a = $this->comp->a;
		#coopy/CompareTable.hx:558: characters 9-32
		$b = $this->comp->b;
		#coopy/CompareTable.hx:559: characters 9-23
		$this->comp->getMeta();
		#coopy/CompareTable.hx:560: characters 9-28
		$nested = false;
		#coopy/CompareTable.hx:561: characters 9-73
		if ($this->comp->p_meta !== null) {
			#coopy/CompareTable.hx:561: characters 32-73
			if ($this->comp->p_meta->isNested()) {
				#coopy/CompareTable.hx:561: characters 60-73
				$nested = true;
			}
		}
		#coopy/CompareTable.hx:562: characters 9-73
		if ($this->comp->a_meta !== null) {
			#coopy/CompareTable.hx:562: characters 32-73
			if ($this->comp->a_meta->isNested()) {
				#coopy/CompareTable.hx:562: characters 60-73
				$nested = true;
			}
		}
		#coopy/CompareTable.hx:563: characters 9-73
		if ($this->comp->b_meta !== null) {
			#coopy/CompareTable.hx:563: characters 32-73
			if ($this->comp->b_meta->isNested()) {
				#coopy/CompareTable.hx:563: characters 60-73
				$nested = true;
			}
		}
		#coopy/CompareTable.hx:564: lines 564-569
		if ($nested) {
			#coopy/CompareTable.hx:566: characters 13-34
			$this->comp->is_equal = false;
			#coopy/CompareTable.hx:567: characters 13-39
			$this->comp->is_equal_known = true;
			#coopy/CompareTable.hx:568: characters 13-24
			return true;
		}
		#coopy/CompareTable.hx:570: characters 9-39
		$eq = $this->isEqual2($a, $b);
		#coopy/CompareTable.hx:571: lines 571-573
		if ($eq && ($p !== null)) {
			#coopy/CompareTable.hx:572: characters 13-31
			$eq = $this->isEqual2($p, $a);
		}
		#coopy/CompareTable.hx:574: characters 9-27
		$this->comp->is_equal = $eq;
		#coopy/CompareTable.hx:575: characters 9-35
		$this->comp->is_equal_known = true;
		#coopy/CompareTable.hx:576: characters 9-20
		return true;
	}

	/**
	 * @return bool
	 */
	public function useSql () {
		#coopy/CompareTable.hx:634: characters 9-53
		if ($this->comp->compare_flags === null) {
			#coopy/CompareTable.hx:634: characters 41-53
			return false;
		}
		#coopy/CompareTable.hx:635: characters 9-23
		$this->comp->getMeta();
		#coopy/CompareTable.hx:636: characters 9-24
		$sql = true;
		#coopy/CompareTable.hx:637: characters 9-69
		if ($this->comp->p_meta !== null) {
			#coopy/CompareTable.hx:637: characters 32-69
			if (!$this->comp->p_meta->isSql()) {
				#coopy/CompareTable.hx:637: characters 58-69
				$sql = false;
			}
		}
		#coopy/CompareTable.hx:638: characters 9-69
		if ($this->comp->a_meta !== null) {
			#coopy/CompareTable.hx:638: characters 32-69
			if (!$this->comp->a_meta->isSql()) {
				#coopy/CompareTable.hx:638: characters 58-69
				$sql = false;
			}
		}
		#coopy/CompareTable.hx:639: characters 9-69
		if ($this->comp->b_meta !== null) {
			#coopy/CompareTable.hx:639: characters 32-69
			if (!$this->comp->b_meta->isSql()) {
				#coopy/CompareTable.hx:639: characters 58-69
				$sql = false;
			}
		}
		#coopy/CompareTable.hx:640: characters 9-59
		if (($this->comp->p !== null) && ($this->comp->p_meta === null)) {
			#coopy/CompareTable.hx:640: characters 48-59
			$sql = false;
		}
		#coopy/CompareTable.hx:641: characters 9-59
		if (($this->comp->a !== null) && ($this->comp->a_meta === null)) {
			#coopy/CompareTable.hx:641: characters 48-59
			$sql = false;
		}
		#coopy/CompareTable.hx:642: characters 9-59
		if (($this->comp->b !== null) && ($this->comp->b_meta === null)) {
			#coopy/CompareTable.hx:642: characters 48-59
			$sql = false;
		}
		#coopy/CompareTable.hx:643: characters 9-19
		return $sql;
	}
}

Boot::registerClass(CompareTable::class, 'coopy.CompareTable');
