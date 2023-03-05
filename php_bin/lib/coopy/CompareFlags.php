<?php
/**
 */

namespace coopy;

use \php\Boot;
use \php\_Boot\HxString;
use \haxe\ds\StringMap;

/**
 *
 * Flags that influence how tables are compared and how information
 * is presented.
 *
 */
class CompareFlags {
	/**
	 * @var StringMap
	 *
	 * Optional filters for what kind of changes we want to show.
	 * Please call `filter()`
	 * to choose your filters, this variable will be made private soon.
	 *
	 */
	public $acts;
	/**
	 * @var bool
	 *
	 * Should cells in diff output contain nested content?
	 * This is the difference between getting eg the string
	 * "version1->version2" and a hash {before: "version1", after: "version2"}.
	 * Defaults to false.
	 *
	 */
	public $allow_nested_cells;
	/**
	 * @var bool
	 *
	 * Should we always give a table header in diffs? This defaults
	 * to true, and - frankly - you should leave it at true for now.
	 *
	 */
	public $always_show_header;
	/**
	 * @var bool
	 *
	 * Diffs for tables where row/column order has been permuted may include
	 * an extra row/column specifying the changes in row numbers.
	 * If you'd like that extra row/column to always be included,
	 * turn on this flag, and turn off never_show_order.
	 *
	 */
	public $always_show_order;
	/**
	 * @var string[]|\Array_hx
	 *
	 * List of columns to ignore in all calculations.  Changes
	 * related to these columns should be discounted.  Please set
	 * via (multiple calls of) `ignoreColumn`.
	 *
	 */
	public $columns_to_ignore;
	/**
	 * @var bool
	 *
	 * Should column numbers, if present, be rendered spreadsheet-style
	 * as A,B,C,...,AA,BB,CC?
	 * Defaults to true.
	 *
	 */
	public $count_like_a_spreadsheet;
	/**
	 * @var string
	 *
	 * Strategy to use when making comparisons.  Valid values are "hash" and "sql".
	 * The latter is only useful for SQL sources.  Leave null for a sensible default.
	 *
	 */
	public $diff_strategy;
	/**
	 * @var string[]|\Array_hx
	 * List of columns that make up a primary key, if known.
	 * Otherwise heuristics are used to find a decent key
	 * (or a set of decent keys). Please set via (multiple
	 * calls of) `addPrimaryKey()`.  This variable will be made private
	 * soon.
	 *
	 */
	public $ids;
	/**
	 * @var bool
	 *
	 * Should case be omitted from comparisons.  Defaults to false.
	 *
	 */
	public $ignore_case;
	/**
	 * @var float
	 *
	 * If set to a positive number, then cells that looks like floating point
	 * numbers are treated as equal if they are within epsilon of each other.
	 * This option does NOT affect the alignment of rows, so if a floating point
	 * number is part of your table's primary key, this option will not help.
	 * Defaults to a negative number (so it is disabled).
	 *
	 */
	public $ignore_epsilon;
	/**
	 * @var bool
	 *
	 * Should whitespace be omitted from comparisons.  Defaults to false.
	 *
	 */
	public $ignore_whitespace;
	/**
	 * @var bool
	 *
	 * Diffs for tables where row/column order has been permuted may include
	 * an extra row/column specifying the changes in row numbers.
	 * If you'd like to be sure that that row/column is *never*
	 * included, turn on this flag, and turn off always_show_order.
	 *
	 */
	public $never_show_order;
	/**
	 * @var bool
	 *
	 * Is the order of rows and columns meaningful? Defaults to `true`.
	 *
	 */
	public $ordered;
	/**
	 * @var string
	 *
	 * Strategy to use when padding columns.  Valid values are "smart", "dense",
	 * and "sparse".  Leave null for a sensible default.
	 *
	 */
	public $padding_strategy;
	/**
	 * @var Table
	 *
	 * Set a common ancestor for use in comparison.  Defaults to null
	 * (no known common ancestor).
	 *
	 */
	public $parent;
	/**
	 * @var bool
	 * Choose whether html elements should be neutralized or passed through,
	 * in html contexts.
	 *
	 */
	public $quote_html;
	/**
	 * @var bool
	 *
	 * Show changes in column properties, not just data, if available.
	 * Defaults to true.
	 *
	 */
	public $show_meta;
	/**
	 * @var bool
	 *
	 * Should we show all rows in diffs?  We default to showing
	 * just rows that have changes (and some context rows around
	 * them, if row order is meaningful), but you can override
	 * this here.
	 *
	 */
	public $show_unchanged;
	/**
	 * @var bool
	 *
	 * Should we show all columns in diffs?  We default to showing
	 * just columns that have changes (and some context columns around
	 * them, if column order is meaningful), but you can override
	 * this here.  Irrespective of this flag, you can rely
	 * on index/key columns needed to identify rows to be included
	 * in the diff.
	 *
	 */
	public $show_unchanged_columns;
	/**
	 * @var bool
	 *
	 * Show all column properties, if available, even if unchanged.
	 * Defaults to false.
	 *
	 */
	public $show_unchanged_meta;
	/**
	 * @var string[]|\Array_hx
	 *
	 * List of tables to process.  Used when reading from a source
	 * with multiple tables.  Defaults to null, meaning all tables.
	 *
	 */
	public $tables;
	/**
	 * @var string
	 *
	 * Format to use for terminal output.  "plain" for plain text,
	 * "ansi", for ansi color codes, null to autodetect.  Defaults to
	 * autodetect.
	 *
	 */
	public $terminal_format;
	/**
	 * @var int
	 *
	 * When showing context columns around a changed column, what
	 * is the minimum number of such columns we should show?
	 *
	 */
	public $unchanged_column_context;
	/**
	 * @var int
	 *
	 * When showing context rows around a changed row, what
	 * is the minimum number of such rows we should show?
	 *
	 */
	public $unchanged_context;
	/**
	 * @var bool
	 *
	 * Choose whether we can use utf8 characters for describing diff
	 * (specifically long arrow).  Defaults to true.
	 *
	 */
	public $use_glyphs;
	/**
	 * @var string[]|\Array_hx
	 *
	 * List of warnings generated during a comparison.
	 *
	 */
	public $warnings;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/CompareFlags.hx:241: characters 9-23
		$this->ordered = true;
		#coopy/CompareFlags.hx:242: characters 9-31
		$this->show_unchanged = false;
		#coopy/CompareFlags.hx:243: characters 9-30
		$this->unchanged_context = 1;
		#coopy/CompareFlags.hx:244: characters 9-34
		$this->always_show_order = false;
		#coopy/CompareFlags.hx:245: characters 9-32
		$this->never_show_order = true;
		#coopy/CompareFlags.hx:246: characters 9-39
		$this->show_unchanged_columns = false;
		#coopy/CompareFlags.hx:247: characters 9-37
		$this->unchanged_column_context = 1;
		#coopy/CompareFlags.hx:248: characters 9-34
		$this->always_show_header = true;
		#coopy/CompareFlags.hx:249: characters 9-20
		$this->acts = null;
		#coopy/CompareFlags.hx:250: characters 9-19
		$this->ids = null;
		#coopy/CompareFlags.hx:251: characters 9-33
		$this->columns_to_ignore = null;
		#coopy/CompareFlags.hx:252: characters 9-35
		$this->allow_nested_cells = false;
		#coopy/CompareFlags.hx:253: characters 9-24
		$this->warnings = null;
		#coopy/CompareFlags.hx:254: characters 9-29
		$this->diff_strategy = null;
		#coopy/CompareFlags.hx:255: characters 9-25
		$this->show_meta = true;
		#coopy/CompareFlags.hx:256: characters 9-36
		$this->show_unchanged_meta = false;
		#coopy/CompareFlags.hx:257: characters 9-22
		$this->tables = null;
		#coopy/CompareFlags.hx:258: characters 9-22
		$this->parent = null;
		#coopy/CompareFlags.hx:259: characters 9-40
		$this->count_like_a_spreadsheet = true;
		#coopy/CompareFlags.hx:260: characters 9-34
		$this->ignore_whitespace = false;
		#coopy/CompareFlags.hx:261: characters 9-28
		$this->ignore_case = false;
		#coopy/CompareFlags.hx:262: characters 9-28
		$this->ignore_epsilon = -1;
		#coopy/CompareFlags.hx:263: characters 9-31
		$this->terminal_format = null;
		#coopy/CompareFlags.hx:264: characters 9-26
		$this->use_glyphs = true;
		#coopy/CompareFlags.hx:265: characters 9-26
		$this->quote_html = true;
	}

	/**
	 *
	 * Add a column to the primary key.  If this is never called,
	 * then we will muddle along without it.  Fine to call multiple
	 * times to set up a multi-column primary key.
	 *
	 * @param column a name of a column to add to the primary key
	 *
	 * 
	 * @param string $column
	 * 
	 * @return void
	 */
	public function addPrimaryKey ($column) {
		#coopy/CompareFlags.hx:354: characters 9-51
		if ($this->ids === null) {
			#coopy/CompareFlags.hx:354: characters 26-51
			$this->ids = new \Array_hx();
		}
		#coopy/CompareFlags.hx:355: characters 9-25
		$_this = $this->ids;
		$_this->arr[$_this->length++] = $column;
	}

	/**
	 * @param string $table
	 * 
	 * @return void
	 */
	public function addTable ($table) {
		#coopy/CompareFlags.hx:373: characters 9-55
		if ($this->tables === null) {
			#coopy/CompareFlags.hx:373: characters 27-55
			$this->tables = new \Array_hx();
		}
		#coopy/CompareFlags.hx:374: characters 9-27
		$_this = $this->tables;
		$_this->arr[$_this->length++] = $table;
	}

	/**
	 *
	 * Add a warning. Used by daff to pass non-critical information
	 * to the developer without disrupting operations.
	 *
	 * @param warn the warning text to record
	 *
	 * 
	 * @param string $warn
	 * 
	 * @return void
	 */
	public function addWarning ($warn) {
		#coopy/CompareFlags.hx:386: characters 9-59
		if ($this->warnings === null) {
			#coopy/CompareFlags.hx:386: characters 29-59
			$this->warnings = new \Array_hx();
		}
		#coopy/CompareFlags.hx:387: characters 9-28
		$_this = $this->warnings;
		$_this->arr[$_this->length++] = $warn;
	}

	/**
	 *
	 * @return true if column additions/deletions are allowed by the current filters.
	 *
	 * 
	 * @return bool
	 */
	public function allowColumn () {
		#coopy/CompareFlags.hx:326: characters 9-36
		if ($this->acts === null) {
			#coopy/CompareFlags.hx:326: characters 25-36
			return true;
		}
		#coopy/CompareFlags.hx:327: characters 16-59
		if (\array_key_exists("column", $this->acts->data)) {
			#coopy/CompareFlags.hx:327: characters 41-59
			return ($this->acts->data["column"] ?? null);
		} else {
			#coopy/CompareFlags.hx:327: characters 16-59
			return false;
		}
	}

	/**
	 *
	 * @return true if deletions are allowed by the current filters.
	 *
	 * 
	 * @return bool
	 */
	public function allowDelete () {
		#coopy/CompareFlags.hx:316: characters 9-36
		if ($this->acts === null) {
			#coopy/CompareFlags.hx:316: characters 25-36
			return true;
		}
		#coopy/CompareFlags.hx:317: characters 16-59
		if (\array_key_exists("delete", $this->acts->data)) {
			#coopy/CompareFlags.hx:317: characters 41-59
			return ($this->acts->data["delete"] ?? null);
		} else {
			#coopy/CompareFlags.hx:317: characters 16-59
			return false;
		}
	}

	/**
	 *
	 * @return true if inserts are allowed by the current filters.
	 *
	 * 
	 * @return bool
	 */
	public function allowInsert () {
		#coopy/CompareFlags.hx:306: characters 9-36
		if ($this->acts === null) {
			#coopy/CompareFlags.hx:306: characters 25-36
			return true;
		}
		#coopy/CompareFlags.hx:307: characters 16-59
		if (\array_key_exists("insert", $this->acts->data)) {
			#coopy/CompareFlags.hx:307: characters 41-59
			return ($this->acts->data["insert"] ?? null);
		} else {
			#coopy/CompareFlags.hx:307: characters 16-59
			return false;
		}
	}

	/**
	 *
	 * @return true if updates are allowed by the current filters.
	 *
	 * 
	 * @return bool
	 */
	public function allowUpdate () {
		#coopy/CompareFlags.hx:296: characters 9-36
		if ($this->acts === null) {
			#coopy/CompareFlags.hx:296: characters 25-36
			return true;
		}
		#coopy/CompareFlags.hx:297: characters 16-59
		if (\array_key_exists("update", $this->acts->data)) {
			#coopy/CompareFlags.hx:297: characters 41-59
			return ($this->acts->data["update"] ?? null);
		} else {
			#coopy/CompareFlags.hx:297: characters 16-59
			return false;
		}
	}

	/**
	 *
	 * Filter for particular kinds of changes.
	 * @param act set this to "update", "insert", "delete", or "column".
	 * @param allow set this to true to allow this kind, or false to
	 * deny it.
	 * @return true if the kind of change was recognized.
	 *
	 * 
	 * @param string $act
	 * @param bool $allow
	 * 
	 * @return bool
	 */
	public function filter ($act, $allow) {
		#coopy/CompareFlags.hx:278: lines 278-284
		if ($this->acts === null) {
			#coopy/CompareFlags.hx:279: characters 13-42
			$this->acts = new StringMap();
			#coopy/CompareFlags.hx:280: characters 13-38
			$this->acts->data["update"] = !$allow;
			#coopy/CompareFlags.hx:281: characters 13-38
			$this->acts->data["insert"] = !$allow;
			#coopy/CompareFlags.hx:282: characters 13-38
			$this->acts->data["delete"] = !$allow;
			#coopy/CompareFlags.hx:283: characters 13-38
			$this->acts->data["column"] = !$allow;
		}
		#coopy/CompareFlags.hx:285: characters 9-44
		if (!\array_key_exists($act, $this->acts->data)) {
			#coopy/CompareFlags.hx:285: characters 32-44
			return false;
		}
		#coopy/CompareFlags.hx:286: characters 9-28
		$this->acts->data[$act] = $allow;
		#coopy/CompareFlags.hx:287: characters 9-20
		return true;
	}

	/**
	 *
	 * If we need a single name for a table/column, we use the local name.
	 *
	 * 
	 * @param string $name
	 * 
	 * @return string
	 */
	public function getCanonicalName ($name) {
		#coopy/CompareFlags.hx:425: characters 9-44
		return $this->getNameByRole($name, "local");
	}

	/**
	 *
	 * Returns primary key for 'local', 'remote', and 'parent' sources.
	 *
	 * 
	 * @param string $role
	 * 
	 * @return string[]|\Array_hx
	 */
	public function getIdsByRole ($role) {
		#coopy/CompareFlags.hx:434: characters 9-42
		$result = new \Array_hx();
		#coopy/CompareFlags.hx:435: lines 435-437
		if ($this->ids === null) {
			#coopy/CompareFlags.hx:436: characters 13-26
			return $result;
		}
		#coopy/CompareFlags.hx:438: lines 438-440
		$_g = 0;
		$_g1 = $this->ids;
		while ($_g < $_g1->length) {
			#coopy/CompareFlags.hx:438: characters 14-18
			$name = ($_g1->arr[$_g] ?? null);
			#coopy/CompareFlags.hx:438: lines 438-440
			++$_g;
			#coopy/CompareFlags.hx:439: characters 13-51
			$x = $this->getNameByRole($name, $role);
			$result->arr[$result->length++] = $x;
		}
		#coopy/CompareFlags.hx:441: characters 9-22
		return $result;
	}

	/**
	 *
	 * @return the columns to ignore, as a map. For internal use.
	 *
	 * 
	 * @return StringMap
	 */
	public function getIgnoredColumns () {
		#coopy/CompareFlags.hx:336: characters 9-49
		if ($this->columns_to_ignore === null) {
			#coopy/CompareFlags.hx:336: characters 38-49
			return null;
		}
		#coopy/CompareFlags.hx:337: characters 9-45
		$ignore = new StringMap();
		#coopy/CompareFlags.hx:338: characters 19-23
		$_g = 0;
		#coopy/CompareFlags.hx:338: characters 23-47
		$_g1 = $this->columns_to_ignore->length;
		#coopy/CompareFlags.hx:338: lines 338-340
		while ($_g < $_g1) {
			#coopy/CompareFlags.hx:338: characters 19-47
			$i = $_g++;
			#coopy/CompareFlags.hx:339: characters 13-50
			$ignore->data[$this->columns_to_ignore[$i]] = true;
		}
		#coopy/CompareFlags.hx:341: characters 9-22
		return $ignore;
	}

	/**
	 *
	 * Primary key and table names may be specified as "local:remote" or "parent:local:remote"
	 * when they should be different for the local, remote, and parent sources.  This
	 * method returns the appropriate part of a name given a role of local, remote, or parent.
	 *
	 * 
	 * @param string $name
	 * @param string $role
	 * 
	 * @return string
	 */
	public function getNameByRole ($name, $role) {
		#coopy/CompareFlags.hx:407: characters 9-37
		$parts = HxString::split($name, ":");
		#coopy/CompareFlags.hx:408: characters 9-48
		if ($parts->length <= 1) {
			#coopy/CompareFlags.hx:408: characters 34-45
			return $name;
		}
		#coopy/CompareFlags.hx:409: lines 409-411
		if ($role === "parent") {
			#coopy/CompareFlags.hx:410: characters 13-28
			return ($parts->arr[0] ?? null);
		}
		#coopy/CompareFlags.hx:413: lines 413-415
		if ($role === "local") {
			#coopy/CompareFlags.hx:414: characters 13-43
			return ($parts->arr[$parts->length - 2] ?? null);
		}
		#coopy/CompareFlags.hx:416: characters 9-39
		return ($parts->arr[$parts->length - 1] ?? null);
	}

	/**
	 *
	 * @return any warnings generated during an operation.
	 *
	 * 
	 * @return string
	 */
	public function getWarning () {
		#coopy/CompareFlags.hx:396: characters 9-35
		return $this->warnings->join("\x0A");
	}

	/**
	 *
	 * Add a table to compare.  Fine to call multiple times,
	 * although multiple tables won't do anything sensible
	 * yet at the time of writing.
	 *
	 * @param table the name of a table to consider
	 *
	 * 
	 * @param string $column
	 * 
	 * @return void
	 */
	public function ignoreColumn ($column) {
		#coopy/CompareFlags.hx:368: characters 9-77
		if ($this->columns_to_ignore === null) {
			#coopy/CompareFlags.hx:368: characters 38-77
			$this->columns_to_ignore = new \Array_hx();
		}
		#coopy/CompareFlags.hx:369: characters 9-39
		$_this = $this->columns_to_ignore;
		$_this->arr[$_this->length++] = $column;
	}
}

Boot::registerClass(CompareFlags::class, 'coopy.CompareFlags');
