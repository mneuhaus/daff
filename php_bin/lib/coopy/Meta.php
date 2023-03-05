<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Describe and manipulate columns of a table.
 *
 */
interface Meta {
	/**
	 *
	 * Change the columns of a table.
	 *
	 * @param columns an ordered list of columns and the changes
	 * to apply.
	 *
	 * @return true on success.
	 *
	 * 
	 * @param ColumnChange[]|\Array_hx $columns
	 * 
	 * @return bool
	 */
	public function alterColumns ($columns) ;

	/**
	 *
	 * Apply flags to control future changes to table.
	 *
	 * @param flags the desired options.
	 *
	 * @return true on success.
	 *
	 * 
	 * @param CompareFlags $flags
	 * 
	 * @return bool
	 */
	public function applyFlags ($flags) ;

	/**
	 *
	 * @return A table describing the columns of a table, if available.
	 * If a table is returned, it should have the same number
	 * of columns as the original, plus on extra
	 * initial column. Its header row should be the same
	 * as the original, with "@" in the extra column.
	 * Subsequent rows may have an arbitrary tag in the first
	 * column, followed by values to be associated with that tag
	 * for each column.
	 *
	 * 
	 * @return Table
	 */
	public function asTable () ;

	/**
	 *
	 * Add, remove, or update a row of the table.
	 *
	 * @param rc the change to make.
	 *
	 * @return true on success.
	 *
	 * 
	 * @param RowChange $rc
	 * 
	 * @return bool
	 */
	public function changeRow ($rc) ;

	/**
	 *
	 * Make a copy.  Deprecated.
	 *
	 * @return a copy of this object.
	 *
	 * 
	 * @param Table $table
	 * 
	 * @return Meta
	 */
	public function cloneMeta ($table = null) ;

	/**
	 *
	 * @return a name for the table if it has one, otherwise null.
	 *
	 * 
	 * @return string
	 */
	public function getName () ;

	/**
	 *
	 * @return a streaming interface for rows.
	 *
	 * 
	 * @return RowStream
	 */
	public function getRowStream () ;

	/**
	 *
	 * @return true if the table may be nested (containing subtables).
	 *
	 * 
	 * @return bool
	 */
	public function isNested () ;

	/**
	 *
	 * @return true if the table is best accessed via sql.
	 *
	 * 
	 * @return bool
	 */
	public function isSql () ;

	/**
	 *
	 * @return true if the interface can make column-level changes.
	 *
	 * 
	 * @return bool
	 */
	public function useForColumnChanges () ;

	/**
	 *
	 * @return true if the interface can make row-level changes.
	 *
	 * 
	 * @return bool
	 */
	public function useForRowChanges () ;
}

Boot::registerClass(Meta::class, 'coopy.Meta');
