<?php
/**
 */

namespace haxe\io;

use \php\Boot;
use \php\_Boot\HxEnum;

/**
 * String binary encoding supported by Haxe I/O
 */
class Encoding extends HxEnum {
	/**
	 * Output the string the way the platform represent it in memory. This is the most efficient but is platform-specific
	 * 
	 * @return Encoding
	 */
	static public function RawNative () {
		static $inst = null;
		if (!$inst) $inst = new Encoding('RawNative', 1, []);
		return $inst;
	}

	/**
	 * @return Encoding
	 */
	static public function UTF8 () {
		static $inst = null;
		if (!$inst) $inst = new Encoding('UTF8', 0, []);
		return $inst;
	}

	/**
	 * Returns array of (constructorIndex => constructorName)
	 *
	 * @return string[]
	 */
	static public function __hx__list () {
		return [
			1 => 'RawNative',
			0 => 'UTF8',
		];
	}

	/**
	 * Returns array of (constructorName => parametersCount)
	 *
	 * @return int[]
	 */
	static public function __hx__paramsCount () {
		return [
			'RawNative' => 0,
			'UTF8' => 0,
		];
	}
}

Boot::registerClass(Encoding::class, 'haxe.io.Encoding');
