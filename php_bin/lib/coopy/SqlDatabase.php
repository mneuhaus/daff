<?php
/**
 */

namespace coopy;

use \php\Boot;

interface SqlDatabase {
	/**
	 * @param string $query
	 * @param mixed[]|\Array_hx $args
	 * @param string[]|\Array_hx $order
	 * 
	 * @return bool
	 */
	public function begin ($query, $args = null, $order = null) ;

	/**
	 * @param SqlTableName $name
	 * @param int $row
	 * @param string[]|\Array_hx $order
	 * 
	 * @return bool
	 */
	public function beginRow ($name, $row, $order = null) ;

	/**
	 * @return bool
	 */
	public function end () ;

	/**
	 * @param int $index
	 * 
	 * @return mixed
	 */
	public function get ($index) ;

	/**
	 * @param SqlTableName $name
	 * 
	 * @return SqlColumn[]|\Array_hx
	 */
	public function getColumns ($name) ;

	/**
	 * @return SqlHelper
	 */
	public function getHelper () ;

	/**
	 * @return string
	 */
	public function getNameForAttachment () ;

	/**
	 * @param string $name
	 * 
	 * @return string
	 */
	public function getQuotedColumnName ($name) ;

	/**
	 * @param SqlTableName $name
	 * 
	 * @return string
	 */
	public function getQuotedTableName ($name) ;

	/**
	 * @return bool
	 */
	public function read () ;

	/**
	 * @return string
	 */
	public function rowid () ;

	/**
	 * @return int
	 */
	public function width () ;
}

Boot::registerClass(SqlDatabase::class, 'coopy.SqlDatabase');
