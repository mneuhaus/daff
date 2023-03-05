<?php
/**
 */

namespace coopy;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Exception;
use \haxe\Log;
use \php\_Boot\HxString;
use \haxe\ds\StringMap;
use \haxe\format\JsonParser;
use \haxe\format\JsonPrinter;

/**
 *
 * This is the main entry-point to the library and the associated
 * command-line tool.
 *
 */
class Coopy {
	/**
	 * @var string
	 *
	 * Library version.
	 *
	 */
	static public $VERSION = "1.3.47";

	/**
	 * @var string
	 */
	public $cache_txt;
	/**
	 * @var string
	 */
	public $css_output;
	/**
	 * @var string
	 */
	public $csv_eol_preference;
	/**
	 * @var string
	 */
	public $daff_cmd;
	/**
	 * @var string
	 */
	public $delim_preference;
	/**
	 * @var bool
	 */
	public $diffs_found;
	/**
	 * @var bool
	 */
	public $extern_preference;
	/**
	 * @var bool
	 */
	public $fail_if_diff;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var string
	 */
	public $format_preference;
	/**
	 * @var bool
	 */
	public $fragment;
	/**
	 * @var TableIO
	 */
	public $io;
	/**
	 * @var Mover
	 */
	public $mv;
	/**
	 * @var bool
	 */
	public $nested_output;
	/**
	 * @var bool
	 */
	public $order_preference;
	/**
	 * @var bool
	 */
	public $order_set;
	/**
	 * @var string
	 */
	public $output_format;
	/**
	 * @var bool
	 */
	public $output_format_set;
	/**
	 * @var StringMap
	 */
	public $status;
	/**
	 * @var string
	 */
	public $strategy;

	/**
	 * @param Table $local
	 * @param Table $remote
	 * @param CompareFlags $flags
	 * @param TableComparisonState $comp
	 * 
	 * @return TableDiff
	 */
	public static function align ($local, $remote, $flags, $comp) {
		#coopy/Coopy.hx:140: characters 9-32
		$comp->a = Coopy::tablify($local);
		#coopy/Coopy.hx:141: characters 9-33
		$comp->b = Coopy::tablify($remote);
		#coopy/Coopy.hx:142: characters 9-52
		if ($flags === null) {
			#coopy/Coopy.hx:142: characters 26-52
			$flags = new CompareFlags();
		}
		#coopy/Coopy.hx:143: characters 9-35
		$comp->compare_flags = $flags;
		#coopy/Coopy.hx:144: characters 9-55
		$ct = new CompareTable($comp);
		#coopy/Coopy.hx:145: characters 9-44
		$align = $ct->align();
		#coopy/Coopy.hx:146: characters 9-57
		$td = new TableDiff($align, $flags);
		#coopy/Coopy.hx:147: characters 9-18
		return $td;
	}

	/**
	 * @param mixed $x
	 * 
	 * @return mixed
	 */
	public static function cellFor ($x) {
		#coopy/Coopy.hx:412: characters 9-17
		return $x;
	}

	/**
	 *
	 * Prepare to compare two tables.
	 *
	 * @param local the reference version of the table
	 * @param remote another version of the table
	 * @param flags control how the comparison will be made
	 * @return a worker you can use to make the comparison (normally you'll just want to call `.align()` on it)
	 *
	 * 
	 * @param Table $local
	 * @param Table $remote
	 * @param CompareFlags $flags
	 * 
	 * @return CompareTable
	 */
	public static function compareTables ($local, $remote, $flags = null) {
		#coopy/Coopy.hx:176: characters 9-70
		$comp = new TableComparisonState();
		#coopy/Coopy.hx:177: characters 9-32
		$comp->a = Coopy::tablify($local);
		#coopy/Coopy.hx:178: characters 9-33
		$comp->b = Coopy::tablify($remote);
		#coopy/Coopy.hx:179: characters 9-35
		$comp->compare_flags = $flags;
		#coopy/Coopy.hx:180: characters 9-55
		$ct = new CompareTable($comp);
		#coopy/Coopy.hx:181: characters 9-18
		return $ct;
	}

	/**
	 *
	 * Prepare to compare two tables, given knowledge of a common ancester.
	 * The comparison will answer: what changes should be made to `local`
	 * in order to incorporate the differences between `parent` and `remote`.
	 * This is useful if the `local` table has changes in it that you want
	 * to preserve.
	 *
	 * @param parent the common ancestor of the `local` and `remote` tables
	 * @param local the reference version of the table
	 * @param remote another version of the table
	 * @param flags control how the comparison will be made
	 * @return a worker you can use to make the comparison (normally you'll just want to call `.align()` on it)
	 *
	 * 
	 * @param Table $parent
	 * @param Table $local
	 * @param Table $remote
	 * @param CompareFlags $flags
	 * 
	 * @return CompareTable
	 */
	public static function compareTables3 ($parent, $local, $remote, $flags = null) {
		#coopy/Coopy.hx:200: characters 9-70
		$comp = new TableComparisonState();
		#coopy/Coopy.hx:201: characters 9-33
		$comp->p = Coopy::tablify($parent);
		#coopy/Coopy.hx:202: characters 9-32
		$comp->a = Coopy::tablify($local);
		#coopy/Coopy.hx:203: characters 9-33
		$comp->b = Coopy::tablify($remote);
		#coopy/Coopy.hx:204: characters 9-35
		$comp->compare_flags = $flags;
		#coopy/Coopy.hx:205: characters 9-55
		$ct = new CompareTable($comp);
		#coopy/Coopy.hx:206: characters 9-18
		return $ct;
	}

	/**
	 * Compare two tables and visualize their difference as another table
	 *
	 * @param local the reference version of the table
	 * @param remote another version of the table
	 * @param flags control how the comparison will be made
	 * @return a table like that produced by `daff a.csv b.csv`
	 *
	 * 
	 * @param Table $local
	 * @param Table $remote
	 * @param CompareFlags $flags
	 * 
	 * @return Table
	 */
	public static function diff ($local, $remote, $flags = null) {
		#coopy/Coopy.hx:119: characters 9-70
		$comp = new TableComparisonState();
		#coopy/Coopy.hx:120: characters 9-61
		$td = Coopy::align($local, $remote, $flags, $comp);
		#coopy/Coopy.hx:121: characters 9-48
		$o = Coopy::getBlankTable($td, $comp);
		#coopy/Coopy.hx:122: characters 9-46
		if ($comp->a !== null) {
			#coopy/Coopy.hx:122: characters 27-46
			$o = $comp->a->create();
		}
		#coopy/Coopy.hx:123: characters 9-57
		if (($o === null) && ($comp->b !== null)) {
			#coopy/Coopy.hx:123: characters 38-57
			$o = $comp->b->create();
		}
		#coopy/Coopy.hx:124: characters 9-46
		if ($o === null) {
			#coopy/Coopy.hx:124: characters 22-46
			$o = new SimpleTable(0, 0);
		}
		#coopy/Coopy.hx:125: characters 9-21
		$td->hilite($o);
		#coopy/Coopy.hx:126: characters 9-17
		return $o;
	}

	/**
	 * Compare two tables and visualize their difference in text decorated with ansi console codes
	 *
	 * @param local the reference version of the table
	 * @param remote another version of the table
	 * @param flags control how the comparison will be made
	 * @return a string like that produced by `daff --color a.csv b.csv`
	 *
	 * 
	 * @param Table $local
	 * @param Table $remote
	 * @param CompareFlags $flags
	 * 
	 * @return string
	 */
	public static function diffAsAnsi ($local, $remote, $flags = null) {
		#coopy/Coopy.hx:99: characters 9-45
		$tool = new Coopy(new TableIO());
		#coopy/Coopy.hx:100: characters 9-28
		$tool->cache_txt = "";
		#coopy/Coopy.hx:101: lines 101-103
		if ($flags === null) {
			#coopy/Coopy.hx:102: characters 13-39
			$flags = new CompareFlags();
		}
		#coopy/Coopy.hx:104: characters 9-35
		$tool->output_format = "csv";
		#coopy/Coopy.hx:105: characters 9-63
		$tool->runDiff($flags->parent, $local, $remote, $flags, null);
		#coopy/Coopy.hx:106: characters 9-30
		return $tool->cache_txt;
	}

	/**
	 * Compare two tables and visualize their difference using html
	 *
	 * @param local the reference version of the table
	 * @param remote another version of the table
	 * @param flags control how the comparison will be made
	 * @return an html string like that produced by `daff --output-format html --fragment a.csv b.csv`
	 *
	 * 
	 * @param Table $local
	 * @param Table $remote
	 * @param CompareFlags $flags
	 * 
	 * @return string
	 */
	public static function diffAsHtml ($local, $remote, $flags = null) {
		#coopy/Coopy.hx:77: characters 9-70
		$comp = new TableComparisonState();
		#coopy/Coopy.hx:78: characters 9-61
		$td = Coopy::align($local, $remote, $flags, $comp);
		#coopy/Coopy.hx:79: characters 9-48
		$o = Coopy::getBlankTable($td, $comp);
		#coopy/Coopy.hx:80: characters 9-46
		if ($comp->a !== null) {
			#coopy/Coopy.hx:80: characters 27-46
			$o = $comp->a->create();
		}
		#coopy/Coopy.hx:81: characters 9-57
		if (($o === null) && ($comp->b !== null)) {
			#coopy/Coopy.hx:81: characters 38-57
			$o = $comp->b->create();
		}
		#coopy/Coopy.hx:82: characters 9-46
		if ($o === null) {
			#coopy/Coopy.hx:82: characters 22-46
			$o = new SimpleTable(0, 0);
		}
		#coopy/Coopy.hx:83: characters 9-32
		$os = new Tables($o);
		#coopy/Coopy.hx:84: characters 9-33
		$td->hiliteWithNesting($os);
		#coopy/Coopy.hx:85: characters 9-39
		$render = new DiffRender();
		#coopy/Coopy.hx:86: characters 9-46
		return $render->renderTables($os)->html();
	}

	/**
	 * @param TableDiff $td
	 * @param TableComparisonState $comp
	 * 
	 * @return Table
	 */
	public static function getBlankTable ($td, $comp) {
		#coopy/Coopy.hx:131: characters 9-30
		$o = null;
		#coopy/Coopy.hx:132: characters 9-46
		if ($comp->a !== null) {
			#coopy/Coopy.hx:132: characters 27-46
			$o = $comp->a->create();
		}
		#coopy/Coopy.hx:133: characters 9-57
		if (($o === null) && ($comp->b !== null)) {
			#coopy/Coopy.hx:133: characters 38-57
			$o = $comp->b->create();
		}
		#coopy/Coopy.hx:134: characters 9-46
		if ($o === null) {
			#coopy/Coopy.hx:134: characters 22-46
			$o = new SimpleTable(0, 0);
		}
		#coopy/Coopy.hx:135: characters 9-17
		return $o;
	}

	/**
	 * @param Table $t
	 * 
	 * @return mixed
	 */
	public static function jsonify ($t) {
		#coopy/Coopy.hx:1196: characters 9-72
		$workbook = new StringMap();
		#coopy/Coopy.hx:1197: characters 9-73
		$sheet = new \Array_hx();
		#coopy/Coopy.hx:1198: characters 9-31
		$w = $t->get_width();
		#coopy/Coopy.hx:1199: characters 9-32
		$h = $t->get_height();
		#coopy/Coopy.hx:1200: characters 9-31
		$txt = "";
		#coopy/Coopy.hx:1201: characters 19-23
		$_g = 0;
		#coopy/Coopy.hx:1201: characters 23-24
		$_g1 = $h;
		#coopy/Coopy.hx:1201: lines 1201-1208
		while ($_g < $_g1) {
			#coopy/Coopy.hx:1201: characters 19-24
			$y = $_g++;
			#coopy/Coopy.hx:1202: characters 13-61
			$row = new \Array_hx();
			#coopy/Coopy.hx:1203: characters 23-27
			$_g2 = 0;
			#coopy/Coopy.hx:1203: characters 27-28
			$_g3 = $w;
			#coopy/Coopy.hx:1203: lines 1203-1206
			while ($_g2 < $_g3) {
				#coopy/Coopy.hx:1203: characters 23-28
				$x = $_g2++;
				#coopy/Coopy.hx:1204: characters 17-40
				$v = $t->getCell($x, $y);
				#coopy/Coopy.hx:1205: characters 17-28
				$row->arr[$row->length++] = $v;
			}
			#coopy/Coopy.hx:1207: characters 13-28
			$sheet->arr[$sheet->length++] = $row;
		}
		#coopy/Coopy.hx:1209: characters 9-36
		$workbook->data["sheet"] = $sheet;
		#coopy/Coopy.hx:1210: characters 9-24
		return $workbook;
	}

