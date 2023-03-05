<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\Exception;
use \haxe\ds\StringMap;

class SqlCompare {
	/**
	 * @var Alignment
	 */
	public $align;
	/**
	 * @var SqlTable
	 */
	public $alt;
	/**
	 * @var bool
	 */
	public $alt_peered;
	/**
	 * @var int
	 */
	public $at0;
	/**
	 * @var int
	 */
	public $at1;
	/**
	 * @var int
	 */
	public $at2;
	/**
	 * @var SqlDatabase
	 */
	public $db;
	/**
	 * @var int
	 */
	public $diff_ct;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var SqlTable
	 */
	public $local;
	/**
	 * @var int[]|\Array_hx
	 */
	public $needed;
	/**
	 * @var bool
	 */
	public $peered;
	/**
	 * @var SqlTable
	 */
	public $remote;

	/**
	 * @param SqlDatabase $db
	 * @param SqlTable $local
	 * @param SqlTable $remote
	 * @param SqlTable $alt
	 * @param Alignment $align
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($db, $local, $remote, $alt, $align = null, $flags = null) {
		#coopy/SqlCompare.hx:26: characters 9-21
		$this->db = $db;
		#coopy/SqlCompare.hx:27: characters 9-27
		$this->local = $local;
		#coopy/SqlCompare.hx:28: characters 9-29
		$this->remote = $remote;
		#coopy/SqlCompare.hx:29: characters 9-23
		$this->alt = $alt;
		#coopy/SqlCompare.hx:30: characters 9-27
		$this->align = $align;
		#coopy/SqlCompare.hx:31: characters 9-27
		$this->flags = $flags;
		#coopy/SqlCompare.hx:32: lines 32-34
		if ($this->flags === null) {
			#coopy/SqlCompare.hx:33: characters 13-44
			$this->flags = new CompareFlags();
		}
		#coopy/SqlCompare.hx:35: characters 9-23
		$this->peered = false;
		#coopy/SqlCompare.hx:36: characters 9-27
		$this->alt_peered = false;
		#coopy/SqlCompare.hx:37: lines 37-45
		if (($local !== null) && ($remote !== null)) {
			#coopy/SqlCompare.hx:38: lines 38-44
			if ($this->remote->getDatabase()->getNameForAttachment() !== null) {
				#coopy/SqlCompare.hx:39: lines 39-43
				if ($this->remote->getDatabase()->getNameForAttachment() !== $this->local->getDatabase()->getNameForAttachment()) {
					#coopy/SqlCompare.hx:41: characters 21-123
					$local->getDatabase()->getHelper()->attach($db, "__peer__", $this->remote->getDatabase()->getNameForAttachment());
					#coopy/SqlCompare.hx:42: characters 21-34
					$this->peered = true;
				}
			}
		}
		#coopy/SqlCompare.hx:46: lines 46-54
		if (($this->alt !== null) && ($local !== null)) {
			#coopy/SqlCompare.hx:47: lines 47-53
			if ($this->alt->getDatabase()->getNameForAttachment() !== null) {
				#coopy/SqlCompare.hx:48: lines 48-52
				if ($this->alt->getDatabase()->getNameForAttachment() !== $this->local->getDatabase()->getNameForAttachment()) {
					#coopy/SqlCompare.hx:50: characters 21-119
					$local->getDatabase()->getHelper()->attach($db, "__alt__", $this->alt->getDatabase()->getNameForAttachment());
					#coopy/SqlCompare.hx:51: characters 21-38
					$this->alt_peered = true;
				}
			}
		}
	}

	/**
	 * @return Alignment
	 */
	public function apply () {
		#coopy/SqlCompare.hx:219: characters 9-34
		if ($this->db === null) {
			#coopy/SqlCompare.hx:219: characters 23-34
			return null;
		}
		#coopy/SqlCompare.hx:221: characters 9-49
		if ($this->align === null) {
			#coopy/SqlCompare.hx:221: characters 26-31
			$this->align = new Alignment();
		}
		#coopy/SqlCompare.hx:223: characters 9-43
		if (!$this->validateSchema()) {
			#coopy/SqlCompare.hx:223: characters 32-43
			return null;
		}
		#coopy/SqlCompare.hx:225: characters 9-46
		$rowid_name = $this->db->rowid();
		#coopy/SqlCompare.hx:227: characters 9-27
		$key_cols = new \Array_hx();
		#coopy/SqlCompare.hx:228: characters 9-28
		$data_cols = new \Array_hx();
		#coopy/SqlCompare.hx:229: characters 9-27
		$all_cols = new \Array_hx();
		#coopy/SqlCompare.hx:230: characters 9-28
		$all_cols1 = new \Array_hx();
		#coopy/SqlCompare.hx:231: characters 9-28
		$all_cols2 = new \Array_hx();
		#coopy/SqlCompare.hx:232: characters 9-28
		$all_cols3 = new \Array_hx();
		#coopy/SqlCompare.hx:233: characters 9-39
		$common = $this->local;
		#coopy/SqlCompare.hx:235: lines 235-253
		if ($this->local !== null) {
			#coopy/SqlCompare.hx:236: characters 13-21
			$key_cols = $this->local->getPrimaryKey();
			#coopy/SqlCompare.hx:237: characters 13-22
			$data_cols = $this->local->getAllButPrimaryKey();
			#coopy/SqlCompare.hx:238: characters 13-21
			$all_cols = $this->local->getColumnNames();
			#coopy/SqlCompare.hx:239: characters 13-22
			$all_cols1 = $this->local->getColumnNames();
			#coopy/SqlCompare.hx:240: lines 240-252
			if ($this->flags->ids !== null) {
				#coopy/SqlCompare.hx:241: characters 17-25
				$key_cols = $this->flags->getIdsByRole("local");
				#coopy/SqlCompare.hx:242: characters 17-26
				$data_cols = new \Array_hx();
				#coopy/SqlCompare.hx:243: characters 27-50
				$this1 = [];
				$pks_data = $this1;
				#coopy/SqlCompare.hx:244: lines 244-246
				$_g = 0;
				while ($_g < $key_cols->length) {
					#coopy/SqlCompare.hx:244: characters 22-25
					$col = ($key_cols->arr[$_g] ?? null);
					#coopy/SqlCompare.hx:244: lines 244-246
					++$_g;
					#coopy/SqlCompare.hx:245: characters 21-39
					$pks_data[$col] = true;
				}
				#coopy/SqlCompare.hx:247: lines 247-251
				$_g = 0;
				while ($_g < $all_cols->length) {
					#coopy/SqlCompare.hx:247: characters 22-25
					$col = ($all_cols->arr[$_g] ?? null);
					#coopy/SqlCompare.hx:247: lines 247-251
					++$_g;
					#coopy/SqlCompare.hx:248: lines 248-250
					if (!\array_key_exists($col, $pks_data)) {
						#coopy/SqlCompare.hx:249: characters 25-44
						$data_cols->arr[$data_cols->length++] = $col;
					}
				}
			}
		}
		#coopy/SqlCompare.hx:255: lines 255-258
		if ($this->remote !== null) {
			#coopy/SqlCompare.hx:256: characters 13-22
			$all_cols2 = $this->remote->getColumnNames();
			#coopy/SqlCompare.hx:257: characters 13-46
			if ($common === null) {
				#coopy/SqlCompare.hx:257: characters 31-37
				$common = $this->remote;
			}
		}
		#coopy/SqlCompare.hx:260: lines 260-265
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:261: characters 13-22
			$all_cols3 = $this->alt->getColumnNames();
			#coopy/SqlCompare.hx:262: characters 13-43
			if ($common === null) {
				#coopy/SqlCompare.hx:262: characters 31-37
				$common = $this->alt;
			}
		} else {
			#coopy/SqlCompare.hx:264: characters 13-22
			$all_cols3 = $all_cols2;
		}
		#coopy/SqlCompare.hx:267: characters 9-51
		$all_common_cols = new \Array_hx();
		#coopy/SqlCompare.hx:268: characters 9-52
		$data_common_cols = new \Array_hx();
		#coopy/SqlCompare.hx:270: characters 9-46
		$present1 = new StringMap();
		#coopy/SqlCompare.hx:271: characters 9-46
		$present2 = new StringMap();
		#coopy/SqlCompare.hx:272: characters 9-46
		$present3 = new StringMap();
		#coopy/SqlCompare.hx:273: characters 31-52
		$this1 = [];
		$present_primary_data = $this1;
		#coopy/SqlCompare.hx:274: characters 9-36
		$has_column_add = false;
		#coopy/SqlCompare.hx:276: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:276: characters 23-40
		$_g1 = $key_cols->length;
		#coopy/SqlCompare.hx:276: lines 276-278
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:276: characters 19-40
			$i = $_g++;
			#coopy/SqlCompare.hx:277: characters 13-47
			$present_primary_data[$key_cols[$i]] = $i;
		}
		#coopy/SqlCompare.hx:279: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:279: characters 23-41
		$_g1 = $all_cols1->length;
		#coopy/SqlCompare.hx:279: lines 279-282
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:279: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:280: characters 13-36
			$key = ($all_cols1->arr[$i] ?? null);
			#coopy/SqlCompare.hx:281: characters 13-32
			$present1->data[$key] = $i;
		}
		#coopy/SqlCompare.hx:283: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:283: characters 23-41
		$_g1 = $all_cols2->length;
		#coopy/SqlCompare.hx:283: lines 283-289
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:283: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:284: characters 13-36
			$key = ($all_cols2->arr[$i] ?? null);
			#coopy/SqlCompare.hx:285: lines 285-287
			if (!\array_key_exists($key, $present1->data)) {
				#coopy/SqlCompare.hx:286: characters 17-31
				$has_column_add = true;
			}
			#coopy/SqlCompare.hx:288: characters 13-32
			$present2->data[$key] = $i;
		}
		#coopy/SqlCompare.hx:290: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:290: characters 23-41
		$_g1 = $all_cols3->length;
		#coopy/SqlCompare.hx:290: lines 290-304
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:290: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:291: characters 13-36
			$key = ($all_cols3->arr[$i] ?? null);
			#coopy/SqlCompare.hx:292: lines 292-294
			if (!\array_key_exists($key, $present1->data)) {
				#coopy/SqlCompare.hx:293: characters 17-31
				$has_column_add = true;
			}
			#coopy/SqlCompare.hx:295: characters 13-32
			$present3->data[$key] = $i;
			#coopy/SqlCompare.hx:296: lines 296-303
			if (\array_key_exists($key, $present1->data)) {
				#coopy/SqlCompare.hx:297: lines 297-302
				if (\array_key_exists($key, $present2->data)) {
					#coopy/SqlCompare.hx:298: characters 21-46
					$all_common_cols->arr[$all_common_cols->length++] = $key;
					#coopy/SqlCompare.hx:299: lines 299-301
					if (!\array_key_exists($key, $present_primary_data)) {
						#coopy/SqlCompare.hx:300: characters 25-51
						$data_common_cols->arr[$data_common_cols->length++] = $key;
					}
				}
			}
		}
		#coopy/SqlCompare.hx:306: characters 9-19
		$this->align->meta = new Alignment();
		#coopy/SqlCompare.hx:307: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:307: characters 23-41
		$_g1 = $all_cols1->length;
		#coopy/SqlCompare.hx:307: lines 307-314
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:307: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:308: characters 13-36
			$key = ($all_cols1->arr[$i] ?? null);
			#coopy/SqlCompare.hx:309: lines 309-313
			if (\array_key_exists($key, $present2->data)) {
				#coopy/SqlCompare.hx:310: characters 17-53
				$this->align->meta->link($i, ($present2->data[$key] ?? null));
			} else {
				#coopy/SqlCompare.hx:312: characters 17-38
				$this->align->meta->link($i, -1);
			}
		}
		#coopy/SqlCompare.hx:315: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:315: characters 23-41
		$_g1 = $all_cols2->length;
		#coopy/SqlCompare.hx:315: lines 315-320
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:315: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:316: characters 13-36
			$key = ($all_cols2->arr[$i] ?? null);
			#coopy/SqlCompare.hx:317: lines 317-319
			if (!\array_key_exists($key, $present1->data)) {
				#coopy/SqlCompare.hx:318: characters 17-38
				$this->align->meta->link(-1, $i);
			}
		}
		#coopy/SqlCompare.hx:321: characters 9-74
		$this->scanColumns($all_cols1, $all_cols2, $key_cols, $present1, $present2, $this->align);
		#coopy/SqlCompare.hx:322: characters 9-35
		$this->align->tables($this->local, $this->remote);
		#coopy/SqlCompare.hx:323: lines 323-326
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:324: characters 13-88
			$this->scanColumns($all_cols1, $all_cols3, $key_cols, $present1, $present3, $this->align->reference);
			#coopy/SqlCompare.hx:325: characters 13-46
			$this->align->reference->tables($this->local, $this->alt);
		}
		#coopy/SqlCompare.hx:328: characters 9-29
		$sql_table1 = "";
		#coopy/SqlCompare.hx:329: characters 9-29
		$sql_table2 = "";
		#coopy/SqlCompare.hx:330: characters 9-29
		$sql_table3 = "";
		#coopy/SqlCompare.hx:331: lines 331-333
		if ($this->local !== null) {
			#coopy/SqlCompare.hx:332: characters 13-23
			$sql_table1 = $this->local->getQuotedTableName();
		}
		#coopy/SqlCompare.hx:334: lines 334-336
		if ($this->remote !== null) {
			#coopy/SqlCompare.hx:335: characters 13-23
			$sql_table2 = $this->remote->getQuotedTableName();
		}
		#coopy/SqlCompare.hx:337: lines 337-339
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:338: characters 13-23
			$sql_table3 = $this->alt->getQuotedTableName();
		}
		#coopy/SqlCompare.hx:340: lines 340-344
		if ($this->peered) {
			#coopy/SqlCompare.hx:342: characters 13-23
			$sql_table1 = "main." . ($sql_table1??'null');
			#coopy/SqlCompare.hx:343: characters 13-23
			$sql_table2 = "__peer__." . ($sql_table2??'null');
		}
		#coopy/SqlCompare.hx:345: lines 345-347
		if ($this->alt_peered) {
			#coopy/SqlCompare.hx:346: characters 13-23
			$sql_table2 = "__alt__." . ($sql_table3??'null');
		}
		#coopy/SqlCompare.hx:348: characters 9-39
		$sql_key_cols = "";
		#coopy/SqlCompare.hx:349: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:349: characters 23-40
		$_g1 = $key_cols->length;
		#coopy/SqlCompare.hx:349: lines 349-352
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:349: characters 19-40
			$i = $_g++;
			#coopy/SqlCompare.hx:350: characters 13-41
			if ($i > 0) {
				#coopy/SqlCompare.hx:350: characters 22-41
				$sql_key_cols = ($sql_key_cols??'null') . ",";
			}
			#coopy/SqlCompare.hx:351: characters 13-68
			$sql_key_cols = ($sql_key_cols??'null') . ($common->getQuotedColumnName(($key_cols->arr[$i] ?? null))??'null');
		}
		#coopy/SqlCompare.hx:353: characters 9-39
		$sql_all_cols = "";
		#coopy/SqlCompare.hx:354: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:354: characters 23-47
		$_g1 = $all_common_cols->length;
		#coopy/SqlCompare.hx:354: lines 354-357
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:354: characters 19-47
			$i = $_g++;
			#coopy/SqlCompare.hx:355: characters 13-41
			if ($i > 0) {
				#coopy/SqlCompare.hx:355: characters 22-41
				$sql_all_cols = ($sql_all_cols??'null') . ",";
			}
			#coopy/SqlCompare.hx:356: characters 13-75
			$sql_all_cols = ($sql_all_cols??'null') . ($common->getQuotedColumnName(($all_common_cols->arr[$i] ?? null))??'null');
		}
		#coopy/SqlCompare.hx:358: characters 9-40
		$sql_all_cols1 = "";
		#coopy/SqlCompare.hx:359: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:359: characters 23-41
		$_g1 = $all_cols1->length;
		#coopy/SqlCompare.hx:359: lines 359-362
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:359: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:360: characters 13-42
			if ($i > 0) {
				#coopy/SqlCompare.hx:360: characters 22-42
				$sql_all_cols1 = ($sql_all_cols1??'null') . ",";
			}
			#coopy/SqlCompare.hx:361: characters 13-88
			$sql_all_cols1 = ($sql_all_cols1??'null') . ($sql_table1??'null') . "." . ($this->local->getQuotedColumnName(($all_cols1->arr[$i] ?? null))??'null');
		}
		#coopy/SqlCompare.hx:363: characters 9-40
		$sql_all_cols2 = "";
		#coopy/SqlCompare.hx:364: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:364: characters 23-41
		$_g1 = $all_cols2->length;
		#coopy/SqlCompare.hx:364: lines 364-367
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:364: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:365: characters 13-42
			if ($i > 0) {
				#coopy/SqlCompare.hx:365: characters 22-42
				$sql_all_cols2 = ($sql_all_cols2??'null') . ",";
			}
			#coopy/SqlCompare.hx:366: characters 13-89
			$sql_all_cols2 = ($sql_all_cols2??'null') . ($sql_table2??'null') . "." . ($this->remote->getQuotedColumnName(($all_cols2->arr[$i] ?? null))??'null');
		}
		#coopy/SqlCompare.hx:368: characters 9-40
		$sql_all_cols3 = "";
		#coopy/SqlCompare.hx:369: lines 369-374
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:370: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:370: characters 27-45
			$_g1 = $all_cols3->length;
			#coopy/SqlCompare.hx:370: lines 370-373
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:370: characters 23-45
				$i = $_g++;
				#coopy/SqlCompare.hx:371: characters 17-46
				if ($i > 0) {
					#coopy/SqlCompare.hx:371: characters 26-46
					$sql_all_cols3 = ($sql_all_cols3??'null') . ",";
				}
				#coopy/SqlCompare.hx:372: characters 17-90
				$sql_all_cols3 = ($sql_all_cols3??'null') . ($sql_table3??'null') . "." . ($this->alt->getQuotedColumnName(($all_cols3->arr[$i] ?? null))??'null');
			}
		}
		#coopy/SqlCompare.hx:375: characters 9-40
		$sql_key_null = "";
		#coopy/SqlCompare.hx:376: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:376: characters 23-40
		$_g1 = $key_cols->length;
		#coopy/SqlCompare.hx:376: lines 376-380
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:376: characters 19-40
			$i = $_g++;
			#coopy/SqlCompare.hx:377: characters 13-45
			if ($i > 0) {
				#coopy/SqlCompare.hx:377: characters 22-45
				$sql_key_null = ($sql_key_null??'null') . " AND ";
			}
			#coopy/SqlCompare.hx:378: characters 13-70
			$n = $common->getQuotedColumnName(($key_cols->arr[$i] ?? null));
			#coopy/SqlCompare.hx:379: characters 13-62
			$sql_key_null = ($sql_key_null??'null') . ($sql_table1??'null') . "." . ($n??'null') . " IS NULL";
		}
		#coopy/SqlCompare.hx:381: characters 9-41
		$sql_key_null2 = "";
		#coopy/SqlCompare.hx:382: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:382: characters 23-40
		$_g1 = $key_cols->length;
		#coopy/SqlCompare.hx:382: lines 382-386
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:382: characters 19-40
			$i = $_g++;
			#coopy/SqlCompare.hx:383: characters 13-46
			if ($i > 0) {
				#coopy/SqlCompare.hx:383: characters 22-46
				$sql_key_null2 = ($sql_key_null2??'null') . " AND ";
			}
			#coopy/SqlCompare.hx:384: characters 13-70
			$n = $common->getQuotedColumnName(($key_cols->arr[$i] ?? null));
			#coopy/SqlCompare.hx:385: characters 13-63
			$sql_key_null2 = ($sql_key_null2??'null') . ($sql_table2??'null') . "." . ($n??'null') . " IS NULL";
		}
		#coopy/SqlCompare.hx:387: characters 9-42
		$sql_key_match2 = "";
		#coopy/SqlCompare.hx:388: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:388: characters 23-40
		$_g1 = $key_cols->length;
		#coopy/SqlCompare.hx:388: lines 388-392
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:388: characters 19-40
			$i = $_g++;
			#coopy/SqlCompare.hx:389: characters 13-47
			if ($i > 0) {
				#coopy/SqlCompare.hx:389: characters 22-47
				$sql_key_match2 = ($sql_key_match2??'null') . " AND ";
			}
			#coopy/SqlCompare.hx:390: characters 13-70
			$n = $common->getQuotedColumnName(($key_cols->arr[$i] ?? null));
			#coopy/SqlCompare.hx:391: characters 13-83
			$sql_key_match2 = ($sql_key_match2??'null') . ($sql_table1??'null') . "." . ($n??'null') . " IS " . ($sql_table2??'null') . "." . ($n??'null');
		}
		#coopy/SqlCompare.hx:393: characters 9-42
		$sql_key_match3 = "";
		#coopy/SqlCompare.hx:394: lines 394-400
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:395: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:395: characters 27-44
			$_g1 = $key_cols->length;
			#coopy/SqlCompare.hx:395: lines 395-399
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:395: characters 23-44
				$i = $_g++;
				#coopy/SqlCompare.hx:396: characters 17-51
				if ($i > 0) {
					#coopy/SqlCompare.hx:396: characters 26-51
					$sql_key_match3 = ($sql_key_match3??'null') . " AND ";
				}
				#coopy/SqlCompare.hx:397: characters 17-74
				$n = $common->getQuotedColumnName(($key_cols->arr[$i] ?? null));
				#coopy/SqlCompare.hx:398: characters 17-87
				$sql_key_match3 = ($sql_key_match3??'null') . ($sql_table1??'null') . "." . ($n??'null') . " IS " . ($sql_table3??'null') . "." . ($n??'null');
			}
		}
		#coopy/SqlCompare.hx:401: characters 9-45
		$sql_data_mismatch = "";
		#coopy/SqlCompare.hx:402: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:402: characters 23-48
		$_g1 = $data_common_cols->length;
		#coopy/SqlCompare.hx:402: lines 402-406
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:402: characters 19-48
			$i = $_g++;
			#coopy/SqlCompare.hx:403: characters 13-49
			if ($i > 0) {
				#coopy/SqlCompare.hx:403: characters 22-49
				$sql_data_mismatch = ($sql_data_mismatch??'null') . " OR ";
			}
			#coopy/SqlCompare.hx:404: characters 13-78
			$n = $common->getQuotedColumnName(($data_common_cols->arr[$i] ?? null));
			#coopy/SqlCompare.hx:405: characters 13-90
			$sql_data_mismatch = ($sql_data_mismatch??'null') . ($sql_table1??'null') . "." . ($n??'null') . " IS NOT " . ($sql_table2??'null') . "." . ($n??'null');
		}
		#coopy/SqlCompare.hx:407: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:407: characters 23-41
		$_g1 = $all_cols2->length;
		#coopy/SqlCompare.hx:407: lines 407-414
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:407: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:408: characters 13-36
			$key = ($all_cols2->arr[$i] ?? null);
			#coopy/SqlCompare.hx:409: lines 409-413
			if (!\array_key_exists($key, $present1->data)) {
				#coopy/SqlCompare.hx:410: characters 17-71
				if ($sql_data_mismatch !== "") {
					#coopy/SqlCompare.hx:410: characters 44-71
					$sql_data_mismatch = ($sql_data_mismatch??'null') . " OR ";
				}
				#coopy/SqlCompare.hx:411: characters 17-66
				$n = $common->getQuotedColumnName($key);
				#coopy/SqlCompare.hx:412: characters 17-75
				$sql_data_mismatch = ($sql_data_mismatch??'null') . ($sql_table2??'null') . "." . ($n??'null') . " IS NOT NULL";
			}
		}
		#coopy/SqlCompare.hx:415: lines 415-429
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:416: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:416: characters 27-52
			$_g1 = $data_common_cols->length;
			#coopy/SqlCompare.hx:416: lines 416-420
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:416: characters 23-52
				$i = $_g++;
				#coopy/SqlCompare.hx:417: characters 17-76
				if (mb_strlen($sql_data_mismatch) > 0) {
					#coopy/SqlCompare.hx:417: characters 49-76
					$sql_data_mismatch = ($sql_data_mismatch??'null') . " OR ";
				}
				#coopy/SqlCompare.hx:418: characters 17-82
				$n = $common->getQuotedColumnName(($data_common_cols->arr[$i] ?? null));
				#coopy/SqlCompare.hx:419: characters 17-94
				$sql_data_mismatch = ($sql_data_mismatch??'null') . ($sql_table1??'null') . "." . ($n??'null') . " IS NOT " . ($sql_table3??'null') . "." . ($n??'null');
			}
			#coopy/SqlCompare.hx:421: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:421: characters 27-45
			$_g1 = $all_cols3->length;
			#coopy/SqlCompare.hx:421: lines 421-428
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:421: characters 23-45
				$i = $_g++;
				#coopy/SqlCompare.hx:422: characters 17-40
				$key = ($all_cols3->arr[$i] ?? null);
				#coopy/SqlCompare.hx:423: lines 423-427
				if (!\array_key_exists($key, $present1->data)) {
					#coopy/SqlCompare.hx:424: characters 21-75
					if ($sql_data_mismatch !== "") {
						#coopy/SqlCompare.hx:424: characters 48-75
						$sql_data_mismatch = ($sql_data_mismatch??'null') . " OR ";
					}
					#coopy/SqlCompare.hx:425: characters 21-70
					$n = $common->getQuotedColumnName($key);
					#coopy/SqlCompare.hx:426: characters 21-79
					$sql_data_mismatch = ($sql_data_mismatch??'null') . ($sql_table3??'null') . "." . ($n??'null') . " IS NOT NULL";
				}
			}
		}
		#coopy/SqlCompare.hx:430: characters 9-39
		$sql_dbl_cols = "";
		#coopy/SqlCompare.hx:431: characters 9-42
		$dbl_cols = new \Array_hx();
		#coopy/SqlCompare.hx:432: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:432: characters 23-41
		$_g1 = $all_cols1->length;
		#coopy/SqlCompare.hx:432: lines 432-438
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:432: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:433: characters 13-54
			if ($sql_dbl_cols !== "") {
				#coopy/SqlCompare.hx:433: characters 35-54
				$sql_dbl_cols = ($sql_dbl_cols??'null') . ",";
			}
			#coopy/SqlCompare.hx:434: characters 13-47
			$buf = "__coopy_" . ($i??'null');
			#coopy/SqlCompare.hx:435: characters 13-71
			$n = $common->getQuotedColumnName(($all_cols1->arr[$i] ?? null));
			#coopy/SqlCompare.hx:436: characters 13-64
			$sql_dbl_cols = ($sql_dbl_cols??'null') . ($sql_table1??'null') . "." . ($n??'null') . " AS " . ($buf??'null');
			#coopy/SqlCompare.hx:437: characters 13-31
			$dbl_cols->arr[$dbl_cols->length++] = $buf;
		}
		#coopy/SqlCompare.hx:439: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:439: characters 23-41
		$_g1 = $all_cols2->length;
		#coopy/SqlCompare.hx:439: lines 439-445
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:439: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:440: characters 13-54
			if ($sql_dbl_cols !== "") {
				#coopy/SqlCompare.hx:440: characters 35-54
				$sql_dbl_cols = ($sql_dbl_cols??'null') . ",";
			}
			#coopy/SqlCompare.hx:441: characters 13-53
			$buf = "__coopy_" . ($i??'null') . "b";
			#coopy/SqlCompare.hx:442: characters 13-71
			$n = $common->getQuotedColumnName(($all_cols2->arr[$i] ?? null));
			#coopy/SqlCompare.hx:443: characters 13-64
			$sql_dbl_cols = ($sql_dbl_cols??'null') . ($sql_table2??'null') . "." . ($n??'null') . " AS " . ($buf??'null');
			#coopy/SqlCompare.hx:444: characters 13-31
			$dbl_cols->arr[$dbl_cols->length++] = $buf;
		}
		#coopy/SqlCompare.hx:446: lines 446-454
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:447: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:447: characters 27-45
			$_g1 = $all_cols3->length;
			#coopy/SqlCompare.hx:447: lines 447-453
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:447: characters 23-45
				$i = $_g++;
				#coopy/SqlCompare.hx:448: characters 17-58
				if ($sql_dbl_cols !== "") {
					#coopy/SqlCompare.hx:448: characters 39-58
					$sql_dbl_cols = ($sql_dbl_cols??'null') . ",";
				}
				#coopy/SqlCompare.hx:449: characters 17-57
				$buf = "__coopy_" . ($i??'null') . "c";
				#coopy/SqlCompare.hx:450: characters 17-75
				$n = $common->getQuotedColumnName(($all_cols3->arr[$i] ?? null));
				#coopy/SqlCompare.hx:451: characters 17-68
				$sql_dbl_cols = ($sql_dbl_cols??'null') . ($sql_table3??'null') . "." . ($n??'null') . " AS " . ($buf??'null');
				#coopy/SqlCompare.hx:452: characters 17-35
				$dbl_cols->arr[$dbl_cols->length++] = $buf;
			}
		}
		#coopy/SqlCompare.hx:455: characters 9-36
		$sql_order = "";
		#coopy/SqlCompare.hx:456: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:456: characters 23-40
		$_g1 = $key_cols->length;
		#coopy/SqlCompare.hx:456: lines 456-460
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:456: characters 19-40
			$i = $_g++;
			#coopy/SqlCompare.hx:457: characters 13-38
			if ($i > 0) {
				#coopy/SqlCompare.hx:457: characters 22-38
				$sql_order = ($sql_order??'null') . ",";
			}
			#coopy/SqlCompare.hx:458: characters 13-70
			$n = $common->getQuotedColumnName(($key_cols->arr[$i] ?? null));
			#coopy/SqlCompare.hx:459: characters 13-27
			$sql_order = ($sql_order??'null') . ($n??'null');
		}
		#coopy/SqlCompare.hx:462: characters 9-35
		$rowid = "-3";
		#coopy/SqlCompare.hx:463: characters 9-36
		$rowid1 = "-3";
		#coopy/SqlCompare.hx:464: characters 9-36
		$rowid2 = "-3";
		#coopy/SqlCompare.hx:465: characters 9-36
		$rowid3 = "-3";
		#coopy/SqlCompare.hx:466: lines 466-477
		if ($rowid_name !== null) {
			#coopy/SqlCompare.hx:467: characters 13-18
			$rowid = $rowid_name;
			#coopy/SqlCompare.hx:468: lines 468-470
			if ($this->local !== null) {
				#coopy/SqlCompare.hx:469: characters 17-23
				$rowid1 = ($sql_table1??'null') . "." . ($rowid_name??'null');
			}
			#coopy/SqlCompare.hx:471: lines 471-473
			if ($this->remote !== null) {
				#coopy/SqlCompare.hx:472: characters 17-23
				$rowid2 = ($sql_table2??'null') . "." . ($rowid_name??'null');
			}
			#coopy/SqlCompare.hx:474: lines 474-476
			if ($this->alt !== null) {
				#coopy/SqlCompare.hx:475: characters 17-23
				$rowid3 = ($sql_table3??'null') . "." . ($rowid_name??'null');
			}
		}
		#coopy/SqlCompare.hx:479: characters 9-12
		$this->at0 = 1;
		#coopy/SqlCompare.hx:480: characters 9-12
		$this->at1 = 1;
		#coopy/SqlCompare.hx:481: characters 9-12
		$this->at2 = 1;
		#coopy/SqlCompare.hx:482: characters 9-16
		$this->diff_ct = 0;
		#coopy/SqlCompare.hx:484: lines 484-494
		if ($this->remote !== null) {
			#coopy/SqlCompare.hx:485: characters 13-155
			$sql_inserts = "SELECT DISTINCT 0 AS __coopy_code, NULL, " . ($rowid2??'null') . " AS rowid, NULL, " . ($sql_all_cols2??'null') . " FROM " . ($sql_table2??'null');
			#coopy/SqlCompare.hx:486: lines 486-489
			if ($this->local !== null) {
				#coopy/SqlCompare.hx:487: characters 17-58
				$sql_inserts = ($sql_inserts??'null') . " LEFT JOIN " . ($sql_table1??'null');
				#coopy/SqlCompare.hx:488: characters 17-77
				$sql_inserts = ($sql_inserts??'null') . " ON " . ($sql_key_match2??'null') . ($this->where($sql_key_null)??'null');
			}
			#coopy/SqlCompare.hx:490: lines 490-493
			if ($sql_table1 !== $sql_table2) {
				#coopy/SqlCompare.hx:491: characters 17-114
				$sql_inserts_order = (\Array_hx::wrap([
					"__coopy_code",
					"NULL",
					"rowid",
					"NULL",
				]))->concat($all_cols2);
				#coopy/SqlCompare.hx:492: characters 17-57
				$this->linkQuery($sql_inserts, $sql_inserts_order);
			}
		}
		#coopy/SqlCompare.hx:496: lines 496-506
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:497: characters 13-155
			$sql_inserts = "SELECT DISTINCT 0 AS __coopy_code, NULL, NULL, " . ($rowid3??'null') . " AS rowid, " . ($sql_all_cols3??'null') . " FROM " . ($sql_table3??'null');
			#coopy/SqlCompare.hx:498: lines 498-501
			if ($this->local !== null) {
				#coopy/SqlCompare.hx:499: characters 17-58
				$sql_inserts = ($sql_inserts??'null') . " LEFT JOIN " . ($sql_table1??'null');
				#coopy/SqlCompare.hx:500: characters 17-77
				$sql_inserts = ($sql_inserts??'null') . " ON " . ($sql_key_match3??'null') . ($this->where($sql_key_null)??'null');
			}
			#coopy/SqlCompare.hx:502: lines 502-505
			if ($sql_table1 !== $sql_table3) {
				#coopy/SqlCompare.hx:503: characters 17-114
				$sql_inserts_order = (\Array_hx::wrap([
					"__coopy_code",
					"NULL",
					"NULL",
					"rowid",
				]))->concat($all_cols3);
				#coopy/SqlCompare.hx:504: characters 17-57
				$this->linkQuery($sql_inserts, $sql_inserts_order);
			}
		}
		#coopy/SqlCompare.hx:508: lines 508-525
		if (($this->local !== null) && ($this->remote !== null)) {
			#coopy/SqlCompare.hx:509: characters 13-146
			$sql_updates = "SELECT DISTINCT 2 AS __coopy_code, " . ($rowid1??'null') . " AS __coopy_rowid0, " . ($rowid2??'null') . " AS __coopy_rowid1, ";
			#coopy/SqlCompare.hx:510: lines 510-514
			if ($this->alt !== null) {
				#coopy/SqlCompare.hx:511: characters 17-62
				$sql_updates = ($sql_updates??'null') . ($rowid3??'null') . " AS __coopy_rowid2,";
			} else {
				#coopy/SqlCompare.hx:513: characters 17-40
				$sql_updates = ($sql_updates??'null') . " NULL,";
			}
			#coopy/SqlCompare.hx:515: characters 13-64
			$sql_updates = ($sql_updates??'null') . ($sql_dbl_cols??'null') . " FROM " . ($sql_table1??'null');
			#coopy/SqlCompare.hx:516: lines 516-518
			if ($sql_table1 !== $sql_table2) {
				#coopy/SqlCompare.hx:517: characters 17-85
				$sql_updates = ($sql_updates??'null') . " INNER JOIN " . ($sql_table2??'null') . " ON " . ($sql_key_match2??'null');
			}
			#coopy/SqlCompare.hx:519: lines 519-521
			if (($this->alt !== null) && ($sql_table1 !== $sql_table3)) {
				#coopy/SqlCompare.hx:520: characters 17-85
				$sql_updates = ($sql_updates??'null') . " INNER JOIN " . ($sql_table3??'null') . " ON " . ($sql_key_match3??'null');
			}
			#coopy/SqlCompare.hx:522: characters 13-52
			$sql_updates = ($sql_updates??'null') . ($this->where($sql_data_mismatch)??'null');
			#coopy/SqlCompare.hx:523: characters 13-138
			$sql_updates_order = (\Array_hx::wrap([
				"__coopy_code",
				"__coopy_rowid0",
				"__coopy_rowid1",
				"__coopy_rowid2",
			]))->concat($dbl_cols);
			#coopy/SqlCompare.hx:524: characters 13-53
			$this->linkQuery($sql_updates, $sql_updates_order);
		}
		#coopy/SqlCompare.hx:527: lines 527-539
		if ($this->alt === null) {
			#coopy/SqlCompare.hx:528: lines 528-538
			if ($this->local !== null) {
				#coopy/SqlCompare.hx:529: characters 17-159
				$sql_deletes = "SELECT DISTINCT 0 AS __coopy_code, " . ($rowid1??'null') . " AS rowid, NULL, NULL, " . ($sql_all_cols1??'null') . " FROM " . ($sql_table1??'null');
				#coopy/SqlCompare.hx:530: lines 530-533
				if ($this->remote !== null) {
					#coopy/SqlCompare.hx:531: characters 21-62
					$sql_deletes = ($sql_deletes??'null') . " LEFT JOIN " . ($sql_table2??'null');
					#coopy/SqlCompare.hx:532: characters 21-82
					$sql_deletes = ($sql_deletes??'null') . " ON " . ($sql_key_match2??'null') . ($this->where($sql_key_null2)??'null');
				}
				#coopy/SqlCompare.hx:534: lines 534-537
				if ($sql_table1 !== $sql_table2) {
					#coopy/SqlCompare.hx:535: characters 21-118
					$sql_deletes_order = (\Array_hx::wrap([
						"__coopy_code",
						"rowid",
						"NULL",
						"NULL",
					]))->concat($all_cols1);
					#coopy/SqlCompare.hx:536: characters 21-61
					$this->linkQuery($sql_deletes, $sql_deletes_order);
				}
			}
		}
		#coopy/SqlCompare.hx:541: lines 541-554
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:543: characters 13-137
			$sql_deletes = "SELECT 2 AS __coopy_code, " . ($rowid1??'null') . " AS __coopy_rowid0, " . ($rowid2??'null') . " AS __coopy_rowid1, ";
			#coopy/SqlCompare.hx:544: characters 13-59
			$sql_deletes = ($sql_deletes??'null') . ($rowid3??'null') . " AS __coopy_rowid2, ";
			#coopy/SqlCompare.hx:545: characters 13-40
			$sql_deletes = ($sql_deletes??'null') . ($sql_dbl_cols??'null');
			#coopy/SqlCompare.hx:546: characters 13-49
			$sql_deletes = ($sql_deletes??'null') . " FROM " . ($sql_table1??'null');
			#coopy/SqlCompare.hx:547: lines 547-549
			if ($this->remote !== null) {
				#coopy/SqlCompare.hx:548: characters 17-90
				$sql_deletes = ($sql_deletes??'null') . " LEFT OUTER JOIN " . ($sql_table2??'null') . " ON " . ($sql_key_match2??'null');
			}
			#coopy/SqlCompare.hx:550: characters 13-86
			$sql_deletes = ($sql_deletes??'null') . " LEFT OUTER JOIN " . ($sql_table3??'null') . " ON " . ($sql_key_match3??'null');
			#coopy/SqlCompare.hx:551: characters 13-85
			$sql_deletes = ($sql_deletes??'null') . " WHERE __coopy_rowid1 IS NULL OR __coopy_rowid2 IS NULL";
			#coopy/SqlCompare.hx:552: characters 13-138
			$sql_deletes_order = (\Array_hx::wrap([
				"__coopy_code",
				"__coopy_rowid0",
				"__coopy_rowid1",
				"__coopy_rowid2",
			]))->concat($dbl_cols);
			#coopy/SqlCompare.hx:553: characters 13-53
			$this->linkQuery($sql_deletes, $sql_deletes_order);
		}
		#coopy/SqlCompare.hx:556: lines 556-558
		if ($this->diff_ct === 0) {
			#coopy/SqlCompare.hx:557: characters 13-34
			$this->align->markIdentical();
		}
		#coopy/SqlCompare.hx:560: characters 9-21
		return $this->align;
	}

	/**
	 * @param int $x
	 * 
	 * @return int
	 */
	public function denull ($x) {
		#coopy/SqlCompare.hx:122: characters 9-31
		if ($x === null) {
			#coopy/SqlCompare.hx:122: characters 22-31
			return -1;
		}
		#coopy/SqlCompare.hx:123: characters 9-17
		return $x;
	}

	/**
	 * @param string[]|\Array_hx $a1
	 * @param string[]|\Array_hx $a2
	 * 
	 * @return bool
	 */
	public function equalArray ($a1, $a2) {
		#coopy/SqlCompare.hx:58: characters 9-47
		if ($a1->length !== $a2->length) {
			#coopy/SqlCompare.hx:58: characters 35-47
			return false;
		}
		#coopy/SqlCompare.hx:59: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:59: characters 23-32
		$_g1 = $a1->length;
		#coopy/SqlCompare.hx:59: lines 59-61
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:59: characters 19-32
			$i = $_g++;
			#coopy/SqlCompare.hx:60: characters 13-43
			if (($a1->arr[$i] ?? null) !== ($a2->arr[$i] ?? null)) {
				#coopy/SqlCompare.hx:60: characters 31-43
				return false;
			}
		}
		#coopy/SqlCompare.hx:62: characters 9-20
		return true;
	}

	/**
	 * @return void
	 */
	public function link () {
		#coopy/SqlCompare.hx:127: characters 9-18
		$this->diff_ct++;
		#coopy/SqlCompare.hx:128: characters 9-36
		$mode = $this->db->get(0);
		#coopy/SqlCompare.hx:129: characters 9-36
		$i0 = $this->denull($this->db->get(1));
		#coopy/SqlCompare.hx:130: characters 9-36
		$i1 = $this->denull($this->db->get(2));
		#coopy/SqlCompare.hx:131: characters 9-36
		$i2 = $this->denull($this->db->get(3));
		#coopy/SqlCompare.hx:132: lines 132-135
		if ($i0 === -3) {
			#coopy/SqlCompare.hx:133: characters 13-21
			$i0 = $this->at0;
			#coopy/SqlCompare.hx:134: characters 13-18
			$this->at0++;
		}
		#coopy/SqlCompare.hx:136: lines 136-139
		if ($i1 === -3) {
			#coopy/SqlCompare.hx:137: characters 13-21
			$i1 = $this->at1;
			#coopy/SqlCompare.hx:138: characters 13-18
			$this->at1++;
		}
		#coopy/SqlCompare.hx:140: lines 140-143
		if ($i2 === -3) {
			#coopy/SqlCompare.hx:141: characters 13-21
			$i2 = $this->at2;
			#coopy/SqlCompare.hx:142: characters 13-18
			$this->at2++;
		}
		#coopy/SqlCompare.hx:144: characters 9-24
		$offset = 4;
		#coopy/SqlCompare.hx:145: lines 145-150
		if ($i0 >= 0) {
			#coopy/SqlCompare.hx:146: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:146: characters 27-38
			$_g1 = $this->local->get_width();
			#coopy/SqlCompare.hx:146: lines 146-148
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:146: characters 23-38
				$x = $_g++;
				#coopy/SqlCompare.hx:147: characters 17-58
				$this->local->setCellCache($x, $i0, $this->db->get($x + $offset));
			}
			#coopy/SqlCompare.hx:149: characters 13-34
			$offset += $this->local->get_width();
		}
		#coopy/SqlCompare.hx:151: lines 151-156
		if ($i1 >= 0) {
			#coopy/SqlCompare.hx:152: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:152: characters 27-39
			$_g1 = $this->remote->get_width();
			#coopy/SqlCompare.hx:152: lines 152-154
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:152: characters 23-39
				$x = $_g++;
				#coopy/SqlCompare.hx:153: characters 17-59
				$this->remote->setCellCache($x, $i1, $this->db->get($x + $offset));
			}
			#coopy/SqlCompare.hx:155: characters 13-35
			$offset += $this->remote->get_width();
		}
		#coopy/SqlCompare.hx:157: lines 157-161
		if ($i2 >= 0) {
			#coopy/SqlCompare.hx:158: characters 23-27
			$_g = 0;
			#coopy/SqlCompare.hx:158: characters 27-36
			$_g1 = $this->alt->get_width();
			#coopy/SqlCompare.hx:158: lines 158-160
			while ($_g < $_g1) {
				#coopy/SqlCompare.hx:158: characters 23-36
				$x = $_g++;
				#coopy/SqlCompare.hx:159: characters 17-56
				$this->alt->setCellCache($x, $i2, $this->db->get($x + $offset));
			}
		}
		#coopy/SqlCompare.hx:162: lines 162-165
		if (($mode === 0) || ($mode === 2)) {
			#coopy/SqlCompare.hx:163: characters 13-30
			$this->align->link($i0, $i1);
			#coopy/SqlCompare.hx:164: characters 13-36
			$this->align->addToOrder($i0, $i1);
		}
		#coopy/SqlCompare.hx:166: lines 166-171
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:167: lines 167-170
			if (($mode === 1) || ($mode === 2)) {
				#coopy/SqlCompare.hx:168: characters 17-44
				$this->align->reference->link($i0, $i2);
				#coopy/SqlCompare.hx:169: characters 17-50
				$this->align->reference->addToOrder($i0, $i2);
			}
		}
	}

	/**
	 * @param string $query
	 * @param string[]|\Array_hx $order
	 * 
	 * @return void
	 */
	public function linkQuery ($query, $order) {
		#coopy/SqlCompare.hx:175: lines 175-180
		if ($this->db->begin($query, null, $order)) {
			#coopy/SqlCompare.hx:176: lines 176-178
			while ($this->db->read()) {
				#coopy/SqlCompare.hx:177: characters 17-23
				$this->link();
			}
			#coopy/SqlCompare.hx:179: characters 13-21
			$this->db->end();
		}
	}

	/**
	 * @param string[]|\Array_hx $all_cols1
	 * @param string[]|\Array_hx $all_cols2
	 * @param string[]|\Array_hx $key_cols
	 * @param StringMap $present1
	 * @param StringMap $present2
	 * @param Alignment $align
	 * 
	 * @return void
	 */
	public function scanColumns ($all_cols1, $all_cols2, $key_cols, $present1, $present2, $align) {
		#coopy/SqlCompare.hx:194: characters 9-37
		$align->meta = new Alignment();
		#coopy/SqlCompare.hx:195: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:195: characters 23-41
		$_g1 = $all_cols1->length;
		#coopy/SqlCompare.hx:195: lines 195-202
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:195: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:196: characters 13-36
			$key = ($all_cols1->arr[$i] ?? null);
			#coopy/SqlCompare.hx:197: lines 197-201
			if (\array_key_exists($key, $present2->data)) {
				#coopy/SqlCompare.hx:198: characters 17-53
				$align->meta->link($i, ($present2->data[$key] ?? null));
			} else {
				#coopy/SqlCompare.hx:200: characters 17-38
				$align->meta->link($i, -1);
			}
		}
		#coopy/SqlCompare.hx:203: characters 19-23
		$_g = 0;
		#coopy/SqlCompare.hx:203: characters 23-41
		$_g1 = $all_cols2->length;
		#coopy/SqlCompare.hx:203: lines 203-208
		while ($_g < $_g1) {
			#coopy/SqlCompare.hx:203: characters 19-41
			$i = $_g++;
			#coopy/SqlCompare.hx:204: characters 13-36
			$key = ($all_cols2->arr[$i] ?? null);
			#coopy/SqlCompare.hx:205: lines 205-207
			if (!\array_key_exists($key, $present1->data)) {
				#coopy/SqlCompare.hx:206: characters 17-38
				$align->meta->link(-1, $i);
			}
		}
		#coopy/SqlCompare.hx:209: characters 9-60
		$align->meta->range($all_cols1->length, $all_cols2->length);
		#coopy/SqlCompare.hx:210: lines 210-213
		$_g = 0;
		while ($_g < $key_cols->length) {
			#coopy/SqlCompare.hx:210: characters 14-17
			$key = ($key_cols->arr[$_g] ?? null);
			#coopy/SqlCompare.hx:210: lines 210-213
			++$_g;
			#coopy/SqlCompare.hx:211: characters 33-50
			$unit = ($present1->data[$key] ?? null);
			#coopy/SqlCompare.hx:211: characters 13-70
			$unit1 = new Unit($unit, ($present2->data[$key] ?? null));
			#coopy/SqlCompare.hx:212: characters 13-40
			$align->addIndexColumns($unit1);
		}
	}

	/**
	 * @return bool
	 */
	public function validateSchema () {
		#coopy/SqlCompare.hx:66: characters 9-28
		$all_cols1 = new \Array_hx();
		#coopy/SqlCompare.hx:67: characters 9-28
		$key_cols1 = new \Array_hx();
		#coopy/SqlCompare.hx:68: characters 9-34
		$access_error = false;
		#coopy/SqlCompare.hx:69: characters 9-32
		$pk_missing = false;
		#coopy/SqlCompare.hx:70: lines 70-78
		if ($this->local !== null) {
			#coopy/SqlCompare.hx:71: characters 13-47
			$all_cols1 = $this->local->getColumnNames();
			#coopy/SqlCompare.hx:72: characters 13-46
			$key_cols1 = $this->local->getPrimaryKey();
			#coopy/SqlCompare.hx:73: characters 13-57
			if ($all_cols1->length === 0) {
				#coopy/SqlCompare.hx:73: characters 38-57
				$access_error = true;
			}
			#coopy/SqlCompare.hx:74: lines 74-76
			if ($this->flags->ids !== null) {
				#coopy/SqlCompare.hx:75: characters 17-56
				$key_cols1 = $this->flags->getIdsByRole("local");
			}
			#coopy/SqlCompare.hx:77: characters 13-55
			if ($key_cols1->length === 0) {
				#coopy/SqlCompare.hx:77: characters 38-55
				$pk_missing = true;
			}
		}
		#coopy/SqlCompare.hx:79: characters 9-28
		$all_cols2 = new \Array_hx();
		#coopy/SqlCompare.hx:80: characters 9-28
		$key_cols2 = new \Array_hx();
		#coopy/SqlCompare.hx:81: lines 81-89
		if ($this->remote !== null) {
			#coopy/SqlCompare.hx:82: characters 13-48
			$all_cols2 = $this->remote->getColumnNames();
			#coopy/SqlCompare.hx:83: characters 13-47
			$key_cols2 = $this->remote->getPrimaryKey();
			#coopy/SqlCompare.hx:84: characters 13-57
			if ($all_cols2->length === 0) {
				#coopy/SqlCompare.hx:84: characters 38-57
				$access_error = true;
			}
			#coopy/SqlCompare.hx:85: lines 85-87
			if ($this->flags->ids !== null) {
				#coopy/SqlCompare.hx:86: characters 17-57
				$key_cols2 = $this->flags->getIdsByRole("remote");
			}
			#coopy/SqlCompare.hx:88: characters 13-55
			if ($key_cols2->length === 0) {
				#coopy/SqlCompare.hx:88: characters 38-55
				$pk_missing = true;
			}
		}
		#coopy/SqlCompare.hx:90: characters 9-35
		$all_cols3 = $all_cols2;
		#coopy/SqlCompare.hx:91: characters 9-35
		$key_cols3 = $key_cols2;
		#coopy/SqlCompare.hx:92: lines 92-100
		if ($this->alt !== null) {
			#coopy/SqlCompare.hx:93: characters 13-45
			$all_cols3 = $this->alt->getColumnNames();
			#coopy/SqlCompare.hx:94: characters 13-44
			$key_cols3 = $this->alt->getPrimaryKey();
			#coopy/SqlCompare.hx:95: characters 13-57
			if ($all_cols3->length === 0) {
				#coopy/SqlCompare.hx:95: characters 38-57
				$access_error = true;
			}
			#coopy/SqlCompare.hx:96: lines 96-98
			if ($this->flags->ids !== null) {
				#coopy/SqlCompare.hx:97: characters 17-57
				$key_cols3 = $this->flags->getIdsByRole("parent");
			}
			#coopy/SqlCompare.hx:99: characters 13-55
			if ($key_cols3->length === 0) {
				#coopy/SqlCompare.hx:99: characters 38-55
				$pk_missing = true;
			}
		}
		#coopy/SqlCompare.hx:101: lines 101-103
		if ($access_error) {
			#coopy/SqlCompare.hx:102: characters 13-18
			throw Exception::thrown("Error accessing SQL table");
		}
		#coopy/SqlCompare.hx:104: lines 104-106
		if ($pk_missing) {
			#coopy/SqlCompare.hx:105: characters 13-18
			throw Exception::thrown("sql diff not possible when primary key not available");
		}
		#coopy/SqlCompare.hx:107: characters 9-31
		$pk_change = false;
		#coopy/SqlCompare.hx:108: lines 108-110
		if (($this->local !== null) && ($this->remote !== null)) {
			#coopy/SqlCompare.hx:109: characters 13-67
			if (!$this->equalArray($key_cols1, $key_cols2)) {
				#coopy/SqlCompare.hx:109: characters 51-67
				$pk_change = true;
			}
		}
		#coopy/SqlCompare.hx:111: lines 111-113
		if (($this->local !== null) && ($this->alt !== null)) {
			#coopy/SqlCompare.hx:112: characters 13-67
			if (!$this->equalArray($key_cols1, $key_cols3)) {
				#coopy/SqlCompare.hx:112: characters 51-67
				$pk_change = true;
			}
		}
		#coopy/SqlCompare.hx:114: lines 114-117
		if ($pk_change) {
			#coopy/SqlCompare.hx:115: characters 13-18
			throw Exception::thrown("sql diff not possible when primary key changes: " . \Std::string(\Array_hx::wrap([
				$key_cols1,
				$key_cols2,
				$key_cols3,
			])));
		}
		#coopy/SqlCompare.hx:118: characters 9-20
		return true;
	}

	/**
	 * @param string $txt
	 * 
	 * @return string
	 */
	public function where ($txt) {
		#coopy/SqlCompare.hx:184: characters 9-43
		if ($txt === "") {
			#coopy/SqlCompare.hx:184: characters 22-43
			return " WHERE 1 = 0";
		}
		#coopy/SqlCompare.hx:185: characters 9-31
		return " WHERE " . ($txt??'null');
	}
}

Boot::registerClass(SqlCompare::class, 'coopy.SqlCompare');
