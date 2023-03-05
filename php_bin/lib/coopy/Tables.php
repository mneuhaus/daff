<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

class Tables {
	/**
	 * @var Alignment
	 */
	public $alignment;
	/**
	 * @var string[]|\Array_hx
	 */
	public $table_order;
	/**
	 * @var StringMap
	 */
	public $tables;
	/**
	 * @var Table
	 */
	public $template;

	/**
	 * @param Table $template
	 * 
	 * @return void
	 */
	public function __construct ($template) {
		#coopy/Tables.hx:16: characters 9-33
		$this->template = $template;
		#coopy/Tables.hx:17: characters 9-46
		$this->tables = new StringMap();
		#coopy/Tables.hx:18: characters 9-47
		$this->table_order = new \Array_hx();
	}

	/**
	 * @param string $name
	 * 
	 * @return Table
	 */
	public function add ($name) {
		#coopy/Tables.hx:22: characters 9-34
		$t = $this->template->clone();
		#coopy/Tables.hx:23: characters 9-27
		$this->tables->data[$name] = $t;
		#coopy/Tables.hx:24: characters 9-31
		$_this = $this->table_order;
		$_this->arr[$_this->length++] = $name;
		#coopy/Tables.hx:25: characters 9-17
		return $t;
	}

	/**
	 * @param string $name
	 * 
	 * @return Table
	 */
	public function get ($name) {
		#coopy/Tables.hx:33: characters 16-32
		return ($this->tables->data[$name] ?? null);
	}

	/**
	 * @return string[]|\Array_hx
	 */
	public function getOrder () {
		#coopy/Tables.hx:29: characters 9-27
		return $this->table_order;
	}

	/**
	 * @return bool
	 */
	public function hasInsDel () {
		#coopy/Tables.hx:41: characters 9-42
		if ($this->alignment === null) {
			#coopy/Tables.hx:41: characters 30-42
			return false;
		}
		#coopy/Tables.hx:42: characters 9-48
		if ($this->alignment->has_addition) {
			#coopy/Tables.hx:42: characters 37-48
			return true;
		}
		#coopy/Tables.hx:43: characters 9-47
		if ($this->alignment->has_removal) {
			#coopy/Tables.hx:43: characters 36-47
			return true;
		}
		#coopy/Tables.hx:44: characters 9-21
		return false;
	}

	/**
	 * @return Table
	 */
	public function one () {
		#coopy/Tables.hx:37: characters 16-42
		return ($this->tables->data[($this->table_order->arr[0] ?? null)] ?? null);
	}
}

Boot::registerClass(Tables::class, 'coopy.Tables');
