Hooktags
########

A ``Hooktag`` is a QuickApps-specific code that lets you do nifty things with very
little effort. Hooktags can for example print current language code/name or call
specifics plugin/themes functions. For instance, the following hooktag (in any
content) would show current language’s code:

.. code:: html

    {locale code /}

.. note::

    If you are a Wordpress user you will find that ``hooktags`` are Wordpress’s
    ``shorcodes`` equivalent.

Defining Hooktags
=================

At core level a hooktag is just an event name prefixed with the ``Hooktag.`` word,
your event listeners classes must indicate which hooktags it will handle using the
``implementedEvents()`` method. For example:

.. code:: php

    namespace MyPlugin\Event;

    //...

    public function implementedEvents()
    {
        return [
            'Hooktag.redBox' => 'redBox',
            'Hooktag.blueBox' => 'blueBox',
        ];
    }

    //...

Where ``redBox`` and ``blueBox`` are methods defined within the event listener
class, these methods must expect four arguments:

-  **$event** (first argument): the event object that was triggered
-  **$atts** (second argument): an associative array of attributes
-  **$content** (third argument): the enclosed content (if the hooktag is used in
   its enclosing form)
-  **$code** (fourth argument): the hooktag name (only when it matches the callback
   name)

For example:

.. code:: php

    public function redBox(\Cake\Event\Event $event, $atts, $content, $code) {
        // logic here, and return HTML
    }

These methods are responsible of converting a hooktag (that looks as ``{locale code
/}``) into their HTML equivalent.

.. note::

    A good practice is to have all your hooktags events defined in independent
    classes, you could also add the `Hooktag` prefix to your class name keep things
    event more clean::

        Blog/
        └── src/
            └── Event/
                ├── ArticlesHooktag.php
                └── CommentsHooktag.php

Attributes
----------

The **$atts** array may include any arbitrary attributes that are specified by the
user. Attribute names are always converted to lowercase before they are passed into
the handler function. Values are untouched. {some_hooktag Foo="bAr"} produces $atts
= array('foo' => 'bAr').

.. warning::

    Don't use camelCase or UPPER-CASE for your $atts attribute names.

Parsing Hooktags
================

Once you have defined your hooktags is time to start converting a hooktag into
HTML. To do so, you can use the ``QuickApps\Event\HooktagAwareTrait`` trait in
any class, by defaults this trait is attached to ``QuickApps\View\View``
which means **you can use hooktag functionalities in any template**.
HookAwareTrait simply adds two methods: ``hooktags()`` and ``stripHooktags()``.

Basically, ``hooktags()`` receives a string as only arguments and look for hooktags
in the given text, for example, in any template you could:

.. code:: php

    echo $this->hooktags("Current language code is: {language code /}");

Depending on the current language you are navigating you will get:

.. code:: html

    Current language code is: en-us

The second method, ``stripHooktags()``, simply removes all hooktags from
the given text:

.. code:: php

    echo $this->stripHooktags("Current language code is: [language code /]");

Now you will get:

.. code:: html

    Current language code is:

.. warning::

    As we mention before, Events names are prefixed with ``Hooktag.`` word, which
    means that ``{language ...}`` will trigger the ``Hooktag.language`` event.

Tutorial: Creating a Hooktag
============================

Lets create a hooktag for displaying HTML content-boxes. We want our hooktag to be
as follow:

-  Its name will be ``content_box``.
-  Will use the ``enclosed`` form ({tag} ... {/tag}), for holding the box’s content.
-  Will accept a ``color`` parameter for specify the color of the box to render.
-  Will be handled by the ``Blog`` plugin.

Basically our hooktag must convert the code below:

    {content_box color=green}Lorem ipsum dolor{/content_box}

To its HTML representation:

.. code:: html

    <div style="background-color:green;">
        Lorem ipsum dolor
    </div>

As first step we must create a hooktag listener class, which would listen for
``content_box``:

.. code:: php

    // Blog/src/Event/BoxesHooktag.php
    namespace Blog\Event;

    use Cake\Event\EventListener;

    class BoxesHooktag implements EventListener
    {
        public function implementedEvents()
        {
            return [
                'Hooktag.content_box' => 'contentBox',
            ];
        }
    }

Now we must define the event handler method which should receive hooktag’s
information and convert it into HTML:

.. code:: php

    public function contentBox(Event $event, $atts, $content = null, $code = '')
    {
        $return = '<div style="background-color:' . $atts['color'] . ';"';
        $return .= $content;
        $return .= '</div>';
        return $return;
    }

**Usage**

Now you should be able to use the ``content_box`` hooktag as part of any content as
follow:

    {content_box color=green}Lorem ipsum dolor{/content_box}

Wherever you place the code above it will replaced by the following HTML code:

.. code:: html

    <div style="background-color:green;">Lorem ipsum dolor</div>

.. meta::
    :title lang=en: Hooktags
    :keywords lang=en: hooktags,events,event system,listener,Hooktag.,shortcode,stripHooktags