	/**
	 * @return int
	 */
	public static function keepAround () {
		#coopy/Coopy.hx:210: characters 9-53
		$st = new SimpleTable(1, 1);
		#coopy/Coopy.hx:211: characters 9-41
		$v = new Viterbi();
		#coopy/Coopy.hx:212: characters 9-55
		$td = new TableDiff(null, null);
		#coopy/Coopy.hx:213: characters 9-52
		$cf = new CompareFlags();
		#coopy/Coopy.hx:214: characters 9-41
		$idx = new Index($cf);
		#coopy/Coopy.hx:215: characters 9-48
		$dr = new DiffRender();
		#coopy/Coopy.hx:216: characters 9-65
		$hp = new HighlightPatch(null, null);
		#coopy/Coopy.hx:217: characters 9-35
		$csv = new Csv();
		#coopy/Coopy.hx:218: characters 9-58
		$tm = new TableModifier(null);
		#coopy/Coopy.hx:219: characters 9-71
		$sc = new SqlCompare(null, null, null, null, null);
		#coopy/Coopy.hx:220: characters 9-51
		$sq = new SqliteHelper();
		#coopy/Coopy.hx:221: characters 9-52
		$sm = new SimpleMeta(null);
		#coopy/Coopy.hx:222: characters 9-58
		$ct = new CombinedTable(null);
		#coopy/Coopy.hx:223: characters 9-17
		return 0;
	}

	/**
	 *
	 * This is the entry point for the daff command-line utility.
	 * It is a thin wrapper around the `coopyhx` method.
	 *
	 * 
	 * @return void
	 */
	public static function main () {
		#coopy/Coopy.hx:1171: characters 5-28
		$io = new TableIO();
		#coopy/Coopy.hx:1172: characters 5-29
		$coopy = new Coopy();
		#coopy/Coopy.hx:1173: characters 5-33
		$ret = $coopy->coopyhx($io);
		#coopy/Coopy.hx:1174: characters 5-30
		if ($ret !== 0) {
			#coopy/Coopy.hx:1174: characters 17-30
			exit($ret);
		}
	}

	/**
	 *
	 * Apply a patch to a table.
	 *
	 * @param local the reference version of the table
	 * @param patch the changes to apply (in daff format)
	 * @param flags control how the patch operations will be made
	 * @return true on success
	 *
	 * 
	 * @param Table $local
	 * @param Table $patch
	 * @param CompareFlags $flags
	 * 
	 * @return bool
	 */
	public static function patch ($local, $patch, $flags = null) {
		#coopy/Coopy.hx:161: characters 42-56
		$patcher = Coopy::tablify($local);
		#coopy/Coopy.hx:161: characters 9-73
		$patcher1 = new HighlightPatch($patcher, Coopy::tablify($patch));
		#coopy/Coopy.hx:162: characters 9-31
		return $patcher1->apply();
	}

	/**
	 * @param Table $t
	 * 
	 * @return void
	 */
	public static function show ($t) {
		#coopy/Coopy.hx:1181: characters 9-31
		$w = $t->get_width();
		#coopy/Coopy.hx:1182: characters 9-32
		$h = $t->get_height();
		#coopy/Coopy.hx:1183: characters 9-31
		$txt = "";
		#coopy/Coopy.hx:1184: characters 19-23
		$_g = 0;
		#coopy/Coopy.hx:1184: characters 23-24
		$_g1 = $h;
		#coopy/Coopy.hx:1184: lines 1184-1190
		while ($_g < $_g1) {
			#coopy/Coopy.hx:1184: characters 19-24
			$y = $_g++;
			#coopy/Coopy.hx:1185: characters 23-27
			$_g2 = 0;
			#coopy/Coopy.hx:1185: characters 27-28
			$_g3 = $w;
			#coopy/Coopy.hx:1185: lines 1185-1188
			while ($_g2 < $_g3) {
				#coopy/Coopy.hx:1185: characters 23-28
				$x = $_g2++;
				#coopy/Coopy.hx:1186: characters 17-38
				$txt = ($txt??'null') . \Std::string($t->getCell($x, $y));
				#coopy/Coopy.hx:1187: characters 17-27
				$txt = ($txt??'null') . " ";
			}
			#coopy/Coopy.hx:1189: characters 13-24
			$txt = ($txt??'null') . "\x0A";
		}
		#coopy/Coopy.hx:1191: characters 9-14
		(Log::$trace)($txt, new _HxAnon_Coopy0("coopy/Coopy.hx", 1191, "coopy.Coopy", "show"));
	}

	/**
	 *
	 * This takes input in an unknown format and tries to make a table out of it,
	 * through the power of guesswork.
	 *
	 * @param t an alleged table
	 * @return a daff-compatible table, or null
	 *
	 * 
	 * @param mixed $data
	 * 
	 * @return Table
	 */
	public static function tablify ($data) {
		#coopy/Coopy.hx:1223: characters 9-36
		if ($data === null) {
			#coopy/Coopy.hx:1223: characters 25-36
			return $data;
		}
		#coopy/Coopy.hx:1227: characters 9-63
		$get_cell_view = \Reflect::field($data, "getCellView");
		#coopy/Coopy.hx:1228: characters 9-45
		if ($get_cell_view !== null) {
			#coopy/Coopy.hx:1228: characters 34-45
			return $data;
		}
		#coopy/Coopy.hx:1240: characters 9-64
		return new coopy_PhpTableView($data);
	}

	/**
	 * @param TableIO $io
	 * 
	 * @return void
	 */
	public function __construct ($io = null) {
		#coopy/Coopy.hx:44: characters 9-15
		$this->init();
		#coopy/Coopy.hx:45: characters 9-21
		$this->io = $io;
	}

	/**
	 * @param string $name
	 * @param DiffRender $renderer
	 * 
	 * @return bool
	 */
	public function applyRenderer ($name, $renderer) {
		#coopy/Coopy.hx:291: lines 291-293
		if (!$this->fragment) {
			#coopy/Coopy.hx:292: characters 13-36
			$renderer->completeHtml();
		}
		#coopy/Coopy.hx:294: lines 294-298
		if ($this->format_preference === "www") {
			#coopy/Coopy.hx:295: characters 13-46
			$this->io->sendToBrowser($renderer->html());
		} else {
			#coopy/Coopy.hx:297: characters 13-43
			$this->saveText($name, $renderer->html());
		}
		#coopy/Coopy.hx:299: lines 299-301
		if ($this->css_output !== null) {
			#coopy/Coopy.hx:300: characters 13-54
			$this->saveText($this->css_output, $renderer->sampleCss());
		}
		#coopy/Coopy.hx:302: characters 9-20
		return true;
	}

	/**
	 * @param string $name
	 * 
	 * @return string
	 */
	public function checkFormat ($name) {
		#coopy/Coopy.hx:229: lines 229-231
		if ($this->extern_preference) {
			#coopy/Coopy.hx:230: characters 13-37
			return $this->format_preference;
		}
		#coopy/Coopy.hx:232: characters 9-22
		$ext = "";
		#coopy/Coopy.hx:233: lines 233-271
		if ($name !== null) {
			#coopy/Coopy.hx:234: characters 13-44
			$pt = HxString::lastIndexOf($name, ".");
			#coopy/Coopy.hx:235: lines 235-270
			if ($pt >= 0) {
				#coopy/Coopy.hx:236: characters 23-54
				$ext = \mb_strtolower(\mb_substr($name, $pt + 1, null));
				#coopy/Coopy.hx:237: lines 237-269
				if ($ext === "csv") {
					#coopy/Coopy.hx:243: characters 21-46
					$this->format_preference = "csv";
					#coopy/Coopy.hx:244: characters 21-43
					$this->delim_preference = ",";
				} else if ($ext === "htm" || $ext === "html") {
					#coopy/Coopy.hx:264: characters 21-47
					$this->format_preference = "html";
				} else if ($ext === "json") {
					#coopy/Coopy.hx:239: characters 21-47
					$this->format_preference = "json";
				} else if ($ext === "ndjson") {
					#coopy/Coopy.hx:241: characters 21-49
					$this->format_preference = "ndjson";
				} else if ($ext === "psv") {
					#coopy/Coopy.hx:253: characters 21-46
					$this->format_preference = "csv";
					#coopy/Coopy.hx:257: characters 21-68
					$this->delim_preference = \mb_chr(128169);
				} else if ($ext === "sqlite") {
					#coopy/Coopy.hx:262: characters 21-49
					$this->format_preference = "sqlite";
				} else if ($ext === "sqlite3") {
					#coopy/Coopy.hx:260: characters 21-49
					$this->format_preference = "sqlite";
				} else if ($ext === "ssv") {
					#coopy/Coopy.hx:249: characters 21-46
					$this->format_preference = "csv";
					#coopy/Coopy.hx:250: characters 21-43
					$this->delim_preference = ";";
					#coopy/Coopy.hx:251: characters 6-36
					$this->format_preference = "csv";
				} else if ($ext === "tsv") {
					#coopy/Coopy.hx:246: characters 21-46
					$this->format_preference = "csv";
					#coopy/Coopy.hx:247: characters 21-44
					$this->delim_preference = "\x09";
				} else if ($ext === "www") {
					#coopy/Coopy.hx:266: characters 21-46
					$this->format_preference = "www";
				} else {
					#coopy/Coopy.hx:268: characters 21-29
					$ext = "";
				}
			}
		}
		#coopy/Coopy.hx:272: characters 9-81
		$this->nested_output = ($this->format_preference === "json") || ($this->format_preference === "ndjson");
		#coopy/Coopy.hx:273: characters 9-42
		$this->order_preference = !$this->nested_output;
		#coopy/Coopy.hx:274: characters 9-19
		return $ext;
	}

	/**
	 * @param TableIO $io
	 * @param string $cmd
	 * @param string[]|\Array_hx $args
	 * 
	 * @return int
	 */
	public function command ($io, $cmd, $args) {
		#coopy/Coopy.hx:547: characters 9-19
		$r = 0;
		#coopy/Coopy.hx:548: characters 9-52
		if ($io->hasAsync()) {
			#coopy/Coopy.hx:548: characters 28-52
			$r = $io->command($cmd, $args);
		}
		#coopy/Coopy.hx:549: lines 549-559
		if ($r !== 999) {
			#coopy/Coopy.hx:550: characters 13-39
			$io->writeStdout("\$ " . ($cmd??'null'));
			#coopy/Coopy.hx:551: lines 551-557
			$_g = 0;
			while ($_g < $args->length) {
				#coopy/Coopy.hx:551: characters 18-21
				$arg = ($args->arr[$_g] ?? null);
				#coopy/Coopy.hx:551: lines 551-557
				++$_g;
				#coopy/Coopy.hx:552: characters 17-36
				$io->writeStdout(" ");
				#coopy/Coopy.hx:553: characters 17-50
				$spaced = HxString::indexOf($arg, " ") >= 0;
				#coopy/Coopy.hx:554: characters 17-49
				if ($spaced) {
					#coopy/Coopy.hx:554: characters 29-49
					$io->writeStdout("\"");
				}
				#coopy/Coopy.hx:555: characters 17-36
				$io->writeStdout($arg);
				#coopy/Coopy.hx:556: characters 17-49
				if ($spaced) {
					#coopy/Coopy.hx:556: characters 29-49
					$io->writeStdout("\"");
				}
			}
			#coopy/Coopy.hx:558: characters 13-33
			$io->writeStdout("\x0A");
		}
		#coopy/Coopy.hx:560: characters 9-53
		if (!$io->hasAsync()) {
			#coopy/Coopy.hx:560: characters 29-53
			$r = $io->command($cmd, $args);
		}
		#coopy/Coopy.hx:561: characters 9-17
		return $r;
	}

