<?php
/**
 */

use \php\Boot;

/**
 * This class defines mathematical functions and constants.
 * @see https://haxe.org/manual/std-math.html
 */
class Math {
	/**
	 * @var float
	 * A special `Float` constant which denotes an invalid number.
	 * `NaN` stands for "Not a Number". It occurs when a mathematically incorrect
	 * operation is executed, such as taking the square root of a negative
	 * number: `Math.sqrt(-1)`.
	 * All further operations with `NaN` as an operand will result in `NaN`.
	 * If this constant is converted to an `Int`, e.g. through `Std.int()`, the
	 * result is unspecified.
	 * In order to test if a value is `NaN`, you should use `Math.isNaN()` function.
	 */
	static public $NaN;


	/**
	 * @internal
	 * @access private
	 */
	static public function __hx__init ()
	{
		static $called = false;
		if ($called) return;
		$called = true;


		self::$NaN = NAN;
	}
}

Boot::registerClass(Math::class, 'Math');
Math::__hx__init();
