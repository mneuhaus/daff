<?php
/**
 */

namespace coopy;

use \php\Boot;
use \php\_Boot\HxString;
use \haxe\ds\StringMap;
use \haxe\format\JsonParser;
use \haxe\format\JsonPrinter;

/**
 *
 * Read and write NDJSON format. You don't need to use this to use daff!
 * Feel free to use your own.
 *
 */
class Ndjson {
	/**
	 * @var StringMap
	 */
	public $columns;
	/**
	 * @var int
	 */
	public $header_row;
	/**
	 * @var Table
	 */
	public $tab;
	/**
	 * @var View
	 */
	public $view;

	/**
	 *
	 * Constructor.
	 *
	 * @param tab a table to read or write.
	 *
	 * 
	 * @param Table $tab
	 * 
	 * @return void
	 */
	public function __construct ($tab) {
		#coopy/Ndjson.hx:28: characters 9-23
		$this->tab = $tab;
		#coopy/Ndjson.hx:29: characters 9-33
		$this->view = $tab->getCellView();
		#coopy/Ndjson.hx:30: characters 9-23
		$this->header_row = 0;
	}

	/**
	 *
	 * Insert column names in the specified row.
	 *
	 * @param r the header row number.  This would usually be zero.
	 *
	 * 
	 * @param int $r
	 * 
	 * @return void
	 */
	public function addHeaderRow ($r) {
		#coopy/Ndjson.hx:119: characters 21-35
		$data = \array_values(\array_map("strval", \array_keys($this->columns->data)));
		$n_current = 0;
		$n_length = \count($data);
		$n_data = $data;
		#coopy/Ndjson.hx:120: characters 19-24
		while ($n_current < $n_length) {
			#coopy/Ndjson.hx:120: lines 120-122
			$n = $n_data[$n_current++];
			#coopy/Ndjson.hx:121: characters 13-58
			$this->tab->setCell(($this->columns->data[$n] ?? null), $r, $this->view->toDatum($n));
		}
	}

	/**
	 *
	 * Parse a string expressing a single row of the table in NDJSON format,
	 * and insert it at the specified location.  The table is resized if
	 * necessary.  Row number zero should be reserved for a header, with actual
	 * data starting at row 1.
	 *
	 * @param r the target row number - the table will be resized if necessary.
	 * @param txt the row expressed as a string in NDJSON format.
	 *
	 * 
	 * @param int $r
	 * @param string $txt
	 * 
	 * @return void
	 */
	public function addRow ($r, $txt) {
		#coopy/Ndjson.hx:85: characters 9-41
		$json = (new JsonParser($txt))->doParse();
		#coopy/Ndjson.hx:86: characters 9-59
		if ($this->columns === null) {
			#coopy/Ndjson.hx:86: characters 28-59
			$this->columns = new StringMap();
		}
		#coopy/Ndjson.hx:87: characters 9-33
		$w = $this->tab->get_width();
		#coopy/Ndjson.hx:88: characters 9-34
		$h = $this->tab->get_height();
		#coopy/Ndjson.hx:89: characters 9-35
		$resize = false;
		#coopy/Ndjson.hx:90: lines 90-96
		$_g = 0;
		$_g1 = \Reflect::fields($json);
		while ($_g < $_g1->length) {
			#coopy/Ndjson.hx:90: characters 14-18
			$name = ($_g1->arr[$_g] ?? null);
			#coopy/Ndjson.hx:90: lines 90-96
			++$_g;
			#coopy/Ndjson.hx:91: lines 91-95
			if (!\array_key_exists($name, $this->columns->data)) {
				#coopy/Ndjson.hx:92: characters 17-36
				$this->columns->data[$name] = $w;
				#coopy/Ndjson.hx:93: characters 17-20
				++$w;
				#coopy/Ndjson.hx:94: characters 17-30
				$resize = true;
			}
		}
		#coopy/Ndjson.hx:97: lines 97-100
		if ($r >= $h) {
			#coopy/Ndjson.hx:98: characters 13-20
			$h = $r + 1;
			#coopy/Ndjson.hx:99: characters 13-26
			$resize = true;
		}
		#coopy/Ndjson.hx:101: lines 101-103
		if ($resize) {
			#coopy/Ndjson.hx:102: characters 13-28
			$this->tab->resize($w, $h);
		}
		#coopy/Ndjson.hx:104: lines 104-108
		$_g = 0;
		$_g1 = \Reflect::fields($json);
		while ($_g < $_g1->length) {
			#coopy/Ndjson.hx:104: characters 14-18
			$name = ($_g1->arr[$_g] ?? null);
			#coopy/Ndjson.hx:104: lines 104-108
			++$_g;
			#coopy/Ndjson.hx:105: characters 13-46
			$v = \Reflect::field($json, $name);
			#coopy/Ndjson.hx:106: characters 13-39
			$c = ($this->columns->data[$name] ?? null);
			#coopy/Ndjson.hx:107: characters 13-31
			$this->tab->setCell($c, $r, $v);
		}
	}