	/**
	 * @param TableIO $io
	 * 
	 * @return int
	 */
	public function coopyhx ($io) {
		#coopy/Coopy.hx:1152: characters 9-46
		$args = $io->args();
		#coopy/Coopy.hx:1155: lines 1155-1157
		if (($args->arr[0] ?? null) === "--keep") {
			#coopy/Coopy.hx:1156: characters 13-32
			return Coopy::keepAround();
		}
		#coopy/Coopy.hx:1159: characters 9-29
		return $this->run($args, $io);
	}

	/**
	 * @param string $name
	 * @param Table $t
	 * @param TerminalDiffRender $render
	 * 
	 * @return string
	 */
	public function encodeTable ($name, $t, $render = null) {
		#coopy/Coopy.hx:324: lines 324-326
		if ($this->output_format !== "copy") {
			#coopy/Coopy.hx:325: characters 13-37
			$this->setFormat($this->output_format);
		}
		#coopy/Coopy.hx:327: characters 9-31
		$txt = "";
		#coopy/Coopy.hx:328: characters 9-26
		$this->checkFormat($name);
		#coopy/Coopy.hx:329: lines 329-331
		if (($this->format_preference === "sqlite") && !$this->extern_preference) {
			#coopy/Coopy.hx:330: characters 13-38
			$this->format_preference = "csv";
		}
		#coopy/Coopy.hx:332: lines 332-350
		if ($render === null) {
			#coopy/Coopy.hx:333: lines 333-347
			if ($this->format_preference === "csv") {
				#coopy/Coopy.hx:334: characters 17-79
				$csv = new Csv($this->delim_preference, $this->csv_eol_preference);
				#coopy/Coopy.hx:335: characters 17-41
				$txt = $csv->renderTable($t);
			} else if ($this->format_preference === "ndjson") {
				#coopy/Coopy.hx:337: characters 17-45
				$txt = (new Ndjson($t))->render();
			} else if (($this->format_preference === "html") || ($this->format_preference === "www")) {
				#coopy/Coopy.hx:339: characters 17-36
				$this->renderTable($name, $t);
				#coopy/Coopy.hx:340: characters 17-28
				return null;
			} else if ($this->format_preference === "sqlite") {
				#coopy/Coopy.hx:343: characters 17-76
				$this->io->writeStderr("! Cannot yet output to sqlite, aborting\x0A");
				#coopy/Coopy.hx:344: characters 17-26
				return "";
			} else {
				#coopy/Coopy.hx:346: characters 23-64
				$txt = JsonPrinter::print(Coopy::jsonify($t), null, "  ");
			}
		} else {
			#coopy/Coopy.hx:349: characters 13-35
			$txt = $render->render($t);
		}
		#coopy/Coopy.hx:351: characters 9-19
		return $txt;
	}

	/**
	 * @return DiffRender
	 */
	public function getRenderer () {
		#coopy/Coopy.hx:284: characters 9-54
		$renderer = new DiffRender();
		#coopy/Coopy.hx:285: characters 9-51
		$renderer->usePrettyArrows($this->flags->use_glyphs);
		#coopy/Coopy.hx:286: characters 9-45
		$renderer->quoteHtml($this->flags->quote_html);
		#coopy/Coopy.hx:287: characters 9-24
		return $renderer;
	}

	/**
	 * @return void
	 */
	public function init () {
		#coopy/Coopy.hx:49: characters 9-34
		$this->extern_preference = false;
		#coopy/Coopy.hx:50: characters 9-33
		$this->format_preference = null;
		#coopy/Coopy.hx:51: characters 9-32
		$this->delim_preference = null;
		#coopy/Coopy.hx:52: characters 9-34
		$this->csv_eol_preference = null;
		#coopy/Coopy.hx:53: characters 9-31
		$this->output_format = "copy";
		#coopy/Coopy.hx:54: characters 9-34
		$this->output_format_set = false;
		#coopy/Coopy.hx:55: characters 9-30
		$this->nested_output = false;
		#coopy/Coopy.hx:56: characters 9-26
		$this->order_set = false;
		#coopy/Coopy.hx:57: characters 9-33
		$this->order_preference = false;
		#coopy/Coopy.hx:58: characters 9-24
		$this->strategy = null;
		#coopy/Coopy.hx:59: characters 9-26
		$this->css_output = null;
		#coopy/Coopy.hx:60: characters 9-25
		$this->fragment = false;
		#coopy/Coopy.hx:61: characters 9-21
		$this->flags = null;
		#coopy/Coopy.hx:62: characters 9-25
		$this->cache_txt = null;
		#coopy/Coopy.hx:63: characters 9-29
		$this->fail_if_diff = false;
		#coopy/Coopy.hx:64: characters 9-28
		$this->diffs_found = false;
	}

	/**
	 * @param TableIO $io
	 * @param string[]|\Array_hx $formats
	 * 
	 * @return int
	 */
	public function installGitDriver ($io, $formats) {
		#coopy/Coopy.hx:565: characters 9-19
		$r = 0;
		#coopy/Coopy.hx:567: lines 567-570
		if ($this->status === null) {
			#coopy/Coopy.hx:568: characters 13-43
			$this->status = new StringMap();
			#coopy/Coopy.hx:569: characters 13-26
			$this->daff_cmd = "";
		}
		#coopy/Coopy.hx:572: characters 9-27
		$key = "hello";
		#coopy/Coopy.hx:573: lines 573-580
		if (!\array_key_exists($key, $this->status->data)) {
			#coopy/Coopy.hx:574: characters 13-60
			$io->writeStdout("Setting up git to use daff on");
			#coopy/Coopy.hx:575: lines 575-577
			$_g = 0;
			while ($_g < $formats->length) {
				#coopy/Coopy.hx:575: characters 18-24
				$format = ($formats->arr[$_g] ?? null);
				#coopy/Coopy.hx:575: lines 575-577
				++$_g;
				#coopy/Coopy.hx:576: characters 17-47
				$io->writeStdout(" *." . ($format??'null'));
			}
			#coopy/Coopy.hx:578: characters 13-39
			$io->writeStdout(" files\x0A");
			#coopy/Coopy.hx:579: characters 13-30
			$this->status->data[$key] = $r;
		}
		#coopy/Coopy.hx:582: characters 9-28
		$key = "can_run_git";
		#coopy/Coopy.hx:583: lines 583-592
		if (!\array_key_exists($key, $this->status->data)) {
			#coopy/Coopy.hx:584: characters 13-48
			$r = $this->command($io, "git", \Array_hx::wrap(["--version"]));
			#coopy/Coopy.hx:585: characters 13-33
			if ($r === 999) {
				#coopy/Coopy.hx:585: characters 25-33
				return $r;
			}
			#coopy/Coopy.hx:586: characters 13-30
			$this->status->data[$key] = $r;
			#coopy/Coopy.hx:587: lines 587-590
			if ($r !== 0) {
				#coopy/Coopy.hx:588: characters 17-63
				$io->writeStderr("! Cannot run git, aborting\x0A");
				#coopy/Coopy.hx:589: characters 17-25
				return 1;
			}
			#coopy/Coopy.hx:591: characters 13-46
			$io->writeStdout("- Can run git\x0A");
		}
		#coopy/Coopy.hx:594: characters 9-50
		$daffs = \Array_hx::wrap([
			"daff",
			"daff.rb",
			"daff.py",
		]);
		#coopy/Coopy.hx:595: lines 595-613
		if ($this->daff_cmd === "") {
			#coopy/Coopy.hx:596: lines 596-608
			$_g = 0;
			while ($_g < $daffs->length) {
				#coopy/Coopy.hx:596: characters 18-22
				$daff = ($daffs->arr[$_g] ?? null);
				#coopy/Coopy.hx:596: lines 596-608
				++$_g;
				#coopy/Coopy.hx:597: characters 17-45
				$key1 = "can_run_" . ($daff??'null');
				#coopy/Coopy.hx:598: lines 598-607
				if (!\array_key_exists($key1, $this->status->data)) {
					#coopy/Coopy.hx:599: characters 21-53
					$r = $this->command($io, $daff, \Array_hx::wrap(["version"]));
					#coopy/Coopy.hx:600: characters 21-41
					if ($r === 999) {
						#coopy/Coopy.hx:600: characters 33-41
						return $r;
					}
					#coopy/Coopy.hx:601: characters 21-38
					$this->status->data[$key1] = $r;
					#coopy/Coopy.hx:602: lines 602-606
					if ($r === 0) {
						#coopy/Coopy.hx:603: characters 25-40
						$this->daff_cmd = $daff;
						#coopy/Coopy.hx:604: characters 25-87
						$io->writeStdout("- Can run " . ($daff??'null') . " as \"" . ($daff??'null') . "\"\x0A");
						#coopy/Coopy.hx:605: characters 25-30
						break;
					}
				}
			}
			#coopy/Coopy.hx:609: lines 609-612
			if ($this->daff_cmd === "") {
				#coopy/Coopy.hx:610: characters 17-76
				$io->writeStderr("! Cannot find daff, is it in your path?\x0A");
				#coopy/Coopy.hx:611: characters 17-25
				return 1;
			}
		}
		#coopy/Coopy.hx:616: lines 616-668
		$_g = 0;
		while ($_g < $formats->length) {
			#coopy/Coopy.hx:616: characters 14-20
			$format = ($formats->arr[$_g] ?? null);
			#coopy/Coopy.hx:616: lines 616-668
			++$_g;
			#coopy/Coopy.hx:618: characters 13-47
			$key = "have_diff_driver_" . ($format??'null');
			#coopy/Coopy.hx:619: lines 619-623
			if (!\array_key_exists($key, $this->status->data)) {
				#coopy/Coopy.hx:620: characters 17-103
				$r = $this->command($io, "git", \Array_hx::wrap([
					"config",
					"--global",
					"--get",
					"diff.daff-" . ($format??'null') . ".command",
				]));
				#coopy/Coopy.hx:621: characters 17-37
				if ($r === 999) {
					#coopy/Coopy.hx:621: characters 29-37
					return $r;
				}
				#coopy/Coopy.hx:622: characters 17-34
				$this->status->data[$key] = $r;
			}
			#coopy/Coopy.hx:625: characters 13-55
			$have_diff_driver = ($this->status->data[$key] ?? null) === 0;
			#coopy/Coopy.hx:627: characters 13-46
			$key = "add_diff_driver_" . ($format??'null');
			#coopy/Coopy.hx:628: lines 628-636
			if (!\array_key_exists($key, $this->status->data)) {
				#coopy/Coopy.hx:629: characters 17-120
				$r = $this->command($io, "git", \Array_hx::wrap([
					"config",
					"--global",
					"diff.daff-" . ($format??'null') . ".command",
					($this->daff_cmd??'null') . " diff --git",
				]));
				#coopy/Coopy.hx:630: characters 17-37
				if ($r === 999) {
					#coopy/Coopy.hx:630: characters 29-37
					return $r;
				}
				#coopy/Coopy.hx:631: lines 631-633
				if ($have_diff_driver) {
					#coopy/Coopy.hx:632: characters 21-95
					$io->writeStdout("- Cleared existing daff diff driver for " . ($format??'null') . "\x0A");
				}
				#coopy/Coopy.hx:634: characters 17-75
				$io->writeStdout("- Added diff driver for " . ($format??'null') . "\x0A");
				#coopy/Coopy.hx:635: characters 17-34
				$this->status->data[$key] = $r;
			}
			#coopy/Coopy.hx:638: characters 13-48
			$key = "have_merge_driver_" . ($format??'null');
			#coopy/Coopy.hx:639: lines 639-643
			if (!\array_key_exists($key, $this->status->data)) {
				#coopy/Coopy.hx:640: characters 17-103
				$r = $this->command($io, "git", \Array_hx::wrap([
					"config",
					"--global",
					"--get",
					"merge.daff-" . ($format??'null') . ".driver",
				]));
				#coopy/Coopy.hx:641: characters 17-37
				if ($r === 999) {
					#coopy/Coopy.hx:641: characters 29-37
					return $r;
				}
				#coopy/Coopy.hx:642: characters 17-34
				$this->status->data[$key] = $r;
			}
			#coopy/Coopy.hx:645: characters 13-56
			$have_merge_driver = ($this->status->data[$key] ?? null) === 0;
			#coopy/Coopy.hx:647: characters 13-48
			$key = "name_merge_driver_" . ($format??'null');
			#coopy/Coopy.hx:648: lines 648-656
			if (!\array_key_exists($key, $this->status->data)) {
				#coopy/Coopy.hx:649: lines 649-654
				if (!$have_merge_driver) {
					#coopy/Coopy.hx:650: characters 21-133
					$r = $this->command($io, "git", \Array_hx::wrap([
						"config",
						"--global",
						"merge.daff-" . ($format??'null') . ".name",
						"daff tabular " . ($format??'null') . " merge",
					]));
					#coopy/Coopy.hx:651: characters 21-41
					if ($r === 999) {
						#coopy/Coopy.hx:651: characters 33-41
						return $r;
					}
				} else {
					#coopy/Coopy.hx:653: characters 21-26
					$r = 0;
				}
				#coopy/Coopy.hx:655: characters 17-34
				$this->status->data[$key] = $r;
			}
			#coopy/Coopy.hx:658: characters 13-47
			$key = "add_merge_driver_" . ($format??'null');
			#coopy/Coopy.hx:659: lines 659-667
			if (!\array_key_exists($key, $this->status->data)) {
				#coopy/Coopy.hx:660: characters 17-136
				$r = $this->command($io, "git", \Array_hx::wrap([
					"config",
					"--global",
					"merge.daff-" . ($format??'null') . ".driver",
					($this->daff_cmd??'null') . " merge --output %A %O %A %B",
				]));
				#coopy/Coopy.hx:661: characters 17-37
				if ($r === 999) {
					#coopy/Coopy.hx:661: characters 29-37
					return $r;
				}
				#coopy/Coopy.hx:662: lines 662-664
				if ($have_merge_driver) {
					#coopy/Coopy.hx:663: characters 21-96
					$io->writeStdout("- Cleared existing daff merge driver for " . ($format??'null') . "\x0A");
				}
				#coopy/Coopy.hx:665: characters 17-76
				$io->writeStdout("- Added merge driver for " . ($format??'null') . "\x0A");
				#coopy/Coopy.hx:666: characters 17-34
				$this->status->data[$key] = $r;
			}
		}
		#coopy/Coopy.hx:670: lines 670-674
		if (!$io->exists(".git/config")) {
			#coopy/Coopy.hx:671: characters 13-86
			$io->writeStderr("! This next part needs to happen in a git repository.\x0A");
			#coopy/Coopy.hx:672: characters 13-86
			$io->writeStderr("! Please run again from the root of a git repository.\x0A");
			#coopy/Coopy.hx:673: characters 13-21
			return 1;
		}
		#coopy/Coopy.hx:676: characters 9-37
		$attr = ".gitattributes";
		#coopy/Coopy.hx:677: characters 9-22
		$txt = "";
		#coopy/Coopy.hx:678: characters 9-23
		$post = "";
		#coopy/Coopy.hx:679: lines 679-684
		if (!$io->exists($attr)) {
			#coopy/Coopy.hx:680: characters 13-57
			$io->writeStdout("- No .gitattributes file\x0A");
		} else {
			#coopy/Coopy.hx:682: characters 13-65
			$io->writeStdout("- You have a .gitattributes file\x0A");
			#coopy/Coopy.hx:683: characters 13-38
			$txt = $io->getContent($attr);
		}
		#coopy/Coopy.hx:686: characters 9-33
		$need_update = false;
		#coopy/Coopy.hx:687: lines 687-699
		$_g = 0;
		while ($_g < $formats->length) {
			#coopy/Coopy.hx:687: characters 14-20
			$format = ($formats->arr[$_g] ?? null);
			#coopy/Coopy.hx:687: lines 687-699
			++$_g;
			#coopy/Coopy.hx:688: lines 688-698
			if (HxString::indexOf($txt, "*." . ($format??'null')) >= 0) {
				#coopy/Coopy.hx:689: characters 17-97
				$io->writeStderr("- Your .gitattributes file already mentions *." . ($format??'null') . "\x0A");
			} else {
				#coopy/Coopy.hx:691: characters 17-70
				$post = ($post??'null') . "*." . ($format??'null') . " diff=daff-" . ($format??'null') . "\x0A";
				#coopy/Coopy.hx:692: characters 17-71
				$post = ($post??'null') . "*." . ($format??'null') . " merge=daff-" . ($format??'null') . "\x0A";
				#coopy/Coopy.hx:693: characters 17-85
				$io->writeStdout("- Placing the following lines in .gitattributes:\x0A");
				#coopy/Coopy.hx:694: characters 17-37
				$io->writeStdout($post);
				#coopy/Coopy.hx:695: characters 17-55
				if (($txt !== "") && !$need_update) {
					#coopy/Coopy.hx:695: characters 44-55
					$txt = ($txt??'null') . "\x0A";
				}
				#coopy/Coopy.hx:696: characters 17-28
				$txt = ($txt??'null') . ($post??'null');
				#coopy/Coopy.hx:697: characters 17-35
				$need_update = true;
			}
		}
		#coopy/Coopy.hx:700: characters 9-50
		if ($need_update) {
			#coopy/Coopy.hx:700: characters 26-50
			$io->saveContent($attr, $txt);
		}
		#coopy/Coopy.hx:702: characters 9-36
		$io->writeStdout("- Done!\x0A");
		#coopy/Coopy.hx:704: characters 9-17
		return 0;
	}

