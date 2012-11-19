<?php
/**
 * Access plugin for Lithium.
 *
 * @author Michael Hüneburg (http://michaelhue.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace li3_access\data\model;

use lithium\core\Libraries;

/**
 * The `Requester` trait should be used by all models which need to ask for access to `Accessible`
 * models.
 *
 * The trait implements the `can` method which is called to check if the model's object has access
 * to a model or entity. For example:
 *
 * {{{
 * $user->can('read', 'Shows');
 *
 * $show = Shows::first();
 * $user->can('update', $show);
 * }}}
 *
 * The target model needs to implement an `isAccessible` method or an exception will be thrown.
 */
trait Requester {

	/**
	 * An instance method that checks if the entity is allowed to perform the specified action
	 * on an object (which is either another entity or a model class name).
	 *
	 * @param object $requester The requesting entity.
	 * @param string $action Name of the action which the entity wants to perform.
	 * @param object|string $object Optional target object, either an entity or a model class name.
	 *      If specified the `isAccessible` method of the object will be called to determine if
	 *      the requester can perform the action.
	 * @return boolean Returns `true` if the entity is allowed to perform the action,
	 *      `false` otherwise.
	 * @throws InvalidArgumentException If the specified object model doesn't implement the
	 *     `isAccessible` method.
	 */
	public function can($requester, $action, $object = null) {
		if (is_string($object) && $class = Libraries::locate('models', $object)) {
			return $class::isAccessible(null, $action, $requester);
		}
		if (is_object($object) && method_exists($object->model(), 'isAccessible')) {
			$class = $object->model();
			return $class::isAccessible($object, $action, $requester);
		}

		$msg = "Object '{$object}' does not implement required 'isAccessible' method.";
		throw new InvalidArgumentException($msg);
	}

}

?>