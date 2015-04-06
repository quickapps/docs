Core Libraries
##############

QuikcAppsCMS features a number of global convenience functions that may come in
handy. Most of this functions are used by QuickAppsCMS's core classes, but many
others make working with arrays or strings a little easier.

Global Constants and Functions
==============================

Here are QuickAppsCMS’s globally available functions. Most of them are just
convenience wrappers for other core plugin functionalities, or shortcuts for
commonly used pieces of logic.

.. php:function:: snapshot()

    Stores some bootstrap-handy information into a persistent file.

    Information is stored in ``SITE/tmp/snapshot.php`` file, it contains
    useful information such as installed languages, content types slugs,
    etc. You can read this information using ``Configure::read()`` as follow::

        Configure::read('QuickApps.<option>');

    Or using the ``quickapps()`` global function::

        quickapps('<option>');


.. php:function:: normalizePath(string $path, string $ds = DIRECTORY_SEPARATOR)

    Normalizes the given file system path, makes sure that all DIRECTORY_SEPARATOR
    are the same, so you won't get a mix of "/" and "\\" in your paths::

        normalizePath('/some/path\to/some\\thing\about.zip');
        // output: /some/path/to/some/thing/about.zip

    You can indicate which "directory separator" symbol to use using the second
    argument::

        normalizePath('/some/path\to//some\thing\about.zip', '\\');
        // output:
        \some\path\to\some\thing\about.zip

    By defaults uses DIRECTORY_SEPARATOR as symbol.


.. php:function:: quickapps(string $key = null)

    Shortcut for reading QuickApps’s snapshot configuration.

    For example, ``quickapps('variables');`` maps to
    ``Configure::read('QuickApps.variables');``. If this function is used with no
    arguments, ``quickapps()``, the entire snapshot will be returned.


.. php:function:: option(string $name, mixed $default = false)

    Shortcut for getting an option value from "options" DB table.

    The second arguments, $default, is used as default value to return if no value
    is found. If not value is found and not default values was given this function
    will return ``false``::

        option('site_slogan');


.. php:function:: plugin(string $plugin = null)

    Shortcut for "Plugin::get()"::

        $specialSetting = plugin('MyPlugin')->settings['special_setting'];


.. php:function:: theme(string $name = null)

    Gets the given (or in use) theme as a package object::

        // current theme
        $bgColor = theme()->settings['background_color'];

        // specific theme
        $bgColor = theme('BlueTheme')->settings['background_color'];


.. php:function:: listeners()

    Returns a list of all registered event listeners in the system.


.. php:function:: packageSplit(string $name, bool $camelize)

    Splits a composer package syntax into its vendor and package name. Commonly used
    like `list($vendor, $package) = packageSplit($name);`. Example::

        list($vsendor, $package) = packageSplit('some-vendor/this-package', true);
        echo "{$vendor} : {$package}";
        // prints: SomeVendor : ThisPackage


.. php:function:: array_move(array $list, integer $index, string $direction)

    Moves up or down the given element by index from a list array of elements.

    If item could not be moved, the original list will be returned. Valid values for
    $direction are ``up`` or ``down``::

        array_move(['a', 'b', 'c'], 1, 'up');
        // returns: ['a', 'c', 'b']


.. php:function:: php_eval(string $code, array $args = [])

    Evaluate a string of PHP code.

    This is a wrapper around PHP’s eval(). It uses output buffering to capture both
    returned and printed text. Unlike eval(), we require code to be surrounded by
    tags; in other words, we evaluate the code as if it were a stand-alone PHP file.

    Using this wrapper also ensures that the PHP code which is evaluated can not
    overwrite any variables in the calling code, unlike a regular eval() call::

        echo php_eval('<?php return "Hello {$world}!"; ?>', ['world' => 'WORLD']);
        // output: Hello WORLD


.. php:function:: get_this_class_methods(string $class)

    Return only the methods for the given object. It will strip out inherited
    methods.


