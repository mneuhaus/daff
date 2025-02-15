<?php
/**
 */

use \php\Boot;
use \php\_Boot\HxClass;

/**
 * The Reflect API is a way to manipulate values dynamically through an
 * abstract interface in an untyped manner. Use with care.
 * @see https://haxe.org/manual/std-reflection.html
 */
class Reflect {
	/**
	 * Compares `a` and `b`.
	 * If `a` is less than `b`, the result is negative. If `b` is less than
	 * `a`, the result is positive. If `a` and `b` are equal, the result is 0.
	 * This function is only defined if `a` and `b` are of the same type.
	 * If that type is a function, the result is unspecified and
	 * `Reflect.compareMethods` should be used instead.
	 * For all other types, the result is 0 if `a` and `b` are equal. If they
	 * are not equal, the result depends on the type and is negative if:
	 * - Numeric types: a is less than b
	 * - String: a is lexicographically less than b
	 * - Other: unspecified
	 * If `a` and `b` are null, the result is 0. If only one of them is null,
	 * the result is unspecified.
	 * 
	 * @param mixed $a
	 * @param mixed $b
	 * 
	 * @return int
	 */
	public static function compare ($a, $b) {
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:128: lines 128-129
		if (Boot::equal($a, $b)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:129: characters 4-12
			return 0;
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:130: lines 130-134
		if (is_string($a)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:131: characters 4-40
			return strcmp($a, $b);
		} else if ($a > $b) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:133: characters 34-35
			return 1;
		} else {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:133: characters 38-40
			return -1;
		}
	}

	/**
	 * Returns the value of the field named `field` on object `o`.
	 * If `o` is not an object or has no field named `field`, the result is
	 * null.
	 * If the field is defined as a property, its accessors are ignored. Refer
	 * to `Reflect.getProperty` for a function supporting property accessors.
	 * If `field` is null, the result is unspecified.
	 * 
	 * @param mixed $o
	 * @param string $field
	 * 
	 * @return mixed
	 */
	public static function field ($o, $field) {
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:50: lines 50-52
		if (is_string($o)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:51: characters 24-45
			$tmp = Boot::dynamicString($o);
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:51: characters 4-53
			return $tmp->{$field};
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:53: lines 53-54
		if (!is_object($o)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:54: characters 4-15
			return null;
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:56: lines 56-58
		if (($field === "") && (PHP_VERSION_ID < 70100)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:57: characters 4-56
			return (((array)($o))[$field] ?? null);
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:60: lines 60-62
		if (property_exists($o, $field)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:61: characters 4-33
			return $o->{$field};
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:63: lines 63-65
		if (method_exists($o, $field)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:64: characters 4-44
			return Boot::getInstanceClosure($o, $field);
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:67: lines 67-78
		if (($o instanceof HxClass)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:68: characters 4-54
			$phpClassName = $o->phpClassName;
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:69: lines 69-71
			if (defined("" . ($phpClassName??'null') . "::" . ($field??'null'))) {
				#/usr/local/lib/haxe/std/php/_std/Reflect.hx:70: characters 5-52
				return constant("" . ($phpClassName??'null') . "::" . ($field??'null'));
			}
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:72: lines 72-74
			if (property_exists($phpClassName, $field)) {
				#/usr/local/lib/haxe/std/php/_std/Reflect.hx:73: characters 5-34
				return $o->{$field};
			}
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:75: lines 75-77
			if (method_exists($phpClassName, $field)) {
				#/usr/local/lib/haxe/std/php/_std/Reflect.hx:76: characters 5-54
				return Boot::getStaticClosure($phpClassName, $field);
			}
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:80: characters 3-14
		return null;
	}

	/**
	 * Returns the fields of structure `o`.
	 * This method is only guaranteed to work on anonymous structures. Refer to
	 * `Type.getInstanceFields` for a function supporting class instances.
	 * If `o` is null, the result is unspecified.
	 * 
	 * @param mixed $o
	 * 
	 * @return string[]|\Array_hx
	 */
	public static function fields ($o) {
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:117: lines 117-119
		if (is_object($o)) {
			#/usr/local/lib/haxe/std/php/_std/Reflect.hx:118: characters 4-77
			return \Array_hx::wrap(array_keys(get_object_vars($o)));
		}
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:120: characters 3-12
		return new \Array_hx();
	}

	/**
	 * Sets the field named `field` of object `o` to value `value`.
	 * If `o` has no field named `field`, this function is only guaranteed to
	 * work for anonymous structures.
	 * If `o` or `field` are null, the result is unspecified.
	 * 
	 * @param mixed $o
	 * @param string $field
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public static function setField ($o, $field, $value) {
		#/usr/local/lib/haxe/std/php/_std/Reflect.hx:84: characters 3-35
		$o->{$field} = $value;
	}
}

Boot::registerClass(Reflect::class, 'Reflect');
