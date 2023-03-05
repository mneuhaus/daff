<?php
/**
 */

namespace coopy;

use \php\Boot;

class SqlColumn {
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var bool
	 */
	public $primary;
	/**
	 * @var string
	 */
	public $type_family;
	/**
	 * @var string
	 */
	public $type_value;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/SqlColumn.hx:15: characters 9-18
		$this->name = "";
		#coopy/SqlColumn.hx:16: characters 9-24
		$this->primary = false;
		#coopy/SqlColumn.hx:17: characters 9-26
		$this->type_value = null;
		#coopy/SqlColumn.hx:18: characters 9-27
		$this->type_family = null;
	}

	/**
	 * @return string
	 */
	public function getName () {
		#coopy/SqlColumn.hx:35: characters 9-20
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function isPrimaryKey () {
		#coopy/SqlColumn.hx:39: characters 9-23
		return $this->primary;
	}

	/**
	 * @param string $name
	 * 
	 * @return void
	 */
	public function setName ($name) {
		#coopy/SqlColumn.hx:22: characters 9-25
		$this->name = $name;
	}

	/**
	 * @param bool $primary
	 * 
	 * @return void
	 */
	public function setPrimaryKey ($primary) {
		#coopy/SqlColumn.hx:26: characters 9-31
		$this->primary = $primary;
	}

	/**
	 * @param string $value
	 * @param string $family
	 * 
	 * @return void
	 */
	public function setType ($value, $family) {
		#coopy/SqlColumn.hx:30: characters 9-32
		$this->type_value = $value;
		#coopy/SqlColumn.hx:31: characters 9-34
		$this->type_family = $family;
	}

	/**
	 * @return string
	 */
	public function toString () {
		#coopy/SqlColumn.hx:43: characters 9-39
		return ((($this->primary ? "*" : ""))??'null') . ($this->name??'null');
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(SqlColumn::class, 'coopy.SqlColumn');
