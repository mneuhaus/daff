<?php
/**
 */

namespace coopy;

use \php\Boot;
use \haxe\ds\StringMap;

/**
 *
 * A basic view implementation, for interpreting the content of cells.
 * Each supported language may have an optimized native implementation.
 * See the `View` interface for documentation.
 *
 */
class SimpleView implements View {
	/**
	 * @return void
	 */
	public function __construct () {
	}

	/**
	 * @param mixed $d1
	 * @param mixed $d2
	 * 
	 * @return bool
	 */
	public function equals ($d1, $d2) {
		#coopy/SimpleView.hx:25: characters 9-46
		if (($d1 === null) && ($d2 === null)) {
			#coopy/SimpleView.hx:25: characters 35-46
			return true;
		}
		#coopy/SimpleView.hx:26: characters 9-47
		if (($d1 === null) || ($d2 === null)) {
			#coopy/SimpleView.hx:26: characters 35-47
			return false;
		}
		#coopy/SimpleView.hx:27: characters 9-38
		return ("" . \Std::string($d1)) === ("" . \Std::string($d2));
	}

	/**
	 * @param mixed $t
	 * 
	 * @return Table
	 */
	public function getTable ($t) {
		#coopy/SimpleView.hx:71: characters 9-22
		return $t;
	}

	/**
	 * @param mixed $h
	 * @param string $str
	 * 
	 * @return bool
	 */
	public function hashExists ($h, $str) {
		#coopy/SimpleView.hx:48: characters 9-47
		$hh = $h;
		#coopy/SimpleView.hx:49: characters 9-30
		return \array_key_exists($str, $hh->data);
	}

	/**
	 * @param mixed $h
	 * @param string $str
	 * 
	 * @return mixed
	 */
	public function hashGet ($h, $str) {
		#coopy/SimpleView.hx:53: characters 9-47
		$hh = $h;
		#coopy/SimpleView.hx:54: characters 9-27
		return ($hh->data[$str] ?? null);
	}

	/**
	 * @param mixed $h
	 * @param string $str
	 * @param mixed $d
	 * 
	 * @return void
	 */
	public function hashSet(&$h, $str, $d) {
		#coopy/SimpleView.hx:43: characters 9-47
		$hh = $h;
		#coopy/SimpleView.hx:44: characters 9-22
		$hh->data[$str] = $d;
	}

	/**
	 * @param mixed $h
	 * 
	 * @return bool
	 */
	public function isHash ($h) {
		#coopy/SimpleView.hx:62: characters 9-43
		return ($h instanceof StringMap);
	}

	/**
	 * @param mixed $t
	 * 
	 * @return bool
	 */
	public function isTable ($t) {
		#coopy/SimpleView.hx:67: characters 9-31
		return ($t instanceof Table);
	}

	/**
	 * @return mixed
	 */
	public function makeHash () {
		#coopy/SimpleView.hx:39: characters 9-41
		return new StringMap();
	}

	/**
	 * @param mixed $x
	 * 
	 * @return mixed
	 */
	public function toDatum ($x) {
		#coopy/SimpleView.hx:34: characters 9-17
		return $x;
	}

	/**
	 * @param mixed $d
	 * 
	 * @return string
	 */
	public function toString ($d) {
		#coopy/SimpleView.hx:20: characters 9-31
		if ($d === null) {
			#coopy/SimpleView.hx:20: characters 22-31
			return "";
		}
		#coopy/SimpleView.hx:21: characters 9-22
		return "" . \Std::string($d);
	}

	/**
	 * @param Table $t
	 * 
	 * @return mixed
	 */
	public function wrapTable ($t) {
		#coopy/SimpleView.hx:75: characters 9-17
		return $t;
	}

	public function __toString() {
		return $this->toString();
	}
}

Boot::registerClass(SimpleView::class, 'coopy.SimpleView');
