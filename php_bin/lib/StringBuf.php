<?php
/**
 */

use \php\Boot;

/**
 * A String buffer is an efficient way to build a big string by appending small
 * elements together.
 * Unlike String, an instance of StringBuf is not immutable in the sense that
 * it can be passed as argument to functions which modify it by appending more
 * values.
 */
class StringBuf {
	/**
	 * @var string
	 */
	public $b;

	/**
	 * Creates a new StringBuf instance.
	 * This may involve initialization of the internal buffer.
	 * 
	 * @return void
	 */
	public function __construct () {
		#/usr/local/lib/haxe/std/php/_std/StringBuf.hx:32: characters 3-9
		$this->b = "";
	}

	/**
	 * Appends the representation of `x` to `this` StringBuf.
	 * The exact representation of `x` may vary per platform. To get more
	 * consistent behavior, this function should be called with
	 * Std.string(x).
	 * If `x` is null, the String "null" is appended.
	 * 
	 * @param mixed $x
	 * 
	 * @return void
	 */
	public function add ($x) {
		#/usr/local/lib/haxe/std/php/_std/StringBuf.hx:40: lines 40-48
		if ($x === null) {
			#/usr/local/lib/haxe/std/php/_std/StringBuf.hx:41: characters 4-32
			$this->b = ($this->b . "null");
		} else if (is_bool($x)) {
			#/usr/local/lib/haxe/std/php/_std/StringBuf.hx:43: characters 4-60
			$this->b = ($this->b . ($x ? "true" : "false"));
		} else if (is_string($x)) {
			#/usr/local/lib/haxe/std/php/_std/StringBuf.hx:45: characters 4-32
			$this->b = ($this->b . $x);
		} else {
			#/usr/local/lib/haxe/std/php/_std/StringBuf.hx:47: characters 4-5
			$tmp = $this;
			#/usr/local/lib/haxe/std/php/_std/StringBuf.hx:47: characters 4-10
			$tmp->b = ($tmp->b??'null') . \Std::string($x);
		}
	}
}

Boot::registerClass(StringBuf::class, 'StringBuf');
