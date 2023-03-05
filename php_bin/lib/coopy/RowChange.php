<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

/**
 *
 * A description of a row-level change to a table.
 *
 */
class RowChange {
	/**
	 * @var string
	 */
	public $action;
	/**
	 * @var StringMap
	 */
	public $cond;
	/**
	 * @var bool
	 */
	public $conflicted;
	/**
	 * @var StringMap
	 */
	public $conflicting_parent_val;
	/**
	 * @var StringMap
	 */
	public $conflicting_val;
	/**
	 * @var StringMap
	 */
	public $is_key;
	/**
	 * @var StringMap
	 */
	public $val;

	/**
	 * @return void
	 */
	public function __construct () {
	}

	/**
	 * @param StringMap $m
	 * 
	 * @return string
	 */
	public function showMap ($m) {
		#coopy/RowChange.hx:26: characters 9-33
		if ($m === null) {
			#coopy/RowChange.hx:26: characters 22-33
			return "{}";
		}
		#coopy/RowChange.hx:27: characters 9-22
		$txt = "";
		#coopy/RowChange.hx:28: characters 19-27
		$data = \array_values(\array_map("strval", \array_keys($m->data)));
		$k_current = 0;
		$k_length = \count($data);
		$k_data = $data;
		while ($k_current < $k_length) {
			#coopy/RowChange.hx:28: lines 28-32
			$k = $k_data[$k_current++];
			#coopy/RowChange.hx:29: characters 13-37
			if ($txt !== "") {
				#coopy/RowChange.hx:29: characters 26-37
				$txt = ($txt??'null') . ", ";
			}
			#coopy/RowChange.hx:30: characters 13-30
			$v = ($m->data[$k] ?? null);
			#coopy/RowChange.hx:31: characters 13-31
			$txt = ($txt??'null') . ($k??'null') . "=" . \Std::string($v);
		}
		#coopy/RowChange.hx:33: characters 9-33
		return "{ " . ($txt??'null') . " }";
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/RowChange.hx:37: characters 9-67
		return ($this->action??'null') . " " . ($this->showMap($this->cond)??'null') . " : " . ($this->showMap($this->val)??'null');
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(RowChange::class, 'coopy.RowChange');
