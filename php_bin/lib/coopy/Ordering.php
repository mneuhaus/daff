<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * An ordered list of units, representing a merged view of rows
 * in a local, remote, and (optionally) parent table.
 *
 */
class Ordering {
	/**
	 * @var bool
	 */
	public $ignore_parent;
	/**
	 * @var Unit[]|\Array_hx
	 */
	public $order;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/Ordering.hx:18: characters 9-34
		$this->order = new \Array_hx();
		#coopy/Ordering.hx:19: characters 9-30
		$this->ignore_parent = false;
	}

	/**
	 *
	 * Add a local/remote/parent triplet.
	 *
	 * @param l the row/column number in the local table (-1 means absent)
	 * @param r the row/column number in the remote table (-1 means absent)
	 * @param p the row/column number in the parent table (-1 means absent,
	 * -2 means there is no parent)
	 *
	 * 
	 * @param int $l
	 * @param int $r
	 * @param int $p
	 * 
	 * @return void
	 */
	public function add ($l, $r, $p = -2) {
		#coopy/Ordering.hx:32: lines 32-35
		if ($p === null) {
			$p = -2;
		}
		#coopy/Ordering.hx:33: characters 9-34
		if ($this->ignore_parent) {
			#coopy/Ordering.hx:33: characters 28-34
			$p = -2;
		}
		#coopy/Ordering.hx:34: characters 9-36
		$_this = $this->order;
		$x = new Unit($l, $r, $p);
		$_this->arr[$_this->length++] = $x;
	}

	/**
	 *
	 * @return the list of units in this ordering
	 *
	 * 
	 * @return Unit[]|\Array_hx
	 */
	public function getList () {
		#coopy/Ordering.hx:43: characters 9-21
		return $this->order;
	}

	/**
	 *
	 * Force any parent row/column numbers to be ignored and discarded.
	 *
	 * 
	 * @return void
	 */
	public function ignoreParent () {
		#coopy/Ordering.hx:77: characters 9-29
		$this->ignore_parent = true;
	}

	/**
	 *
	 * Replace the order with a prepared list.
	 *
	 * @param lst the new order
	 *
	 * 
	 * @param Unit[]|\Array_hx $lst
	 * 
	 * @return void
	 */
	public function setList ($lst) {
		#coopy/Ordering.hx:54: characters 9-20
		$this->order = $lst;
	}

	/**
	 *
	 * @return the list of units in text form
	 *
	 * 
	 * @return string
	 */
	public function toString () {
		#coopy/Ordering.hx:63: characters 9-31
		$txt = "";
		#coopy/Ordering.hx:64: characters 19-23
		$_g = 0;
		#coopy/Ordering.hx:64: characters 23-35
		$_g1 = $this->order->length;
		#coopy/Ordering.hx:64: lines 64-67
		while ($_g < $_g1) {
			#coopy/Ordering.hx:64: characters 19-35
			$i = $_g++;
			#coopy/Ordering.hx:65: characters 13-33
			if ($i > 0) {
				#coopy/Ordering.hx:65: characters 22-33
				$txt = ($txt??'null') . ", ";
			}
			#coopy/Ordering.hx:66: characters 13-28
			$txt = ($txt??'null') . \Std::string(($this->order->arr[$i] ?? null));
		}
		#coopy/Ordering.hx:68: characters 9-19
		return $txt;
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(Ordering::class, 'coopy.Ordering');
