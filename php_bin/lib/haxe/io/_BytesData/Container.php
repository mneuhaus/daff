<?php
/**
 */

namespace haxe\io\_BytesData;

use \php\Boot;

class Container {
	/**
	 * @var mixed
	 */
	public $s;

	/**
	 * @param mixed $s
	 * 
	 * @return void
	 */
	public function __construct ($s) {
		#/usr/local/lib/haxe/std/php/_std/haxe/io/BytesData.hx:35: characters 3-13
		$this->s = $s;
	}
}

Boot::registerClass(Container::class, 'haxe.io._BytesData.Container');
