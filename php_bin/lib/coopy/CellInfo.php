<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Interpretation of a cell in a diff, produced by `DiffRender.renderCell`.
 * Useful for custom views of a diff.
 *
 */
class CellInfo {
	/**
	 * @var string
	 *
	 * The type of activity going on in the cell: "move", "add", "remove",
	 * "modify", "conflict", "header", "spec"
	 *
	 *  + "move" means a row/column that has moved
	 *  + "add" means a row/column that has been inserted
	 *  + "remove" means a row/column that has been deleted
	 *  + "modify" means a cell that has been changed
	 *  + "conflict" means a cell that has been changed in a conflicting way
	 *  + "header" means part of a row giving column names
	 *  + "spec" means part of a row specifying column changes
	 *
	 */
	public $category;
	/**
	 * @var string
	 *
	 * The type of activity going on in the cell, based only on
	 * knowledge of what row it is in.
	 *
	 */
	public $category_given_tr;
	/**
	 * @var bool
	 *
	 * True if there is a conflicting update in the cell, the cell
	 * contains three values, a `pvalue` (common ancestor/parent),
	 * an `lvalue` (local change) and an `rvalue` (remote change)
	 *
	 */
	public $conflicted;
	/**
	 * @var string
	 *
	 * Local/reference cell value if applicable.
	 *
	 */
	public $lvalue;
	/**
	 * @var string
	 *
	 * If this is a change in a property of the table rather than
	 * the data in the table itself, this field names that property.
	 *
	 */
	public $meta;
	/**
	 * @var string
	 *
	 * Any separator found in the cell, made pretty using a glyph.
	 *
	 */
	public $pretty_separator;
	/**
	 * @var string
	 *
	 * The cell value in text form, with some special characters rendered
	 * prettier (e.g. `->` is converted to an appropriate glyph, and
	 * certain spaces in diffs are converted to a visible space glyph)
	 *
	 */
	public $pretty_value;
	/**
	 * @var string
	 *
	 * Parent cell value if applicable.
	 *
	 */
	public $pvalue;
	/**
	 * @var mixed
	 *
	 * The cell value "as is".
	 *
	 */
	public $raw;
	/**
	 * @var string
	 *
	 * Remote/changed cell value if applicable.
	 *
	 */
	public $rvalue;
	/**
	 * @var string
	 *
	 * Any separator found in the cell.
	 *
	 */
	public $separator;
	/**
	 * @var bool
	 *
	 * True if there is an update in the cell, the cell contains
	 * two values, an `lvalue` (before) and an `rvalue` (after)
	 *
	 */
	public $updated;
	/**
	 * @var string
	 *
	 * The cell value in text form.
	 *
	 */
	public $value;

	/**
	 * @return void
	 */
	public function __construct () {
	}

	/**
	 *
	 * Give a summary of the information contained for debugging purposes.
	 *
	 * 
	 * @return string
	 */
	public function toString () {
		#coopy/CellInfo.hx:130: characters 9-35
		if (!$this->updated) {
			#coopy/CellInfo.hx:130: characters 23-35
			return $this->value;
		}
		#coopy/CellInfo.hx:131: characters 9-55
		if (!$this->conflicted) {
			#coopy/CellInfo.hx:131: characters 26-55
			return ($this->lvalue??'null') . "::" . ($this->rvalue??'null');
		}
		#coopy/CellInfo.hx:132: characters 9-54
		return ($this->pvalue??'null') . "||" . ($this->lvalue??'null') . "::" . ($this->rvalue??'null');
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(CellInfo::class, 'coopy.CellInfo');
