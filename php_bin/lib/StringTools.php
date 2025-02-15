<?php
/**
 */

use \php\Boot;

/**
 * This class provides advanced methods on Strings. It is ideally used with
 * `using StringTools` and then acts as an [extension](https://haxe.org/manual/lf-static-extension.html)
 * to the `String` class.
 * If the first argument to any of the methods is null, the result is
 * unspecified.
 */
class StringTools {
	/**
	 * Concatenates `c` to `s` until `s.length` is at least `l`.
	 * If `c` is the empty String `""` or if `l` does not exceed `s.length`,
	 * `s` is returned unchanged.
	 * If `c.length` is 1, the resulting String length is exactly `l`.
	 * Otherwise the length may exceed `l`.
	 * If `c` is null, the result is unspecified.
	 * 
	 * @param string $s
	 * @param string $c
	 * @param int $l
	 * 
	 * @return string
	 */
	public static function lpad ($s, $c, $l) {
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:89: characters 3-26
		$cLength = mb_strlen($c);
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:90: characters 3-26
		$sLength = mb_strlen($s);
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:91: lines 91-92
		if (($cLength === 0) || ($sLength >= $l)) {
			#/usr/local/lib/haxe/std/php/_std/StringTools.hx:92: characters 4-12
			return $s;
		}
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:93: characters 3-31
		$padLength = $l - $sLength;
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:94: characters 3-50
		$padCount = (int)(($padLength / $cLength));
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:95: lines 95-100
		if ($padCount > 0) {
			#/usr/local/lib/haxe/std/php/_std/StringTools.hx:96: characters 4-106
			$result = str_pad($s, strlen($s) + $padCount * strlen($c), $c, STR_PAD_LEFT);
			#/usr/local/lib/haxe/std/php/_std/StringTools.hx:97: characters 11-80
			if (($padCount * $cLength) >= $padLength) {
				#/usr/local/lib/haxe/std/php/_std/StringTools.hx:97: characters 47-53
				return $result;
			} else {
				#/usr/local/lib/haxe/std/php/_std/StringTools.hx:97: characters 56-80
				return ($c . $result);
			}
		} else {
			#/usr/local/lib/haxe/std/php/_std/StringTools.hx:99: characters 4-30
			return ($c . $s);
		}
	}

	/**
	 * Replace all occurrences of the String `sub` in the String `s` by the
	 * String `by`.
	 * If `sub` is the empty String `""`, `by` is inserted after each character
	 * of `s` except the last one. If `by` is also the empty String `""`, `s`
	 * remains unchanged.
	 * If `sub` or `by` are null, the result is unspecified.
	 * 
	 * @param string $s
	 * @param string $sub
	 * @param string $by
	 * 
	 * @return string
	 */
	public static function replace ($s, $sub, $by) {
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:104: lines 104-106
		if ($sub === "") {
			#/usr/local/lib/haxe/std/php/_std/StringTools.hx:105: characters 4-89
			return implode($by, preg_split("//u", $s, -1, PREG_SPLIT_NO_EMPTY));
		}
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:107: characters 3-40
		return str_replace($sub, $by, $s);
	}

	/**
	 * Returns the character code at position `index` of String `s`, or an
	 * end-of-file indicator at if `position` equals `s.length`.
	 * This method is faster than `String.charCodeAt()` on some platforms, but
	 * the result is unspecified if `index` is negative or greater than
	 * `s.length`.
	 * This operation is not guaranteed to work if `s` contains the `\0`
	 * character.
	 * 
	 * @param string $s
	 * @param int $index
	 * 
	 * @return int
	 */
	public static function unsafeCodeAt ($s, $index) {
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:128: characters 3-76
		$char = ($index === 0 ? $s : mb_substr($s, $index, 1));
		#/usr/local/lib/haxe/std/php/_std/StringTools.hx:129: characters 10-30
		$code = ord($char[0]);
		if ($code < 192) {
			return $code;
		} else if ($code < 224) {
			return (($code - 192) << 6) + ord($char[1]) - 128;
		} else if ($code < 240) {
			return (($code - 224) << 12) + ((ord($char[1]) - 128) << 6) + ord($char[2]) - 128;
		} else {
			return (($code - 240) << 18) + ((ord($char[1]) - 128) << 12) + ((ord($char[2]) - 128) << 6) + ord($char[3]) - 128;
		}
	}
}

Boot::registerClass(StringTools::class, 'StringTools');
