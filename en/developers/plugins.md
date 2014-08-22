What are Plugins ?
==================

QuickAppsCMS is designed to be modular. Instead of always having every possible
tool or feature in every site's code, you can just have those you're actually
going to use. QuickAppsCMS's core —what you get when you install it— is like a
very basic box of Lego™: a platform and some basic bricks (plugins) to get you
started. You can do a lot with just those basics, but usually you'll want more.

That's where contributed plugins come in. Contributed plugins are packages of
code that extend or enhance QuickAppsCMS's core to add additional (or alternate)
functionality and features. These plugins have been "contributed" back to the
QuickAppsCMS community by their authors.


Structure
=========

Basic structure of plugins:

    |- Blog/
        |- config/
        :    |- bootstrap.php
        :    |- routes.php
        |- src/
        :   |- Controller/
        :   |- Model/
        :   |- Template/
        |- webroot/
        :- composer.json

Plugin's structure is the same defined by CakePHP, the only main difference is
that plugins MUST define a `composer.json` file, this file contains information
about the plugin itself which is used by QuickAppsCMS.


The "composer.json" file
========================

Each plugin has a "composer.json" file which contains information about itself,
such as name, description, version, etc. The schema of this file is the same
[required by composer](https://getcomposer.org/doc/04-schema.md), but with some
additional requirements specifically for QuickAppsCMS. These special requirements
are described below:

- key `name` must be present. A follow the pattern `author-name/package-name`
- key `version` must be present.
- key `type` must be present and be **quickapps-plugin** (even if it's a theme).
- key `name` must be present.
- key `description` must be present.
- key `extra.regions` must be present if it's a theme (its `name` ends with
  `-theme`, e.g. `quickapps/blue-sky-theme`)

**NOTES:**
- Plugins may behave as themes if their name ends with `-theme`
- Plugin's names are inflected from the `name` key, they are camelized, for
  example for `author-name/super-name`, plugin name is `SuperName`.


## Dependencies

You can indicate your plugin depends on another plugin, to do this you must use
the `require` key in your "composer.json". QuickAppsCMS's dependencies resolver
system works pretty [similar to composer's](https://getcomposer.org/doc/01-basic-usage.md#package-versions).
For example you may indicate your plugin requires certain version of QuickAppsCMS:

```json
{
    "require": {
        "quickapps/cms": ">1.0"
    }
}
```

Which means: This plugin can only be installed on QuickAppsCMS v1.0 or higher.


The (Un)Installation Process
============================

The following describes some of the tasks automatically performed by QuickAppsCMS
during the (un)installation process, as well as some tasks that plugins should
consider during these processes. In both cases a series of events are
automatically triggered which plugins may responds to in order to change the
(un)installation process.


During Installation
-------------------

### Tasks automatically performed by QuickAppsCMS

- Checks plugin folder/files consistency.
- Checks version compatibilities.
- Checks dependencies.
- Generate plugins ACO tree.
- Register plugin on `plugins` table.
- Regenerate related caches.

### Common tasks which plugins may do

- Create new tables on Database.
- Add new blocks.
- Add new menus.
- Add links to an existing menu.
- Add new options to the `options` table

### Events triggered

- `<PluginName>.beforeInstall`: Before plugins is registered on DB and before
   plugin's directory is moved to "/plugins"
- `<PluginName>.afterInstall`: After plugins was registered in DB and after
   plugin's directory was moved to "/plugins"

Where `<PluginName>` is the inflected name of your plugin, for example, if in your
"composer.json" your package name is `author-name/super-plugin-name` then plugin's
inflected name is `SuperPluginName`.


During Uninstallation
---------------------

### Tasks automatically performed by QuickAppsCMS

- Remove all related [ACOs and AROs](http://book.cakephp.org/2.0/en/core-libraries/components/access-control-lists.html#understanding-how-acl-works)
- Remove all menus created by the plugin during installation.
- Remove all Blocks defined by the plugin during installation.
- Unregister plugin from the `plugins` table.
- Regenerate related caches.


### Tasks to consider by plugin

The following tasks should be performed by the plugins during the uninstallation
process. The best place to perform these tasks is on `afterUninstall` or
`beforeUninstall` callbacks.

- Remove all related Database tables.
- Remove all defined options from the `options` table.

In general, your plugin should remove anything that is not automatically removed
by QuickAppsCMS.

### Events triggered

- `<PluginName>.beforeUninstall`: Before plugins is removed from DB and before
   plugin's directory is deleted from "/plugins".
- `<PluginName>.afterUninstall`: After plugins was removed from DB and after
   plugin's directory was deleted from "/plugins"

Where `<PluginName>` is the inflected name of your plugin, for example, if in your
"composer.json" your package name is `author-name/super-plugin-name` then plugin's
inflected name is `SuperPluginName`.


The (En)Disabling Process
=========================

Plugins can be installed and uninstalled from your system, but also they can also
be enabled or disabled. Disabled plugins have not interaction with the system,
which means all their Event Listeners classes will not respond to any event.

Plugins can be disabled only if they are not required by any other plugins, that
is, for instance, if plugin `A` needs some functionalities provided by plugin `B`
then you are not able to disable plugin `B` as plugin `A` would stop working properly.

When plugins are enabled or disabled the following events are triggered:

- `<PluginName>.beforeEnable`
- `<PluginName>.afterEnable`
- `<PluginName>.beforeDisable`
- `<PluginName>.afterDisable`

The names of these events should be descriptive enough to let you know what they
do.


For more information about:
===========================

* [Events System](events.md)
* [Hooktags](hooktags.md)