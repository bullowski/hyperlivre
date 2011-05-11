<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

// ------------------------------------------------------------------------

/**
 * Fieldset Class (extended)
 *
 * Define a set of fields that can be used to generate a form or to validate input.
 *
 * @package		Fuel
 * @category	Core
 * @author		Alex Bulla
 */
class Fieldset extends Fuel\Core\Fieldset
{

	public static function factory($name = 'default', Array $config = array())
	{
		if ($exists = static::instance($name))
		{
			\Error::notice('Fieldset with this name exists already, cannot be overwritten.');
			return $exists;
		}

		static::$_instances[$name] = new Fieldset($name, $config);

		if ($name == 'default')
		{
			static::$_instance = static::$_instances[$name];
		}

		return static::$_instances[$name];
	}

	/**
	 * Add a model's fields
	 * The model must have a method "set_form_fields" that takes this Fieldset instance
	 * and adds fields to it.
	 * Modified: the callable is added before the method call to the class. Allows us to use
	 * _validation_ methods from the class in the "set_form_fields" method.
	 *
	 * @param	string|Object	either a full classname (including full namespace) or object instance
	 * @param	array|Object	array or object that has the exactly same named properties to populate the fields
	 * @param	string			method name to call on model for field fetching
	 * @return	Fieldset		this, to allow chaining
	 */
	public function add_model($class, $instance = null, $method = 'set_form_fields')
	{
		// Add model to validation callables for validation rules
		$this->validation()->add_callable($class);

		if ((is_string($class) && is_callable($callback = array('\\'.$class, $method)))
			|| is_callable($callback = array($class, $method)))
		{
			$instance ? call_user_func($callback, $this, $instance) : call_user_func($callback, $this);
		}

		return $this;
	}

}