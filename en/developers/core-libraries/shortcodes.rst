Hooktags
########

.. php:namespace:: CMS\Shortcode

.. php:trait:: ShortcodeTrait

This trait adds shortcode parsing functionality to any class. A Shortcode is a
QuickApps-specific code that lets you do nifty things with very little effort.
Shortcodes can for example print current language code or call specifics
plugins/themes functions. For more details please check the :doc:`Shortcode API
documentation </developers/shortcode-api>`.

Basic Usage
===========

This trait is mainly used to parse shortcodes in text contents, this is achieved
using the ``shortcodes()`` method:

.. code:: php

    // Some class

    use CMS\Shortcode\ShortcodeTrait;

    // ...

    $text = 'Current language is: {locale /}';
    echo $this->shortcodes($text);
    // Prints depending on current language code, e.g. "Current language is: en_US"

Some times you would need to scape every shortcode present in your content so they
wont be ignored by ``shortcodes()``:

.. code:: php

    $text = 'Current language is: {locale /}';
    $textEscaped = $this->escapeShortcodes($text);

    echo $this->shortcodes($text);
    // Prints "Current language is: en_US"

    echo $this->shortcodes($textEscaped);
    // Prints "Current language is: {locale /}"

    echo $textEscaped;
    // Prints "Current language is: {{locale /}}"

QuickAppsCMS provides a very handy shortcode that can be used to escape any
shortcode within portions of HTML code in your view templates:

.. code:: php

    // MyPlugin/Template/MyController/some_action.ctp

    {no_shortcode}
    <h2>Supported shortcodes are:</h2>
    <ul>
        <li>- {my_shortcode}</li>
        <li>- {another-shortcode}</li>
    </ul>
    {/no_shortcode}

In the example above, after template is rendered you will get the following HTML
code:

.. code:: html

    <h2>Supported shortcodes are:</h2>
    <ul>
        <li>- {my_shortcode}</li>
        <li>- {another_shortcode}</li>
    </ul>

As you can see ``{my_shortcode}`` and ``{another_shortcode}`` are ignored and are
automatically escaped.

.. note::

    QuickAppsCMS comes with a few built-in shortcodes, check System plugin
    documentation for more details.


Trait API
=========

.. php:method:: shortcodes($content, $context = null)

    Look for shortcodes in the given text.

.. php:method:: stripShortcodes($content)

    Removes all shortcodes from the given content.

.. php:method:: escapeShortcodes($content)

    Escapes all shortcodes from the given content.

.. php:method:: enableShortcodes()

    Enables shortcodes feature.

.. php:method:: disableShortcodes()

    Globally disables shortcodes feature.