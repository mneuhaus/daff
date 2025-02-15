<?php
/**
 */

namespace haxe\ds;

use \php\Boot;
use \haxe\IMap;
use \php\_NativeIndexedArray\NativeIndexedArrayIterator;

/**
 * ObjectMap allows mapping of object keys to arbitrary values.
 * On static targets, the keys are considered to be strong references. Refer
 * to `haxe.ds.WeakMap` for a weak reference version.
 * See `Map` for documentation details.
 * @see https://haxe.org/manual/std-Map.html
 */
class ObjectMap implements IMap {
	/**
	 * @var array
	 */
	public $_keys;
	/**
	 * @var array
	 */
	public $_values;

	/**
	 * Creates a new ObjectMap.
	 * 
	 * @return void
	 */
	public function __construct () {
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/ObjectMap.hx:33: characters 11-33
		$this1 = [];
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/ObjectMap.hx:33: characters 3-33
		$this->_keys = $this1;
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/ObjectMap.hx:34: characters 13-35
		$this1 = [];
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/ObjectMap.hx:34: characters 3-35
		$this->_values = $this1;
	}

	/**
	 * See `Map.iterator`
	 * (cs, java) Implementation detail: Do not `set()` any new value while
	 * iterating, as it may cause a resize, which will break iteration.
	 * 
	 * @return object
	 */
	public function iterator () {
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/ObjectMap.hx:68: characters 10-28
		return new NativeIndexedArrayIterator(\array_values($this->_values));
	}
}

Boot::registerClass(ObjectMap::class, 'haxe.ds.ObjectMap');
