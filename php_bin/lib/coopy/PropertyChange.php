<?php
/**
 */

namespace coopy;

use \php\Boot;

/**
 *
 * Represent a change to a single property.
 *
 */
class PropertyChange {
	/**
	 * @var string
	 *
	 * The new name of the property. If null, the property
	 * is to be destroyed (if possible).
	 *
	 */
	public $name;
	/**
	 * @var string
	 *
	 * The original name of the property. If null, the property
	 * is to be created (if possible).
	 *
	 */
	public $prevName;
	/**
	 * @var mixed
	 *
	 * The value of the property.
	 *
	 */
	public $val;

	/**
	 *
	 *
	 * Constructor.
	 *
	 * 
	 * @return void
	 */
	public function __construct () {
	}
}

Boot::registerClass(PropertyChange::class, 'coopy.PropertyChange');
