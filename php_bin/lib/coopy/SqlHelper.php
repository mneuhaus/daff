<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

interface SqlHelper {
	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param ColumnChange[]|\Array_hx $columns
	 * 
	 * @return bool
	 */
	public function alterColumns ($db, $name, $columns) ;

	/**
	 * @param SqlDatabase $db
	 * @param string $tag
	 * @param string $resource_name
	 * 
	 * @return bool
	 */
	public function attach ($db, $tag, $resource_name) ;

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * 
	 * @return int
	 */
	public function countRows ($db, $name) ;

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param StringMap $conds
	 * 
	 * @return bool
	 */
	public function delete ($db, $name, $conds) ;

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * 
	 * @return int[]|\Array_hx
	 */
	public function getRowIDs ($db, $name) ;

	/**
	 * @param SqlDatabase $db
	 * 
	 * @return string[]|\Array_hx
	 */
	public function getTableNames ($db) ;

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param StringMap $vals
	 * 
	 * @return bool
	 */
	public function insert ($db, $name, $vals) ;

	/**
	 * @param SqlDatabase $db
	 * @param SqlTableName $name
	 * @param StringMap $conds
	 * @param StringMap $vals
	 * 
	 * @return bool
	 */
	public function update ($db, $name, $conds, $vals) ;
}

Boot::registerClass(SqlHelper::class, 'coopy.SqlHelper');
