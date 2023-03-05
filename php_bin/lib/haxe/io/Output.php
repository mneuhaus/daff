<?php
/**
 */

namespace haxe\io;

use \haxe\io\_BytesData\Container;
use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Exception;
use \haxe\exceptions\NotImplementedException;

/**
 * An Output is an abstract write. A specific output implementation will only
 * have to override the `writeByte` and maybe the `write`, `flush` and `close`
 * methods. See `File.write` and `String.write` for two ways of creating an
 * Output.
 */
class Output {
	/**
	 * Write one byte.
	 * 
	 * @param int $c
	 * 
	 * @return void
	 */
	public function writeByte ($c) {
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:47: characters 3-8
		throw new NotImplementedException(null, null, new _HxAnon_Output0("haxe/io/Output.hx", 47, "haxe.io.Output", "writeByte"));
	}

	/**
	 * Write `len` bytes from `s` starting by position specified by `pos`.
	 * Returns the actual length of written data that can differ from `len`.
	 * See `writeFullBytes` that tries to write the exact amount of specified bytes.
	 * 
	 * @param Bytes $s
	 * @param int $pos
	 * @param int $len
	 * 
	 * @return int
	 */
	public function writeBytes ($s, $pos, $len) {
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:59: lines 59-60
		if (($pos < 0) || ($len < 0) || (($pos + $len) > $s->length)) {
			#/usr/local/lib/haxe/std/haxe/io/Output.hx:60: characters 4-9
			throw Exception::thrown(Error::OutsideBounds());
		}
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:62: characters 3-61
		$b = $s->b;
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:63: characters 3-15
		$k = $len;
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:64: lines 64-78
		while ($k > 0) {
			#/usr/local/lib/haxe/std/haxe/io/Output.hx:68: characters 4-25
			$this->writeByte(\ord($b->s[$pos]));
			#/usr/local/lib/haxe/std/haxe/io/Output.hx:76: characters 4-9
			++$pos;
			#/usr/local/lib/haxe/std/haxe/io/Output.hx:77: characters 4-7
			--$k;
		}
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:79: characters 3-13
		return $len;
	}

	/**
	 * Write `len` bytes from `s` starting by position specified by `pos`.
	 * Unlike `writeBytes`, this method tries to write the exact `len` amount of bytes.
	 * 
	 * @param Bytes $s
	 * @param int $pos
	 * @param int $len
	 * 
	 * @return void
	 */
	public function writeFullBytes ($s, $pos, $len) {
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:121: lines 121-125
		while ($len > 0) {
			#/usr/local/lib/haxe/std/haxe/io/Output.hx:122: characters 4-36
			$k = $this->writeBytes($s, $pos, $len);
			#/usr/local/lib/haxe/std/haxe/io/Output.hx:123: characters 4-12
			$pos += $k;
			#/usr/local/lib/haxe/std/haxe/io/Output.hx:124: characters 4-12
			$len -= $k;
		}
	}

	/**
	 * Write `s` string.
	 * 
	 * @param string $s
	 * @param Encoding $encoding
	 * 
	 * @return void
	 */
	public function writeString ($s, $encoding = null) {
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:282: characters 11-38
		$b = \strlen($s);
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:282: characters 3-39
		$b1 = new Bytes($b, new Container($s));
		#/usr/local/lib/haxe/std/haxe/io/Output.hx:284: characters 3-33
		$this->writeFullBytes($b1, 0, $b1->length);
	}
}

class _HxAnon_Output0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

Boot::registerClass(Output::class, 'haxe.io.Output');