	/**
	 *
	 * Convert a string containing rows in NDJSON format into a table.
	 *
	 * @param txt the table expressed as a string in NDJSON format
	 *
	 * 
	 * @param string $txt
	 * 
	 * @return void
	 */
	public function parse ($txt) {
		#coopy/Ndjson.hx:133: characters 9-23
		$this->columns = null;
		#coopy/Ndjson.hx:134: characters 9-36
		$rows = HxString::split($txt, "\x0A");
		#coopy/Ndjson.hx:135: characters 9-29
		$h = $rows->length;
		#coopy/Ndjson.hx:136: lines 136-139
		if ($h === 0) {
			#coopy/Ndjson.hx:137: characters 13-24
			$this->tab->clear();
			#coopy/Ndjson.hx:138: characters 13-19
			return;
		}
		#coopy/Ndjson.hx:140: lines 140-142
		if (($rows->arr[$h - 1] ?? null) === "") {
			#coopy/Ndjson.hx:141: characters 13-16
			--$h;
		}
		#coopy/Ndjson.hx:143: characters 19-23
		$_g = 0;
		#coopy/Ndjson.hx:143: characters 23-24
		$_g1 = $h;
		#coopy/Ndjson.hx:143: lines 143-146
		while ($_g < $_g1) {
			#coopy/Ndjson.hx:143: characters 19-24
			$i = $_g++;
			#coopy/Ndjson.hx:144: characters 13-28
			$at = $h - $i - 1;
			#coopy/Ndjson.hx:145: characters 13-34
			$this->addRow($at + 1, ($rows->arr[$at] ?? null));
		}
		#coopy/Ndjson.hx:147: characters 9-24
		$this->addHeaderRow(0);
	}

	/**
	 *
	 * @return an entire table converted into a single string in NDJSON format.
	 *
	 * 
	 * @return string
	 */
	public function render () {
		#coopy/Ndjson.hx:58: characters 9-22
		$txt = "";
		#coopy/Ndjson.hx:59: characters 9-24
		$offset = 0;
		#coopy/Ndjson.hx:60: characters 9-38
		if ($this->tab->get_height() === 0) {
			#coopy/Ndjson.hx:60: characters 28-38
			return $txt;
		}
		#coopy/Ndjson.hx:61: characters 9-37
		if ($this->tab->get_width() === 0) {
			#coopy/Ndjson.hx:61: characters 27-37
			return $txt;
		}
		#coopy/Ndjson.hx:62: lines 62-64
		if ($this->tab->getCell(0, 0) === "@:@") {
			#coopy/Ndjson.hx:63: characters 13-23
			$offset = 1;
		}
		#coopy/Ndjson.hx:65: characters 9-28
		$this->header_row = $offset;
		#coopy/Ndjson.hx:66: characters 19-33
		$_g = $this->header_row + 1;
		#coopy/Ndjson.hx:66: characters 36-46
		$_g1 = $this->tab->get_height();
		#coopy/Ndjson.hx:66: lines 66-69
		while ($_g < $_g1) {
			#coopy/Ndjson.hx:66: characters 19-46
			$r = $_g++;
			#coopy/Ndjson.hx:67: characters 13-32
			$txt = ($txt??'null') . ($this->renderRow($r)??'null');
			#coopy/Ndjson.hx:68: characters 13-24
			$txt = ($txt??'null') . "\x0A";
		}
		#coopy/Ndjson.hx:70: characters 9-19
		return $txt;
	}

	/**
	 *
	 * Convert a table row to a string in NDJSON format.
	 *
	 * @param t the table to render
	 * @param r the row to render
	 * @return the row as a string in NDJSON format
	 *
	 * 
	 * @param int $r
	 * 
	 * @return string
	 */
	public function renderRow ($r) {
		#coopy/Ndjson.hx:43: characters 9-45
		$row = new StringMap();
		#coopy/Ndjson.hx:44: characters 19-23
		$_g = 0;
		#coopy/Ndjson.hx:44: characters 23-32
		$_g1 = $this->tab->get_width();
		#coopy/Ndjson.hx:44: lines 44-48
		while ($_g < $_g1) {
			#coopy/Ndjson.hx:44: characters 19-32
			$c = $_g++;
			#coopy/Ndjson.hx:45: characters 13-64
			$key = $this->view->toString($this->tab->getCell($c, $this->header_row));
			#coopy/Ndjson.hx:46: characters 13-49
			if (($c === 0) && ($this->header_row === 1)) {
				#coopy/Ndjson.hx:46: characters 38-49
				$key = "@:@";
			}
			#coopy/Ndjson.hx:47: characters 13-42
			$value = $this->tab->getCell($c, $r);
			$row->data[$key] = $value;
		}
		#coopy/Ndjson.hx:49: characters 16-40
		return JsonPrinter::print($row, null, null);
	}
}

Boot::registerClass(Ndjson::class, 'coopy.Ndjson');
