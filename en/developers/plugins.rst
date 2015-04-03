Plugins
#######

What are Plugins ?
==================

QuickAppsCMS is designed to be modular. Instead of always having every
possible tool or feature in every site’s code, you can just have those
you're actually going to use. QuickAppsCMS’s core —what you get when you
install it— is like a very basic box of Lego™: a platform and some basic
bricks (plugins) to get you started. You can do a lot with just those
basics, but usually you'll want more.

That’s where contributed plugins come in. Contributed plugins are
packages of code that extend or enhance QuickAppsCMS’s core to add
additional (or alternate) functionality and features. These plugins have
been "contributed" back to the QuickAppsCMS community by their authors.

Plugins Anatomy
===============

Basic structure of plugins:

::

    Blog/
    ├── config/
    │   ├── bootstrap.php
    │   └── routes.php
    ├── src/
    │   ├── Controller/
    │   ├── Event/
    │   ├── Model/
    │   └── Template/
    │       └── Element/
    │           └── Help/
    │               └── help.ctp
    ├── tests/
    ├── webroot/
    │   ├── css/
    │   ├── img/
    │   └── js/
    └── composer.json

Plugin’s structure is the same defined by CakePHP, the only main difference is that
QuickAppsCMS’s plugins MUST define a ``composer.json`` file, this file is used by
`composer <https://getcomposer.org/>`__ to identify your package, but it’s also used
by QuickAppsCMS to get information about your plugins such as version number, name,
description, etc.

The "composer.json" file
------------------------

Each plugin has a "composer.json" file which contains information about
itself, such as name, description, version, etc. The schema of this file
is the same `required by composer <https://getcomposer.org/doc/04-schema.md>`__,
but with some additional requirements specifically for QuickAppsCMS.
These special requirements are described below:

-  key "name" must be present. A follow the pattern ``author-name/package-name``

-  key "type" must be present and be **quickapps-plugin** or **cakephp/cakephp**
   (even if it’s a theme).

-  key "name" must be present.

-  key "extra.regions" must be present if it’s a theme (its "name" ends with the
   "theme" word, e.g. "quickapps/blue-sky-theme")

NOTES
    -  Plugins may behave as themes if their name ends with ``-theme``.
    -  Plugin names are inflected from the ``name`` key, they
       are camelized, for example for ``author-name/super-name``, plugin name
       is ``SuperName``.

Dependencies
------------

You can indicate your plugin depends on another plugin, to do so, you must use the
``require`` key in your "composer.json". QuickAppsCMS’s dependencies resolver system
works pretty `similar to composer’s <https://getcomposer.org/doc/01-basic-usage.md
#package-versions>`__. For example you may indicate your plugin requires certain
version of QuickAppsCMS:

.. code:: json

    {
        "require": {
            "quickapps/cms": "2.*",
            "cakephp/cakephp": "3.0.1",
            "php": "5.4.*",
            "some-author/some-plugin-required": "*"
        }
    }

There are a few special packages names, such as:

- **quickapps/cms**: Required version of QuickAppsCMS.

- **cakephp/cakephp**: Required version of CakePHP (commonly used by cakephp's
  community plugins).

- **php**: Required version of PHP.

Install & Uninstall Process
===========================

The following describes some of the tasks automatically performed by QuickAppsCMS
during the (un)installation process, as well as some tasks that plugins should
consider during these processes. In both cases a series of events are automatically
triggered which plugins may responds to in order to change the (un)installation
process.

Installation
------------

What QuickAppsCMS does
~~~~~~~~~~~~~~~~~~~~~~

-  Checks plugin folder/files consistency.
-  Checks version compatibilities.
-  Checks dependencies.
-  Generate plugins ACO tree.
-  Register plugin on ``plugins`` table.
-  Regenerate related caches.

What plugins may do
~~~~~~~~~~~~~~~~~~~

-  Create new tables on Database.
-  Add new blocks.
-  Add new menus.
-  Add links to an existing menu.
-  Add new options to the ``options`` table

Events triggered
~~~~~~~~~~~~~~~~

-  Plugin.<PluginName>.beforeInstall: Before plugins is registered on DB and
   before plugin’s directory is moved to "/plugins"

-  Plugin.<PluginName>.afterInstall: After plugins was registered in DB and after
   plugin’s directory was moved to "/plugins"

Where ``<PluginName>`` is the inflected name of your plugin, for example, if in your
"composer.json" your package name is ``author-name/super-plugin-name`` then plugin’s
inflected name is ``SuperPluginName``.

Uninstallation
--------------

What QuickAppsCMS does
~~~~~~~~~~~~~~~~~~~~~~

-  Remove all related `ACOs and
   AROs <http://book.cakephp.org/2.0/en/core-libraries/components/access-control-lists.html#understanding-how-acl-works>`__
