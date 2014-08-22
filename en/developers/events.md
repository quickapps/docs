Events
======

The Events System is most important piece of QuickAppsCMS's architecture, this
system allows [plugins](plugins.md) to communicate with each other, respond to
certain events fired during execution of the script, etc. So for example, "User"
plugin may trigger an event "user is has been logged in", the rest of the plugins
in the system may respond to this "signal" and act in consequence.

NOTE: As QuickAppsCMS's events system is built on top of CakePHP's events system
we recommend you to read their [documentation](http://book.cakephp.org/3.0/en/core-libraries/events.html).


Architecture
============

QuickAppsCMS's events system is composed of three primary elements:

- `Event Listener`: An event listeners class implementing the EventListener
   interface.
- `Event Handler`: A method in your your listener class which take care of a
   single event.
- `Event`: Name of the event. e.g.: `FormHelper.input`.

An Event Listener class, may listen to many Events. But a Event Handler can only
responds to a single Event.

All `Event Listeners` classes must implement the `\Cake\Event\EventListener`
interface and provide the `implementedEvents()` method. This method must return
an associative array with all Event names the class will handle. For example:

```php
public function implementedEvents() {
    return [
        'User.beforeLogin' => 'userBeforeLogin',
        'User.afterLogin' => 'userAfterLogin',
    ];
}
```

Where `userBeforeLogin` and `userAfterLogin` are methods defined in the Event
Listener class.


Registering Listeners
=====================

By default in CakePHP you must create an instance of your Event Listener class
and then attach it to the [EventManager](http://book.cakephp.org/3.0/en/core-libraries/events.html#global-event-manager),
in order to make this easier QuickAppsCMS's will automatically load all events
listeners classes within plugin's "Event" directory. That is, if you want your
"Blog" plugin's listener classes to be automatically loaded you must place these
classes as follow:

    - Blog/
     |-- src/
        |-- Event/
           |-- Listener1Hook.php
           |-- Listener2Hook.php
           |-- Listener3Hook.php

All three classes (Listener1Hook, Listener2Hook and Listener3Hook) will be
automatically loaded and registered on the `EventManager`. In order to keep the
things dry, we add the `Hook` suffix to each class name.


Dispatching Events
==================

Once your listeners classes were automatically loaded and attached, you can now
start triggering events and see how your listeners respond.

You can trigger events within any class you wish just by using
`QuickApps\Core\HooKTrait`, this trait will add a few handy methods for triggering
events.

By default, this trait is attached to  `QuickApps\Controller\Controller`,
`QuickApps\View\View` & `QuickApps\View\Helper` classes. This means you can use
this trait's methods in any controller of your plugin, in any template or within
any helper. Of course you must extend these classes in order to inherit this
methods.

For example, in our "Blog" plugin example, we could have an `ArticlesController.php`
that may looks as follow:

```php
namespace Blog\Controller;

use QuickApps\Controller\Controller;

class ArticlesController extends Controller {
    public function view_post($id) {
        $this->hook('event_name', $id);
    }
}
```

The `QuickApps\Core\HooKTrait` trait provides the methods: `hook()`, `didHook()`
and `alter()` which are described below.

---

### hook($eventName [, $arg0, ..., $argN, ...]);

Triggers the given event name. You can pass an unlimited number of arguments to
your event handler method.

#### Usage:

```php
$this->hook('GetTime', $arg_0, $arg_0, ..., $arg_1);
```

Your `Event Listener` must implement the `GetTime` event name, for instance:

```php
public function implementedEvents() {
    return ['GetTime' => 'handlerForGetTime'];
}
```

You can provide a context to use by passing an array as first arguments where
the first element is the event name and the second one is the context:

```php
$this->hook(['GetTime', new ContextObject()], $arg_0, $arg_0, ..., $arg_1);
```

If no context is given "$this" will be used by default.


### didHook([$eventName]);

Retrieves the number of times an event was fired, or the complete list of events
that were fired. For example:

```php
$this->didHook('event_name');
// may returns: 10
```

If used with no arguments the full list of event as counters will be returned:

```php
$this->didHook();
// may produce:
[
    'event_name' => 10,
    'another_event_name' => 5,
    ...
    'User.loggin' => 1,
    'Block.Menu.beforeSave' => 1,
]
```

### alter($eventName [, $arg0, ..., $arg14]);

Similar to "hook()" but aimed to alter the given arguments. You can pass up to
15 arguments by reference. The main difference with `hook()` is that `alert()`
will prefix event names with the `Alter.` word, so invoking "alter_this" will
actually triggers the event name "Alter.alter_this"

#### Usage:

```php
$this->alter('Time', $arg_0, $arg_0, ..., $arg_1);
```

Your `Event Listener` must implement the event name `Alter.Time`:

```php
public function implementedEvents() {
    return ['Alter.Time' => 'handlerForAlterTime'];
}
```

(Note the `Alter.` prefix).

You can provide a context to use by passing an array as first arguments where
the first element is the event name and the second one is the context:

```php
$this->alter(['Time', new ContextObject()], $arg0, $arg1, ...);
```

If no context is given "$this" will be used by default.

---

## "Hello World!" Example:

```php
// Blog/src/event/MyEventListener.php
namespace Blog\Event;

use Cake\Event\EventListener;

class MyEventListener implements EventListener {
    public function implementedEvents() {
        return [
            'Alter.Hello' => 'alterWorld',
            'Hello' => 'world',
        ];
    }

    public function alterWorld(Event $event, &$byReference) {
        // Remember the "&" for referencing
        $byReference .= ' World!';
    }

    public function world(Event $event, $byValue) {
        return $byValue . ' world!';
    }
}
```

***

```php
// Wherever you are able to use hook() and alter():

$hello = 'Hello';
$this->alter('Hello', $hello);

echo $hello; // out: "Hello World!"
echo $this->hook('Hello', $hello); // out: "Hello World! world!"
echo $this->hook('Hello', 'hellooo'); // out: "hellooo world!"
```

Recommended Reading
===================

As QuickAppsCMS's hook system is built on top of CakePHP's events system we
highly recommend you to take a look at this part of CakePHP's book:

[CakePHP's Events System](http://book.cakephp.org/3.0/en/core-libraries/events.html)