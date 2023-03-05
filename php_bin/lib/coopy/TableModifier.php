<?php
/**
 */

namespace coopy;

use \php\Boot;

class TableModifier {
	/**
	 * @var Table
	 */
	public $t;

	/**
	 * @param Table $t
	 * 
	 * @return void
	 */
	public function __construct ($t) {
		#coopy/TableModifier.hx:13: characters 9-19
		$this->t = $t;
	}

	/**
	 * @param int $at
	 * 
	 * @return bool
	 */
	public function removeColumn ($at) {
		#coopy/TableModifier.hx:17: characters 9-36
		$fate = new \Array_hx();
		#coopy/TableModifier.hx:18: characters 19-23
		$_g = 0;
		#coopy/TableModifier.hx:18: characters 23-30
		$_g1 = $this->t->get_width();
		#coopy/TableModifier.hx:18: lines 18-26
		while ($_g < $_g1) {
			#coopy/TableModifier.hx:18: characters 19-30
			$i = $_g++;
			#coopy/TableModifier.hx:19: lines 19-25
			if ($i < $at) {
				#coopy/TableModifier.hx:20: characters 17-29
				$fate->arr[$fate->length++] = $i;
			} else if ($i > $at) {
				#coopy/TableModifier.hx:22: characters 17-31
				$fate->arr[$fate->length++] = $i - 1;
			} else {
				#coopy/TableModifier.hx:24: characters 17-30
				$fate->arr[$fate->length++] = -1;
			}
		}
		#coopy/TableModifier.hx:27: characters 9-55
		return $this->t->insertOrDeleteColumns($fate, $this->t->get_width() - 1);
	}
}

Boot::registerClass(TableModifier::class, 'coopy.TableModifier');
