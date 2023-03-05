<?php
/**
 */

namespace haxe;

use \php\Boot;
use \php\_Boot\HxString;

class SysTools {
	/**
	 * @var int[]|\Array_hx
	 * Character codes of the characters that will be escaped by `quoteWinArg(_, true)`.
	 */
	static public $winMetaCharacters;

	/**
	 * Returns a String that can be used as a single command line argument
	 * on Unix.
	 * The input will be quoted, or escaped if necessary.
	 * 
	 * @param string $argument
	 * 
	 * @return string
	 */
	public static function quoteUnixArg ($argument) {
		#/usr/local/lib/haxe/std/haxe/SysTools.hx:22: lines 22-23
		if ($argument === "") {
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:23: characters 4-15
			return "''";
		}
		#/usr/local/lib/haxe/std/haxe/SysTools.hx:25: lines 25-26
		if (!(new \EReg("[^a-zA-Z0-9_@%+=:,./-]", ""))->match($argument)) {
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:26: characters 4-19
			return $argument;
		}
		#/usr/local/lib/haxe/std/haxe/SysTools.hx:30: characters 3-67
		return "'" . (\StringTools::replace($argument, "'", "'\"'\"'")??'null') . "'";
	}

	/**
	 * Returns a String that can be used as a single command line argument
	 * on Windows.
	 * The input will be quoted, or escaped if necessary, such that the output
	 * will be parsed as a single argument using the rule specified in
	 * http://msdn.microsoft.com/en-us/library/ms880421
	 * Examples:
	 * ```haxe
	 * quoteWinArg("abc") == "abc";
	 * quoteWinArg("ab c") == '"ab c"';
	 * ```
	 * 
	 * @param string $argument
	 * @param bool $escapeMetaCharacters
	 * 
	 * @return string
	 */
	public static function quoteWinArg ($argument, $escapeMetaCharacters) {
		#/usr/local/lib/haxe/std/haxe/SysTools.hx:48: lines 48-90
		if (!(new \EReg("^[^ \x09\\\\\"]+\$", ""))->match($argument)) {
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:52: characters 4-33
			$result = new \StringBuf();
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:53: characters 4-98
			$needquote = (HxString::indexOf($argument, " ") !== -1) || (HxString::indexOf($argument, "\x09") !== -1) || ($argument === "");
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:55: lines 55-56
			if ($needquote) {
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:56: characters 5-20
				$result->add("\"");
			}
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:58: characters 4-33
			$bs_buf = new \StringBuf();
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:59: characters 14-18
			$_g = 0;
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:59: characters 18-33
			$_g1 = mb_strlen($argument);
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:59: lines 59-79
			while ($_g < $_g1) {
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:59: characters 14-33
				$i = $_g++;
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:60: characters 13-35
				$_g2 = HxString::charCodeAt($argument, $i);
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:60: lines 60-77
				if ($_g2 === null) {
					#/usr/local/lib/haxe/std/haxe/SysTools.hx:71: characters 11-16
					$c = $_g2;
					#/usr/local/lib/haxe/std/haxe/SysTools.hx:73: lines 73-76
					if (mb_strlen($bs_buf->b) > 0) {
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:74: characters 8-37
						$result->add($bs_buf->b);
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:75: characters 8-32
						$bs_buf = new \StringBuf();
					}
					#/usr/local/lib/haxe/std/haxe/SysTools.hx:77: characters 7-13
					$result1 = $result;
					#/usr/local/lib/haxe/std/haxe/SysTools.hx:77: characters 7-24
					$result1->b = ($result1->b??'null') . (\mb_chr($c)??'null');
				} else {
					#/usr/local/lib/haxe/std/haxe/SysTools.hx:60: characters 13-35
					if ($_g2 === 34) {
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:66: characters 7-34
						$bs = $bs_buf->b;
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:67: characters 7-21
						$result->add($bs);
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:68: characters 7-21
						$result->add($bs);
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:69: characters 7-31
						$bs_buf = new \StringBuf();
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:70: characters 7-24
						$result->add("\\\"");
					} else if ($_g2 === 92) {
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:63: characters 7-23
						$bs_buf->add("\\");
					} else {
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:71: characters 11-16
						$c1 = $_g2;
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:73: lines 73-76
						if (mb_strlen($bs_buf->b) > 0) {
							#/usr/local/lib/haxe/std/haxe/SysTools.hx:74: characters 8-37
							$result->add($bs_buf->b);
							#/usr/local/lib/haxe/std/haxe/SysTools.hx:75: characters 8-32
							$bs_buf = new \StringBuf();
						}
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:77: characters 7-13
						$result2 = $result;
						#/usr/local/lib/haxe/std/haxe/SysTools.hx:77: characters 7-24
						$result2->b = ($result2->b??'null') . (\mb_chr($c1)??'null');
					}
				}
			}
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:82: characters 4-33
			$result->add($bs_buf->b);
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:84: lines 84-87
			if ($needquote) {
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:85: characters 5-34
				$result->add($bs_buf->b);
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:86: characters 5-20
				$result->add("\"");
			}
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:89: characters 4-32
			$argument = $result->b;
		}
		#/usr/local/lib/haxe/std/haxe/SysTools.hx:92: lines 92-104
		if ($escapeMetaCharacters) {
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:93: characters 4-33
			$result = new \StringBuf();
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:94: characters 14-18
			$_g = 0;
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:94: characters 18-33
			$_g1 = mb_strlen($argument);
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:94: lines 94-100
			while ($_g < $_g1) {
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:94: characters 14-33
				$i = $_g++;
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:95: characters 5-36
				$c = HxString::charCodeAt($argument, $i);
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:96: lines 96-98
				if (SysTools::$winMetaCharacters->indexOf($c) >= 0) {
					#/usr/local/lib/haxe/std/haxe/SysTools.hx:97: characters 6-12
					$result1 = $result;
					#/usr/local/lib/haxe/std/haxe/SysTools.hx:97: characters 6-30
					$result1->b = ($result1->b??'null') . (\mb_chr(94)??'null');
				}
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:99: characters 5-11
				$result2 = $result;
				#/usr/local/lib/haxe/std/haxe/SysTools.hx:99: characters 5-22
				$result2->b = ($result2->b??'null') . (\mb_chr($c)??'null');
			}
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:101: characters 4-28
			return $result->b;
		} else {
			#/usr/local/lib/haxe/std/haxe/SysTools.hx:103: characters 4-19
			return $argument;
		}
	}

	/**
	 * @internal
	 * @access private
	 */
	static public function __hx__init ()
	{
		static $called = false;
		if ($called) return;
		$called = true;


		self::$winMetaCharacters = \Array_hx::wrap([
			32,
			40,
			41,
			37,
			33,
			94,
			34,
			60,
			62,
			38,
			124,
			10,
			13,
			44,
			59,
		]);
	}
}

Boot::registerClass(SysTools::class, 'haxe.SysTools');
SysTools::__hx__init();
