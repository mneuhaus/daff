<?php
/**
 */

namespace haxe\ds;

use \php\Boot;
use \haxe\IMap;
use \php\_NativeIndexedArray\NativeIndexedArrayIterator;

/**
 * IntMap allows mapping of Int keys to arbitrary values.
 * See `Map` for documentation details.
 * @see https://haxe.org/manual/std-Map.html
 */
class IntMap implements IMap {
	/**
	 * @var mixed[]
	 */
	public $data;

	/**
	 * Creates a new IntMap.
	 * 
	 * @return void
	 */
	public function __construct () {
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:34: characters 10-34
		$this1 = [];
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:34: characters 3-34
		$this->data = $this1;
	}

	/**
	 * See `Map.iterator`
	 * (cs, java) Implementation detail: Do not `set()` any new value while
	 * iterating, as it may cause a resize, which will break iteration.
	 * 
	 * @return object
	 */
	public function iterator () {
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:64: characters 10-46
		return new NativeIndexedArrayIterator(\array_values($this->data));
	}

	/**
	 * See `Map.remove`
	 * 
	 * @param int $key
	 * 
	 * @return bool
	 */
	public function remove ($key) {
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:50: lines 50-53
		if (\array_key_exists($key, $this->data)) {
			#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:51: characters 4-27
			unset($this->data[$key]);
			#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:52: characters 4-15
			return true;
		}
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:55: characters 3-15
		return false;
	}

	/**
	 * See `Map.toString`
	 * 
	 * @return string
	 */
	public function toString () {
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:77: characters 15-32
		$this1 = [];
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:77: characters 3-33
		$parts = $this1;
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:78: lines 78-80
		$collection = $this->data;
		foreach ($collection as $key => $value) {
			#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:79: characters 4-60
			\array_push($parts, "" . ($key??'null') . " => " . \Std::string($value));
		}
		#/usr/local/lib/haxe/std/php/_std/haxe/ds/IntMap.hx:82: characters 3-49
		return "{" . (\implode(", ", $parts)??'null') . "}";
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(IntMap::class, 'haxe.ds.IntMap');