.. php:function:: str_replace_once(string $search, string $replace, string $subject)

    Replace the first occurrence only::

        echo str_replace_once('A', 'a', 'AAABBBCCC');
        // out: aAABBBCCC


.. php:function:: str_replace_last(string $search, string $replace, string $subject)

    Replace the last occurrence only::

        echo str_replace_once('A', 'a', 'AAABBBCCC');
        // out: AAaBBBCCC


.. php:function:: str_starts_with(string $haystack, string $needle)

    Check if $haystack string starts with $needle string::

        str_starts_with('lorem ipsum', 'lo'); // true
        str_starts_with('lorem ipsum', 'ipsum'); // false


.. php:function:: str_ends_with(string $haystack, string $needle)

    Check if $haystack string ends with $needle string::

        str_ends_with('lorem ipsum', 'm'); // true
        str_ends_with('dolorem sit amet', 'at'); // false


.. php:function:: language(string $key = null)

    Retrieves information for current language.

    Useful when you need to read current language’s code, direction, etc. It will
    return all the information if no ``$key`` is given::

        language('code');
        // may return: en-us

        language();
        // may return:
        [
            'name' => 'English',
            'code' => 'en-us',
            'iso' => 'en',
            'country' => 'US',
            'direction' => 'ltr',
            'icon' => 'us.gif',
        ]

    Accepted keys are:

    -  ``name``: Language’s name, e.g. ``English``, ``Spanish``, etc.

    -  ``code``: Localized language's code, e.g. ``en-us``, ``es``, etc.

    -  ``iso``: Language’s ISO 639-1 code, e.g. ``en``, ``es``, ``fr``, etc.

    -  ``country``: Language’s country code, e.g. ``US``, ``ES``, ``FR``, etc.

    -  ``direction``: Language writing direction, possible values are "ltr" or
       "rtl".

    -  ``icon``: Flag icon (it may be empty) e.g. ``es.gif``, ``en.gif``, icons
       files are located in Locale plugin’s ``/webroot/img/flags/`` directory, to
       render an icon using HtmlHelper you should do as follow:


.. php:function:: user()

    Retrieves current user’s information (logged in or not) as an entity object::

        $user = user();
        echo user()->name;
        // prints "Anonymous" if not logged in


Core Definition Constants
=========================

In addition to `CakePHP’s constants <http://book.cakephp.org/3.0/en/core-libraries
/global-constants-and-functions.html>`_, QuickAppsCMS’s provides some commonly used
constants. Most of the following constants refer to paths in your application.

.. php:const:: VENDOR_INCLUDE_PATH

    Absolute path to composer's "vendor" directory where quickapps & cakephp can be
    found. Includes trailing slash.

.. php:const:: SITE_ROOT

    Path to site’s root directory, where "webroot" directory can be found. No
    trailing slash.

.. php:const:: QUICKAPPS_CORE

    Path to QuickAppsCMS’s core directory, where "src" directory can be found.
    Includes trailing slash.

.. php:const:: USER_TOKEN_EXPIRATION

    Time in seconds for how long user’s token are valid. Defaults to ``DAY`` (24
    hours).

.. php:const:: ROLE_ID_ADMINISTRATOR

    ID for "administrator" role, must match the ID stored in DB. You should never
    change this value on production site.

.. php:const:: ROLE_ID_AUTHENTICATED

    ID for "authenticated" role, must match the ID stored in DB. You should never
    change this value on production site.

.. php:const:: ROLE_ID_ANONYMOUS

    ID for "anonymous" role, must match the ID stored in DB. You should never
    change this value on production site.

.. php:const:: CORE_LOCALE

    Language in which QuickAppsCMS’s core was written. This value is commonly used
    as fallback language and should NEVER be changed! alto. Defaults to ``en_US``


.. meta::
    :title lang=en: Core Libraries
    :keywords lang=en: functions,global function,library,libraries,snapshot,normalizePath,quickapps,option,php_eval,eval,php,listeners,pluginName,array_move,get_this_class_methods,str_replace_once,str_replace_last,str_starts_with,str_ends_with,language,user,session,loggin,replace,str_replace
