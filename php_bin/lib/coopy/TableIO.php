<?php
/**
 */

namespace coopy;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Log;
use \sys\io\File;

/**
 *
 * System services for the daff command-line utility.
 *
 */
class TableIO {
	/**
	 * @return void
	 */
	public function __construct () {
	}

	/**
	 *
	 * @return the command-line arguments
	 *
	 * 
	 * @return string[]|\Array_hx
	 */
	public function args () {
		#coopy/TableIO.hx:70: characters 9-26
		return \Sys::args();
	}

	/**
	 *
	 * Execute a command.
	 * @param cmd the command to execute
	 * @param args the arguments to pass
	 * @return the return value of the command
	 *
	 * 
	 * @param string $cmd
	 * @param string[]|\Array_hx $args
	 * 
	 * @return int
	 */
	public function command ($cmd, $args) {
		#coopy/TableIO.hx:108: lines 108-112
		try {
			#coopy/TableIO.hx:109: characters 13-41
			return \Sys::command($cmd, $args);
		} catch(\Throwable $_g) {
			#coopy/TableIO.hx:111: characters 13-21
			return 1;
		}
	}

	/**
	 *
	 * Check if a file exists.
	 * @param path the name of the (putative) file
	 * @return true if the file exists
	 *
	 * 
	 * @param string $path
	 * 
	 * @return bool
	 */
	public function exists ($path) {
		#coopy/TableIO.hx:137: characters 16-43
		\clearstatcache(true, $path);
		return \file_exists($path);
	}

	/**
	 *
	 * Read a file.
	 * @param name the name of the file to read
	 * @return the content of the file
	 *
	 * 
	 * @param string $name
	 * 
	 * @return string
	 */
	public function getContent ($name) {
		#coopy/TableIO.hx:40: characters 9-44
		return File::getContent($name);
	}

	/**
	 *
	 * @return true if the platform has no built-in way to call a command
	 * synchronously i.e. IT IS (OLD) NODE
	 *
	 * 
	 * @return bool
	 */
	public function hasAsync () {
		#coopy/TableIO.hx:125: characters 9-21
		return false;
	}

	/**
	 *
	 * @return true if output is a TTY. Only trustworthy if isTtyKnown() is true.
	 *
	 * 
	 * @return bool
	 */
	public function isTty () {
		#coopy/TableIO.hx:169: characters 9-64
		if (\Sys::getEnv("GIT_PAGER_IN_USE") === "true") {
			#coopy/TableIO.hx:169: characters 53-64
			return true;
		}
		#coopy/TableIO.hx:170: characters 9-21
		return false;
	}

	/**
	 *
	 * @return true if we can determine whether the output is a TTY. This needs to be
	 * implemented natively, I haven't found a call for this in Haxe.
	 *
	 * 
	 * @return bool
	 */
	public function isTtyKnown () {
		#coopy/TableIO.hx:153: characters 9-21
		return false;
	}

	/**
	 *
	 * Try to open an sqlite database.
	 * @param path to the database
	 * @return opened database, or null on failure
	 *
	 * 
	 * @param string $path
	 * 
	 * @return SqlDatabase
	 */
	public function openSqliteDatabase ($path) {
		#coopy/TableIO.hx:185: characters 9-20
		return null;
	}

	/**
	 *
	 * Save a file.
	 * @param name the name of the file to save
	 * @param txt the content of the file
	 * @return true on success
	 *
	 * 
	 * @param string $name
	 * @param string $txt
	 * 
	 * @return bool
	 */
	public function saveContent ($name, $txt) {
		#coopy/TableIO.hx:56: characters 9-42
		File::saveContent($name, $txt);
		#coopy/TableIO.hx:57: characters 9-20
		return true;
	}

	/**
	 * @param string $html
	 * 
	 * @return void
	 */
	public function sendToBrowser ($html) {
		#coopy/TableIO.hx:200: characters 9-14
		(Log::$trace)("do not know how to send to browser in this language", new _HxAnon_TableIO0("coopy/TableIO.hx", 200, "coopy.TableIO", "sendToBrowser"));
	}

	/**
	 *
	 * Check if system services are in fact implemented.  For some
	 * platforms, an external implementation needs to be passed in.
	 *
	 * 
	 * @return bool
	 */
	public function valid () {
		#coopy/TableIO.hx:25: characters 9-20
		return true;
	}

	/**
	 *
	 * @param txt text to write to standard error stream
	 *
	 * 
	 * @param string $txt
	 * 
	 * @return void
	 */
	public function writeStderr ($txt) {
		#coopy/TableIO.hx:94: characters 9-38
		\Sys::stderr()->writeString($txt);
	}

	/**
	 *
	 * @param txt text to write to standard output stream
	 *
	 * 
	 * @param string $txt
	 * 
	 * @return void
	 */
	public function writeStdout ($txt) {
		#coopy/TableIO.hx:83: characters 9-38
		\Sys::stdout()->writeString($txt);
	}
}

class _HxAnon_TableIO0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

Boot::registerClass(TableIO::class, 'coopy.TableIO');
