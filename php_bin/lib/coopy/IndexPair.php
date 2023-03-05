<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * An index of rows in two tables. We add a list of columns to use
 * as a key. Rows in the two tables that have the same key are
 * treated as matches. Good indexes have distinct keys within a
 * table, and keys that match (ideally just once) across tables.
 *
 */
class IndexPair {
	/**
	 * @var CompareFlags
	 */
	public $flags;
	/**
	 * @var int
	 */
	public $hdr;
	/**
	 * @var Index
	 */
	public $ia;
	/**
	 * @var Index
	 */
	public $ib;
	/**
	 * @var float
	 */
	public $quality;

	/**
	 * @param CompareFlags $flags
	 * 
	 * @return void
	 */
	public function __construct ($flags) {
		#coopy/IndexPair.hx:23: characters 9-27
		$this->flags = $flags;
		#coopy/IndexPair.hx:24: characters 9-30
		$this->ia = new Index($flags);
		#coopy/IndexPair.hx:25: characters 9-30
		$this->ib = new Index($flags);
		#coopy/IndexPair.hx:26: characters 9-20
		$this->quality = 0;
		#coopy/IndexPair.hx:27: characters 9-16
		$this->hdr = 0;
	}

	/**
	 *
	 * Add a column in each table to treat as part of a key.
	 * Fine to call repeatedly.
	 *
	 * @param ca column in first table
	 * @param cb column in second table
	 *
	 * 
	 * @param int $ca
	 * @param int $cb
	 * 
	 * @return void
	 */
	public function addColumns ($ca, $cb) {
		#coopy/IndexPair.hx:40: characters 9-25
		$this->ia->addColumn($ca);
		#coopy/IndexPair.hx:41: characters 9-25
		$this->ib->addColumn($cb);
	}

	/**
	 *
	 * Get a measure of the quality of this index pair.  Higher values
	 * are better.
	 *
	 * @return index quality
	 *
	 * 
	 * @return float
	 */
	public function getQuality () {
		#coopy/IndexPair.hx:158: characters 9-23
		return $this->quality;
	}

	/**
	 *
	 * Get the highest number of key collisions for any given key
	 * within an individual table.  High numbers of collisions are
	 * a bad sign.
	 *
	 * @return frequency of key collisions
	 *
	 * 
	 * @return int
	 */
	public function getTopFreq () {
		#coopy/IndexPair.hx:145: characters 9-56
		if ($this->ib->top_freq > $this->ia->top_freq) {
			#coopy/IndexPair.hx:145: characters 38-56
			return $this->ib->top_freq;
		}
		#coopy/IndexPair.hx:146: characters 9-27
		return $this->ia->top_freq;
	}

	/**
	 *
	 * Go ahead and index all the rows in the given tables.
	 * Make sure to call `addColumns` first.
	 *
	 * @param a the first reference table
	 * @param a the second table
	 *
	 * 
	 * @param Table $a
	 * @param Table $b
	 * @param int $hdr
	 * 
	 * @return void
	 */
	public function indexTables ($a, $b, $hdr) {
		#coopy/IndexPair.hx:54: characters 9-29
		$this->ia->indexTable($a, $hdr);
		#coopy/IndexPair.hx:55: characters 9-29
		$this->ib->indexTable($b, $hdr);
		#coopy/IndexPair.hx:56: characters 9-17
		$this->hdr = $hdr;
		#coopy/IndexPair.hx:60: characters 9-28
		$good = 0;
		#coopy/IndexPair.hx:61: characters 21-36
		$data = \array_values(\array_map("strval", \array_keys($this->ia->items->data)));
		$key_current = 0;
		$key_length = \count($data);
		$key_data = $data;
		while ($key_current < $key_length) {
			#coopy/IndexPair.hx:61: lines 61-70
			$key = $key_data[$key_current++];
			#coopy/IndexPair.hx:62: characters 13-56
			$item_a = ($this->ia->items->data[$key] ?? null);
			#coopy/IndexPair.hx:63: characters 13-48
			$spot_a = $item_a->lst->length;
			#coopy/IndexPair.hx:64: characters 13-56
			$item_b = ($this->ib->items->data[$key] ?? null);
			#coopy/IndexPair.hx:65: characters 13-34
			$spot_b = 0;
			#coopy/IndexPair.hx:66: characters 13-55
			if ($item_b !== null) {
				#coopy/IndexPair.hx:66: characters 31-37
				$spot_b = $item_b->lst->length;
			}
			#coopy/IndexPair.hx:67: lines 67-69
			if (($spot_a === 1) && ($spot_b === 1)) {
				#coopy/IndexPair.hx:68: characters 17-23
				++$good;
			}
		}
		#coopy/IndexPair.hx:71: characters 24-46
		$b = $a->get_height();
		#coopy/IndexPair.hx:71: characters 9-16
		$this->quality = $good / ((\is_nan(1.0) || \is_nan($b) ? \Math::$NaN : \max(1.0, $b)));
	}