	/**
	 * @param mixed $json
	 * 
	 * @return Table
	 */
	public function jsonToTable ($json) {
		#coopy/Coopy.hx:425: characters 9-35
		$output = null;
		#coopy/Coopy.hx:426: lines 426-456
		$_g = 0;
		$_g1 = \Reflect::fields($json);
		while ($_g < $_g1->length) {
			#coopy/Coopy.hx:426: characters 14-18
			$name = ($_g1->arr[$_g] ?? null);
			#coopy/Coopy.hx:426: lines 426-456
			++$_g;
			#coopy/Coopy.hx:427: characters 13-46
			$t = \Reflect::field($json, $name);
			#coopy/Coopy.hx:428: characters 13-70
			$columns = \Reflect::field($t, "columns");
			#coopy/Coopy.hx:429: characters 13-40
			if ($columns === null) {
				#coopy/Coopy.hx:429: characters 32-40
				continue;
			}
			#coopy/Coopy.hx:430: characters 13-65
			$rows = \Reflect::field($t, "rows");
			#coopy/Coopy.hx:431: characters 13-37
			if ($rows === null) {
				#coopy/Coopy.hx:431: characters 29-37
				continue;
			}
			#coopy/Coopy.hx:432: characters 13-65
			$output = new SimpleTable($columns->length, $rows->length);
			#coopy/Coopy.hx:433: characters 13-41
			$has_hash = false;
			#coopy/Coopy.hx:434: characters 13-47
			$has_hash_known = false;
			#coopy/Coopy.hx:435: characters 23-27
			$_g2 = 0;
			#coopy/Coopy.hx:435: characters 27-38
			$_g3 = $rows->length;
			#coopy/Coopy.hx:435: lines 435-455
			while ($_g2 < $_g3) {
				#coopy/Coopy.hx:435: characters 23-38
				$i = $_g2++;
				#coopy/Coopy.hx:436: characters 17-35
				$row = ($rows->arr[$i] ?? null);
				#coopy/Coopy.hx:437: lines 437-442
				if (!$has_hash_known) {
					#coopy/Coopy.hx:438: lines 438-440
					if (\Reflect::fields($row)->length === $columns->length) {
						#coopy/Coopy.hx:439: characters 25-40
						$has_hash = true;
					}
					#coopy/Coopy.hx:441: characters 21-42
					$has_hash_known = true;
				}
				#coopy/Coopy.hx:443: lines 443-454
				if (!$has_hash) {
					#coopy/Coopy.hx:444: characters 21-57
					$lst = $row;
					#coopy/Coopy.hx:445: characters 31-35
					$_g4 = 0;
					#coopy/Coopy.hx:445: characters 35-49
					$_g5 = $columns->length;
					#coopy/Coopy.hx:445: lines 445-448
					while ($_g4 < $_g5) {
						#coopy/Coopy.hx:445: characters 31-49
						$j = $_g4++;
						#coopy/Coopy.hx:446: characters 25-42
						$val = ($lst->arr[$j] ?? null);
						#coopy/Coopy.hx:447: characters 25-57
						$output->setCell($j, $i, Coopy::cellFor($val));
					}
				} else {
					#coopy/Coopy.hx:450: characters 31-35
					$_g6 = 0;
					#coopy/Coopy.hx:450: characters 35-49
					$_g7 = $columns->length;
					#coopy/Coopy.hx:450: lines 450-453
					while ($_g6 < $_g7) {
						#coopy/Coopy.hx:450: characters 31-49
						$j1 = $_g6++;
						#coopy/Coopy.hx:451: characters 25-65
						$val1 = \Reflect::field($row, ($columns->arr[$j1] ?? null));
						#coopy/Coopy.hx:452: characters 25-57
						$output->setCell($j1, $i, Coopy::cellFor($val1));
					}
				}
			}
		}
		#coopy/Coopy.hx:457: characters 9-45
		if ($output !== null) {
			#coopy/Coopy.hx:457: characters 27-45
			$output->trimBlank();
		}
		#coopy/Coopy.hx:458: characters 9-22
		return $output;
	}

	/**
	 * @param mixed $json
	 * 
	 * @return Table
	 */
	public function jsonToTables ($json) {
		#coopy/Coopy.hx:417: characters 9-51
		$tables = \Reflect::field($json, "tables");
		#coopy/Coopy.hx:418: lines 418-420
		if ($tables === null) {
			#coopy/Coopy.hx:419: characters 13-37
			return $this->jsonToTable($json);
		}
		#coopy/Coopy.hx:421: characters 9-42
		return new JsonTables($json, $this->flags);
	}

