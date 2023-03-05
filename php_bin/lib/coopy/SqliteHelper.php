<?php
/**
 */

namespace coopy;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Log;
use \haxe\ds\StringMap;

class SqliteHelper implements SqlHelper {
	/**
	 * @return void
	 */
	public function __construct () {
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param ColumnChange[]|\Array_hx $columns
	 * 
	 * @return bool
	 */
	public function alterColumns ($db, $name, $columns) {
		#coopy/SqliteHelper.hx:262: lines 262-267
		$notBlank = function ($x) {
			#coopy/SqliteHelper.hx:263: lines 263-265
			if (($x === null) || ($x === "") || ($x === "null")) {
				#coopy/SqliteHelper.hx:264: characters 17-29
				return false;
			}
			#coopy/SqliteHelper.hx:266: characters 13-24
			return true;
		};
		#coopy/SqliteHelper.hx:269: characters 9-40
		$sql = $this->fetchSchema($db, $name);
		#coopy/SqliteHelper.hx:270: characters 9-47
		$schema = $this->splitSchema($db, $name, $sql);
		#coopy/SqliteHelper.hx:271: characters 9-34
		$parts = $schema->parts;
		#coopy/SqliteHelper.hx:272: characters 9-42
		$nparts = new \Array_hx();
		#coopy/SqliteHelper.hx:274: characters 9-51
		$new_column_list = new \Array_hx();
		#coopy/SqliteHelper.hx:275: characters 9-51
		$ins_column_list = new \Array_hx();
		#coopy/SqliteHelper.hx:276: characters 9-51
		$sel_column_list = new \Array_hx();
		#coopy/SqliteHelper.hx:277: characters 9-35
		$meta = $schema->columns;
		#coopy/SqliteHelper.hx:278: characters 19-23
		$_g = 0;
		#coopy/SqliteHelper.hx:278: characters 23-37
		$_g1 = $columns->length;
		#coopy/SqliteHelper.hx:278: lines 278-314
		while ($_g < $_g1) {
			#coopy/SqliteHelper.hx:278: characters 19-37
			$i = $_g++;
			#coopy/SqliteHelper.hx:279: characters 13-32
			$c = ($columns->arr[$i] ?? null);
			#coopy/SqliteHelper.hx:280: lines 280-313
			if ($c->name !== null) {
				#coopy/SqliteHelper.hx:281: lines 281-284
				if ($c->prevName !== null) {
					#coopy/SqliteHelper.hx:282: characters 21-53
					$sel_column_list->arr[$sel_column_list->length++] = $c->prevName;
					#coopy/SqliteHelper.hx:283: characters 21-49
					$ins_column_list->arr[$ins_column_list->length++] = $c->name;
				}
				#coopy/SqliteHelper.hx:285: characters 17-36
				$orig_type = "";
				#coopy/SqliteHelper.hx:286: characters 17-42
				$orig_primary = false;
				#coopy/SqliteHelper.hx:287: lines 287-291
				if (\array_key_exists($c->name, $schema->name2column->data)) {
					#coopy/SqliteHelper.hx:288: characters 21-60
					$m = ($schema->name2column->data[$c->name] ?? null);
					#coopy/SqliteHelper.hx:289: characters 21-45
					$orig_type = $m->type_value;
					#coopy/SqliteHelper.hx:290: characters 21-45
					$orig_primary = $m->primary;
				}
				#coopy/SqliteHelper.hx:292: characters 17-43
				$next_type = $orig_type;
				#coopy/SqliteHelper.hx:293: characters 17-49
				$next_primary = $orig_primary;
				#coopy/SqliteHelper.hx:294: lines 294-303
				if ($c->props !== null) {
					#coopy/SqliteHelper.hx:295: lines 295-302
					$_g2 = 0;
					$_g3 = $c->props;
					while ($_g2 < $_g3->length) {
						#coopy/SqliteHelper.hx:295: characters 26-27
						$p = ($_g3->arr[$_g2] ?? null);
						#coopy/SqliteHelper.hx:295: lines 295-302
						++$_g2;
						#coopy/SqliteHelper.hx:296: lines 296-298
						if ($p->name === "type") {
							#coopy/SqliteHelper.hx:297: characters 29-46
							$next_type = $p->val;
						}
						#coopy/SqliteHelper.hx:299: lines 299-301
						if ($p->name === "key") {
							#coopy/SqliteHelper.hx:300: characters 29-67
							$next_primary = ("" . \Std::string($p->val)) === "primary";
						}
					}
				}
				#coopy/SqliteHelper.hx:304: characters 17-40
				$part = "" . ($c->name??'null');
				#coopy/SqliteHelper.hx:305: lines 305-307
				if ($notBlank($next_type)) {
					#coopy/SqliteHelper.hx:306: characters 21-44
					$part = ($part??'null') . " " . ($next_type??'null');
				}
				#coopy/SqliteHelper.hx:308: lines 308-310
				if ($next_primary) {
					#coopy/SqliteHelper.hx:309: characters 21-43
					$part = ($part??'null') . " PRIMARY KEY";
				}
				#coopy/SqliteHelper.hx:311: characters 17-34
				$nparts->arr[$nparts->length++] = $part;
				#coopy/SqliteHelper.hx:312: characters 17-45
				$new_column_list->arr[$new_column_list->length++] = $c->name;
			}
		}
		#coopy/SqliteHelper.hx:315: characters 9-56
		if (!$this->exec($db, "BEGIN TRANSACTION")) {
			#coopy/SqliteHelper.hx:315: characters 44-56
			return false;
		}
		#coopy/SqliteHelper.hx:316: characters 9-49
		$c1 = $this->columnListSql($ins_column_list);
		#coopy/SqliteHelper.hx:317: characters 9-49
		$tname = $db->getQuotedTableName($name);
		#coopy/SqliteHelper.hx:318: characters 9-88
		if (!$this->exec($db, "CREATE TEMPORARY TABLE __coopy_backup(" . ($c1??'null') . ")")) {
			#coopy/SqliteHelper.hx:318: characters 76-88
			return false;
		}
		#coopy/SqliteHelper.hx:319: characters 9-110
		if (!$this->exec($db, "INSERT INTO __coopy_backup (" . ($c1??'null') . ") SELECT " . ($c1??'null') . " FROM " . ($tname??'null'))) {
			#coopy/SqliteHelper.hx:319: characters 98-110
			return false;
		}
		#coopy/SqliteHelper.hx:320: characters 9-58
		if (!$this->exec($db, "DROP TABLE " . ($tname??'null'))) {
			#coopy/SqliteHelper.hx:320: characters 46-58
			return false;
		}
		#coopy/SqliteHelper.hx:321: characters 9-84
		if (!$this->exec($db, ($schema->preamble??'null') . "(" . ($nparts->join(", ")??'null') . ")")) {
			#coopy/SqliteHelper.hx:321: characters 72-84
			return false;
		}
		#coopy/SqliteHelper.hx:322: characters 9-115
		if (!$this->exec($db, "INSERT INTO " . ($tname??'null') . " (" . ($c1??'null') . ") SELECT " . ($c1??'null') . " FROM __coopy_backup")) {
			#coopy/SqliteHelper.hx:322: characters 103-115
			return false;
		}
		#coopy/SqliteHelper.hx:323: characters 9-64
		if (!$this->exec($db, "DROP TABLE __coopy_backup")) {
			#coopy/SqliteHelper.hx:323: characters 52-64
			return false;
		}
		#coopy/SqliteHelper.hx:324: characters 9-45
		if (!$this->exec($db, "COMMIT")) {
			#coopy/SqliteHelper.hx:324: characters 33-45
			return false;
		}
		#coopy/SqliteHelper.hx:325: characters 9-20
		return true;
	}

	/**
	 * @param SqlDatabase $db
	 * @param string $tag
	 * @param string $resource_name
	 * 
	 * @return bool
	 */
	public function attach ($db, $tag, $resource_name) {
		#coopy/SqliteHelper.hx:126: characters 9-33
		$tag_present = false;
		#coopy/SqliteHelper.hx:127: characters 9-33
		$tag_correct = false;
		#coopy/SqliteHelper.hx:128: characters 9-39
		$result = new \Array_hx();
		#coopy/SqliteHelper.hx:129: characters 9-40
		$q = "PRAGMA database_list";
		#coopy/SqliteHelper.hx:130: characters 9-66
		if (!$db->begin($q, null, \Array_hx::wrap([
			"seq",
			"name",
			"file",
		]))) {
			#coopy/SqliteHelper.hx:130: characters 54-66
			return false;
		}
		#coopy/SqliteHelper.hx:131: lines 131-140
		while ($db->read()) {
			#coopy/SqliteHelper.hx:132: characters 13-48
			$name = $db->get(1);
			#coopy/SqliteHelper.hx:133: lines 133-139
			if ($name === $tag) {
				#coopy/SqliteHelper.hx:134: characters 17-35
				$tag_present = true;
				#coopy/SqliteHelper.hx:135: characters 17-52
				$file = $db->get(2);
				#coopy/SqliteHelper.hx:136: lines 136-138
				if ($file === $resource_name) {
					#coopy/SqliteHelper.hx:137: characters 21-39
					$tag_correct = true;
				}
			}
		}
		#coopy/SqliteHelper.hx:141: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:143: lines 143-151
		if ($tag_present) {
			#coopy/SqliteHelper.hx:144: characters 13-41
			if ($tag_correct) {
				#coopy/SqliteHelper.hx:144: characters 30-41
				return true;
			}
			#coopy/SqliteHelper.hx:146: lines 146-149
			if (!$db->begin("DETACH `" . ($tag??'null') . "`", null, new \Array_hx())) {
				#coopy/SqliteHelper.hx:147: characters 17-22
				(Log::$trace)("Failed to detach " . ($tag??'null'), new _HxAnon_SqliteHelper0("coopy/SqliteHelper.hx", 147, "coopy.SqliteHelper", "attach"));
				#coopy/SqliteHelper.hx:148: characters 17-29
				return false;
			}
			#coopy/SqliteHelper.hx:150: characters 13-21
			$db->end();
		}
		#coopy/SqliteHelper.hx:153: lines 153-156
		if (!$db->begin("ATTACH ? AS `" . ($tag??'null') . "`", \Array_hx::wrap([$resource_name]), new \Array_hx())) {
			#coopy/SqliteHelper.hx:154: characters 13-18
			(Log::$trace)("Failed to attach " . ($resource_name??'null') . " as " . ($tag??'null'), new _HxAnon_SqliteHelper0("coopy/SqliteHelper.hx", 154, "coopy.SqliteHelper", "attach"));
			#coopy/SqliteHelper.hx:155: characters 13-25
			return false;
		}
		#coopy/SqliteHelper.hx:157: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:158: characters 9-20
		return true;
	}

	/**
	 * @param string[]|\Array_hx $x
	 * 
	 * @return string
	 */
	public function columnListSql ($x) {
		#coopy/SqliteHelper.hx:162: characters 9-27
		return $x->join(",");
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * 
	 * @return int
	 */
	public function countRows ($db, $name) {
		#coopy/SqliteHelper.hx:24: characters 9-77
		$q = "SELECT COUNT(*) AS ct FROM " . ($db->getQuotedTableName($name)??'null');
		#coopy/SqliteHelper.hx:25: characters 9-48
		if (!$db->begin($q, null, \Array_hx::wrap(["ct"]))) {
			#coopy/SqliteHelper.hx:25: characters 39-48
			return -1;
		}
		#coopy/SqliteHelper.hx:26: characters 9-27
		$ct = -1;
		#coopy/SqliteHelper.hx:27: lines 27-29
		while ($db->read()) {
			#coopy/SqliteHelper.hx:28: characters 13-27
			$ct = $db->get(0);
		}
		#coopy/SqliteHelper.hx:30: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:31: characters 9-18
		return $ct;
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param StringMap $conds
	 * 
	 * @return bool
	 */
	public function delete ($db, $name, $conds) {
		#coopy/SqliteHelper.hx:79: characters 9-74
		$q = "DELETE FROM " . ($db->getQuotedTableName($name)??'null') . " WHERE ";
		#coopy/SqliteHelper.hx:80: characters 9-40
		$lst = new \Array_hx();
		#coopy/SqliteHelper.hx:81: characters 19-31
		$data = \array_values(\array_map("strval", \array_keys($conds->data)));
		$k_current = 0;
		$k_length = \count($data);
		$k_data = $data;
		while ($k_current < $k_length) {
			#coopy/SqliteHelper.hx:81: lines 81-88
			$k = $k_data[$k_current++];
			#coopy/SqliteHelper.hx:82: lines 82-84
			if ($lst->length > 0) {
				#coopy/SqliteHelper.hx:83: characters 17-29
				$q = ($q??'null') . " and ";
			}
			#coopy/SqliteHelper.hx:85: characters 13-43
			$q = ($q??'null') . ($db->getQuotedColumnName($k)??'null');
			#coopy/SqliteHelper.hx:86: characters 13-24
			$q = ($q??'null') . " = ?";
			#coopy/SqliteHelper.hx:87: characters 13-35
			$x = ($conds->data[$k] ?? null);
			$lst->arr[$lst->length++] = $x;
		}
		#coopy/SqliteHelper.hx:89: lines 89-92
		if (!$db->begin($q, $lst, new \Array_hx())) {
			#coopy/SqliteHelper.hx:90: characters 13-18
			(Log::$trace)("Problem with database delete", new _HxAnon_SqliteHelper0("coopy/SqliteHelper.hx", 90, "coopy.SqliteHelper", "delete"));
			#coopy/SqliteHelper.hx:91: characters 13-25
			return false;
		}
		#coopy/SqliteHelper.hx:93: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:94: characters 9-20
		return true;
	}

	/**
	 * @param SqlDatabase $db
	 * @param string $query
	 * 
	 * @return bool
	 */
	public function exec ($db, $query) {
		#coopy/SqliteHelper.hx:249: lines 249-252
		if (!$db->begin($query)) {
			#coopy/SqliteHelper.hx:250: characters 13-18
			(Log::$trace)("database problem", new _HxAnon_SqliteHelper0("coopy/SqliteHelper.hx", 250, "coopy.SqliteHelper", "exec"));
			#coopy/SqliteHelper.hx:251: characters 13-25
			return false;
		}
		#coopy/SqliteHelper.hx:253: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:254: characters 9-20
		return true;
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * 
	 * @return string
	 */
	public function fetchSchema ($db, $name) {
		#coopy/SqliteHelper.hx:166: characters 9-49
		$tname = $db->getQuotedTableName($name);
		#coopy/SqliteHelper.hx:167: characters 9-75
		$query = "select sql from sqlite_master where name = " . ($tname??'null');
		#coopy/SqliteHelper.hx:168: lines 168-171
		if (!$db->begin($query, null, \Array_hx::wrap(["sql"]))) {
			#coopy/SqliteHelper.hx:169: characters 13-18
			(Log::$trace)("Cannot find schema for table " . ($tname??'null'), new _HxAnon_SqliteHelper0("coopy/SqliteHelper.hx", 169, "coopy.SqliteHelper", "fetchSchema"));
			#coopy/SqliteHelper.hx:170: characters 13-24
			return null;
		}
		#coopy/SqliteHelper.hx:172: characters 9-22
		$sql = "";
		#coopy/SqliteHelper.hx:173: lines 173-175
		if ($db->read()) {
			#coopy/SqliteHelper.hx:174: characters 13-28
			$sql = $db->get(0);
		}
		#coopy/SqliteHelper.hx:176: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:177: characters 9-19
		return $sql;
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * 
	 * @return int[]|\Array_hx
	 */
	public function getRowIDs ($db, $name) {
		#coopy/SqliteHelper.hx:35: characters 9-39
		$result = new \Array_hx();
		#coopy/SqliteHelper.hx:36: characters 9-93
		$q = "SELECT ROWID AS r FROM " . ($db->getQuotedTableName($name)??'null') . " ORDER BY ROWID";
		#coopy/SqliteHelper.hx:37: characters 9-49
		if (!$db->begin($q, null, \Array_hx::wrap(["r"]))) {
			#coopy/SqliteHelper.hx:37: characters 38-49
			return null;
		}
		#coopy/SqliteHelper.hx:38: lines 38-41
		while ($db->read()) {
			#coopy/SqliteHelper.hx:39: characters 13-42
			$c = $db->get(0);
			#coopy/SqliteHelper.hx:40: characters 13-27
			$result->arr[$result->length++] = $c;
		}
		#coopy/SqliteHelper.hx:42: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:44: characters 9-22
		return $result;
	}

	/**
	 * @param SqlDatabase $db
	 * 
	 * @return string[]|\Array_hx
	 */
	public function getTableNames ($db) {
		#coopy/SqliteHelper.hx:13: characters 9-83
		$q = "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name";
		#coopy/SqliteHelper.hx:14: characters 9-52
		if (!$db->begin($q, null, \Array_hx::wrap(["name"]))) {
			#coopy/SqliteHelper.hx:14: characters 41-52
			return null;
		}
		#coopy/SqliteHelper.hx:15: characters 9-41
		$names = new \Array_hx();
		#coopy/SqliteHelper.hx:16: lines 16-18
		while ($db->read()) {
			#coopy/SqliteHelper.hx:17: characters 13-34
			$x = $db->get(0);
			$names->arr[$names->length++] = $x;
		}
		#coopy/SqliteHelper.hx:19: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:20: characters 9-21
		return $names;
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param StringMap $vals
	 * 
	 * @return bool
	 */
	public function insert ($db, $name, $vals) {
		#coopy/SqliteHelper.hx:98: characters 9-69
		$q = "INSERT INTO " . ($db->getQuotedTableName($name)??'null') . " (";
		#coopy/SqliteHelper.hx:99: characters 9-40
		$lst = new \Array_hx();
		#coopy/SqliteHelper.hx:100: characters 19-30
		$data = \array_values(\array_map("strval", \array_keys($vals->data)));
		$k_current = 0;
		$k_length = \count($data);
		$k_data = $data;
		while ($k_current < $k_length) {
			#coopy/SqliteHelper.hx:100: lines 100-106
			$k = $k_data[$k_current++];
			#coopy/SqliteHelper.hx:101: lines 101-103
			if ($lst->length > 0) {
				#coopy/SqliteHelper.hx:102: characters 17-25
				$q = ($q??'null') . ",";
			}
			#coopy/SqliteHelper.hx:104: characters 13-43
			$q = ($q??'null') . ($db->getQuotedColumnName($k)??'null');
			#coopy/SqliteHelper.hx:105: characters 13-34
			$x = ($vals->data[$k] ?? null);
			$lst->arr[$lst->length++] = $x;
		}
		#coopy/SqliteHelper.hx:107: characters 9-25
		$q = ($q??'null') . ") VALUES(";
		#coopy/SqliteHelper.hx:108: characters 9-32
		$need_comma = false;
		#coopy/SqliteHelper.hx:109: characters 19-30
		$data = \array_values(\array_map("strval", \array_keys($vals->data)));
		$k_current = 0;
		$k_length = \count($data);
		$k_data = $data;
		while ($k_current < $k_length) {
			#coopy/SqliteHelper.hx:109: lines 109-115
			$k = $k_data[$k_current++];
			#coopy/SqliteHelper.hx:110: lines 110-112
			if ($need_comma) {
				#coopy/SqliteHelper.hx:111: characters 17-25
				$q = ($q??'null') . ",";
			}
			#coopy/SqliteHelper.hx:113: characters 13-21
			$q = ($q??'null') . "?";
			#coopy/SqliteHelper.hx:114: characters 13-23
			$need_comma = true;
		}
		#coopy/SqliteHelper.hx:116: characters 9-17
		$q = ($q??'null') . ")";
		#coopy/SqliteHelper.hx:117: lines 117-120
		if (!$db->begin($q, $lst, new \Array_hx())) {
			#coopy/SqliteHelper.hx:118: characters 13-18
			(Log::$trace)("Problem with database insert", new _HxAnon_SqliteHelper0("coopy/SqliteHelper.hx", 118, "coopy.SqliteHelper", "insert"));
			#coopy/SqliteHelper.hx:119: characters 13-25
			return false;
		}
		#coopy/SqliteHelper.hx:121: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:122: characters 9-20
		return true;
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param string $sql
	 * 
	 * @return object
	 */
	public function splitSchema ($db, $name, $sql) {
		#coopy/SqliteHelper.hx:181: characters 9-27
		$preamble = "";
		#coopy/SqliteHelper.hx:182: characters 9-41
		$parts = new \Array_hx();
		#coopy/SqliteHelper.hx:184: characters 9-34
		$double_quote = false;
		#coopy/SqliteHelper.hx:185: characters 9-34
		$single_quote = false;
		#coopy/SqliteHelper.hx:186: characters 9-24
		$token = "";
		#coopy/SqliteHelper.hx:187: characters 9-25
		$nesting = 0;
		#coopy/SqliteHelper.hx:188: characters 19-23
		$_g = 0;
		#coopy/SqliteHelper.hx:188: characters 23-33
		$_g1 = mb_strlen($sql);
		#coopy/SqliteHelper.hx:188: lines 188-230
		while ($_g < $_g1) {
			#coopy/SqliteHelper.hx:188: characters 19-33
			$i = $_g++;
			#coopy/SqliteHelper.hx:189: characters 13-36
			$ch = ($i < 0 ? "" : \mb_substr($sql, $i, 1));
			#coopy/SqliteHelper.hx:190: lines 190-199
			if ($double_quote || $single_quote) {
				#coopy/SqliteHelper.hx:191: lines 191-193
				if ($double_quote) {
					#coopy/SqliteHelper.hx:192: characters 21-55
					if ($ch === "\"") {
						#coopy/SqliteHelper.hx:192: characters 35-55
						$double_quote = false;
					}
				}
				#coopy/SqliteHelper.hx:194: lines 194-196
				if ($single_quote) {
					#coopy/SqliteHelper.hx:195: characters 21-55
					if ($ch === "'") {
						#coopy/SqliteHelper.hx:195: characters 35-55
						$single_quote = false;
					}
				}
				#coopy/SqliteHelper.hx:197: characters 17-28
				$token = ($token??'null') . ($ch??'null');
				#coopy/SqliteHelper.hx:198: characters 17-25
				continue;
			}
			#coopy/SqliteHelper.hx:200: characters 13-29
			$brk = false;
			#coopy/SqliteHelper.hx:201: lines 201-211
			if ($ch === "(") {
				#coopy/SqliteHelper.hx:202: characters 17-26
				++$nesting;
				#coopy/SqliteHelper.hx:203: lines 203-205
				if ($nesting === 1) {
					#coopy/SqliteHelper.hx:204: characters 21-31
					$brk = true;
				}
			} else if ($ch === ")") {
				#coopy/SqliteHelper.hx:207: characters 17-26
				--$nesting;
				#coopy/SqliteHelper.hx:208: lines 208-210
				if ($nesting === 0) {
					#coopy/SqliteHelper.hx:209: characters 21-31
					$brk = true;
				}
			}
			#coopy/SqliteHelper.hx:212: lines 212-216
			if ($ch === ",") {
				#coopy/SqliteHelper.hx:213: characters 17-27
				$brk = true;
				#coopy/SqliteHelper.hx:214: characters 21-31
				$tmp = $nesting === 1;
			}
			#coopy/SqliteHelper.hx:217: lines 217-229
			if ($brk) {
				#coopy/SqliteHelper.hx:218: lines 218-220
				if (\mb_substr($token, 0, 1) === " ") {
					#coopy/SqliteHelper.hx:219: characters 29-57
					$token = \mb_substr($token, 1, mb_strlen($token));
				}
				#coopy/SqliteHelper.hx:221: lines 221-225
				if ($preamble === "") {
					#coopy/SqliteHelper.hx:222: characters 21-37
					$preamble = $token;
				} else {
					#coopy/SqliteHelper.hx:224: characters 21-38
					$parts->arr[$parts->length++] = $token;
				}
				#coopy/SqliteHelper.hx:226: characters 17-27
				$token = "";
			} else {
				#coopy/SqliteHelper.hx:228: characters 17-28
				$token = ($token??'null') . ($ch??'null');
			}
		}
		#coopy/SqliteHelper.hx:231: characters 9-40
		$cols = $db->getColumns($name);
		#coopy/SqliteHelper.hx:232: characters 9-50
		$name2part = new StringMap();
		#coopy/SqliteHelper.hx:233: characters 9-52
		$name2col = new StringMap();
		#coopy/SqliteHelper.hx:234: characters 19-23
		$_g = 0;
		#coopy/SqliteHelper.hx:234: characters 23-34
		$_g1 = $cols->length;
		#coopy/SqliteHelper.hx:234: lines 234-238
		while ($_g < $_g1) {
			#coopy/SqliteHelper.hx:234: characters 19-34
			$i = $_g++;
			#coopy/SqliteHelper.hx:235: characters 13-31
			$col = ($cols->arr[$i] ?? null);
			#coopy/SqliteHelper.hx:236: characters 13-45
			$name2part->data[$col->name] = ($parts->arr[$i] ?? null);
			#coopy/SqliteHelper.hx:237: characters 13-43
			$name2col->data[$col->name] = ($cols->arr[$i] ?? null);
		}
		#coopy/SqliteHelper.hx:239: lines 239-245
		return new _HxAnon_SqliteHelper1($preamble, $parts, $name2part, $cols, $name2col);
	}

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param StringMap $conds
	 * @param StringMap $vals
	 * 
	 * @return bool
	 */
	public function update ($db, $name, $conds, $vals) {
		#coopy/SqliteHelper.hx:50: characters 9-67
		$q = "UPDATE " . ($db->getQuotedTableName($name)??'null') . " SET ";
		#coopy/SqliteHelper.hx:51: characters 9-40
		$lst = new \Array_hx();
		#coopy/SqliteHelper.hx:52: characters 19-30
		$data = \array_values(\array_map("strval", \array_keys($vals->data)));
		$k_current = 0;
		$k_length = \count($data);
		$k_data = $data;
		while ($k_current < $k_length) {
			#coopy/SqliteHelper.hx:52: lines 52-59
			$k = $k_data[$k_current++];
			#coopy/SqliteHelper.hx:53: lines 53-55
			if ($lst->length > 0) {
				#coopy/SqliteHelper.hx:54: characters 17-26
				$q = ($q??'null') . ", ";
			}
			#coopy/SqliteHelper.hx:56: characters 13-43
			$q = ($q??'null') . ($db->getQuotedColumnName($k)??'null');
			#coopy/SqliteHelper.hx:57: characters 13-24
			$q = ($q??'null') . " = ?";
			#coopy/SqliteHelper.hx:58: characters 13-34
			$x = ($vals->data[$k] ?? null);
			$lst->arr[$lst->length++] = $x;
		}
		#coopy/SqliteHelper.hx:60: characters 9-34
		$val_len = $lst->length;
		#coopy/SqliteHelper.hx:61: characters 9-23
		$q = ($q??'null') . " WHERE ";
		#coopy/SqliteHelper.hx:62: characters 19-31
		$data = \array_values(\array_map("strval", \array_keys($conds->data)));
		$k_current = 0;
		$k_length = \count($data);
		$k_data = $data;
		while ($k_current < $k_length) {
			#coopy/SqliteHelper.hx:62: lines 62-69
			$k = $k_data[$k_current++];
			#coopy/SqliteHelper.hx:63: lines 63-65
			if ($lst->length > $val_len) {
				#coopy/SqliteHelper.hx:64: characters 17-29
				$q = ($q??'null') . " and ";
			}
			#coopy/SqliteHelper.hx:66: characters 13-43
			$q = ($q??'null') . ($db->getQuotedColumnName($k)??'null');
			#coopy/SqliteHelper.hx:67: characters 13-25
			$q = ($q??'null') . " IS ?";
			#coopy/SqliteHelper.hx:68: characters 13-35
			$x = ($conds->data[$k] ?? null);
			$lst->arr[$lst->length++] = $x;
		}
		#coopy/SqliteHelper.hx:70: lines 70-73
		if (!$db->begin($q, $lst, new \Array_hx())) {
			#coopy/SqliteHelper.hx:71: characters 13-18
			(Log::$trace)("Problem with database update", new _HxAnon_SqliteHelper0("coopy/SqliteHelper.hx", 71, "coopy.SqliteHelper", "update"));
			#coopy/SqliteHelper.hx:72: characters 13-25
			return false;
		}
		#coopy/SqliteHelper.hx:74: characters 9-17
		$db->end();
		#coopy/SqliteHelper.hx:75: characters 9-20
		return true;
	}
}

class _HxAnon_SqliteHelper0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

class _HxAnon_SqliteHelper1 extends HxAnon {
	function __construct($_hx_0, $_hx_1, $_hx_2, $_hx_3, $_hx_4) {
		$this->{"preamble"} = $_hx_0;
		$this->{"parts"} = $_hx_1;
		$this->{"name2part"} = $_hx_2;
		$this->{"columns"} = $_hx_3;
		$this->{"name2column"} = $_hx_4;
	}
}

Boot::registerClass(SqliteHelper::class, 'coopy.SqliteHelper');
