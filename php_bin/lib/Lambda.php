<?php
/**
 */

use \php\Boot;

/**
 * The `Lambda` class is a collection of methods to support functional
 * programming. It is ideally used with `using Lambda` and then acts as an
 * extension to Iterable types.
 * On static platforms, working with the Iterable structure might be slower
 * than performing the operations directly on known types, such as Array and
 * List.
 * If the first argument to any of the methods is null, the result is
 * unspecified.
 * @see https://haxe.org/manual/std-Lambda.html
 */
class Lambda {
	/**
	 * Creates an Array from Iterable `it`.
	 * If `it` is an Array, this function returns a copy of it.
	 * 
	 * @param object $it
	 * 
	 * @return mixed[]|\Array_hx
	 */
	public static function array ($it) {
		#/usr/local/lib/haxe/std/Lambda.hx:46: characters 3-26
		$a = new \Array_hx();
		#/usr/local/lib/haxe/std/Lambda.hx:47: characters 13-15
		$i = $it->iterator();
		while ($i->hasNext()) {
			#/usr/local/lib/haxe/std/Lambda.hx:47: lines 47-48
			$i1 = $i->next();
			#/usr/local/lib/haxe/std/Lambda.hx:48: characters 4-13
			$a->arr[$a->length++] = $i1;
		}
		#/usr/local/lib/haxe/std/Lambda.hx:49: characters 3-11
		return $a;
	}

	/**
	 * Tells if `it` contains `elt`.
	 * This function returns true as soon as an element is found which is equal
	 * to `elt` according to the `==` operator.
	 * If no such element is found, the result is false.
	 * 
	 * @param object $it
	 * @param mixed $elt
	 * 
	 * @return bool
	 */
	public static function has ($it, $elt) {
		#/usr/local/lib/haxe/std/Lambda.hx:109: characters 13-15
		$x = $it->iterator();
		while ($x->hasNext()) {
			#/usr/local/lib/haxe/std/Lambda.hx:109: lines 109-111
			$x1 = $x->next();
			#/usr/local/lib/haxe/std/Lambda.hx:110: lines 110-111
			if (Boot::equal($x1, $elt)) {
				#/usr/local/lib/haxe/std/Lambda.hx:111: characters 5-16
				return true;
			}
		}
		#/usr/local/lib/haxe/std/Lambda.hx:112: characters 3-15
		return false;
	}
}

Boot::registerClass(Lambda::class, 'Lambda');