	/**
	 *
	 * Load a table from a file.
	 *
	 * @param name filename to read from
	 * @param role one of "parent", "local", or "remote"
	 * @return a table
	 *
	 * 
	 * @param string $name
	 * @param string $role
	 * 
	 * @return Table
	 */
	public function loadTable ($name, $role) {
		#coopy/Coopy.hx:504: characters 9-37
		$ext = $this->checkFormat($name);
		#coopy/Coopy.hx:505: lines 505-513
		if ($ext === "sqlite") {
			#coopy/Coopy.hx:506: characters 13-51
			$sql = $this->io->openSqliteDatabase($name);
			#coopy/Coopy.hx:507: lines 507-510
			if ($sql === null) {
				#coopy/Coopy.hx:508: characters 17-69
				$this->io->writeStderr("! Cannot open database, aborting\x0A");
				#coopy/Coopy.hx:509: characters 17-28
				return null;
			}
			#coopy/Coopy.hx:511: characters 13-53
			$tab = new SqlTables($sql, $this->flags, $role);
			#coopy/Coopy.hx:512: characters 13-23
			return $tab;
		}
		#coopy/Coopy.hx:514: characters 9-48
		$txt = $this->io->getContent($name);
		#coopy/Coopy.hx:515: lines 515-520
		if ($ext === "ndjson") {
			#coopy/Coopy.hx:516: characters 13-50
			$t = new SimpleTable(0, 0);
			#coopy/Coopy.hx:517: characters 13-40
			$ndjson = new Ndjson($t);
			#coopy/Coopy.hx:518: characters 13-30
			$ndjson->parse($txt);
			#coopy/Coopy.hx:519: characters 13-21
			return $t;
		}
		#coopy/Coopy.hx:521: lines 521-531
		if (($ext === "json") || ($ext === "")) {
			#coopy/Coopy.hx:522: lines 522-530
			try {
				#coopy/Coopy.hx:523: characters 17-49
				$json = (new JsonParser($txt))->doParse();
				#coopy/Coopy.hx:524: characters 17-43
				$this->format_preference = "json";
				#coopy/Coopy.hx:525: characters 17-52
				$t = $this->jsonToTables($json);
				#coopy/Coopy.hx:526: characters 17-35
				if ($t === null) {
					#coopy/Coopy.hx:526: characters 30-35
					throw Exception::thrown("JSON failed");
				}
				#coopy/Coopy.hx:527: characters 17-25
				return $t;
			} catch(\Throwable $_g) {
				#coopy/Coopy.hx:528: characters 22-23
				$e = Exception::caught($_g)->unwrap();
				#coopy/Coopy.hx:529: characters 17-41
				if ($ext === "json") {
					#coopy/Coopy.hx:529: characters 36-41
					throw Exception::thrown($e);
				}
			}
		}
		#coopy/Coopy.hx:532: characters 9-34
		$this->format_preference = "csv";
		#coopy/Coopy.hx:533: characters 9-51
		$csv = new Csv($this->delim_preference);
		#coopy/Coopy.hx:534: characters 9-43
		$output = new SimpleTable(0, 0);
		#coopy/Coopy.hx:535: characters 9-35
		$csv->parseTable($txt, $output);
		#coopy/Coopy.hx:536: lines 536-538
		if ($this->csv_eol_preference === null) {
			#coopy/Coopy.hx:537: characters 13-56
			$this->csv_eol_preference = $csv->getDiscoveredEol();
		}
		#coopy/Coopy.hx:539: characters 9-45
		if ($output !== null) {
			#coopy/Coopy.hx:539: characters 27-45
			$output->trimBlank();
		}
		#coopy/Coopy.hx:540: characters 9-22
		return $output;
	}

	/**
	 * @param string $name
	 * @param Table $t
	 * 
	 * @return bool
	 */
	public function renderTable ($name, $t) {
		#coopy/Coopy.hx:306: characters 9-51
		$renderer = $this->getRenderer();
		#coopy/Coopy.hx:307: characters 9-27
		$renderer->render($t);
		#coopy/Coopy.hx:308: characters 9-45
		return $this->applyRenderer($name, $renderer);
	}

	/**
	 * @param string $name
	 * @param Tables $t
	 * 
	 * @return bool
	 */
	public function renderTables ($name, $t) {
		#coopy/Coopy.hx:312: characters 9-51
		$renderer = $this->getRenderer();
		#coopy/Coopy.hx:313: characters 9-33
		$renderer->renderTables($t);
		#coopy/Coopy.hx:314: characters 9-45
		return $this->applyRenderer($name, $renderer);
	}

