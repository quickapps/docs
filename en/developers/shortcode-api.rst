Shortcode API
#############

The Shortcode API allows you to map specifically formatted content to a callback to
transform them into something else, usually to HTML code. You might know this
concept from forum software which don't allow you to insert direct HTML, instead
resorting to a custom syntax (e.g. `BBCode <http://es.wikipedia.org/wiki/BBCode>`_).

A ``Shortcode`` is a QuickApps-specific pseudo-code that lets you do nifty things
with very little effort. Shortcodes can for example print current language code/name
or call specifics plugin/themes functions. For instance, the following shortcode can
be used anywhere to print current language’s code:

.. code:: html

    {locale code /}


Defining Shortcodes
===================

Shortcodes are implemented on top of Event System, so basically: a shortcode is a
way to trigger events using pseudo-code. To create a shortcode you must simply
create an event listener class **suffixed with the Shortcode** word and indicate
which shortcodes it will handle using the ``implementedEvents()`` method. For
example:

.. code:: php

    // Blog\Event\BoxesShortcode.php
    namespace MyPlugin\Event;

    //...

    public function implementedEvents()
    {
        return [
            'redBox' => 'redBox',
            'blueBox' => 'blueBox',
        ];
    }

    //...

Where ``redBox`` and ``blueBox`` are methods defined within the event listener
class, these methods must expect four arguments:

-  **$event** (first argument): the event object that was triggered by the parser
-  **$atts** (second argument): an associative array of attributes
-  **$content** (third argument): the enclosed content (if the shortcode is used in
   its enclosing form)
-  **$code** (fourth argument): the shortcode name (only when it matches the callback
   name)

For example:

.. code:: php

    public function redBox(Event $event, $atts, $content, $code) {
        // logic here, and return HTML
    }

These methods are responsible of converting a shortcode (that looks as ``{locale
code /}``) into their HTML equivalent.

Attributes
----------

The **$atts** array may include any arbitrary attributes that are specified by the
user. Attribute names are always converted to lowercase before they are passed into
the handler function. Values are untouched. {some_shortcode Foo="bAr"} produces $atts
= array('foo' => 'bAr').

.. warning::

    Don't use camelCase or UPPER-CASE for your $atts attribute names.

Parsing Shortcodes
==================

Once you have defined your shortcodes is time to start converting a shortcode into
HTML. By default **QuickAppsCMS parses shortcodes on every piece of content that is
sent to users**, This means you don't require to do anything special for parsing
shortcodes.

However, you can use the ``QuickApps\Shortcode\ShortcodeTrait`` trait in any class,
to add shortcodes parsing functionalities. By defaults this trait is attached to
``QuickApps\View\View``, which means **you can use shortcode functionalities within
any template** using the methods ``shortcodes()`` & ``stripShortcodes()`` it
provides.

Basically, ``shortcodes()`` receives a string as only arguments and look for
shortcodes in the given text, for example, in any template you could:

.. code:: php

    echo $this->shortcodes("Current language code is: <b>{language code /}</b>");

Depending on the current language you are navigating you will get:

.. code:: html

    Current language code is: <b>en-us</b>

The second method, ``stripShortcodes()``, simply removes all shortcodes from
the given text:

.. code:: php

    echo $this->stripShortcodes("Current language code is: <b>{language code /}</b>");

Now you will get:

.. code:: html

    Current language code is: <b></b>


Escaping Shortcodes
===================

Some times you would need to scape shortcodes so they are not processed by the
Shortcode parser. Here we'll explain how to escape shortcodes so they are ignored by
the parser.


Basic
-----

The most simple way to escape a shortcode is by surrounding it with ``{`` and ``}``
symbols. For example:

.. code:: html

    Please use the <code>{{locale /}}</code> shortcode for printing language code.

After parser is applied the following will be presented to the user:

.. code:: html

    Please use the <code>{locale /}</code> shortcode for printing language code.


And in the case of shortcodes using Enclosed form you must do the same:

.. code:: html

    This is an {{enclose_form_shortcode attr=value}with an enclosed content{/enclose_form_shortcode}}

Which result in:

.. code:: html

    This is an {enclose_form_shortcode attr=value}with an enclosed content{/enclose_form_shortcode}


Block Escaping
--------------

Some times you would need to escape entire portions of HTML code mixed with
shortcodes. You can escape chunks of code by surrounding it with ``{no_shortcode}``
and ``{/no_shortcode}``, which ironically is a shortcode itself. For example:

.. code:: html

    {no_shortcode}
    <h2>Allowed shortcodes are</h2>
    <ul>
        <li>{box_blue /}</li>
        <li>{box_red /}</li>
        <li>{box_green /}</li>
    </ul>
    {/no_shortcode}

The code above will produce the following code after processed by the Shortcode
Parser:

.. code:: html

    <h2>Allowed shortcodes are</h2>
    <ul>
        <li>{box_blue /}</li>
        <li>{box_red /}</li>
        <li>{box_green /}</li>
    </ul>


Tutorial: Creating a Shortcode
==============================

In this tutorial we'll be creating a shortcode for displaying HTML content-boxes of
different colors. We want our shortcode to be as follow:

-  Its name will be ``content_box``.
-  Will use the ``enclosed`` form ({tag} (...box content...) {/tag}), for holding the box’s content.
-  Will accept a ``color`` parameter for specify the color of the box to render.
-  Will be handled by the ``Blog`` plugin.

So our shortcode definition would looks as follow:

    {content_box color=green}Lorem ipsum dolor{/content_box}

Which should be converted to HTML like so:

.. code:: html

    <div style="background-color:green;">
        Lorem ipsum dolor
    </div>

Defining the listener class
---------------------------

As first step we must create a shortcode listener class, which would listen for the
``content_box`` event:

.. code:: php

    // Blog/src/Event/BoxesShortcode.php
    namespace Blog\Event;

    use Cake\Event\EventListener;

    class BoxesShortcode implements EventListener
    {
        public function implementedEvents()
        {
            return [
                'content_box' => 'contentBox',
            ];
        }
    }

Creating the event handler method
---------------------------------

Now we must define the event handler method which should receive shortcode’s
information and convert it into HTML:

.. code:: php

    public function contentBox(Event $event, $atts, $content = null, $code = '')
    {
        $return = '<div style="background-color:' . $atts['color'] . ';"';
        $return .= $content;
        $return .= '</div>';
        return $return;
    }

.. note::

    The event's subject is the View instance being used in current request, so a
    good practice is to rely on view-elements when rendering HTML, for instance::

        return $event
            ->subject()
            ->element('shortcode_content_box', compact('attrs', 'content', 'code'));


Using the shortcode
-------------------

Now you should be able to use the ``content_box`` shortcode as part of any content
as follow:

    {content_box color=green}Lorem ipsum dolor{/content_box}

Wherever you place the code above it will replaced by the following HTML code:

.. code:: html

    <div style="background-color:green;">Lorem ipsum dolor</div>

.. meta::
    :title lang=en: Shortcodes
    :keywords lang=en: shortcodes,events,event system,listener,shortcode,stripShortcodes