	/**
	 *
	 * Get the key of a row in the first (local) table.
	 *
	 * @param row the row to get a key for
	 * @return the key
	 *
	 * 
	 * @param int $row
	 * 
	 * @return string
	 */
	public function localKey ($row) {
		#coopy/IndexPair.hx:120: characters 9-43
		return $this->ia->toKey($this->ia->getTable(), $row);
	}

	/**
	 *
	 * Find matches for a given row.
	 *
	 * @return match information
	 *
	 * 
	 * @param Row $row
	 * 
	 * @return CrossMatch
	 */
	public function queryByContent ($row) {
		#coopy/IndexPair.hx:94: characters 9-52
		$result = new CrossMatch();
		#coopy/IndexPair.hx:95: characters 9-50
		$ka = $this->ia->toKeyByContent($row);
		#coopy/IndexPair.hx:96: characters 9-30
		return $this->queryByKey($ka);
	}

	/**
	 * @param string $ka
	 * 
	 * @return CrossMatch
	 */
	public function queryByKey ($ka) {
		#coopy/IndexPair.hx:75: characters 9-52
		$result = new CrossMatch();
		#coopy/IndexPair.hx:76: characters 9-41
		$result->item_a = ($this->ia->items->data[$ka] ?? null);
		#coopy/IndexPair.hx:77: characters 9-41
		$result->item_b = ($this->ib->items->data[$ka] ?? null);
		#coopy/IndexPair.hx:78: characters 9-42
		$result->spot_a = $result->spot_b = 0;
		#coopy/IndexPair.hx:79: lines 79-82
		if ($ka !== "") {
			#coopy/IndexPair.hx:80: characters 13-76
			if ($result->item_a !== null) {
				#coopy/IndexPair.hx:80: characters 38-76
				$result->spot_a = $result->item_a->lst->length;
			}
			#coopy/IndexPair.hx:81: characters 13-76
			if ($result->item_b !== null) {
				#coopy/IndexPair.hx:81: characters 38-76
				$result->spot_b = $result->item_b->lst->length;
			}
		}
		#coopy/IndexPair.hx:83: characters 9-22
		return $result;
	}

	/**
	 *
	 * Find matches for a given row in the first (local) table.
	 *
	 * @return match information
	 *
	 * 
	 * @param int $row
	 * 
	 * @return CrossMatch
	 */
	public function queryLocal ($row) {
		#coopy/IndexPair.hx:107: characters 9-55
		$ka = $this->ia->toKey($this->ia->getTable(), $row);
		#coopy/IndexPair.hx:108: characters 9-30
		return $this->queryByKey($ka);
	}

	/**
	 *
	 * Get the key of a row in the second (remote) table.
	 *
	 * @param row the row to get a key for
	 * @return the key
	 *
	 * 
	 * @param int $row
	 * 
	 * @return string
	 */
	public function remoteKey ($row) {
		#coopy/IndexPair.hx:132: characters 9-43
		return $this->ib->toKey($this->ib->getTable(), $row);
	}
}

Boot::registerClass(IndexPair::class, 'coopy.IndexPair');