	/**
	 *
	 * This implements the daff command-line utility.
	 *
	 * @param args the list of command-line arguments
	 * @param io should be an implementation of all the system services daff needs,
	 * if null one will be created
	 * @return 0 on success, non-zero on error.
	 *
	 * 
	 * @param string[]|\Array_hx $args
	 * @param TableIO $io
	 * 
	 * @return int
	 */
	public function run ($args, $io = null) {
		#coopy/Coopy.hx:720: lines 720-722
		if ($io === null) {
			#coopy/Coopy.hx:721: characters 13-31
			$io = new TableIO();
		}
		#coopy/Coopy.hx:724: lines 724-727
		if ($io === null) {
			#coopy/Coopy.hx:725: characters 13-18
			(Log::$trace)("No system interface available", new _HxAnon_Coopy0("coopy/Coopy.hx", 725, "coopy.Coopy", "run"));
			#coopy/Coopy.hx:726: characters 13-21
			return 1;
		}
		#coopy/Coopy.hx:729: characters 9-15
		$this->init();
		#coopy/Coopy.hx:730: characters 9-21
		$this->io = $io;
		#coopy/Coopy.hx:732: characters 9-32
		$more = true;
		#coopy/Coopy.hx:733: characters 9-36
		$output = null;
		#coopy/Coopy.hx:734: characters 9-36
		$inplace = false;
		#coopy/Coopy.hx:735: characters 9-32
		$git = false;
		#coopy/Coopy.hx:736: characters 9-33
		$help = false;
		#coopy/Coopy.hx:738: characters 9-35
		$this->flags = new CompareFlags();
		#coopy/Coopy.hx:739: characters 9-40
		$this->flags->always_show_header = true;
		#coopy/Coopy.hx:742: lines 742-936
		while ($more) {
			#coopy/Coopy.hx:743: characters 13-25
			$more = false;
			#coopy/Coopy.hx:744: characters 23-27
			$_g = 0;
			#coopy/Coopy.hx:744: characters 27-38
			$_g1 = $args->length;
			#coopy/Coopy.hx:744: lines 744-935
			while ($_g < $_g1) {
				#coopy/Coopy.hx:744: characters 23-38
				$i = $_g++;
				#coopy/Coopy.hx:745: characters 17-44
				$tag = ($args->arr[$i] ?? null);
				#coopy/Coopy.hx:746: lines 746-934
				if ($tag === "--output") {
					#coopy/Coopy.hx:747: characters 21-32
					$more = true;
					#coopy/Coopy.hx:748: characters 21-39
					$output = ($args->arr[$i + 1] ?? null);
					#coopy/Coopy.hx:749: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:750: characters 21-26
					break;
				} else if ($tag === "--css") {
					#coopy/Coopy.hx:752: characters 21-32
					$more = true;
					#coopy/Coopy.hx:753: characters 21-36
					$this->fragment = true;
					#coopy/Coopy.hx:754: characters 21-43
					$this->css_output = ($args->arr[$i + 1] ?? null);
					#coopy/Coopy.hx:755: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:756: characters 21-26
					break;
				} else if ($tag === "--fragment") {
					#coopy/Coopy.hx:758: characters 21-32
					$more = true;
					#coopy/Coopy.hx:759: characters 21-36
					$this->fragment = true;
					#coopy/Coopy.hx:760: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:761: characters 21-26
					break;
				} else if ($tag === "--plain") {
					#coopy/Coopy.hx:763: characters 21-32
					$more = true;
					#coopy/Coopy.hx:764: characters 21-45
					$this->flags->use_glyphs = false;
					#coopy/Coopy.hx:765: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:766: characters 21-26
					break;
				} else if ($tag === "--unquote") {
					#coopy/Coopy.hx:768: characters 21-32
					$more = true;
					#coopy/Coopy.hx:769: characters 21-45
					$this->flags->quote_html = false;
					#coopy/Coopy.hx:770: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:771: characters 21-26
					break;
				} else if ($tag === "--all") {
					#coopy/Coopy.hx:773: characters 21-32
					$more = true;
					#coopy/Coopy.hx:774: characters 21-48
					$this->flags->show_unchanged = true;
					#coopy/Coopy.hx:775: characters 21-56
					$this->flags->show_unchanged_columns = true;
					#coopy/Coopy.hx:776: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:777: characters 21-26
					break;
				} else if ($tag === "--all-rows") {
					#coopy/Coopy.hx:779: characters 21-32
					$more = true;
					#coopy/Coopy.hx:780: characters 21-48
					$this->flags->show_unchanged = true;
					#coopy/Coopy.hx:781: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:782: characters 21-26
					break;
				} else if ($tag === "--all-columns") {
					#coopy/Coopy.hx:784: characters 21-32
					$more = true;
					#coopy/Coopy.hx:785: characters 21-56
					$this->flags->show_unchanged_columns = true;
					#coopy/Coopy.hx:786: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:787: characters 21-26
					break;
				} else if ($tag === "--act") {
					#coopy/Coopy.hx:789: characters 21-32
					$more = true;
					#coopy/Coopy.hx:790: lines 790-792
					if ($this->flags->acts === null) {
						#coopy/Coopy.hx:791: characters 25-61
						$this->flags->acts = new StringMap();
					}
					#coopy/Coopy.hx:793: characters 21-49
					$this->flags->acts->data[$args[$i + 1]] = true;
					#coopy/Coopy.hx:794: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:795: characters 21-26
					break;
				} else if ($tag === "--context") {
					#coopy/Coopy.hx:797: characters 21-32
					$more = true;
					#coopy/Coopy.hx:798: characters 21-65
					$context = \Std::parseInt(($args->arr[$i + 1] ?? null));
					#coopy/Coopy.hx:799: characters 21-70
					if ($context >= 0) {
						#coopy/Coopy.hx:799: characters 37-70
						$this->flags->unchanged_context = $context;
					}
					#coopy/Coopy.hx:800: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:801: characters 21-26
					break;
				} else if ($tag === "--context-columns") {
					#coopy/Coopy.hx:803: characters 21-32
					$more = true;
					#coopy/Coopy.hx:804: characters 21-65
					$context1 = \Std::parseInt(($args->arr[$i + 1] ?? null));
					#coopy/Coopy.hx:805: characters 21-77
					if ($context1 >= 0) {
						#coopy/Coopy.hx:805: characters 37-77
						$this->flags->unchanged_column_context = $context1;
					}
					#coopy/Coopy.hx:806: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:807: characters 21-26
					break;
				} else if ($tag === "--inplace") {
					#coopy/Coopy.hx:809: characters 21-32
					$more = true;
					#coopy/Coopy.hx:810: characters 21-35
					$inplace = true;
					#coopy/Coopy.hx:811: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:812: characters 21-26
					break;
				} else if ($tag === "--git") {
					#coopy/Coopy.hx:814: characters 21-32
					$more = true;
					#coopy/Coopy.hx:815: characters 21-31
					$git = true;
					#coopy/Coopy.hx:816: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:817: characters 21-26
					break;
				} else if ($tag === "--unordered") {
					#coopy/Coopy.hx:819: characters 21-32
					$more = true;
					#coopy/Coopy.hx:820: characters 21-42
					$this->flags->ordered = false;
					#coopy/Coopy.hx:821: characters 21-48
					$this->flags->unchanged_context = 0;
					#coopy/Coopy.hx:822: characters 21-37
					$this->order_set = true;
					#coopy/Coopy.hx:823: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:824: characters 21-26
					break;
				} else if ($tag === "--ordered") {
					#coopy/Coopy.hx:826: characters 21-32
					$more = true;
					#coopy/Coopy.hx:827: characters 21-41
					$this->flags->ordered = true;
					#coopy/Coopy.hx:828: characters 21-37
					$this->order_set = true;
					#coopy/Coopy.hx:829: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:830: characters 21-26
					break;
				} else if ($tag === "--color") {
					#coopy/Coopy.hx:832: characters 21-32
					$more = true;
					#coopy/Coopy.hx:833: characters 21-51
					$this->flags->terminal_format = "ansi";
					#coopy/Coopy.hx:834: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:835: characters 21-26
					break;
				} else if ($tag === "--no-color") {
					#coopy/Coopy.hx:837: characters 21-32
					$more = true;
					#coopy/Coopy.hx:838: characters 21-52
					$this->flags->terminal_format = "plain";
					#coopy/Coopy.hx:839: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:840: characters 21-26
					break;
				} else if ($tag === "--input-format") {
					#coopy/Coopy.hx:842: characters 21-32
					$more = true;
					#coopy/Coopy.hx:843: characters 21-41
					$this->setFormat(($args->arr[$i + 1] ?? null));
					#coopy/Coopy.hx:844: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:845: characters 21-26
					break;
				} else if ($tag === "--output-format") {
					#coopy/Coopy.hx:847: characters 21-32
					$more = true;
					#coopy/Coopy.hx:848: characters 21-46
					$this->output_format = ($args->arr[$i + 1] ?? null);
					#coopy/Coopy.hx:849: characters 21-45
					$this->output_format_set = true;
					#coopy/Coopy.hx:850: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:851: characters 21-26
					break;
				} else if ($tag === "--id") {
					#coopy/Coopy.hx:853: characters 21-32
					$more = true;
					#coopy/Coopy.hx:854: lines 854-856
					if ($this->flags->ids === null) {
						#coopy/Coopy.hx:855: characters 25-56
						$this->flags->ids = new \Array_hx();
					}
					#coopy/Coopy.hx:857: characters 21-46
					$_this = $this->flags->ids;
					#coopy/Coopy.hx:857: characters 36-45
					$args1 = ($args->arr[$i + 1] ?? null);
					#coopy/Coopy.hx:857: characters 21-46
					$_this->arr[$_this->length++] = $args1;
					#coopy/Coopy.hx:858: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:859: characters 21-26
					break;
				} else if ($tag === "--ignore") {
					#coopy/Coopy.hx:861: characters 21-32
					$more = true;
					#coopy/Coopy.hx:862: characters 21-50
					$this->flags->ignoreColumn(($args->arr[$i + 1] ?? null));
					#coopy/Coopy.hx:863: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:864: characters 21-26
					break;
				} else if ($tag === "--index") {
					#coopy/Coopy.hx:866: characters 21-32
					$more = true;
					#coopy/Coopy.hx:867: characters 21-51
					$this->flags->always_show_order = true;
					#coopy/Coopy.hx:868: characters 21-51
					$this->flags->never_show_order = false;
					#coopy/Coopy.hx:869: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:870: characters 21-26
					break;
				} else if ($tag === "--www") {
					#coopy/Coopy.hx:872: characters 21-32
					$more = true;
					#coopy/Coopy.hx:873: characters 21-42
					$this->output_format = "www";
					#coopy/Coopy.hx:874: characters 21-45
					$this->output_format_set = true;
					#coopy/Coopy.hx:875: characters 21-37
					$args->splice($i, 1);
				} else if ($tag === "--table") {
					#coopy/Coopy.hx:877: characters 21-32
					$more = true;
					#coopy/Coopy.hx:878: characters 21-46
					$this->flags->addTable(($args->arr[$i + 1] ?? null));
					#coopy/Coopy.hx:879: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:880: characters 21-26
					break;
				} else if (($tag === "-w") || ($tag === "--ignore-whitespace")) {
					#coopy/Coopy.hx:882: characters 21-32
					$more = true;
					#coopy/Coopy.hx:883: characters 21-51
					$this->flags->ignore_whitespace = true;
					#coopy/Coopy.hx:884: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:885: characters 21-26
					break;
				} else if (($tag === "-i") || ($tag === "--ignore-case")) {
					#coopy/Coopy.hx:887: characters 21-32
					$more = true;
					#coopy/Coopy.hx:888: characters 21-45
					$this->flags->ignore_case = true;
					#coopy/Coopy.hx:889: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:890: characters 21-26
					break;
				} else if (($tag === "-d") || ($tag === "--ignore-epsilon")) {
					#coopy/Coopy.hx:892: characters 21-32
					$more = true;
					#coopy/Coopy.hx:893: characters 21-41
					$eps = ($args->arr[$i + 1] ?? null);
					#coopy/Coopy.hx:894: characters 21-63
					$this->flags->ignore_epsilon = \Std::parseFloat($eps);
					#coopy/Coopy.hx:895: lines 895-898
					if (\is_nan($this->flags->ignore_epsilon)) {
						#coopy/Coopy.hx:896: characters 25-91
						$io->writeStderr("Epsilon for numeric comparison must be numeric\x0A");
						#coopy/Coopy.hx:897: characters 25-33
						return 1;
					}
					#coopy/Coopy.hx:899: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:900: characters 21-26
					break;
				} else if ($tag === "--padding") {
					#coopy/Coopy.hx:902: characters 21-32
					$more = true;
					#coopy/Coopy.hx:903: characters 21-55
					$this->flags->padding_strategy = ($args->arr[$i + 1] ?? null);
					#coopy/Coopy.hx:904: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:905: characters 21-26
					break;
				} else if (($tag === "-e") || ($tag === "--eol")) {
					#coopy/Coopy.hx:907: characters 21-32
					$more = true;
					#coopy/Coopy.hx:908: characters 21-44
					$ending = ($args->arr[$i + 1] ?? null);
					#coopy/Coopy.hx:909: lines 909-920
					if ($ending === "crlf") {
						#coopy/Coopy.hx:910: characters 25-40
						$ending = "\x0D\x0A";
					} else if ($ending === "lf") {
						#coopy/Coopy.hx:912: characters 25-38
						$ending = "\x0A";
					} else if ($ending === "cr") {
						#coopy/Coopy.hx:914: characters 25-38
						$ending = "\x0D";
					} else if ($ending === "auto") {
						#coopy/Coopy.hx:916: characters 25-38
						$ending = null;
					} else {
						#coopy/Coopy.hx:918: characters 25-113
						$io->writeStderr("Expected line ending of either 'crlf' or 'lf' but got " . ($ending??'null') . "\x0A");
						#coopy/Coopy.hx:919: characters 25-33
						return 1;
					}
					#coopy/Coopy.hx:921: characters 21-48
					$this->csv_eol_preference = $ending;
					#coopy/Coopy.hx:922: characters 21-37
					$args->splice($i, 2);
					#coopy/Coopy.hx:923: characters 21-26
					break;
				} else if ($tag === "--fail-if-diff") {
					#coopy/Coopy.hx:925: characters 21-32
					$more = true;
					#coopy/Coopy.hx:926: characters 21-40
					$this->fail_if_diff = true;
					#coopy/Coopy.hx:927: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:928: characters 21-26
					break;
				} else if (($tag === "help") || ($tag === "-h") || ($tag === "--help")) {
					#coopy/Coopy.hx:930: characters 21-32
					$more = true;
					#coopy/Coopy.hx:931: characters 21-37
					$args->splice($i, 1);
					#coopy/Coopy.hx:932: characters 21-32
					$help = true;
					#coopy/Coopy.hx:933: characters 21-26
					break;
				}
			}
		}
		#coopy/Coopy.hx:938: characters 9-36
		$cmd = ($args->arr[0] ?? null);
		#coopy/Coopy.hx:939: characters 9-30
		$ok = true;
		#coopy/Coopy.hx:940: lines 940-943
		if ($help) {
			#coopy/Coopy.hx:941: characters 13-21
			$cmd = "";
			#coopy/Coopy.hx:942: characters 13-22
			$args = new \Array_hx();
		}
		#coopy/Coopy.hx:944: lines 944-1146
		try {
			#coopy/Coopy.hx:945: lines 945-1028
			if ($args->length < 2) {
				#coopy/Coopy.hx:946: lines 946-949
				if ($cmd === "version") {
					#coopy/Coopy.hx:947: characters 21-51
					$io->writeStdout((Coopy::$VERSION??'null') . "\x0A");
					#coopy/Coopy.hx:948: characters 21-29
					return 0;
				}
				#coopy/Coopy.hx:950: lines 950-974
				if ($cmd === "git") {
					#coopy/Coopy.hx:951: characters 21-230
					$io->writeStdout("You can use daff to improve git's handling of csv files, by using it as a\x0Adiff driver (for showing what has changed) and as a merge driver (for merging\x0Achanges between multiple versions).\x0A");
					#coopy/Coopy.hx:952: characters 21-41
					$io->writeStdout("\x0A");
					#coopy/Coopy.hx:953: characters 21-56
					$io->writeStdout("Automatic setup\x0A");
					#coopy/Coopy.hx:954: characters 21-58
					$io->writeStdout("---------------\x0A\x0A");
					#coopy/Coopy.hx:955: characters 21-45
					$io->writeStdout("Run:\x0A");
					#coopy/Coopy.hx:956: characters 21-55
					$io->writeStdout("  daff git csv\x0A");
					#coopy/Coopy.hx:957: characters 21-41
					$io->writeStdout("\x0A");
					#coopy/Coopy.hx:958: characters 21-53
					$io->writeStdout("Manual setup\x0A");
					#coopy/Coopy.hx:959: characters 21-55
					$io->writeStdout("------------\x0A\x0A");
					#coopy/Coopy.hx:960: characters 21-141
					$io->writeStdout("Create and add a file called .gitattributes in the root directory of your\x0Arepository, containing:\x0A\x0A");
					#coopy/Coopy.hx:961: characters 21-62
					$io->writeStdout("  *.csv diff=daff-csv\x0A");
					#coopy/Coopy.hx:962: characters 21-63
					$io->writeStdout("  *.csv merge=daff-csv\x0A");
					#coopy/Coopy.hx:963: characters 21-173
					$io->writeStdout("\x0ACreate a file called .gitconfig in your home directory (or alternatively\x0Aopen .git/config for a particular repository) and add:\x0A\x0A");
					#coopy/Coopy.hx:964: characters 21-62
					$io->writeStdout("  [diff \"daff-csv\"]\x0A");
					#coopy/Coopy.hx:965: characters 21-68
					$io->writeStdout("  command = daff diff --git\x0A");
					#coopy/Coopy.hx:966: characters 21-41
					$io->writeStderr("\x0A");
					#coopy/Coopy.hx:967: characters 21-63
					$io->writeStdout("  [merge \"daff-csv\"]\x0A");
					#coopy/Coopy.hx:968: characters 21-68
					$io->writeStdout("  name = daff tabular merge\x0A");
					#coopy/Coopy.hx:969: characters 21-85
					$io->writeStdout("  driver = daff merge --output %A %O %A %B\x0A\x0A");
					#coopy/Coopy.hx:971: characters 21-266
					$io->writeStderr("Make sure you can run daff from the command-line as just \"daff\" - if not,\x0Areplace \"daff\" in the driver and command lines above with the correct way\x0Ato call it. Add --no-color if your terminal does not support ANSI colors.");
					#coopy/Coopy.hx:972: characters 21-41
					$io->writeStderr("\x0A");
					#coopy/Coopy.hx:973: characters 21-29
					return 0;
				}
				#coopy/Coopy.hx:975: lines 975-1027
				if ($args->length < 1) {
					#coopy/Coopy.hx:976: characters 21-82
					$io->writeStderr("daff can produce and apply tabular diffs.\x0A");
					#coopy/Coopy.hx:977: characters 21-49
					$io->writeStderr("Call as:\x0A");
					#coopy/Coopy.hx:978: characters 21-59
					$io->writeStderr("  daff a.csv b.csv\x0A");
					#coopy/Coopy.hx:979: characters 21-104
					$io->writeStderr("  daff [--color] [--no-color] [--output OUTPUT.csv] a.csv b.csv\x0A");
					#coopy/Coopy.hx:980: characters 21-82
					$io->writeStderr("  daff [--output OUTPUT.html] a.csv b.csv\x0A");
					#coopy/Coopy.hx:981: characters 21-67
					$io->writeStderr("  daff [--www] a.csv b.csv\x0A");
					#coopy/Coopy.hx:982: characters 21-70
					$io->writeStderr("  daff parent.csv a.csv b.csv\x0A");
					#coopy/Coopy.hx:983: characters 21-79
					$io->writeStderr("  daff --input-format sqlite a.db b.db\x0A");
					#coopy/Coopy.hx:984: characters 21-81
					$io->writeStderr("  daff patch [--inplace] a.csv patch.csv\x0A");
					#coopy/Coopy.hx:985: characters 21-88
					$io->writeStderr("  daff merge [--inplace] parent.csv a.csv b.csv\x0A");
					#coopy/Coopy.hx:986: characters 21-85
					$io->writeStderr("  daff trim [--output OUTPUT.csv] source.csv\x0A");
					#coopy/Coopy.hx:987: characters 21-86
					$io->writeStderr("  daff render [--output OUTPUT.html] diff.csv\x0A");
					#coopy/Coopy.hx:988: characters 21-67
					$io->writeStderr("  daff copy in.csv out.tsv\x0A");
					#coopy/Coopy.hx:989: characters 21-54
					$io->writeStderr("  daff in.csv\x0A");
					#coopy/Coopy.hx:990: characters 21-51
					$io->writeStderr("  daff git\x0A");
					#coopy/Coopy.hx:991: characters 21-55
					$io->writeStderr("  daff version\x0A");
					#coopy/Coopy.hx:992: characters 21-41
					$io->writeStderr("\x0A");
					#coopy/Coopy.hx:993: characters 21-118
					$io->writeStderr("The --inplace option to patch and merge will result in modification of a.csv.\x0A");
					#coopy/Coopy.hx:994: characters 21-41
					$io->writeStderr("\x0A");
					#coopy/Coopy.hx:995: characters 21-98
					$io->writeStderr("If you need more control, here is the full list of flags:\x0A");
					#coopy/Coopy.hx:996: characters 21-122
					$io->writeStderr("  daff diff [--output OUTPUT.csv] [--context NUM] [--all] [--act ACT] a.csv b.csv\x0A");
					#coopy/Coopy.hx:997: characters 21-128
					$io->writeStderr("     --act ACT:     show only a certain kind of change (update, insert, delete, column)\x0A");
					#coopy/Coopy.hx:998: characters 21-99
					$io->writeStderr("     --all:         do not prune unchanged rows or columns\x0A");
					#coopy/Coopy.hx:999: characters 21-88
					$io->writeStderr("     --all-rows:    do not prune unchanged rows\x0A");
					#coopy/Coopy.hx:1000: characters 21-91
					$io->writeStderr("     --all-columns: do not prune unchanged columns\x0A");
					#coopy/Coopy.hx:1001: characters 21-122
					$io->writeStderr("     --color:       highlight changes with terminal colors (default in terminals)\x0A");
					#coopy/Coopy.hx:1002: characters 21-94
					$io->writeStderr("     --context NUM: show NUM rows of context (0=none)\x0A");
					#coopy/Coopy.hx:1003: characters 21-105
					$io->writeStderr("     --context-columns NUM: show NUM columns of context (0=none)\x0A");
					#coopy/Coopy.hx:1004: characters 21-119
					$io->writeStderr("     --fail-if-diff: return status is 0 if equal, 1 if different, 2 if problem\x0A");
					#coopy/Coopy.hx:1005: characters 21-132
					$io->writeStderr("     --id:          specify column name to use as primary key (repeat for multi-column key)\x0A");
					#coopy/Coopy.hx:1006: characters 21-114
					$io->writeStderr("     --ignore:      specify column name to ignore completely (can repeat)\x0A");
					#coopy/Coopy.hx:1007: characters 21-109
					$io->writeStderr("     --index:       include row/columns numbers from original tables\x0A");
					#coopy/Coopy.hx:1008: characters 21-122
					$io->writeStderr("     --input-format [csv|tsv|ssv|psv|json|sqlite]: set format to expect for input\x0A");
					#coopy/Coopy.hx:1009: characters 21-108
					$io->writeStderr("     --eol [crlf|lf|cr|auto]: separator between rows of csv output.\x0A");
					#coopy/Coopy.hx:1010: characters 21-99
					$io->writeStderr("     --no-color:    make sure terminal colors are not used\x0A");
					#coopy/Coopy.hx:1011: characters 21-109
					$io->writeStderr("     --ordered:     assume row order is meaningful (default for CSV)\x0A");
					#coopy/Coopy.hx:1012: characters 21-117
					$io->writeStderr("     --output-format [csv|tsv|ssv|psv|json|copy|html]: set format for output\x0A");
					#coopy/Coopy.hx:1013: characters 21-117
					$io->writeStderr("     --padding [dense|sparse|smart]: set padding method for aligning columns\x0A");
					#coopy/Coopy.hx:1014: characters 21-137
					$io->writeStderr("     --table NAME:  compare the named table, used with SQL sources. If name changes, use 'n1:n2'\x0A");
					#coopy/Coopy.hx:1015: characters 21-119
					$io->writeStderr("     --unordered:   assume row order is meaningless (default for json formats)\x0A");
					#coopy/Coopy.hx:1016: characters 21-117
					$io->writeStderr("     -w / --ignore-whitespace: ignore changes in leading/trailing whitespace\x0A");
					#coopy/Coopy.hx:1017: characters 21-92
					$io->writeStderr("     -i / --ignore-case: ignore differences in case\x0A");
					#coopy/Coopy.hx:1018: characters 21-116
					$io->writeStderr("     -d EPS / --ignore-epsilon EPS: ignore small floating point differences\x0A");
					#coopy/Coopy.hx:1019: characters 21-41
					$io->writeStderr("\x0A");
					#coopy/Coopy.hx:1020: characters 21-125
					$io->writeStderr("  daff render [--output OUTPUT.html] [--css CSS.css] [--fragment] [--plain] diff.csv\x0A");
					#coopy/Coopy.hx:1021: characters 21-109
					$io->writeStderr("     --css CSS.css: generate a suitable css file to go with the html\x0A");
					#coopy/Coopy.hx:1022: characters 21-109
					$io->writeStderr("     --fragment:    generate just a html fragment rather than a page\x0A");
					#coopy/Coopy.hx:1023: characters 21-117
					$io->writeStderr("     --plain:       do not use fancy utf8 characters to make arrows prettier\x0A");
					#coopy/Coopy.hx:1024: characters 21-103
					$io->writeStderr("     --unquote:     do not quote html characters in html diffs\x0A");
					#coopy/Coopy.hx:1025: characters 21-85
					$io->writeStderr("     --www:         send output to a browser\x0A");
					#coopy/Coopy.hx:1026: characters 21-29
					return 1;
				}
			}
			#coopy/Coopy.hx:1029: characters 13-40
			$cmd = ($args->arr[0] ?? null);
			#coopy/Coopy.hx:1030: characters 13-34
			$offset = 1;
			#coopy/Coopy.hx:1033: lines 1033-1046
			if (!\Lambda::has(\Array_hx::wrap([
				"diff",
				"patch",
				"merge",
				"trim",
				"render",
				"git",
				"version",
				"copy",
			]), $cmd)) {
				#coopy/Coopy.hx:1034: lines 1034-1045
				if (HxString::indexOf($cmd, "--") === 0) {
					#coopy/Coopy.hx:1035: characters 21-33
					$cmd = "diff";
					#coopy/Coopy.hx:1036: characters 21-31
					$offset = 0;
				} else if (HxString::indexOf($cmd, ".") !== -1) {
					#coopy/Coopy.hx:1038: lines 1038-1044
					if ($args->length === 2) {
						#coopy/Coopy.hx:1039: characters 25-37
						$cmd = "diff";
						#coopy/Coopy.hx:1040: characters 25-35
						$offset = 0;
					} else if ($args->length === 1) {
						#coopy/Coopy.hx:1042: characters 25-37
						$cmd = "copy";
						#coopy/Coopy.hx:1043: characters 25-35
						$offset = 0;
					}
				}
			}
			#coopy/Coopy.hx:1047: lines 1047-1050
			if ($cmd === "git") {
				#coopy/Coopy.hx:1048: characters 17-68
				$types = $args->splice($offset, $args->length - $offset);
				#coopy/Coopy.hx:1049: characters 17-50
				return $this->installGitDriver($io, $types);
			}
			#coopy/Coopy.hx:1051: lines 1051-1072
			if ($git) {
				#coopy/Coopy.hx:1052: characters 17-45
				$ct = $args->length - $offset;
				#coopy/Coopy.hx:1053: lines 1053-1056
				if (($ct !== 7) && ($ct !== 9)) {
					#coopy/Coopy.hx:1054: characters 21-96
					$io->writeStderr("Expected 7 or 9 parameters from git, but got " . ($ct??'null') . "\x0A");
					#coopy/Coopy.hx:1055: characters 21-29
					return 1;
				}
				#coopy/Coopy.hx:1057: characters 17-55
				$git_args = $args->splice($offset, $ct);
				#coopy/Coopy.hx:1058: characters 17-43
				$args->splice(0, $args->length);
				#coopy/Coopy.hx:1059: characters 17-27
				$offset = 0;
				#coopy/Coopy.hx:1060: characters 17-52
				$old_display_path = ($git_args->arr[0] ?? null);
				#coopy/Coopy.hx:1061: characters 17-52
				$new_display_path = ($git_args->arr[0] ?? null);
				#coopy/Coopy.hx:1062: characters 17-44
				$old_file = ($git_args->arr[1] ?? null);
				#coopy/Coopy.hx:1063: characters 17-44
				$new_file = ($git_args->arr[4] ?? null);
				#coopy/Coopy.hx:1064: lines 1064-1067
				if ($ct === 9) {
					#coopy/Coopy.hx:1065: characters 21-48
					$io->writeStdout(($git_args->arr[8] ?? null));
					#coopy/Coopy.hx:1066: characters 21-51
					$new_display_path = ($git_args->arr[7] ?? null);
				}
				#coopy/Coopy.hx:1068: characters 17-67
				$io->writeStdout("--- a/" . ($old_display_path??'null') . "\x0A");
				#coopy/Coopy.hx:1069: characters 17-67
				$io->writeStdout("+++ b/" . ($new_display_path??'null') . "\x0A");
				#coopy/Coopy.hx:1070: characters 17-36
				$args->arr[$args->length++] = $old_file;
				#coopy/Coopy.hx:1071: characters 17-36
				$args->arr[$args->length++] = $new_file;
			}
			#coopy/Coopy.hx:1073: characters 13-31
			$parent = null;
			#coopy/Coopy.hx:1074: lines 1074-1077
			if (($args->length - $offset) >= 3) {
				#coopy/Coopy.hx:1075: characters 17-59
				$parent = $this->loadTable(($args->arr[$offset] ?? null), "parent");
				#coopy/Coopy.hx:1076: characters 17-25
				++$offset;
			}
			#coopy/Coopy.hx:1078: characters 13-40
			$aname = ($args->arr[$offset] ?? null);
			#coopy/Coopy.hx:1079: characters 13-47
			$a = $this->loadTable($aname, "local");
			#coopy/Coopy.hx:1080: characters 13-26
			$b = null;
			#coopy/Coopy.hx:1081: lines 1081-1087
			if (($args->length - $offset) >= 2) {
				#coopy/Coopy.hx:1082: lines 1082-1086
				if ($cmd !== "copy") {
					#coopy/Coopy.hx:1083: characters 21-60
					$b = $this->loadTable(($args->arr[1 + $offset] ?? null), "remote");
				} else {
					#coopy/Coopy.hx:1085: characters 21-44
					$output = ($args->arr[1 + $offset] ?? null);
				}
			}
			#coopy/Coopy.hx:1088: characters 13-43
			$this->flags->diff_strategy = $this->strategy;
			#coopy/Coopy.hx:1090: lines 1090-1096
			if ($inplace) {
				#coopy/Coopy.hx:1091: lines 1091-1093
				if ($output !== null) {
					#coopy/Coopy.hx:1092: characters 21-95
					$io->writeStderr("Please do not use --inplace when specifying an output.\x0A");
				}
				#coopy/Coopy.hx:1094: characters 17-31
				$output = $aname;
				#coopy/Coopy.hx:1095: characters 17-25
				return 1;
			}
			#coopy/Coopy.hx:1098: lines 1098-1100
			if ($output === null) {
				#coopy/Coopy.hx:1099: characters 17-29
				$output = "-";
			}
			#coopy/Coopy.hx:1102: lines 1102-1140
			if ($cmd === "diff") {
				#coopy/Coopy.hx:1103: lines 1103-1106
				if (!$this->order_set) {
					#coopy/Coopy.hx:1104: characters 21-53
					$this->flags->ordered = $this->order_preference;
					#coopy/Coopy.hx:1105: characters 21-68
					if (!$this->flags->ordered) {
						#coopy/Coopy.hx:1105: characters 41-68
						$this->flags->unchanged_context = 0;
					}
				}
				#coopy/Coopy.hx:1107: characters 17-57
				$this->flags->allow_nested_cells = $this->nested_output;
				#coopy/Coopy.hx:1108: lines 1108-1119
				if ($this->fail_if_diff) {
					#coopy/Coopy.hx:1109: lines 1109-1113
					try {
						#coopy/Coopy.hx:1110: characters 25-57
						$this->runDiff($parent, $a, $b, $this->flags, $output);
					} catch(\Throwable $_g) {
						#coopy/Coopy.hx:1112: characters 25-33
						return 2;
					}
					#coopy/Coopy.hx:1114: lines 1114-1116
					if ($this->diffs_found) {
						#coopy/Coopy.hx:1115: characters 25-33
						return 1;
					}
				} else {
					#coopy/Coopy.hx:1118: characters 21-53
					$this->runDiff($parent, $a, $b, $this->flags, $output);
				}
			} else if ($cmd === "patch") {
				#coopy/Coopy.hx:1121: characters 17-72
				$patcher = new HighlightPatch($a, $b);
				#coopy/Coopy.hx:1122: characters 17-32
				$patcher->apply();
				#coopy/Coopy.hx:1123: characters 17-36
				$this->saveTable($output, $a);
			} else if ($cmd === "merge") {
				#coopy/Coopy.hx:1125: characters 17-68
				$merger = new Merger($parent, $a, $b, $this->flags);
				#coopy/Coopy.hx:1126: characters 17-48
				$conflicts = $merger->apply();
				#coopy/Coopy.hx:1127: characters 17-36
				$ok = $conflicts === 0;
				#coopy/Coopy.hx:1128: lines 1128-1130
				if ($conflicts > 0) {
					#coopy/Coopy.hx:1129: characters 21-92
					$io->writeStderr(($conflicts??'null') . " conflict" . ((($conflicts > 1 ? "s" : ""))??'null') . "\x0A");
				}
				#coopy/Coopy.hx:1131: characters 17-36
				$this->saveTable($output, $a);
			} else if ($cmd === "trim") {
				#coopy/Coopy.hx:1133: characters 17-36
				$this->saveTable($output, $a);
			} else if ($cmd === "render") {
				#coopy/Coopy.hx:1135: characters 17-38
				$this->renderTable($output, $a);
			} else if ($cmd === "copy") {
				#coopy/Coopy.hx:1137: characters 17-40
				$os = new Tables($a);
				#coopy/Coopy.hx:1138: characters 17-35
				$os->add("untitled");
				#coopy/Coopy.hx:1139: characters 17-67
				$this->saveTables($output, $os, $this->useColor($this->flags, $output), false);
			}
		} catch(\Throwable $_g) {
			#coopy/Coopy.hx:1141: characters 18-19
			$e = Exception::caught($_g)->unwrap();
			#coopy/Coopy.hx:1142: lines 1142-1144
			if (!$this->fail_if_diff) {
				#coopy/Coopy.hx:1143: characters 17-22
				throw Exception::thrown($e);
			}
			#coopy/Coopy.hx:1145: characters 13-21
			return 2;
		}
		#coopy/Coopy.hx:1147: characters 16-39
		if ($ok) {
			#coopy/Coopy.hx:1147: characters 19-20
			return 0;
		} else if ($this->fail_if_diff) {
			#coopy/Coopy.hx:1147: characters 35-36
			return 2;
		} else {
			#coopy/Coopy.hx:1147: characters 37-38
			return 1;
		}
	}

