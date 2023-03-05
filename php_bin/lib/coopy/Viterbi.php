<?php
/**
 */

namespace coopy;

use \php\_Boot\HxAnon;
use \php\Boot;
use \haxe\Log;

/**
 *
 * Do a Viterbi lattice calculation to calculate the optimum state
 * to be in at each step of a sequence, given the costs of
 * transitions between those states at each step.
 *
 */
class Viterbi {
	/**
	 * @var int
	 */
	public $K;
	/**
	 * @var int
	 */
	public $T;
	/**
	 * @var float
	 */
	public $best_cost;
	/**
	 * @var SparseSheet
	 */
	public $cost;
	/**
	 * @var int
	 */
	public $index;
	/**
	 * @var int
	 */
	public $mode;
	/**
	 * @var SparseSheet
	 */
	public $path;
	/**
	 * @var bool
	 */
	public $path_valid;
	/**
	 * @var SparseSheet
	 */
	public $src;

	/**
	 * @return void
	 */
	public function __construct () {
		#coopy/Viterbi.hx:27: characters 9-18
		$this->K = $this->T = 0;
		#coopy/Viterbi.hx:28: characters 9-16
		$this->reset();
		#coopy/Viterbi.hx:29: characters 9-40
		$this->cost = new SparseSheet();
		#coopy/Viterbi.hx:30: characters 9-37
		$this->src = new SparseSheet();
		#coopy/Viterbi.hx:31: characters 9-38
		$this->path = new SparseSheet();
	}

	/**
	 *
	 * For the current step in the sequence, we assert that
	 * transitioning from state `s0` to state `s1` would cost `c`.
	 *
	 * 
	 * @param int $s0
	 * @param int $s1
	 * @param float $c
	 * 
	 * @return void
	 */
	public function addTransition ($s0, $s1, $c) {
		#coopy/Viterbi.hx:74: characters 9-35
		$resize = false;
		#coopy/Viterbi.hx:75: lines 75-78
		if ($s0 >= $this->K) {
			#coopy/Viterbi.hx:76: characters 13-21
			$this->K = $s0 + 1;
			#coopy/Viterbi.hx:77: characters 13-26
			$resize = true;
		}
		#coopy/Viterbi.hx:79: lines 79-82
		if ($s1 >= $this->K) {
			#coopy/Viterbi.hx:80: characters 13-21
			$this->K = $s1 + 1;
			#coopy/Viterbi.hx:81: characters 13-26
			$resize = true;
		}
		#coopy/Viterbi.hx:83: lines 83-87
		if ($resize) {
			#coopy/Viterbi.hx:84: characters 13-45
			$this->cost->nonDestructiveResize($this->K, $this->T, 0);
			#coopy/Viterbi.hx:85: characters 13-45
			$this->src->nonDestructiveResize($this->K, $this->T, -1);
			#coopy/Viterbi.hx:86: characters 13-46
			$this->path->nonDestructiveResize(1, $this->T, -1);
		}
		#coopy/Viterbi.hx:88: characters 9-27
		$this->path_valid = false;
		#coopy/Viterbi.hx:89: characters 9-22
		$this->assertMode(1);
		#coopy/Viterbi.hx:90: lines 90-95
		if ($this->index >= $this->T) {
			#coopy/Viterbi.hx:91: characters 13-22
			$this->T = $this->index + 1;
			#coopy/Viterbi.hx:92: characters 13-45
			$this->cost->nonDestructiveResize($this->K, $this->T, 0);
			#coopy/Viterbi.hx:93: characters 13-45
			$this->src->nonDestructiveResize($this->K, $this->T, -1);
			#coopy/Viterbi.hx:94: characters 13-46
			$this->path->nonDestructiveResize(1, $this->T, -1);
		}
		#coopy/Viterbi.hx:96: characters 9-36
		$sourced = false;
		#coopy/Viterbi.hx:97: lines 97-102
		if ($this->index > 0) {
			#coopy/Viterbi.hx:98: characters 13-38
			$c += $this->cost->get($s0, $this->index - 1);
			#coopy/Viterbi.hx:99: characters 13-48
			$sourced = $this->src->get($s0, $this->index - 1) !== -1;
		} else {
			#coopy/Viterbi.hx:101: characters 13-27
			$sourced = true;
		}
		#coopy/Viterbi.hx:104: lines 104-109
		if ($sourced) {
			#coopy/Viterbi.hx:105: lines 105-108
			if (($c < $this->cost->get($s1, $this->index)) || ($this->src->get($s1, $this->index) === -1)) {
				#coopy/Viterbi.hx:106: characters 17-37
				$this->cost->set($s1, $this->index, $c);
				#coopy/Viterbi.hx:107: characters 17-37
				$this->src->set($s1, $this->index, $s0);
			}
		}
	}

