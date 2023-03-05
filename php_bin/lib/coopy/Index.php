<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

class Index {
	/**
	 * @var int[]|\Array_hx
	 */
	public $cols;
	/**
	 * @var int
	 */
	public $hdr;
	/**
	 * @var int
	 */
	public $height;
	/**
	 * @var bool
	 */
	public $ignore_case;
	/**
	 * @var bool
	 */
	public $ignore_whitespace;
	/**
	 * @var Table
	 */
	public $indexed_table;
	/**
	 * @var StringMap
	 */
	public $items;
	/**
	 * @var string[]|\Array_hx
	 */
	public $keys;
	/**
	 * @var int
	 */
	public $top_freq;
	/**
	 * @var View
	 */
	public $v;

	/**
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($flags) {
		#coopy/Index.hx:22: characters 9-44
		$this->items = new StringMap();
		#coopy/Index.hx:23: characters 9-32
		$this->cols = new \Array_hx();
		#coopy/Index.hx:24: characters 9-35
		$this->keys = new \Array_hx();
		#coopy/Index.hx:25: characters 9-21
		$this->top_freq = 0;
		#coopy/Index.hx:26: characters 9-19
		$this->height = 0;
		#coopy/Index.hx:27: characters 9-16
		$this->hdr = 0;
		#coopy/Index.hx:28: characters 9-34
		$this->ignore_whitespace = false;
		#coopy/Index.hx:29: characters 9-28
		$this->ignore_case = false;
		#coopy/Index.hx:30: lines 30-33
		if ($flags !== null) {
			#coopy/Index.hx:31: characters 13-56
			$this->ignore_whitespace = $flags->ignore_whitespace;
			#coopy/Index.hx:32: characters 13-44
			$this->ignore_case = $flags->ignore_case;
		}
	}

	/**
	 * @param int $i
	 * 
	 * @return void
	 */
	public function addColumn ($i) {
		#coopy/Index.hx:37: characters 9-21
		$_this = $this->cols;
		$_this->arr[$_this->length++] = $i;
	}

	/**
	 * @return Table
	 */
	public function getTable () {
		#coopy/Index.hx:102: characters 9-29
		return $this->indexed_table;
	}

	/**
	 * @param Table $t
	 * @param int $hdr
	 * 
	 * @return void
	 */
	public function indexTable ($t, $hdr) {
		#coopy/Index.hx:41: characters 9-26
		$this->indexed_table = $t;
		#coopy/Index.hx:42: characters 9-23
		$this->hdr = $hdr;
		#coopy/Index.hx:43: lines 43-46
		if (($this->keys->length !== $t->get_height()) && ($t->get_height() > 0)) {
			#coopy/Index.hx:45: characters 13-36
			$this->keys->offsetSet($t->get_height() - 1, null);
		}
		#coopy/Index.hx:47: characters 19-23
		$_g = 0;
		#coopy/Index.hx:47: characters 23-31
		$_g1 = $t->get_height();
		#coopy/Index.hx:47: lines 47-60
		while ($_g < $_g1) {
			#coopy/Index.hx:47: characters 19-31
			$i = $_g++;
			#coopy/Index.hx:48: characters 13-40
			$key = ($this->keys->arr[$i] ?? null);
			#coopy/Index.hx:49: lines 49-52
			if ($key === null) {
				#coopy/Index.hx:50: characters 17-33
				$key = $this->toKey($t, $i);
				#coopy/Index.hx:51: characters 17-30
				$this->keys->offsetSet($i, $key);
			}
			#coopy/Index.hx:53: characters 13-51
			$item = ($this->items->data[$key] ?? null);
			#coopy/Index.hx:54: lines 54-57
			if ($item === null) {
				#coopy/Index.hx:55: characters 17-39
				$item = new IndexItem();
				#coopy/Index.hx:56: characters 17-36
				$this->items->data[$key] = $item;
			}
			#coopy/Index.hx:58: characters 28-39
			if ($item->lst === null) {
				$item->lst = new \Array_hx();
			}
			$_this = $item->lst;
			$_this->arr[$_this->length++] = $i;
			#coopy/Index.hx:58: characters 13-40
			$ct = $item->lst->length;
			#coopy/Index.hx:59: characters 13-43
			if ($ct > $this->top_freq) {
				#coopy/Index.hx:59: characters 30-43
				$this->top_freq = $ct;
			}
		}
		#coopy/Index.hx:61: characters 9-26
		$this->height = $t->get_height();
	}