	/**
	 * @param Table $parent
	 * @param Table $a
	 * @param Table $b
	 * @param CompareFlags $flags
	 * @param string $output
	 * 
	 * @return void
	 */
	public function runDiff ($parent, $a, $b, $flags, $output) {
		#coopy/Coopy.hx:476: characters 9-66
		$ct = Coopy::compareTables3($parent, $a, $b, $flags);
		#coopy/Coopy.hx:477: characters 9-44
		$align = $ct->align();
		#coopy/Coopy.hx:478: characters 9-57
		$td = new TableDiff($align, $flags);
		#coopy/Coopy.hx:479: characters 9-38
		$o = new SimpleTable(0, 0);
		#coopy/Coopy.hx:480: characters 9-32
		$os = new Tables($o);
		#coopy/Coopy.hx:481: characters 9-33
		$td->hiliteWithNesting($os);
		#coopy/Coopy.hx:482: characters 9-48
		$use_color = $this->useColor($flags, $output);
		#coopy/Coopy.hx:483: characters 9-45
		$this->saveTables($output, $os, $use_color, true);
		#coopy/Coopy.hx:485: lines 485-490
		if ($this->fail_if_diff) {
			#coopy/Coopy.hx:486: characters 13-43
			$summary = $td->getSummary();
			#coopy/Coopy.hx:487: lines 487-489
			if ($summary->different) {
				#coopy/Coopy.hx:488: characters 17-35
				$this->diffs_found = true;
			}
		}
	}

