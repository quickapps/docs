View Modes
##########

How a content is displayed depends on the context in which it is rendered, such
as the difference between ``full`` nodes and ``teasers``.

In a standard QuickAppsCMS installation, the following view modes are available:

-  **Default** (default): Default is used as a generic view mode if no other
   view mode has been specified.
-  **Teaser** (teaser): Teaser is a really short format that is typically used
   in main the main page, such as "last news", etc.
-  **Search Result** (search-result): Search Result is a short format that is
   typically used in lists of multiple content items such as search results.
-  **RSS** (rss): Similar to "Search Result" but intended to be used when
   rendering content as part of a RSS feed list.
-  **Full** (full): Full content is typically used when the content is displayed
   on its own page.

**NOTE:** In parenthesis are "machine-names" of each view mode, these names are
used internally when referring to a specific view mode.

These context are automatically set by QuickAppsCMS during the rendering process
of every content, however other :doc:`plugins </developers/plugins>` may change
this on the fly and define their owns.


Registering View Modes
======================

Other plugin may define new view modes, using the
``QuickApps\View\ViewModeRegistry`` class. A good place to define new view modes is
in your plugin's "bootstrap.php" file, so other plugins will be aware of this as
soon as possible.

.. code:: php

    // MyPlugin/config/bootstrap.php
    use QuickApps\View\ViewModeRegistry;

    ViewModeRegistry::add('machine-name', 'My View Mode', 'Description');

Check the API for more details.


Switch View Modes
=================

Once you have registered some view modes in your system, you can now tell the system
to switch from one view mode to another as follow:

.. code:: php

    ViewModeRegistry::uses('machine-name');

As view modes are frequently switched at controller side (before content is
rendered), in order to make this process easier QuickAppsCMS provides a few
controller methods for handling with view modes. For instance, in any controller
action:

.. code:: php

    public function myAction()
    {
        // action logic
        $this->viewMode('machine-name');
    }

For more information check ``QuickApps\View\ViewModeAwareTrait`` API.

.. meta::
    :title lang=en: View Modes
    :keywords lang=en: view mode,full,teaser,rss,search result,machine name
