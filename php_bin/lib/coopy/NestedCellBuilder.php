<?php
/**
 */

namespace coopy;

use \php\Boot;

class NestedCellBuilder implements CellBuilder {
	/**
	 * @var View
	 */
	public $view;

	/**
	 * @return void
	 */
	public function __construct () {
	}

	/**
	 * @param mixed $parent
	 * @param mixed $local
	 * @param mixed $remote
	 * 
	 * @return mixed
	 */
	public function conflict ($parent, $local, $remote) {
		#coopy/NestedCellBuilder.hx:37: characters 9-33
		$h = $this->view->makeHash();
		#coopy/NestedCellBuilder.hx:38: characters 9-40
		$this->view->hashSet($h, "before", $parent);
		#coopy/NestedCellBuilder.hx:39: characters 9-37
		$this->view->hashSet($h, "ours", $local);
		#coopy/NestedCellBuilder.hx:40: characters 9-40
		$this->view->hashSet($h, "theirs", $remote);
		#coopy/NestedCellBuilder.hx:41: characters 9-17
		return $h;
	}

	/**
	 * @param Unit $unit
	 * @param bool $row_like
	 * 
	 * @return mixed
	 */
	public function links ($unit, $row_like) {
		#coopy/NestedCellBuilder.hx:54: characters 9-33
		$h = $this->view->makeHash();
		#coopy/NestedCellBuilder.hx:55: lines 55-60
		if ($unit->p >= -1) {
			#coopy/NestedCellBuilder.hx:56: characters 13-55
			$this->view->hashSet($h, "before", $this->negToNull($unit->p));
			#coopy/NestedCellBuilder.hx:57: characters 13-53
			$this->view->hashSet($h, "ours", $this->negToNull($unit->l));
			#coopy/NestedCellBuilder.hx:58: characters 13-55
			$this->view->hashSet($h, "theirs", $this->negToNull($unit->r));
			#coopy/NestedCellBuilder.hx:59: characters 13-21
			return $h;
		}
		#coopy/NestedCellBuilder.hx:61: characters 9-51
		$this->view->hashSet($h, "before", $this->negToNull($unit->l));
		#coopy/NestedCellBuilder.hx:62: characters 9-50
		$this->view->hashSet($h, "after", $this->negToNull($unit->r));
		#coopy/NestedCellBuilder.hx:63: characters 9-17
		return $h;
	}

	/**
	 * @param string $label
	 * 
	 * @return mixed
	 */
	public function marker ($label) {
		#coopy/NestedCellBuilder.hx:45: characters 9-35
		return $this->view->toDatum($label);
	}

	/**
	 * @return bool
	 */
	public function needSeparator () {
		#coopy/NestedCellBuilder.hx:15: characters 9-21
		return false;
	}

	/**
	 * @param int $x
	 * 
	 * @return int
	 */
	public function negToNull ($x) {
		#coopy/NestedCellBuilder.hx:49: characters 9-29
		if ($x < 0) {
			#coopy/NestedCellBuilder.hx:49: characters 18-29
			return null;
		}
		#coopy/NestedCellBuilder.hx:50: characters 9-17
		return $x;
	}

	/**
	 * @param string $separator
	 * 
	 * @return void
	 */
	public function setConflictSeparator ($separator) {
	}

	/**
	 * @param string $separator
	 * 
	 * @return void
	 */
	public function setSeparator ($separator) {
	}

	/**
	 * @param View $view
	 * 
	 * @return void
	 */
	public function setView ($view) {
		#coopy/NestedCellBuilder.hx:25: characters 9-25
		$this->view = $view;
	}

	/**
	 * @param mixed $local
	 * @param mixed $remote
	 * 
	 * @return mixed
	 */
	public function update ($local, $remote) {
		#coopy/NestedCellBuilder.hx:29: characters 9-33
		$h = $this->view->makeHash();
		#coopy/NestedCellBuilder.hx:30: characters 9-39
		$this->view->hashSet($h, "before", $local);
		#coopy/NestedCellBuilder.hx:31: characters 9-39
		$this->view->hashSet($h, "after", $remote);
		#coopy/NestedCellBuilder.hx:32: characters 9-17
		return $h;
	}
}

Boot::registerClass(NestedCellBuilder::class, 'coopy.NestedCellBuilder');
