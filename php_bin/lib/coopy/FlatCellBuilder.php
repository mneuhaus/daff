<?php
/**
 */

namespace coopy;

use \php\Boot;
use \php\_Boot\HxString;

class FlatCellBuilder implements CellBuilder {
	/**
	 * @var string
	 */
	public $conflict_separator;
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var string
	 */
	public $separator;
	/**
	 * @var View
	 */
	public $view;

	/**
	 * @param View $v
	 * @param mixed $d
	 * 
	 * @return string
	 */
	public static function quoteForDiff ($v, $d) {
		#coopy/FlatCellBuilder.hx:59: characters 9-35
		$nil = "NULL";
		#coopy/FlatCellBuilder.hx:60: lines 60-62
		if ($v->equals($d, null)) {
			#coopy/FlatCellBuilder.hx:61: characters 13-23
			return $nil;
		}
		#coopy/FlatCellBuilder.hx:63: characters 9-42
		$str = $v->toString($d);
		#coopy/FlatCellBuilder.hx:64: characters 9-29
		$score = 0;
		#coopy/FlatCellBuilder.hx:65: characters 19-23
		$_g = 0;
		#coopy/FlatCellBuilder.hx:65: characters 23-33
		$_g1 = mb_strlen($str);
		#coopy/FlatCellBuilder.hx:65: lines 65-68
		while ($_g < $_g1) {
			#coopy/FlatCellBuilder.hx:65: characters 19-33
			$i = $_g++;
			#coopy/FlatCellBuilder.hx:66: characters 13-55
			if (HxString::charCodeAt($str, $score) !== 95) {
				#coopy/FlatCellBuilder.hx:66: characters 50-55
				break;
			}
			#coopy/FlatCellBuilder.hx:67: characters 13-20
			++$score;
		}
		#coopy/FlatCellBuilder.hx:69: lines 69-71
		if (\mb_substr($str, $score, null) === $nil) {
			#coopy/FlatCellBuilder.hx:70: characters 13-28
			$str = "_" . ($str??'null');
		}
		#coopy/FlatCellBuilder.hx:72: characters 9-19
		return $str;
	}

	/**
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($flags) {
		#coopy/FlatCellBuilder.hx:15: characters 9-27
		$this->flags = $flags;
	}

	/**
	 * @param mixed $parent
	 * @param mixed $local
	 * @param mixed $remote
	 * 
	 * @return mixed
	 */
	public function conflict ($parent, $local, $remote) {
		#coopy/FlatCellBuilder.hx:42: lines 42-44
		return ($this->view->toString($parent)??'null') . ($this->conflict_separator??'null') . ($this->view->toString($local)??'null') . ($this->conflict_separator??'null') . ($this->view->toString($remote)??'null');
	}

	/**
	 * @param Unit $unit
	 * @param bool $row_like
	 * 
	 * @return mixed
	 */
	public function links ($unit, $row_like) {
		#coopy/FlatCellBuilder.hx:52: lines 52-54
		if ($this->flags->count_like_a_spreadsheet && !$row_like) {
			#coopy/FlatCellBuilder.hx:53: characters 13-55
			return $this->view->toDatum($unit->toBase26String());
		}
		#coopy/FlatCellBuilder.hx:55: characters 9-45
		return $this->view->toDatum($unit->toString());
	}

	/**
	 * @param string $label
	 * 
	 * @return mixed
	 */
	public function marker ($label) {
		#coopy/FlatCellBuilder.hx:48: characters 9-35
		return $this->view->toDatum($label);
	}

	/**
	 * @return bool
	 */
	public function needSeparator () {
		#coopy/FlatCellBuilder.hx:19: characters 9-20
		return true;
	}

	/**
	 * @param string $separator
	 * 
	 * @return void
	 */
	public function setConflictSeparator ($separator) {
		#coopy/FlatCellBuilder.hx:27: characters 9-44
		$this->conflict_separator = $separator;
	}

	/**
	 * @param string $separator
	 * 
	 * @return void
	 */
	public function setSeparator ($separator) {
		#coopy/FlatCellBuilder.hx:23: characters 9-35
		$this->separator = $separator;
	}

	/**
	 * @param View $view
	 * 
	 * @return void
	 */
	public function setView ($view) {
		#coopy/FlatCellBuilder.hx:31: characters 9-25
		$this->view = $view;
	}

	/**
	 * @param mixed $local
	 * @param mixed $remote
	 * 
	 * @return mixed
	 */
	public function update ($local, $remote) {
		#coopy/FlatCellBuilder.hx:35: lines 35-37
		return $this->view->toDatum((FlatCellBuilder::quoteForDiff($this->view, $local)??'null') . ($this->separator??'null') . (FlatCellBuilder::quoteForDiff($this->view, $remote)??'null'));
	}
}

Boot::registerClass(FlatCellBuilder::class, 'coopy.FlatCellBuilder');
