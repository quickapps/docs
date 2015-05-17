Plugin Class
############

.. php:namespace:: QuickApps\Core

.. php:class:: Plugin

Plugin is used to load and locate plugins. Wrapper for ``Cake\\Core\\Plugin``, it
adds some QuickAppsCMS specifics methods.

Basic Usage
===========

.. php:staticmethod:: get($plugin = null)

This method is used to retrieve information about any installed plugin in the
system, plugin is represented as Package object
(``QuickApps\\Core\\Package\\PluginPackage``), this object contains all the
information related to that plugin, if invoked with no arguments it will returns all
installed plugins as a collection::

    $debugKit = Plugin::get('DebugKit');
    echo $debugKit->package;
    // prints: cakephp/debug_kit

    echo $debugKit->path;
    // prints: /full/path/to/DebugKit/

To get all plugins matching certain criteria you could get all of them as a
collection and then filter it::

    $enabledPlugins = Plugin::get()->filter(function ($plugin) {
        return $plugin->status;
    });

    $disabledThemes = Plugin::get()->filter(function ($plugin) {
        return !$plugin->status && $plugin->isTheme;
    });

Plugin API
==========

.. php:staticmethod:: scan($ignoreThemes = false)

    Scan plugin directories and returns plugin names and their paths within file
    system. We consider "plugin name" as the name of the container directory.

.. php:staticmethod:: exists($plugin)

    Checks whether a plugins is installed on the system regardless of its status.

.. php:staticmethod:: validateJson($json, $errorMessages = false)

    Validates a composer.json file.

.. php:staticmethod:: checkReverseDependency($pluginName)

    Checks if there is any active plugin that depends of $pluginName.