	/**
	 * @param int $next
	 * 
	 * @return void
	 */
	public function assertMode ($next) {
		#coopy/Viterbi.hx:63: characters 9-38
		if (($next === 0) && ($this->mode === 1)) {
			#coopy/Viterbi.hx:63: characters 31-38
			$this->index++;
		}
		#coopy/Viterbi.hx:64: characters 9-20
		$this->mode = $next;
	}

	/**
	 *
	 * Begin one individual step in the sequence.
	 * After this, we call `addTransition` for every possible state
	 * transition, and then `endTransitions`.
	 * Then we repeat the cycle for the next step in the sequence,
	 * or call `calculatePath`.
	 *
	 * 
	 * @return void
	 */
	public function beginTransitions () {
		#coopy/Viterbi.hx:135: characters 9-27
		$this->path_valid = false;
		#coopy/Viterbi.hx:136: characters 9-22
		$this->assertMode(1);
	}

	/**
	 *
	 * Compute the best state sequence.
	 *
	 * 
	 * @return void
	 */
	public function calculatePath () {
		#coopy/Viterbi.hx:145: characters 9-31
		if ($this->path_valid) {
			#coopy/Viterbi.hx:145: characters 25-31
			return;
		}
		#coopy/Viterbi.hx:146: characters 9-25
		$this->endTransitions();
		#coopy/Viterbi.hx:147: characters 9-30
		$best = 0;
		#coopy/Viterbi.hx:148: characters 9-30
		$bestj = -1;
		#coopy/Viterbi.hx:149: lines 149-153
		if ($this->index <= 0) {
			#coopy/Viterbi.hx:151: characters 13-30
			$this->path_valid = true;
			#coopy/Viterbi.hx:152: characters 13-19
			return;
		}
		#coopy/Viterbi.hx:154: characters 19-23
		$_g = 0;
		#coopy/Viterbi.hx:154: characters 23-24
		$_g1 = $this->K;
		#coopy/Viterbi.hx:154: lines 154-160
		while ($_g < $_g1) {
			#coopy/Viterbi.hx:154: characters 19-24
			$j = $_g++;
			#coopy/Viterbi.hx:155: lines 155-159
			if ((($this->cost->get($j, $this->index - 1) < $best) || ($bestj === -1)) && ($this->src->get($j, $this->index - 1) !== -1)) {
				#coopy/Viterbi.hx:157: characters 17-43
				$best = $this->cost->get($j, $this->index - 1);
				#coopy/Viterbi.hx:158: characters 17-26
				$bestj = $j;
			}
		}
		#coopy/Viterbi.hx:161: characters 9-25
		$this->best_cost = $best;
		#coopy/Viterbi.hx:163: characters 19-23
		$_g = 0;
		#coopy/Viterbi.hx:163: characters 23-28
		$_g1 = $this->index;
		#coopy/Viterbi.hx:163: lines 163-170
		while ($_g < $_g1) {
			#coopy/Viterbi.hx:163: characters 19-28
			$j = $_g++;
			#coopy/Viterbi.hx:164: characters 13-37
			$i = $this->index - 1 - $j;
			#coopy/Viterbi.hx:165: characters 13-32
			$this->path->set(0, $i, $bestj);
			#coopy/Viterbi.hx:166: lines 166-168
			if (!(($bestj !== -1) && (($bestj >= 0) && ($bestj < $this->K)))) {
				#coopy/Viterbi.hx:167: characters 17-22
				(Log::$trace)("Problem in Viterbi", new _HxAnon_Viterbi0("coopy/Viterbi.hx", 167, "coopy.Viterbi", "calculatePath"));
			}
			#coopy/Viterbi.hx:169: characters 13-37
			$bestj = $this->src->get($bestj, $i);
		}
		#coopy/Viterbi.hx:171: characters 9-26
		$this->path_valid = true;
	}

	/**
	 *
	 * Declare that we are finished asserting possible state transitions
	 * for the current step in the sequence.  After this, we either
	 * call `beginTransitions` again for the next step, or call
	 * `calculatePath`.
	 *
	 * 
	 * @return void
	 */
	public function endTransitions () {
		#coopy/Viterbi.hx:121: characters 9-27
		$this->path_valid = false;
		#coopy/Viterbi.hx:122: characters 9-22
		$this->assertMode(0);
	}

