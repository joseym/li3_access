# Access plugin for Lithium

Provides an interface for simple access control in your Lithium application. Models can act as
`Requester` or as `Accessible` (or both) and manage access to each other. The plugin uses Traits
to inject methods into your classes (which means PHP 5.4 is required).

## Models

The plugin provides the `Requester` and `Accessible` traits to manage access control between
models.

### Requester

Requesters are objects that may request permissions from accessible objects. For example an User
entity that requests the permission to edit a Post entity. You can make your model a requester by
using the `li3_access\data\model\Requester` trait:

```php
class Users extends \lithium\data\Model {
    use \li3_access\data\model\Requester;
}
```

The `Requester` trait implements a `can` method which you can use to check permissions to models
that use the `Accessible` trait. For example:

```php
$user = Users::first('johndoe');

// Check general access to a model.
if ($user->can('update', 'Posts')) {
	// ...
}

// Check specific access to an entity.
$post = Posts::first();
if ($user->can('update', $post)) {
    // ...
}
```

### Accessible

Access-controlled need to implement the `Accessible` trait and an `isAccessible` method. The method
is called when `Requester` models ask for permissions via `Requester::can()`. Use the method to
determine if the requester has access to the specified resource.

The following `Posts` model will grant access to everyone for `create` and `read` actions but will
require the requester to be the author of the object for `update` and `delete` actions:

```php
class Posts extends \lithium\data\Model {
    use \li3_access\data\model\Accessible;

    public static method isAccessible($object, $action = null, $requester = null) {
        switch ($action) {
        	case 'create':
        	case 'read':
        		return true;

        	case 'update':
        	case 'delete':
        		return $requester && $requester->id == $object->user_id;
        }
        return false;
    }
}
```

## TODO

- Unit Tests
- Implement trait for managing controller authorization requirements