	/**
	 * @param string $name
	 * @param Table $t
	 * @param TerminalDiffRender $render
	 * 
	 * @return bool
	 */
	public function saveTable ($name, $t, $render = null) {
		#coopy/Coopy.hx:318: characters 9-48
		$txt = $this->encodeTable($name, $t, $render);
		#coopy/Coopy.hx:319: characters 9-37
		if ($txt === null) {
			#coopy/Coopy.hx:319: characters 26-37
			return true;
		}
		#coopy/Coopy.hx:320: characters 9-34
		return $this->saveText($name, $txt);
	}

	/**
	 * @param string $name
	 * @param Tables $os
	 * @param bool $use_color
	 * @param bool $is_diff
	 * 
	 * @return bool
	 */
	public function saveTables ($name, $os, $use_color, $is_diff) {
		#coopy/Coopy.hx:355: lines 355-357
		if ($this->output_format !== "copy") {
			#coopy/Coopy.hx:356: characters 13-37
			$this->setFormat($this->output_format);
		}
		#coopy/Coopy.hx:358: characters 9-31
		$txt = "";
		#coopy/Coopy.hx:359: characters 9-26
		$this->checkFormat($name);
		#coopy/Coopy.hx:360: characters 9-48
		$render = null;
		#coopy/Coopy.hx:361: characters 9-89
		if ($use_color) {
			#coopy/Coopy.hx:361: characters 24-89
			$render = new TerminalDiffRender($this->flags, $this->delim_preference, $is_diff);
		}
		#coopy/Coopy.hx:363: characters 9-35
		$order = $os->getOrder();
		#coopy/Coopy.hx:364: lines 364-366
		if ($order->length === 1) {
			#coopy/Coopy.hx:365: characters 13-51
			return $this->saveTable($name, $os->one(), $render);
		}
		#coopy/Coopy.hx:367: lines 367-369
		if (($this->format_preference === "html") || ($this->format_preference === "www")) {
			#coopy/Coopy.hx:368: characters 13-42
			return $this->renderTables($name, $os);
		}
		#coopy/Coopy.hx:370: characters 9-32
		$need_blank = false;
		#coopy/Coopy.hx:371: lines 371-374
		if (($order->length === 0) || $os->hasInsDel()) {
			#coopy/Coopy.hx:372: characters 13-55
			$txt = ($txt??'null') . ($this->encodeTable($name, $os->one(), $render)??'null');
			#coopy/Coopy.hx:373: characters 13-30
			$need_blank = true;
		}
		#coopy/Coopy.hx:375: lines 375-392
		if ($order->length > 1) {
			#coopy/Coopy.hx:376: characters 23-27
			$_g = 1;
			#coopy/Coopy.hx:376: characters 27-39
			$_g1 = $order->length;
			#coopy/Coopy.hx:376: lines 376-391
			while ($_g < $_g1) {
				#coopy/Coopy.hx:376: characters 23-39
				$i = $_g++;
				#coopy/Coopy.hx:377: characters 17-42
				$t = $os->get(($order->arr[$i] ?? null));
				#coopy/Coopy.hx:378: lines 378-390
				if ($t !== null) {
					#coopy/Coopy.hx:379: lines 379-381
					if ($need_blank) {
						#coopy/Coopy.hx:380: characters 25-36
						$txt = ($txt??'null') . "\x0A";
					}
					#coopy/Coopy.hx:382: characters 21-38
					$need_blank = true;
					#coopy/Coopy.hx:383: characters 21-41
					$txt = ($txt??'null') . (($order->arr[$i] ?? null)??'null') . "\x0A";
					#coopy/Coopy.hx:384: characters 21-35
					$line = "";
					#coopy/Coopy.hx:385: characters 31-35
					$_g2 = 0;
					#coopy/Coopy.hx:385: characters 35-50
					$_g3 = mb_strlen(($order->arr[$i] ?? null));
					#coopy/Coopy.hx:385: lines 385-387
					while ($_g2 < $_g3) {
						#coopy/Coopy.hx:385: characters 31-50
						$i1 = $_g2++;
						#coopy/Coopy.hx:386: characters 25-36
						$line = ($line??'null') . "=";
					}
					#coopy/Coopy.hx:388: characters 21-39
					$txt = ($txt??'null') . ($line??'null') . "\x0A";
					#coopy/Coopy.hx:389: characters 21-71
					$txt = ($txt??'null') . ($this->encodeTable($name, $os->get(($order->arr[$i] ?? null)), $render)??'null');
				}
			}
		}
		#coopy/Coopy.hx:393: characters 9-35
		return $this->saveText($name, $txt);
	}

	/**
	 * @param string $name
	 * @param string $txt
	 * 
	 * @return bool
	 */
	public function saveText ($name, $txt) {
		#coopy/Coopy.hx:397: lines 397-403
		if ($name === null) {
			#coopy/Coopy.hx:398: characters 13-29
			$this->cache_txt = ($this->cache_txt??'null') . ($txt??'null');
		} else if ($name !== "-") {
			#coopy/Coopy.hx:400: characters 13-37
			$this->io->saveContent($name, $txt);
		} else {
			#coopy/Coopy.hx:402: characters 13-32
			$this->io->writeStdout($txt);
		}
		#coopy/Coopy.hx:404: characters 9-20
		return true;
	}

	/**
	 * @param string $name
	 * 
	 * @return void
	 */
	public function setFormat ($name) {
		#coopy/Coopy.hx:278: characters 9-34
		$this->extern_preference = false;
		#coopy/Coopy.hx:279: characters 9-32
		$this->checkFormat("." . ($name??'null'));
		#coopy/Coopy.hx:280: characters 9-33
		$this->extern_preference = true;
	}

	/**
	 * @param CompareFlags $flags
	 * @param string $output
	 * 
	 * @return bool
	 */
	public function useColor ($flags, $output) {
		#coopy/Coopy.hx:462: characters 9-57
		$use_color = $flags->terminal_format === "ansi";
		#coopy/Coopy.hx:463: lines 463-470
		if ($flags->terminal_format === null) {
			#coopy/Coopy.hx:464: lines 464-469
			if ((($output === null) || ($output === "-")) && (($this->output_format === "copy") || ($this->output_format === "csv") || ($this->output_format === "psv"))) {
				#coopy/Coopy.hx:466: lines 466-468
				if ($this->io !== null) {
					#coopy/Coopy.hx:467: characters 21-64
					if ($this->io->isTtyKnown()) {
						#coopy/Coopy.hx:467: characters 42-64
						$use_color = $this->io->isTty();
					}
				}
			}
		}
		#coopy/Coopy.hx:471: characters 9-25
		return $use_color;
	}
}

class _HxAnon_Coopy0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

Boot::registerClass(Coopy::class, 'coopy.Coopy');
