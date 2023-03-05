<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\IntMap;

/**
 *
 * Store the relationship between tables. Answers the question: where
 * does a row/column of table A appear in table B?
 *
 */
class Alignment {
	/**
	 * @var TableComparisonState
	 */
	public $comp;
	/**
	 * @var int
	 */
	public $ha;
	/**
	 * @var bool
	 */
	public $has_addition;
	/**
	 * @var bool
	 */
	public $has_removal;
	/**
	 * @var int
	 */
	public $hb;
	/**
	 * @var int
	 */
	public $ia;
	/**
	 * @var int
	 */
	public $ib;
	/**
	 * @var Unit[]|\Array_hx
	 */
	public $index_columns;
	/**
	 * @var IntMap
	 */
	public $map_a2b;
	/**
	 * @var IntMap
	 */
	public $map_b2a;
	/**
	 * @var int
	 */
	public $map_count;
	/**
	 * @var bool
	 */
	public $marked_as_identical;
	/**
	 * @var Alignment
	 */
	public $meta;
	/**
	 * @var Ordering
	 */
	public $order_cache;
	/**
	 * @var bool
	 */
	public $order_cache_has_reference;
	/**
	 * @var Alignment
	 */
	public $reference;
	/**
	 * @var Table
	 */
	public $ta;
	/**
	 * @var Table
	 */
	public $tb;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/Alignment.hx:35: characters 9-37
		$this->map_a2b = new IntMap();
		#coopy/Alignment.hx:36: characters 9-37
		$this->map_b2a = new IntMap();
		#coopy/Alignment.hx:37: characters 9-20
		$this->ha = $this->hb = 0;
		#coopy/Alignment.hx:38: characters 9-22
		$this->map_count = 0;
		#coopy/Alignment.hx:39: characters 9-25
		$this->reference = null;
		#coopy/Alignment.hx:40: characters 9-20
		$this->meta = null;
		#coopy/Alignment.hx:41: characters 9-20
		$this->comp = null;
		#coopy/Alignment.hx:42: characters 9-42
		$this->order_cache_has_reference = false;
		#coopy/Alignment.hx:43: characters 9-16
		$this->ia = -1;
		#coopy/Alignment.hx:44: characters 9-16
		$this->ib = -1;
		#coopy/Alignment.hx:45: characters 9-36
		$this->marked_as_identical = false;
	}

	/**
	 *
	 * @return given a row/column number in table A, this returns
	 * the row/column number in table B (or null if not in that table)
	 *
	 * 
	 * @param int $a
	 * 
	 * @return int
	 */
	public function a2b ($a) {
		#coopy/Alignment.hx:150: characters 16-30
		return ($this->map_a2b->data[$a] ?? null);
	}

	/**
	 *
	 * Record a column as being important for identifying rows.
	 * This is important for making sure it gets preserved in
	 * diffs, for example.
	 *
	 * @param unit the column's location in table A (l/left) and
	 * in table B (r/right).
	 *
	 * 
	 * @param Unit $unit
	 * 
	 * @return void
	 */
	public function addIndexColumns ($unit) {
		#coopy/Alignment.hx:128: lines 128-130
		if ($this->index_columns === null) {
			#coopy/Alignment.hx:129: characters 13-46
			$this->index_columns = new \Array_hx();
		}
		#coopy/Alignment.hx:131: characters 9-33
		$_this = $this->index_columns;
		$_this->arr[$_this->length++] = $unit;
	}

	/**
	 *
	 * Manually set an ordered version of the alignment.
	 * @param l row/column number in local table
	 * @param r row/column number in remote table
	 * @param p row/column number in parent table (if there is one)
	 *
	 * 
	 * @param int $l
	 * @param int $r
	 * @param int $p
	 * 
	 * @return void
	 */
	public function addToOrder ($l, $r, $p = -2) {
		#coopy/Alignment.hx:212: lines 212-216
		if ($p === null) {
			$p = -2;
		}
		#coopy/Alignment.hx:213: characters 9-60
		if ($this->order_cache === null) {
			#coopy/Alignment.hx:213: characters 32-60
			$this->order_cache = new Ordering();
		}
		#coopy/Alignment.hx:214: characters 9-31
		$this->order_cache->add($l, $r, $p);
		#coopy/Alignment.hx:215: characters 9-44
		$this->order_cache_has_reference = $p !== -2;
	}

	/**
	 *
	 * @return given a row/column number in table B, this returns
	 * the row/column number in table A (or null if not in that table)
	 *
	 * 
	 * @param int $b
	 * 
	 * @return int
	 */
	public function b2a ($b) {
		#coopy/Alignment.hx:160: characters 16-30
		return ($this->map_b2a->data[$b] ?? null);
	}

	/**
	 *
	 * @return a count of how many row/columns have been linked
	 *
	 * 
	 * @return int
	 */
	public function count () {
		#coopy/Alignment.hx:169: characters 9-25
		return $this->map_count;
	}

	/**
	 *
	 * @return a list of columns important for identifying rows
	 *
	 * 
	 * @return Unit[]|\Array_hx
	 */
	public function getIndexColumns () {
		#coopy/Alignment.hx:140: characters 9-29
		return $this->index_columns;
	}

	/**
	 *
	 * @return table A
	 *
	 * 
	 * @return Table
	 */
	public function getSource () {
		#coopy/Alignment.hx:224: characters 9-18
		return $this->ta;
	}

	/**
	 *
	 * Get the header row for table A, if present.
	 *
	 * @return header row for table A, or -1 if not present or not applicable
	 *
	 * 
	 * @return int
	 */
	public function getSourceHeader () {
		#coopy/Alignment.hx:244: characters 9-18
		return $this->ia;
	}

	/**
	 *
	 * @return table B
	 *
	 * 
	 * @return Table
	 */
	public function getTarget () {
		#coopy/Alignment.hx:233: characters 9-18
		return $this->tb;
	}

	/**
	 *
	 * Get the header row for table B, if present.
	 *
	 * @return header row for table B, or -1 if not present or not applicable
	 *
	 * 
	 * @return int
	 */
	public function getTargetHeader () {
		#coopy/Alignment.hx:255: characters 9-18
		return $this->ib;
	}

	/**
	 *
	 * Mark the header rows of tables A and B, if present.
	 * Not applicable for column alignments.
	 *
	 * @param ia index of the header row of table A
	 * @param ia index of the header row of table B
	 *
	 * 
	 * @param int $ia
	 * @param int $ib
	 * 
	 * @return void
	 */
	public function headers ($ia, $ib) {
		#coopy/Alignment.hx:80: characters 9-21
		$this->ia = $ia;
		#coopy/Alignment.hx:81: characters 9-21
		$this->ib = $ib;
	}

	/**
	 * @return bool
	 */
	public function isMarkedAsIdentical () {
		#coopy/Alignment.hx:405: characters 9-35
		return $this->marked_as_identical;
	}

	/**
	 *
	 * Declare the specified rows/columns to be the "same" row/column
	 * in the two tables.
	 *
	 * @param a row/column in table A
	 * @param b row/column in table B
	 *
	 * 
	 * @param int $a
	 * @param int $b
	 * 
	 * @return void
	 */
	public function link ($a, $b) {
		#coopy/Alignment.hx:104: lines 104-108
		if ($a !== -1) {
			#coopy/Alignment.hx:105: characters 13-29
			$this->map_a2b->data[$a] = $b;
		} else {
			#coopy/Alignment.hx:107: characters 13-32
			$this->has_addition = true;
		}
		#coopy/Alignment.hx:109: lines 109-113
		if ($b !== -1) {
			#coopy/Alignment.hx:110: characters 13-29
			$this->map_b2a->data[$b] = $a;
		} else {
			#coopy/Alignment.hx:112: characters 13-31
			$this->has_removal = true;
		}
		#coopy/Alignment.hx:114: characters 9-20
		$this->map_count++;
	}

	/**
	 * @return void
	 */
	public function markIdentical () {
		#coopy/Alignment.hx:401: characters 9-35
		$this->marked_as_identical = true;
	}

	/**
	 *
	 * Record the heights of tables A and B.
	 *
	 * 
	 * @param int $ha
	 * @param int $hb
	 * 
	 * @return void
	 */
	public function range ($ha, $hb) {
		#coopy/Alignment.hx:54: characters 9-21
		$this->ha = $ha;
		#coopy/Alignment.hx:55: characters 9-21
		$this->hb = $hb;
	}

	/**
	 *
	 * Set whether we are aligning rows or columns.
	 *
	 * @param flag true when aligning rows, false when aligning columns
	 *
	 * 
	 * @param bool $flag
	 * 
	 * @return void
	 */
	public function setRowlike ($flag) {
	}

	/**
	 *
	 * Keep references to tables A and B.  The `Alignment` class never
	 * looks at these tables itself, these references are stored only
	 * for the convenience of users of the alignment.
	 *
	 * 
	 * @param Table $ta
	 * @param Table $tb
	 * 
	 * @return void
	 */
	public function tables ($ta, $tb) {
		#coopy/Alignment.hx:66: characters 9-21
		$this->ta = $ta;
		#coopy/Alignment.hx:67: characters 9-21
		$this->tb = $tb;
	}

	/**
	 *
	 * @return an ordered version of the alignment, as a merged list
	 * of rows/columns
	 *
	 * 
	 * @return Ordering
	 */
	public function toOrder () {
		#coopy/Alignment.hx:192: lines 192-198
		if ($this->order_cache !== null) {
			#coopy/Alignment.hx:193: lines 193-197
			if ($this->reference !== null) {
				#coopy/Alignment.hx:194: lines 194-196
				if (!$this->order_cache_has_reference) {
					#coopy/Alignment.hx:195: characters 21-39
					$this->order_cache = null;
				}
			}
		}
		#coopy/Alignment.hx:199: characters 9-56
		if ($this->order_cache === null) {
			#coopy/Alignment.hx:199: characters 32-56
			$this->order_cache = $this->toOrder3();
		}
		#coopy/Alignment.hx:200: characters 9-62
		if ($this->reference !== null) {
			#coopy/Alignment.hx:200: characters 30-62
			$this->order_cache_has_reference = true;
		}
		#coopy/Alignment.hx:201: characters 9-27
		return $this->order_cache;
	}

	/**
	 * @return Ordering
	 */
	public function toOrder3 () {
		#coopy/Alignment.hx:259: characters 9-39
		$order = new \Array_hx();
		#coopy/Alignment.hx:260: lines 260-301
		if ($this->reference === null) {
			#coopy/Alignment.hx:261: characters 23-37
			$data = \array_keys($this->map_a2b->data);
			$k_current = 0;
			$k_length = \count($data);
			$k_data = $data;
			while ($k_current < $k_length) {
				#coopy/Alignment.hx:261: lines 261-266
				$k = $k_data[$k_current++];
				#coopy/Alignment.hx:262: characters 17-39
				$unit = new Unit();
				#coopy/Alignment.hx:263: characters 17-23
				$unit->l = $k;
				#coopy/Alignment.hx:264: characters 17-23
				$unit->r = $this->a2b($k);
				#coopy/Alignment.hx:265: characters 17-33
				$order->arr[$order->length++] = $unit;
			}
			#coopy/Alignment.hx:267: characters 23-37
			$data = \array_keys($this->map_b2a->data);
			$k_current = 0;
			$k_length = \count($data);
			$k_data = $data;
			while ($k_current < $k_length) {
				#coopy/Alignment.hx:267: lines 267-274
				$k = $k_data[$k_current++];
				#coopy/Alignment.hx:268: lines 268-273
				if ($this->b2a($k) === -1) {
					#coopy/Alignment.hx:269: characters 21-43
					$unit = new Unit();
					#coopy/Alignment.hx:270: characters 21-27
					$unit->l = -1;
					#coopy/Alignment.hx:271: characters 21-27
					$unit->r = $k;
					#coopy/Alignment.hx:272: characters 21-37
					$order->arr[$order->length++] = $unit;
				}
			}
		} else {
			#coopy/Alignment.hx:276: characters 23-37
			$data = \array_keys($this->map_a2b->data);
			$k_current = 0;
			$k_length = \count($data);
			$k_data = $data;
			while ($k_current < $k_length) {
				#coopy/Alignment.hx:276: lines 276-282
				$k = $k_data[$k_current++];
				#coopy/Alignment.hx:277: characters 17-39
				$unit = new Unit();
				#coopy/Alignment.hx:278: characters 17-23
				$unit->p = $k;
				#coopy/Alignment.hx:279: characters 17-23
				$unit->l = $this->reference->a2b($k);
				#coopy/Alignment.hx:280: characters 17-23
				$unit->r = $this->a2b($k);
				#coopy/Alignment.hx:281: characters 17-33
				$order->arr[$order->length++] = $unit;
			}
			#coopy/Alignment.hx:283: characters 23-47
			$data = \array_keys($this->reference->map_b2a->data);
			$k_current = 0;
			$k_length = \count($data);
			$k_data = $data;
			while ($k_current < $k_length) {
				#coopy/Alignment.hx:283: lines 283-291
				$k = $k_data[$k_current++];
				#coopy/Alignment.hx:284: lines 284-290
				if ($this->reference->b2a($k) === -1) {
					#coopy/Alignment.hx:285: characters 21-43
					$unit = new Unit();
					#coopy/Alignment.hx:286: characters 21-27
					$unit->p = -1;
					#coopy/Alignment.hx:287: characters 21-27
					$unit->l = $k;
					#coopy/Alignment.hx:288: characters 21-27
					$unit->r = -1;
					#coopy/Alignment.hx:289: characters 21-37
					$order->arr[$order->length++] = $unit;
				}
			}
			#coopy/Alignment.hx:292: characters 23-37
			$data = \array_keys($this->map_b2a->data);
			$k_current = 0;
			$k_length = \count($data);
			$k_data = $data;
			while ($k_current < $k_length) {
				#coopy/Alignment.hx:292: lines 292-300
				$k = $k_data[$k_current++];
				#coopy/Alignment.hx:293: lines 293-299
				if ($this->b2a($k) === -1) {
					#coopy/Alignment.hx:294: characters 21-43
					$unit = new Unit();
					#coopy/Alignment.hx:295: characters 21-27
					$unit->p = -1;
					#coopy/Alignment.hx:296: characters 21-27
					$unit->l = -1;
					#coopy/Alignment.hx:297: characters 21-27
					$unit->r = $k;
					#coopy/Alignment.hx:298: characters 21-37
					$order->arr[$order->length++] = $unit;
				}
			}
		}
		#coopy/Alignment.hx:302: characters 9-32
		$top = $order->length;
		#coopy/Alignment.hx:303: characters 9-40
		$remotes = new \Array_hx();
		#coopy/Alignment.hx:304: characters 9-39
		$locals = new \Array_hx();
		#coopy/Alignment.hx:305: characters 19-23
		$_g = 0;
		#coopy/Alignment.hx:305: characters 23-26
		$_g1 = $top;
		#coopy/Alignment.hx:305: lines 305-311
		while ($_g < $_g1) {
			#coopy/Alignment.hx:305: characters 19-26
			$o = $_g++;
			#coopy/Alignment.hx:306: lines 306-310
			if (($order->arr[$o] ?? null)->r >= 0) {
				#coopy/Alignment.hx:307: characters 17-32
				$remotes->arr[$remotes->length++] = $o;
			} else {
				#coopy/Alignment.hx:309: characters 17-31
				$locals->arr[$locals->length++] = $o;
			}
		}
		#coopy/Alignment.hx:312: lines 312-314
		$remote_sort = function ($a, $b) use (&$order) {
			#coopy/Alignment.hx:313: characters 13-41
			return ($order->arr[$a] ?? null)->r - ($order->arr[$b] ?? null)->r;
		};
		#coopy/Alignment.hx:315: lines 315-323
		$local_sort = function ($a, $b) use (&$order) {
			#coopy/Alignment.hx:316: characters 13-31
			if ($a === $b) {
				#coopy/Alignment.hx:316: characters 23-31
				return 0;
			}
			#coopy/Alignment.hx:317: lines 317-319
			if ((($order->arr[$a] ?? null)->l >= 0) && (($order->arr[$b] ?? null)->l >= 0)) {
				#coopy/Alignment.hx:318: characters 17-45
				return ($order->arr[$a] ?? null)->l - ($order->arr[$b] ?? null)->l;
			}
			#coopy/Alignment.hx:320: characters 13-40
			if (($order->arr[$a] ?? null)->l >= 0) {
				#coopy/Alignment.hx:320: characters 32-40
				return 1;
			}
			#coopy/Alignment.hx:321: characters 13-41
			if (($order->arr[$b] ?? null)->l >= 0) {
				#coopy/Alignment.hx:321: characters 32-41
				return -1;
			}
			#coopy/Alignment.hx:322: characters 13-23
			return $a - $b;
		};
		#coopy/Alignment.hx:324: lines 324-354
		if ($this->reference !== null) {
			#coopy/Alignment.hx:325: characters 13-24
			$remote_sort = function ($a, $b) use (&$order) {
				#coopy/Alignment.hx:326: characters 17-35
				if ($a === $b) {
					#coopy/Alignment.hx:326: characters 27-35
					return 0;
				}
				#coopy/Alignment.hx:327: characters 17-48
				$o1 = ($order->arr[$a] ?? null)->r - ($order->arr[$b] ?? null)->r;
				#coopy/Alignment.hx:328: lines 328-335
				if ((($order->arr[$a] ?? null)->p >= 0) && (($order->arr[$b] ?? null)->p >= 0)) {
					#coopy/Alignment.hx:329: characters 21-52
					$o2 = ($order->arr[$a] ?? null)->p - ($order->arr[$b] ?? null)->p;
					#coopy/Alignment.hx:330: lines 330-332
					if (($o1 * $o2) < 0) {
						#coopy/Alignment.hx:331: characters 25-34
						return $o1;
					}
					#coopy/Alignment.hx:333: characters 21-52
					$o3 = ($order->arr[$a] ?? null)->l - ($order->arr[$b] ?? null)->l;
					#coopy/Alignment.hx:334: characters 21-30
					return $o3;
				}
				#coopy/Alignment.hx:336: characters 17-26
				return $o1;
			};
			#coopy/Alignment.hx:338: characters 13-23
			$local_sort = function ($a, $b) use (&$order) {
				#coopy/Alignment.hx:339: characters 17-35
				if ($a === $b) {
					#coopy/Alignment.hx:339: characters 27-35
					return 0;
				}
				#coopy/Alignment.hx:340: lines 340-349
				if ((($order->arr[$a] ?? null)->l >= 0) && (($order->arr[$b] ?? null)->l >= 0)) {
					#coopy/Alignment.hx:341: characters 21-52
					$o1 = ($order->arr[$a] ?? null)->l - ($order->arr[$b] ?? null)->l;
					#coopy/Alignment.hx:342: lines 342-348
					if ((($order->arr[$a] ?? null)->p >= 0) && (($order->arr[$b] ?? null)->p >= 0)) {
						#coopy/Alignment.hx:343: characters 25-56
						$o2 = ($order->arr[$a] ?? null)->p - ($order->arr[$b] ?? null)->p;
						#coopy/Alignment.hx:344: lines 344-346
						if (($o1 * $o2) < 0) {
							#coopy/Alignment.hx:345: characters 29-38
							return $o1;
						}
						#coopy/Alignment.hx:347: characters 25-34
						return $o2;
					}
				}
				#coopy/Alignment.hx:350: characters 17-44
				if (($order->arr[$a] ?? null)->l >= 0) {
					#coopy/Alignment.hx:350: characters 36-44
					return 1;
				}
				#coopy/Alignment.hx:351: characters 17-45
				if (($order->arr[$b] ?? null)->l >= 0) {
					#coopy/Alignment.hx:351: characters 36-45
					return -1;
				}
				#coopy/Alignment.hx:352: characters 17-27
				return $a - $b;
			};
		}
		#coopy/Alignment.hx:355: characters 9-34
		\usort($remotes->arr, $remote_sort);
		#coopy/Alignment.hx:356: characters 9-32
		\usort($locals->arr, $local_sort);
		#coopy/Alignment.hx:357: characters 9-47
		$revised_order = new \Array_hx();
		#coopy/Alignment.hx:358: characters 9-22
		$at_r = 0;
		#coopy/Alignment.hx:359: characters 9-22
		$at_l = 0;
		#coopy/Alignment.hx:360: characters 19-23
		$_g = 0;
		#coopy/Alignment.hx:360: characters 23-26
		$_g1 = $top;
		#coopy/Alignment.hx:360: lines 360-391
		while ($_g < $_g1) {
			#coopy/Alignment.hx:360: characters 19-26
			$o = $_g++;
			#coopy/Alignment.hx:361: lines 361-378
			if (($at_r < $remotes->length) && ($at_l < $locals->length)) {
				#coopy/Alignment.hx:362: characters 17-47
				$ur = ($order->arr[($remotes->arr[$at_r] ?? null)] ?? null);
				#coopy/Alignment.hx:363: characters 17-46
				$ul = ($order->arr[($locals->arr[$at_l] ?? null)] ?? null);
				#coopy/Alignment.hx:364: lines 364-374
				if (($ul->l === -1) && ($ul->p >= 0) && ($ur->p >= 0)) {
					#coopy/Alignment.hx:365: lines 365-369
					if ($ur->p > $ul->p) {
						#coopy/Alignment.hx:366: characters 25-47
						$revised_order->arr[$revised_order->length++] = $ul;
						#coopy/Alignment.hx:367: characters 25-31
						++$at_l;
						#coopy/Alignment.hx:368: characters 25-33
						continue;
					}
				} else if ($ur->l > $ul->l) {
					#coopy/Alignment.hx:371: characters 21-43
					$revised_order->arr[$revised_order->length++] = $ul;
					#coopy/Alignment.hx:372: characters 21-27
					++$at_l;
					#coopy/Alignment.hx:373: characters 21-29
					continue;
				}
				#coopy/Alignment.hx:375: characters 17-39
				$revised_order->arr[$revised_order->length++] = $ur;
				#coopy/Alignment.hx:376: characters 17-23
				++$at_r;
				#coopy/Alignment.hx:377: characters 17-25
				continue;
			}
			#coopy/Alignment.hx:379: lines 379-384
			if ($at_r < $remotes->length) {
				#coopy/Alignment.hx:380: characters 17-47
				$ur1 = ($order->arr[($remotes->arr[$at_r] ?? null)] ?? null);
				#coopy/Alignment.hx:381: characters 17-39
				$revised_order->arr[$revised_order->length++] = $ur1;
				#coopy/Alignment.hx:382: characters 17-23
				++$at_r;
				#coopy/Alignment.hx:383: characters 17-25
				continue;
			}
			#coopy/Alignment.hx:385: lines 385-390
			if ($at_l < $locals->length) {
				#coopy/Alignment.hx:386: characters 17-46
				$ul1 = ($order->arr[($locals->arr[$at_l] ?? null)] ?? null);
				#coopy/Alignment.hx:387: characters 17-39
				$revised_order->arr[$revised_order->length++] = $ul1;
				#coopy/Alignment.hx:388: characters 17-23
				++$at_l;
				#coopy/Alignment.hx:389: characters 17-25
				continue;
			}
		}
		#coopy/Alignment.hx:392: characters 9-14
		$order = $revised_order;
		#coopy/Alignment.hx:394: characters 9-37
		$result = new Ordering();
		#coopy/Alignment.hx:395: characters 9-30
		$result->setList($order);
		#coopy/Alignment.hx:396: characters 9-51
		if ($this->reference === null) {
			#coopy/Alignment.hx:396: characters 30-51
			$result->ignoreParent();
		}
		#coopy/Alignment.hx:397: characters 9-22
		return $result;
	}

	/**
	 *
	 * @return text representation of alignment
	 *
	 * 
	 * @return string
	 */
	public function toString () {
		#coopy/Alignment.hx:178: characters 9-54
		$result = "" . ((($this->map_a2b === null ? "null" : $this->map_a2b->toString()))??'null') . " // " . ((($this->map_b2a === null ? "null" : $this->map_b2a->toString()))??'null');
		#coopy/Alignment.hx:179: lines 179-181
		if ($this->reference !== null) {
			#coopy/Alignment.hx:180: characters 13-45
			$result = ($result??'null') . " (" . \Std::string($this->reference) . ")";
		}
		#coopy/Alignment.hx:182: characters 9-22
		return $result;
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(Alignment::class, 'coopy.Alignment');
