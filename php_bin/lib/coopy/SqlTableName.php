<?php
/**
 */

namespace coopy;

use \php\Boot;

class SqlTableName {
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	public $prefix;

	/**
	 * @param string $name
	 * @param string $prefix
	 * 
	 * @return void
	 */
	public function __construct ($name = "", $prefix = "") {
		#coopy/SqlTableName.hx:12: lines 12-15
		if ($name === null) {
			$name = "";
		}
		if ($prefix === null) {
			$prefix = "";
		}
		#coopy/SqlTableName.hx:13: characters 9-25
		$this->name = $name;
		#coopy/SqlTableName.hx:14: characters 9-29
		$this->prefix = $prefix;
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/SqlTableName.hx:18: characters 9-36
		if ($this->prefix === "") {
			#coopy/SqlTableName.hx:18: characters 25-36
			return $this->name;
		}
		#coopy/SqlTableName.hx:19: characters 9-35
		return ($this->prefix??'null') . "." . ($this->name??'null');
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(SqlTableName::class, 'coopy.SqlTableName');