-  Remove all menus created by the plugin during installation.
-  Remove all Blocks defined by the plugin during installation.
-  Unregister plugin from the ``plugins`` table.
-  Regenerate related caches.


What plugins should do
~~~~~~~~~~~~~~~~~~~~~~

The following tasks should be performed by the plugins during the uninstallation
process. The best place to perform these tasks is on ``afterUninstall`` or
``beforeUninstall`` callbacks.

-  Remove all related Database tables.
-  Remove all defined options from the ``options`` table.

In general, your plugin should remove anything that is not automatically removed by
QuickAppsCMS.

Events triggered
~~~~~~~~~~~~~~~~

-  Plugin.<PluginName>.beforeUninstall: Before plugins is removed from DB and before
   plugin’s directory is deleted from "/plugins".

-  Plugin.<PluginName>.afterUninstall: After plugins was removed from DB and after
   plugin’s directory was deleted from "/plugins"

Where ``<PluginName>`` is the inflected name of your plugin, for example, if in your
"composer.json" your package name is ``author-name/super-plugin-name`` then plugin’s
inflected name is ``SuperPluginName``.


Plugin Options
--------------

QuickAppsCMS provides a simple "options" repository for storage of simple key-value
pairs. This values can be read anywhere using the global function
:doc:`option() <core-libraries>`.

Plugins can declare this pairs on their "composer.json". When a new plugins is
installed QuickAppsCMS moves this pairs from plugin's "composer.json" file to the
repository mentioned before. And removes these pairs when plugin is uninstalled
from the system.

Declaring options
~~~~~~~~~~~~~~~~~

Plugins are able declare options pairs under the ``extra.options`` key of their
"composer.json" file, for instance:

.. code:: json

    {
        "extra": {
            "options": [
                {"name": "MyPlugin.option-1", "value": "value1", "autoload": false},
                {"name": "MyPlugin.option-2", "value": "value2"},
                {"name": "MyPlugin.option-3"}
            ]
        }
    }


In the example above, you can read your options values by using the ``option()``
function as follows:

.. code:: php

    <?php
        echo option('MyPlugin.option-1'); // returns "value1"
        echo option('MyPlugin.option-2'); // returns "value2"
        echo option('MyPlugin.option-3'); // returns NULL
    ?>

The ``autoload`` and ``value`` option are optional and their default values are
``false`` and ``NULL`` respectively. The `autoload` flag indicates QuickAppsCMS to
load this value on bootstrap, if set to false (do not autoload) value will be fetched
on-demand, that is, when ``option()`` function is used.


**IMPORTANT:** You should always try to keep option names unique as possible. In
order to avoid collisions is always a good practice to prefix plugin's options with
plugin's name. For instance, instead of ``generic_name`` you could use
``MyPlugin.generic_name``. During plugin installation QuickAppsCMS will raise an
error if a collision is found and stopping the installation process.


Enabling & Disabling Process
============================

Plugins can be installed and uninstalled from your system, but they can
also be enabled or disabled. Disabled plugins have not interaction with
the system, which means all their Event Listeners classes will not
respond to any event, as their
`routes <http://book.cakephp.org/3.0/en/development/routing.html#plugin-routing>`__
as well.

Plugins can be disabled only if they are not required by any other
plugins, that is, for instance if plugin ``A`` needs some
functionalities provided by plugin ``B`` then you are not able to
disable plugin ``B`` as plugin ``A`` would stop working properly.

When plugins are enabled or disabled the following events are triggered:

-  ``Plugin.<PluginName>.beforeEnable``
-  ``Plugin.<PluginName>.afterEnable``
-  ``Plugin.<PluginName>.beforeDisable``
-  ``Plugin.<PluginName>.afterDisable``

The names of these events should be descriptive enough to let you know
what they do.

IMPORTANT
    Plugin’s assets are not accessible when plugins are disabled, which means
    anything within the ``/webroot`` directory of your plugin will not be accessible
    via URL.

Update Process
==============

Plugins can also be updated to newer versions, the update & install
process are both very similar as they perform similar actions during
their process.

Plugins can be updated using a ZIP package only if the current version (version
currently installed) is older than the version in the ZIP package.

During this process two events are triggered:

-  Plugin.<PluginName>.beforeUpdate: Before plugins’s old directory is removed from
   "/plugins"

-  Plugin.<PluginName>.afterUpdate: Before plugins’s old directory was removed from
   "/plugins" and after placing new directory in its place.

The update process basically replaces one directory (older) by another (latest).
Plugins should take care of migration tasks if needed using the events described
above.

Configurable Settings
=====================

Plugins are allowed to define a series of customizable parameters, this parameters
can be tweaked on the administration section by users with proper permissions.

