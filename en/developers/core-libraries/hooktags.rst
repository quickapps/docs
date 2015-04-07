Hooktags
########

.. php:namespace:: QuickApps\Event

.. php:trait:: HooktagAwareTrait

This trait adds hooktags parsing functionality to any class. A Hooktag is a
QuickApps-specific code that lets you do nifty things with very little effort.
Hooktags can for example print current language code or call specifics
plugins/themes functions. For more details please check the :doc:`Hooktags
documentation </developers/hooktags>`.

Basic Usage
===========

This trait is mainly used to parse hooktags in text contents, this is achieved using
the ``hooktags()`` method:

.. code:: php

    $text = 'Current language is: {locale /}';
    echo $this->hooktags($text);
    // Prints depending on current language code, e.g. "Current language is: en_US"

Some times you would need to scape every hooktag present in your content so they
wont be ignored by ``hooktags()``:

.. code:: php

    $text = 'Current language is: {locale /}';
    $textEscaped = $this->escapeHooktags($text);

    echo $this->hooktags($text);
    // Prints "Current language is: en_US"

    echo $this->hooktags($textEscaped);
    // Prints "Current language is: {locale /}"

    echo $textEscaped;
    // Prints "Current language is: {{locale /}}"

QuickAppsCMS provides a very handy hooktag that can be used to escape any hooktag
within portions of HTML code in your view templates:

.. code:: php

    // MyPlugin/Template/MyController/some_action.ctp

    {no-hooktag}
    <h2>Supported hooktags are:</h2>
    <ul>
        <li>- {my-hooktag}</li>
        <li>- {another-hooktag}</li>
    </ul>
    {/no-hooktag}

In the example above, after template is rendered you will get the following HTML
code:

.. code:: html

    <h2>Supported hooktags are:</h2>
    <ul>
        <li>- {my-hooktag}</li>
        <li>- {another-hooktag}</li>
    </ul>

As you can see ``{my-hooktag}`` and ``{another-hooktag}`` are ignored and are
automatically escaped.

.. node::

    QuickAppsCMS comes with a few built-in hooktags, check System plugin
    documentation for more details.


Trait API
=========

.. php:method:: hooktags($content, $context = null)

    Look for hooktags in the given text.

.. php:method:: stripHooktags($content)

    Removes all hooktags from the given content.

.. php:method:: escapeHooktags($content)

    Escapes all hooktags from the given content.

.. php:method:: disableHooktags()

    Globally disables hooktags feature.

.. php:method:: enableHooktags()

    Enables hooktags feature.