Hooktags
########

A ``Hooktag`` is a QuickApps-specific code that lets you do nifty things
with very little effort. Hooktags can for example print current language
code/name or call specifics plugin/themes functions. For instance, the
following hooktag (in any content) would show current language's code:

.. code:: html

    [locale code /]

Note: If you are a Wordpress user you will find that ``hooktags`` are
Wordpress's ``shorcodes`` equivalent.

Defining Hooktags
=================

Hooktags are actually `Event Listener classes <01_Events_System.md>`__,
they may be located in each Plugin's or Theme's ``Event`` directory. For
example, in our ``Blog`` plugin example we could place a "Hooktag
listener" class within Blog's "Event" directory as follow:

::

    - Blog/
     |-- src/
        |-- Event/
           |-- ArticlesHooktag.php
           |-- CommentsHooktag.php

Similar as Event Listener classes, they must define which ``hooktags``
this class will handle using the ``implementedEvents()`` method. The
only main difference between the event system and hooktag system, is
that **Even names must be prefixed with the ``Hooktag.`` word**, for
example:

.. code:: php

    namespace MyPlugin\Event;

    //...

    public function implementedEvents() {
        return [
            'Hooktag.redBox' => 'redBox',
            'Hooktag.blueBox' => 'blueBox',
        ];
    }

    //...

Where ``redBox`` and ``blueBox`` are methods defined within the Event
Listener class, these methods must expect four arguments:

-  **$event** (first argument): the event object that was triggered
-  **$atts** (second argument): an associative array of attributes
-  **$content** (third argument): the enclosed content (if the hooktag
   is used in its enclosing form)
-  **$code** (fourth argument): the hooktag name (only when it matches
   the callback name)

For example:

.. code:: php

    public function redBox(\Cake\Event\Event $event, $atts, $content, $code) {
        // logic here, and return HTML
    }

These methods are responsible of converting a hooktag (that looks as
``[locale code /]``) into their HTML equivalent.

Attributes
----------

The **$atts** array may include any arbitrary attributes that are
specified by the user. Attribute names are always converted to lowercase
before they are passed into the handler function. Values are untouched.
[some\_hooktag Foo="bAr"] produces $atts = array('foo' => 'bAr').

**TIP: Don't use camelCase or UPPER-CASE for your $atts attribute
names**

Parsing Hooktags
================

Once you have defined your hooktag classes is time to start converting a
hooktag into HTML. To do this, you can use the
``QuickApps\Core\HooktagTrait`` trait in any class, by defaults this
trait is attached to ``QuickApps\View\View`` which means **you can use
hooktag functionalities in any template**. HooktagTrait simply adds two
methods; ``hooktags()`` and ``stripHooktags()``.

Basically, ``hooktags()`` receives a string as only arguments and look
for hooktags in the given text, for example, in any template you could:

.. code:: php

    echo $this->hooktags("Current language's code is: [language code /]");

Depending on the current language you are navigating you will get:

.. code:: html

    Current language's code is: en-us

The second method, ``stripHooktags()``, simply removes all hooktags from
the given text:

.. code:: php

    echo $this->stripHooktags("Current language's code is: [language code /]");

Now you will get:

.. code:: html

    Current language's code is:

**Important:** As we mention before, Events names are prefixed with
``Hooktag.`` word, which means that ``[language ...]`` will trigger the
``Hooktag.language`` event.

Example, creating a Hooktag
===========================

Lets create a hooktag for displaying HTML content-boxes. We want our
hooktag to be as follow:

-  Its name will be ``content_box``.
-  Will use the ``enclosed`` form ([tag] ... [/tag]), for holding the
   box's content.
-  Will accept a ``color`` parameter for specify the color of the box to
   render.
-  Will be handled by the ``Blog`` plugin.

Basically our hooktag must convert the code below:

    [content\_box color=green]Lorem ipsum dolor[/content\_box]

To its HTML representation:

.. code:: html

    <div style="background-color:green;">
        Lorem ipsum dolor
    </div>

As first step we must create a hooktag listener class, which would
listen for ``content_box``:

.. code:: php

    // Blog/src/Event/BoxesHooktag.php
    namespace Blog\Event;

    use Cake\Event\EventListener;

    class BoxesHooktag implements EventListener {
        public function implementedEvents() {
            return [
                'Hooktag.content_box' => 'contentBox',
            ];
        }
    }

Now we must define the event handler method which should receive
hooktag's information and convert it into HTML:

.. code:: php

    public function contentBox(Event $event, $atts, $content = null, $code = '') {
        $return = '<div style="background-color:' . $atts['color'] . ';"';
        $return .= $content;
        $return .= '</div>';
        return $return;
    }

**Usage**

Now you should be able to use the ``content_box`` hooktag in any Node's
contents, or wherever hooktags are allowed.

    [content\_box color=green]Lorem ipsum dolor[/content\_box]

Wherever you place the code above it will replaced by the following HTML
code:

.. code:: html

    <div style="background-color:green;">Lorem ipsum dolor</div>