	/**
	 * @param Table $t
	 * @param int $i
	 * 
	 * @return string
	 */
	public function toKey ($t, $i) {
		#coopy/Index.hx:66: characters 9-44
		$wide = ($i < $this->hdr ? "_" : "");
		#coopy/Index.hx:67: characters 9-41
		if ($this->v === null) {
			#coopy/Index.hx:67: characters 22-41
			$this->v = $t->getCellView();
		}
		#coopy/Index.hx:68: characters 19-23
		$_g = 0;
		#coopy/Index.hx:68: characters 23-34
		$_g1 = $this->cols->length;
		#coopy/Index.hx:68: lines 68-80
		while ($_g < $_g1) {
			#coopy/Index.hx:68: characters 19-34
			$k = $_g++;
			#coopy/Index.hx:69: characters 13-52
			$d = $t->getCell(($this->cols->arr[$k] ?? null), $i);
			#coopy/Index.hx:70: characters 13-46
			$txt = $this->v->toString($d);
			#coopy/Index.hx:71: lines 71-73
			if ($this->ignore_whitespace) {
				#coopy/Index.hx:72: characters 17-44
				$txt = \trim($txt);
			}
			#coopy/Index.hx:74: lines 74-76
			if ($this->ignore_case) {
				#coopy/Index.hx:75: characters 17-40
				$txt = \mb_strtolower($txt);
			}
			#coopy/Index.hx:77: characters 13-36
			if ($k > 0) {
				#coopy/Index.hx:77: characters 22-36
				$wide = ($wide??'null') . " // ";
			}
			#coopy/Index.hx:78: characters 13-82
			if (($txt === null) || ($txt === "") || ($txt === "null") || ($txt === "undefined")) {
				#coopy/Index.hx:78: characters 74-82
				continue;
			}
			#coopy/Index.hx:79: characters 13-24
			$wide = ($wide??'null') . ($txt??'null');
		}
		#coopy/Index.hx:81: characters 9-20
		return $wide;
	}

	/**
	 * @param Row $row
	 * 
	 * @return string
	 */
	public function toKeyByContent ($row) {
		#coopy/Index.hx:85: characters 9-53
		$wide = ($row->isPreamble() ? "_" : "");
		#coopy/Index.hx:86: characters 19-23
		$_g = 0;
		#coopy/Index.hx:86: characters 23-34
		$_g1 = $this->cols->length;
		#coopy/Index.hx:86: lines 86-97
		while ($_g < $_g1) {
			#coopy/Index.hx:86: characters 19-34
			$k = $_g++;
			#coopy/Index.hx:87: characters 13-58
			$txt = $row->getRowString(($this->cols->arr[$k] ?? null));
			#coopy/Index.hx:88: lines 88-90
			if ($this->ignore_whitespace) {
				#coopy/Index.hx:89: characters 17-44
				$txt = \trim($txt);
			}
			#coopy/Index.hx:91: lines 91-93
			if ($this->ignore_case) {
				#coopy/Index.hx:92: characters 17-40
				$txt = \mb_strtolower($txt);
			}
			#coopy/Index.hx:94: characters 13-36
			if ($k > 0) {
				#coopy/Index.hx:94: characters 22-36
				$wide = ($wide??'null') . " // ";
			}
			#coopy/Index.hx:95: characters 13-82
			if (($txt === null) || ($txt === "") || ($txt === "null") || ($txt === "undefined")) {
				#coopy/Index.hx:95: characters 74-82
				continue;
			}
			#coopy/Index.hx:96: characters 13-24
			$wide = ($wide??'null') . ($txt??'null');
		}
		#coopy/Index.hx:98: characters 9-20
		return $wide;
	}
}

Boot::registerClass(Index::class, 'coopy.Index');
