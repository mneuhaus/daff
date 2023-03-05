<?php
/**
 */

use \php\Boot;
use \haxe\io\Output;
use \sys\io\FileOutput;
use \php\_Boot\HxString;
use \haxe\SysTools;

/**
 * This class provides access to various base functions of system platforms.
 * Look in the `sys` package for more system APIs.
 */
class Sys {
	/**
	 * Returns all the arguments that were passed in the command line.
	 * This does not include the interpreter or the name of the program file.
	 * (java)(eval) On Windows, non-ASCII Unicode arguments will not work correctly.
	 * (cs) Non-ASCII Unicode arguments will not work correctly.
	 * 
	 * @return string[]|\Array_hx
	 */
	public static function args () {
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:41: lines 41-45
		if (array_key_exists("argv", $_SERVER)) {
			#/usr/local/lib/haxe/std/php/_std/Sys.hx:42: characters 4-89
			return \Array_hx::wrap(array_slice($_SERVER["argv"], 1));
		} else {
			#/usr/local/lib/haxe/std/php/_std/Sys.hx:44: characters 4-13
			return new \Array_hx();
		}
	}

	/**
	 * Runs the given command. The command output will be printed to the same output as the current process.
	 * The current process will block until the command terminates.
	 * The return value is the exit code of the command (usually `0` indicates no error).
	 * Command arguments can be passed in two ways:
	 * 1. Using `args` to pass command arguments. Each argument will be automatically quoted and shell meta-characters will be escaped if needed.
	 * `cmd` should be an executable name that can be located in the `PATH` environment variable, or a full path to an executable.
	 * 2. When `args` is not given or is `null`, command arguments can be appended to `cmd`. No automatic quoting/escaping will be performed. `cmd` should be formatted exactly as it would be when typed at the command line.
	 * It can run executables, as well as shell commands that are not executables (e.g. on Windows: `dir`, `cd`, `echo` etc).
	 * Use the `sys.io.Process` API for more complex tasks, such as background processes, or providing input to the command.
	 * 
	 * @param string $cmd
	 * @param string[]|\Array_hx $args
	 * 
	 * @return int
	 */
	public static function command ($cmd, $args = null) {
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:85: lines 85-95
		if ($args !== null) {
			#/usr/local/lib/haxe/std/php/_std/Sys.hx:86: characters 12-24
			if (Sys::systemName() === "Windows") {
				#/usr/local/lib/haxe/std/php/_std/Sys.hx:88: lines 88-91
				$_g = new \Array_hx();
				#/usr/local/lib/haxe/std/php/_std/Sys.hx:89: lines 89-90
				$_g1 = 0;
				$_g2 = (\Array_hx::wrap([\StringTools::replace($cmd, "/", "\\")]))->concat($args);
				while ($_g1 < $_g2->length) {
					#/usr/local/lib/haxe/std/php/_std/Sys.hx:89: characters 12-13
					$a = ($_g2->arr[$_g1] ?? null);
					#/usr/local/lib/haxe/std/php/_std/Sys.hx:89: lines 89-90
					++$_g1;
					#/usr/local/lib/haxe/std/php/_std/Sys.hx:90: characters 8-37
					$x = SysTools::quoteWinArg($a, true);
					$_g->arr[$_g->length++] = $x;
				}
				#/usr/local/lib/haxe/std/php/_std/Sys.hx:88: characters 6-9
				$cmd = $_g->join(" ");
			} else {
				#/usr/local/lib/haxe/std/php/_std/Sys.hx:93: characters 12-57
				$_this = (\Array_hx::wrap([$cmd]))->concat($args);
				$f = Boot::getStaticClosure(SysTools::class, 'quoteUnixArg');
				$result = [];
				$data = $_this->arr;
				$_g_current = 0;
				$_g_length = count($data);
				$_g_data = $data;
				while ($_g_current < $_g_length) {
					$item = $_g_data[$_g_current++];
					$result[] = $f($item);
				}
				#/usr/local/lib/haxe/std/php/_std/Sys.hx:93: characters 6-9
				$cmd = \Array_hx::wrap($result)->join(" ");
			}
		}
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:96: characters 3-30
		$result = Boot::deref(0);
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:97: characters 3-29
		system($cmd, $result);
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:98: characters 3-16
		return $result;
	}

	/**
	 * Returns the value of the given environment variable, or `null` if it
	 * doesn't exist.
	 * 
	 * @param string $s
	 * 
	 * @return string
	 */
	public static function getEnv ($s) {
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:49: characters 3-32
		$value = getenv($s);
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:50: characters 10-39
		if ($value === false) {
			#/usr/local/lib/haxe/std/php/_std/Sys.hx:50: characters 27-31
			return null;
		} else {
			#/usr/local/lib/haxe/std/php/_std/Sys.hx:50: characters 34-39
			return $value;
		}
	}

	/**
	 * Returns the standard error of the process, to which program errors can be written.
	 * 
	 * @return Output
	 */
	public static function stderr () {
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:143: characters 3-87
		$p = (defined("STDERR") ? STDERR : fopen("php://stderr", "w"));
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:144: characters 3-43
		return new FileOutput($p);
	}

	/**
	 * Returns the standard output of the process, to which program output can be written.
	 * 
	 * @return Output
	 */
	public static function stdout () {
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:138: characters 3-87
		$p = (defined("STDOUT") ? STDOUT : fopen("php://stdout", "w"));
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:139: characters 3-43
		return new FileOutput($p);
	}

	/**
	 * Returns the type of the current system. Possible values are:
	 * - `"Windows"`
	 * - `"Linux"`
	 * - `"BSD"`
	 * - `"Mac"`
	 * 
	 * @return string
	 */
	public static function systemName () {
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:79: characters 3-33
		$s = php_uname("s");
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:80: characters 3-26
		$p = HxString::indexOf($s, " ");
		#/usr/local/lib/haxe/std/php/_std/Sys.hx:81: characters 10-39
		if ($p >= 0) {
			#/usr/local/lib/haxe/std/php/_std/Sys.hx:81: characters 20-34
			return mb_substr($s, 0, $p);
		} else {
			#/usr/local/lib/haxe/std/php/_std/Sys.hx:81: characters 37-38
			return $s;
		}
	}
}

Boot::registerClass(Sys::class, 'Sys');
