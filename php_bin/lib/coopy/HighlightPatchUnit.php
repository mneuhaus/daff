<?php
/**
 */

namespace coopy;

use \php\Boot;

class HighlightPatchUnit {
	/**
	 * @var bool
	 */
	public $add;
	/**
	 * @var string
	 */
	public $code;
	/**
	 * @var int
	 */
	public $destRow;
	/**
	 * @var int
	 */
	public $patchRow;
	/**
	 * @var bool
	 */
	public $rem;
	/**
	 * @var int
	 */
	public $sourceNextRow;
	/**
	 * @var int
	 */
	public $sourcePrevRow;
	/**
	 * @var int
	 */
	public $sourceRow;
	/**
	 * @var int
	 */
	public $sourceRowOffset;
	/**
	 * @var bool
	 */
	public $update;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/HighlightPatchUnit.hx:23: characters 9-20
		$this->add = false;
		#coopy/HighlightPatchUnit.hx:24: characters 9-20
		$this->rem = false;
		#coopy/HighlightPatchUnit.hx:25: characters 9-23
		$this->update = false;
		#coopy/HighlightPatchUnit.hx:26: characters 9-23
		$this->sourceRow = -1;
		#coopy/HighlightPatchUnit.hx:27: characters 9-28
		$this->sourceRowOffset = 0;
		#coopy/HighlightPatchUnit.hx:28: characters 9-27
		$this->sourcePrevRow = -1;
		#coopy/HighlightPatchUnit.hx:29: characters 9-27
		$this->sourceNextRow = -1;
		#coopy/HighlightPatchUnit.hx:30: characters 9-21
		$this->destRow = -1;
		#coopy/HighlightPatchUnit.hx:31: characters 9-22
		$this->patchRow = -1;
		#coopy/HighlightPatchUnit.hx:32: characters 9-18
		$this->code = "";
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/HighlightPatchUnit.hx:36: characters 9-169
		return "(" . ($this->code??'null') . " patch " . ($this->patchRow??'null') . " source " . ($this->sourcePrevRow??'null') . ":" . ($this->sourceRow??'null') . ":" . ($this->sourceNextRow??'null') . "+" . ($this->sourceRowOffset??'null') . " dest " . ($this->destRow??'null') . ")";
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(HighlightPatchUnit::class, 'coopy.HighlightPatchUnit');