	/**
	 *
	 * @param the step in the sequence
	 * @return the optimal state for that step
	 *
	 * 
	 * @param int $i
	 * 
	 * @return int
	 */
	public function get ($i) {
		#coopy/Viterbi.hx:213: characters 9-24
		$this->calculatePath();
		#coopy/Viterbi.hx:214: characters 9-29
		return $this->path->get(0, $i);
	}

	/**
	 *
	 * @return the total cost of the optimal state sequence
	 *
	 * 
	 * @return float
	 */
	public function getCost () {
		#coopy/Viterbi.hx:223: characters 9-24
		$this->calculatePath();
		#coopy/Viterbi.hx:224: characters 9-25
		return $this->best_cost;
	}

	/**
	 *
	 * @return the length of the optimal state sequence
	 *
	 * 
	 * @return int
	 */
	public function length () {
		#coopy/Viterbi.hx:200: lines 200-202
		if ($this->index > 0) {
			#coopy/Viterbi.hx:201: characters 13-28
			$this->calculatePath();
		}
		#coopy/Viterbi.hx:203: characters 9-21
		return $this->index;
	}

	/**
	 *
	 * Reset the state to its initial value.
	 *
	 * 
	 * @return void
	 */
	public function reset () {
		#coopy/Viterbi.hx:40: characters 9-18
		$this->index = 0;
		#coopy/Viterbi.hx:41: characters 9-17
		$this->mode = 0;
		#coopy/Viterbi.hx:42: characters 9-27
		$this->path_valid = false;
		#coopy/Viterbi.hx:43: characters 9-22
		$this->best_cost = 0;
	}

	/**
	 *
	 * Configure the maximum number of states and the maximum sequence
	 * length that we care about.
	 * @param states maximum number of states
	 * @param sequence_length maximum sequence length
	 *
	 * 
	 * @param int $states
	 * @param int $sequence_length
	 * 
	 * @return void
	 */
	public function setSize ($states, $sequence_length) {
		#coopy/Viterbi.hx:55: characters 9-19
		$this->K = $states;
		#coopy/Viterbi.hx:56: characters 9-28
		$this->T = $sequence_length;
		#coopy/Viterbi.hx:57: characters 9-27
		$this->cost->resize($this->K, $this->T, 0);
		#coopy/Viterbi.hx:58: characters 9-27
		$this->src->resize($this->K, $this->T, -1);
		#coopy/Viterbi.hx:59: characters 9-28
		$this->path->resize(1, $this->T, -1);
	}

	/**
	 *
	 * @return the optimal state sequence as a string
	 *
	 * 
	 * @return string
	 */
	public function toString () {
		#coopy/Viterbi.hx:180: characters 9-24
		$this->calculatePath();
		#coopy/Viterbi.hx:181: characters 9-31
		$txt = "";
		#coopy/Viterbi.hx:182: characters 19-23
		$_g = 0;
		#coopy/Viterbi.hx:182: characters 23-28
		$_g1 = $this->index;
		#coopy/Viterbi.hx:182: lines 182-189
		while ($_g < $_g1) {
			#coopy/Viterbi.hx:182: characters 19-28
			$i = $_g++;
			#coopy/Viterbi.hx:183: lines 183-187
			if ($this->path->get(0, $i) === -1) {
				#coopy/Viterbi.hx:184: characters 17-27
				$txt = ($txt??'null') . "*";
			} else {
				#coopy/Viterbi.hx:186: characters 17-37
				$txt = ($txt??'null') . ($this->path->get(0, $i)??'null');
			}
			#coopy/Viterbi.hx:188: characters 13-34
			if ($this->K >= 10) {
				#coopy/Viterbi.hx:188: characters 24-34
				$txt = ($txt??'null') . " ";
			}
		}
		#coopy/Viterbi.hx:190: characters 9-37
		$txt = ($txt??'null') . " costs " . ($this->getCost()??'null');
		#coopy/Viterbi.hx:191: characters 9-19
		return $txt;
	}

	public function __toString() {
		return $this->toString();
	}
}

class _HxAnon_Viterbi0 extends HxAnon {
	function __construct($fileName, $lineNumber, $className, $methodName) {
		$this->fileName = $fileName;
		$this->lineNumber = $lineNumber;
		$this->className = $className;
		$this->methodName = $methodName;
	}
}

Boot::registerClass(Viterbi::class, 'coopy.Viterbi');
