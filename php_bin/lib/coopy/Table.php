<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Everything daff needs to know about a table.  This interface
 * gets implemented natively on each language/platform daff supports,
 * so that we don't waste time making copies of tables from one format
 * to another.
 *
 */
interface Table {
	/**
	 *
	 * Clear the table if possible, leaving it with zero rows and columns.
	 *
	 * 
	 * @return void
	 */
	public function clear () ;

	/**
	 *
	 * @return a copy of the table.
	 *
	 * 
	 * @return Table
	 */
	public function clone () ;

	/**
	 *
	 * @return an empty table of the same type, if possible, or null if not possible.
	 *
	 * 
	 * @return Table
	 */
	public function create () ;

	/**
	 *
	 * Read a cell
	 *
	 * @param x the *column* to read from
	 * @param y the *row* to read from
	 * @return the content of the cell at row y and column x
	 *
	 * 
	 * @param int $x
	 * @param int $y
	 * 
	 * @return mixed
	 */
	public function getCell ($x, $y) ;

	/**
	 *
	 * Get an interface for interpreting cell contents (e.g.
	 * converting to a string).  We never call any methods
	 * directly on a cell, since we've no idea what they
	 * are.  To learn about the contents of a cell, we pass
	 * it to methods of a `View`.
	 *
	 * @return a `View` interface for interpreting cell contents
	 *
	 * 
	 * @return View
	 */
	public function getCellView () ;

	/**
	 *
	 * Get the underlying data object backing the table, if possible.
	 * This is platform specific.  The daff library never uses this
	 * method.
	 *
	 * @return an object of some kind - enjoy!
	 *
	 * 
	 * @return mixed
	 */
	public function getData () ;

	/**
	 *
	 * @return a interface to the columns of this table, or null
	 * if no interface is available.
	 *
	 * 
	 * @return Meta
	 */
	public function getMeta () ;

	/**
	 *
	 * Get the height of the table.  Sorry for the inconsistent
	 * capitalization, it is due to a confusion I had over haxe
	 * setter/getters.
	 *
	 * @return the number of rows in the table
	 *
	 * 
	 * @return int
	 */
	public function get_height () ;

	/**
	 *
	 * Get the width of the table.  Sorry for the inconsistent
	 * capitalization, it is due to a confusion I had over haxe
	 * setter/getters.
	 *
	 * @return the number of columns in the table
	 *
	 * 
	 * @return int
	 */
	public function get_width () ;

	/**
	 *
	 * Insert, delete, and/or shuffle columns. We bundle all these operations
	 * together since things can get creakingly slow otherwise.
	 *
	 * @param fate an array specifying, for each existing column, where that
	 * column should be now placed (-1 means "delete").
	 * @param hfate the total number of columns after the operation. Any
	 * columns that did not receive an existing column should be initialized
	 * as a column of empty cells (nulls).
	 * @return true on success
	 *
	 * 
	 * @param int[]|\Array_hx $fate
	 * @param int $wfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteColumns ($fate, $wfate) ;

	/**
	 *
	 * Insert, delete, and/or shuffle rows. We bundle all these operations
	 * together since things can get creakingly slow otherwise.
	 *
	 * @param fate an array specifying, for each existing row, where that
	 * row should be now placed (-1 means "delete").
	 * @param hfate the total number of rows after the operation. Any
	 * rows that did not receive an existing row should be initialized
	 * as a row of empty cells (nulls).
	 * @return true on success
	 *
	 * 
	 * @param int[]|\Array_hx $fate
	 * @param int $hfate
	 * 
	 * @return bool
	 */
	public function insertOrDeleteRows ($fate, $hfate) ;

	/**
	 *
	 * Check if a table can be resized.
	 *
	 * @return true if the table can be resized
	 *
	 * 
	 * @return bool
	 */
	public function isResizable () ;

	/**
	 *
	 * Resize a table, if possible, preserving existing contents that fit.
	 * Any newly created cells should be `null`.
	 *
	 * @param w desired number of columns
	 * @param h desired number of rows
	 * @return true if the table was successfully resized
	 *
	 * 
	 * @param int $w
	 * @param int $h
	 * 
	 * @return bool
	 */
	public function resize ($w, $h) ;

	/**
	 *
	 * Write to a cell
	 *
	 * @param x the *column* to write to
	 * @param y the *row* to write to
	 * @param c the value to write
	 *
	 * 
	 * @param int $x
	 * @param int $y
	 * @param mixed $c
	 * 
	 * @return void
	 */
	public function setCell ($x, $y, $c) ;

	/**
	 *
	 * Remove empty final rows or final columns. This method is not in
	 * fact used by the daff library.
	 *
	 * @return true on success
	 *
	 * 
	 * @return bool
	 */
	public function trimBlank () ;
}

Boot::registerClass(Table::class, 'coopy.Table');
Boot::registerGetters('coopy\\Table', [
	'width' => true,
	'height' => true
]);
