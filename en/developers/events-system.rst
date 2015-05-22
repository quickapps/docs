Events System
#############

The Events System is one of the most important pieces of QuickAppsCMS’s
architecture, this system allows :doc:`plugins <plugins>` to communicate with
each other, respond to certain events fired during execution of the script and
so on etc. As an example, "User" plugin may trigger an event "user logged in",
the rest of the plugins in the system may respond to this "signal" and act in
consequence.

.. note::

    As QuickAppsCMS’s events system is built on top of CakePHP’s events system we
    recommend you to read their `documentation <http://book.cakephp.org/3.0/en
    /core-libraries/events.html>`__.

Architecture
============

QuickAppsCMS’s events system is composed of three primary elements:

-  **Event Listener**: An event listener class implementing the EventListenerInterface
   interface.

-  **Event Handler**: A method within your listener class which handles a single
   event.

-  **Event**: An event object that represents the event itself. e.g. ``my_event``.

All Event Listeners classes must implement the
``\Cake\Event\EventListenerInterface`` interface and provide the
``implementedEvents()`` method. This method must return an associative array with
all Event names the class will handle. For example:

.. code:: php

    <?php
        public function implementedEvents()
        {
            return [
                'User.beforeLogin' => 'userBeforeLogin',
                'User.afterLogin' => 'userAfterLogin',
            ];
        }

Where ``userBeforeLogin`` and ``userAfterLogin`` are methods defined in the
Event Listener class.

Registering Listeners
=====================

By default in `CakePHP <http://book.cakephp.org/3.0/en/core-libraries/events.html
#registering-listeners>`_ you must create an instance of your Event Listener class
and then attach it to the `EventManager <http://book.cakephp.org/3.0/en/core-
libraries/events.html#global-event-manager>`__, in order to make this easier
QuickAppsCMS’s will automatically load and register all event listeners classes
within plugin’s "Event" directory. That is, if you want your "Blog" plugin’s
listener classes to be automatically loaded you must place these classes as follow:

::

    Blog/
    └── src/
        ├── Controller/
        └── Event/
            ├── MyListener.php
            ├── AnotherListener.php
            └── BlogListener.php

All three classes (MyListener, AnotherListener and BlogListener) will be
automatically loaded and registered on the EventManager.


Triggering Events
=================

Once your listeners classes were automatically loaded and registered, you can now
start triggering events and see how your listeners respond.

You can trigger events within any class you wish just by using
``CMS\Core\EventDispatcherTrait``, this trait will add a few handy methods for
triggering events.

By default, this trait is attached to ``CMS\Controller\Controller``, to
``CMS\View\View`` and to ``CMS\View\Helper`` classes. Means you can trigger events
within any controller, any view template or within any helper.

For example, in our "Blog" plugin example, we could have an
``ArticlesController.php`` that may looks as follow:

.. code:: php

    <?php
        namespace Blog\Controller;

        use CMS\Controller\Controller;

        class ArticlesController extends Controller
        {
            public function viewPost($id)
            {
                $this->trigger('event_name', $id);
            }
        }

The ``CMS\Event\EventDispatcherTrait`` trait provides the methods: ``trigger()`` and
``triggered()`` which are described below.


.. php:function:: trigger(mixed $eventName[, mixed $arg0, ..., mixed $argN, ...])

    Triggers the given event name. You can pass an unlimited number of arguments to
    your event handler method::

        $this->trigger('GetTime', $arg_0, $arg_0, ..., $arg_1);

    Your ``Event Listener`` must implement the ``GetTime`` event name, for
    instance::

        public function implementedEvents()
        {
            return ['GetTime' => 'handlerForGetTime'];
        }

    You can provide a context to use by passing an array as first arguments where
    the first element is the event name and the second one is the context::

        $this->trigger(['GetTime', new ContextObject()], $arg_0, $arg_0, ..., $arg_1);

    If no context is given ``$this`` will be used by default.


.. php:function:: triggered(string $eventName = null)

    Retrieves the number of times an event was triggered, or the complete list
    of events that were triggered. For example::

        $this->triggered('event_name');
        // may returns: 10

    If used with no arguments the full list of event and counters will be
    returned::

        $this->triggered();
        // may produce:
        [
            'event_name' => 10,
            'another_event_name' => 5,
            ...
            'User.loggin' => 1,
            'Block.Menu.beforeSave' => 1,
        ]


Tutorial: Creating Event Listeners
==================================

In this tutorial we'll be creating an event listener class, triggering some events,
and see how to use the trigger() method.

Consider the following event listener class:

.. code:: php

    <?php
        // Blog/src/event/MyEventListener.php
        namespace Blog\Event;

        use Cake\Event\EventListenerInterface;

        class MyEventListener implements EventListenerInterface
        {
            public function implementedEvents()
            {
                return [
                    'Hello' => 'world',
                ];
            }

            public function world(Event $event, $byValue)
            {
                return $byValue . ' world!';
            }
        }

Once listener class is created and (automatically) attached, you can start
triggering events and see how your handlers responds to. Wherever you are able to
use trigger() method you could:

.. code:: php

    <?php
        $hello = 'Hello';

        echo $this->trigger('Hello', $hello); // out: "Hello world!"
        echo $this->trigger('Hello', 'hellooo'); // out: "hellooo world!"


Recommended Reading
===================

As QuickAppsCMS’s events system is built on top of CakePHP’s events system we highly
recommend you to take a look at this part of CakePHP’s book:

`CakePHP’s Events
System <http://book.cakephp.org/3.0/en/core-libraries/events.html>`__

.. meta::
    :title lang=en: Events System
    :keywords lang=en: events,events system,event,trigger,event,listeners,listener,event listener
