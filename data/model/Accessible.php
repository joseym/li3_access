<?php
/**
 * Access plugin for Lithium.
 *
 * @author Michael Hüneburg (http://michaelhue.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace li3_access\data\model;

/**
 * The `Accessible` trait should be used by models that are access-controlled.
 *
 * The trait provides a basic `isAccessible` method which is called by models that act as requesters
 * by using the `li3_access\data\model\Requester` trait.
 *
 * Example for an accessible model:
 *
 * {{{
 * class Posts extends \lithium\data\Model {
 *     use \li3_access\data\model\Accessible;
 *
 *     public static function isAccessible($object, $action = null, $requester = null) {
 *         switch($action) {
 *             case 'create':
 *             case 'read':
 *                 return true;
 *
 *             case 'update':
 *             case 'delete':
 *                 return $requester && $requester->id == $object->user_id;
 *         }
 *         return false;
 *     }
 * }}}
 *
 * Note: technically it isn't required to use this trait in your models since it's only method has
 * to be overriden.
 */
trait Accessible {

	/**
	 * Manages access to the model. Override this method in your model.
	 *
	 * @param object $object The model's entity which is subject of the access request.
	 * @param string $action Optional name of the action requester wants to perform.
	 * @param object $requester The requesting entity, if applicable.
	 * @return boolean Returns `true` if the object is accessible by the requester.
	 */
	public static function isAccessible($object, $action = null, $requester = null) {
		return true;
	}

}

?>