For example, a "Blog" plugin could allow users to change plugin’s behavior by
providing a series of form inputs where users may indicate certain values that will
alter plugin’s functionalities, for example "show publish date" which would display
article’s "publish date" when an article is being rendered.

Plugins can provide these form inputs by placing them into
``/src/Tempalte/Element/settings.ctp``, here is where you should render all form
elements that users will be able to teak. For our "Blog" example, this file could
look as follow:

.. code:: php

    <?php
        echo $this->Form->input('show_publish_date', [
            'type' => 'checkbox',
            'label' => 'Show publish date',
        ]);

As you can see, you must simply create all the form inputs you want to provide to
users, you must omit ``Form::create()`` & ``Form::end()`` as they are automatically
created by QuickAppsCMS.

Reading settings values
-----------------------

Once you have provided certain teakable values, you may need to read those values in
order to change your plugin’s behavior, in our "Blog" example we want to know
whether the "publish date" should be rendered or not. To read these values you
should use the ``QuickApps\Core\Plugin`` class as follow:

.. code:: php

    <?php Plugin::get('Blog')->settings['show_publish_date']; ?>

IMPORTANT
    In some cases you will encounter that no values has been set for a setting key,
    for example if user has not indicated any value for your settings yet. This can
    be solved using the feature described below.

Default Setting Values
----------------------

You can provide default values for each of your settings keys using the event below:

::

    Plugin.<PluginName>.settingsDefaults

This event is automatically triggered every time you try to read a setting value,
your must implement this event handler in any of your plugin’s :doc:`Event Listener
<events-system>` classes and it must return an associative array for setting keys
and their values, a full example:

.. code:: php

    <?php
        // Blog/src/Event/BlogHook.php
        namespace Blog\Event;

        use Cake\Event\Event;
        use Cake\Event\EventListener;

        class BlogHook implements EventListener
        {
            public function implementedEvents()
            {
                return [
                    'Plugin.Blog.settingsDefaults' => 'settingsDefaults',
                ];
            }

            public function settingsDefaults(Event $event)
            {
                return [
                    'show_publish_date' => 1,
                ];
            }

        }

In the example above, if user has not indicated whether to show "publish date" or
not the default value will be ``1`` which we'll consider as "YES, show publish
date".

Validating Settings
-------------------

Usually you would need to restrict what user’s types in your settings form inputs,
so for example you may need an users to type in only integer values for certain
setting parameter. To validate these inputs you must use the
``Plugin.<PluginName>.settingsValidate`` event which is automatically triggered
before plugin information is persisted into DB. Event listeners methods should
expect two arguments: an entity as first arguments representing all settings values,
and an instance of validator object being used, you should alter this object as
needed to add your own validation rules. For example:

.. code:: php

    <?php
        // Blog/src/Event/BlogHook.php
        namespace Blog\Event;

        use Cake\Event\Event;
        use Cake\Event\EventListener;

        class BlogHook implements EventListener
        {
            public function implementedEvents()
            {
                return [
                    'Plugin.Blog.settingsValidate' => 'settingsValidate',
                ];
            }

            public function settingsValidate(Event $event, $settingsEntity, $validator)
            {
                $validator
                    ->validatePresence('show_publish_date')
                    ->notEmpty('show_publish_date', 'This field is required!')
                    ->add('another_settings_input_name', [
                        // ... rules & messages
                    ]);
            }

        }


Documenting your Plugin
=======================

Optionally you can provide help documentation, so users can access it and read it
trough the "Help" panel in the administration area (/admin/system/help).

To do this you must simply create a view-element containing all information you want
to provide about your plugin. This view-element should be placed in the following
directory of your plugin:

::

    PluginName/src/Template/Element/Help/help.ctp

Documentation in multiple languages
-----------------------------------

You can provide documentation in different languages simply by creating view-
elements following this pattern:

::

    PluginName/src/Template/Element/Help/help_<language-code>.ctp

Where ``<language-code>`` can be any active language code, check Locale plugin
documentation for more information.

For instance, if you want to provide help information in French and English you
should create the following view-elements:

- PluginName/src/Template/Element/Help/help_en_US.ctp
- PluginName/src/Template/Element/Help/help_fr_FR.ctp

If no translated documentation is found for certain language then ``help.ctp`` will
be used by default.


Recommended Reading
===================

-  :doc:`Events System <events-system>`
-  :doc:`Hooktags <hooktags>`
-  `CakePHP’s
   Validation <http://book.cakephp.org/3.0/en/core-libraries/validation.html>`__

.. meta::
    :title lang=en: Plugins
    :keywords lang=en: plugins,anatomy,composer,dependencies,install,uninstall,update,enable,disable,settings,custom settings
