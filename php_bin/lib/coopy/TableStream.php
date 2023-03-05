<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\Exception;
use \haxe\ds\StringMap;

class TableStream implements RowStream {
	/**
	 * @var int
	 */
	public $at;
	/**
	 * @var string[]|\Array_hx
	 */
	public $columns;
	/**
	 * @var int
	 */
	public $h;
	/**
	 * @var StringMap
	 */
	public $row;
	/**
	 * @var RowStream
	 */
	public $src;
	/**
	 * @var Table
	 */
	public $t;

	/**
	 * @param Table $t
	 * 
	 * @return void
	 */
	public function __construct ($t) {
		#coopy/TableStream.hx:16: characters 9-19
		$this->t = $t;
		#coopy/TableStream.hx:17: characters 9-16
		$this->at = -1;
		#coopy/TableStream.hx:18: characters 9-21
		$this->h = $t->get_height();
		#coopy/TableStream.hx:19: characters 9-19
		$this->src = null;
		#coopy/TableStream.hx:20: lines 20-29
		if ($this->h < 0) {
			#coopy/TableStream.hx:21: characters 13-36
			$meta = $t->getMeta();
			#coopy/TableStream.hx:22: lines 22-24
			if ($meta === null) {
				#coopy/TableStream.hx:23: characters 17-22
				throw Exception::thrown("Cannot get meta information for table");
			}
			#coopy/TableStream.hx:25: characters 13-38
			$this->src = $meta->getRowStream();
			#coopy/TableStream.hx:26: lines 26-28
			if ($this->src === null) {
				#coopy/TableStream.hx:27: characters 17-22
				throw Exception::thrown("Cannot iterate table");
			}
		}
	}

	/**
	 * @return bool
	 */
	public function fetch () {
		#coopy/TableStream.hx:56: lines 56-60
		if ($this->at === -1) {
			#coopy/TableStream.hx:57: characters 13-17
			$this->at++;
			#coopy/TableStream.hx:58: characters 13-42
			if ($this->src !== null) {
				#coopy/TableStream.hx:58: characters 28-42
				$this->fetchColumns();
			}
			#coopy/TableStream.hx:59: characters 13-24
			return true;
		}
		#coopy/TableStream.hx:61: lines 61-65
		if ($this->src !== null) {
			#coopy/TableStream.hx:62: characters 13-19
			$this->at = 1;
			#coopy/TableStream.hx:63: characters 13-29
			$this->row = $this->fetchRow();
			#coopy/TableStream.hx:64: characters 13-29
			return $this->row !== null;
		}
		#coopy/TableStream.hx:66: characters 9-13
		$this->at++;
		#coopy/TableStream.hx:67: characters 9-20
		return $this->at < $this->h;
	}

	/**
	 * @return string[]|\Array_hx
	 */
	public function fetchColumns () {
		#coopy/TableStream.hx:33: characters 9-42
		if ($this->columns !== null) {
			#coopy/TableStream.hx:33: characters 28-42
			return $this->columns;
		}
		#coopy/TableStream.hx:34: lines 34-37
		if ($this->src !== null) {
			#coopy/TableStream.hx:35: characters 13-41
			$this->columns = $this->src->fetchColumns();
			#coopy/TableStream.hx:36: characters 13-27
			return $this->columns;
		}
		#coopy/TableStream.hx:38: characters 9-38
		$this->columns = new \Array_hx();
		#coopy/TableStream.hx:39: characters 19-23
		$_g = 0;
		#coopy/TableStream.hx:39: characters 23-30
		$_g1 = $this->t->get_width();
		#coopy/TableStream.hx:39: lines 39-41
		while ($_g < $_g1) {
			#coopy/TableStream.hx:39: characters 19-30
			$i = $_g++;
			#coopy/TableStream.hx:40: characters 13-41
			$_this = $this->columns;
			$x = $this->t->getCell($i, 0);
			$_this->arr[$_this->length++] = $x;
		}
		#coopy/TableStream.hx:42: characters 9-23
		return $this->columns;
	}

	/**
	 * @return StringMap
	 */
	public function fetchRow () {
		#coopy/TableStream.hx:46: characters 9-45
		if ($this->src !== null) {
			#coopy/TableStream.hx:46: characters 24-45
			return $this->src->fetchRow();
		}
		#coopy/TableStream.hx:47: characters 9-31
		if ($this->at >= $this->h) {
			#coopy/TableStream.hx:47: characters 20-31
			return null;
		}
		#coopy/TableStream.hx:48: characters 9-45
		$row = new StringMap();
		#coopy/TableStream.hx:49: characters 19-23
		$_g = 0;
		#coopy/TableStream.hx:49: characters 23-37
		$_g1 = $this->columns->length;
		#coopy/TableStream.hx:49: lines 49-51
		while ($_g < $_g1) {
			#coopy/TableStream.hx:49: characters 19-37
			$i = $_g++;
			#coopy/TableStream.hx:50: characters 13-46
			$k = ($this->columns->arr[$i] ?? null);
			$v = $this->t->getCell($i, $this->at);
			$row->data[$k] = $v;
		}
		#coopy/TableStream.hx:52: characters 9-19
		return $row;
	}

	/**
	 * @param int $x
	 * 
	 * @return mixed
	 */
	public function getCell ($x) {
		#coopy/TableStream.hx:71: lines 71-73
		if ($this->at === 0) {
			#coopy/TableStream.hx:72: characters 13-30
			return ($this->columns->arr[$x] ?? null);
		}
		#coopy/TableStream.hx:74: lines 74-76
		if ($this->row !== null) {
			#coopy/TableStream.hx:75: characters 20-35
			return ($this->row->data[($this->columns->arr[$x] ?? null)] ?? null);
		}
		#coopy/TableStream.hx:77: characters 9-31
		return $this->t->getCell($x, $this->at);
	}

	/**
	 * @return int
	 */
	public function width () {
		#coopy/TableStream.hx:81: characters 9-23
		$this->fetchColumns();
		#coopy/TableStream.hx:82: characters 9-30
		return $this->columns->length;
	}
}

Boot::registerClass(TableStream::class, 'coopy.TableStream');
