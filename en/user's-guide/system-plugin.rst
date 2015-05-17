System Plugin
#############

About
=====

The System plugin is integral to the site, and provides basic but
extensible functionality for use by other plugins and themes. Some
integral elements of QuickApps are contained in and managed by the
System plugin, including caching, enabling and disabling plugins and
themes and configuring fundamental site settings.

Uses
====

Hooktags
--------

A Hooktag is a QuickApps-specific code that lets you do nifty things
with very little effort. Hooktags can for example print current language
code (i.e. "en") or call especifics plugins/themes functions. Plugins
and themes are able to define their own hooktags. The System plugin
provides a series of buil-in hooktags as described below:


{locale OPTION/}
    Possible values of OPTION are: code, name, native or direction.

    -  code: Returns language’s ISO 639-2 code (en, es, fr, etc)
    -  name: Returns language’s english name (English, Spanish, German, French, etc)
    -  direction: Returns direction that text is presented. lft (Left To Right) or rtl
       (Right to Left)


{locale /}
    Shortcut for {language code/} which return current language code (en, es, fr,
    etc).


{t domain=DOMAIN}text to translate by domain{/t}
    Search for translation in specified domain, e.g: {t domain=system}Help{/t} will
    try to find translation for Help in "System" plugin translation table.


{t}text to translate using default domain{/t}
    Search for translation in default translation domain.


{url}/some_path/image.jpg{/url}
    Return well formatted url. URL can be an relative url (/node-type/my- post.html)
    or external (http://www.example.com/my-url).


{date format=FORMAT}TIME_STAMP_OR_ENGLISH_DATE{/date}
    Returns php result of date(FORMAT, TIME_STAMP_OR_ENGLISH_DATE). `More info about
    date() <http://www.php.net/manual/function.date.php>`__. It accepts both:
    numeric time stamp or English formatted date (Year-month-day Hours:Mins:Secs) as
    second parameter.


{date format=FORMAT /}
    Returns php result of date(FORMAT). `More info about date()
    <http://www.php.net/manual/function.date.php>`__.


{random}values,by,comma{/random}
    Returns a random value from the specified group. e.g.
    {random}one,two,three{/random}. If only two numeric values are given as group,
    then PHP function `rand(min, max)
    <http://www.php.net/manual/function.rand.php>`__ is returned. e.g.
    {random}3,10{/random}


Managing plugins
----------------

The System plugin allows users with the appropriate permissions to enable and
disable plugins on the Plugins administration page. QuickAppsCMS comes with a number
of core plugins, and each plugin provides a discrete set of features and may be
enabled or disabled depending on the needs of the site.

Managing themes
---------------

The System plugin allows users with the appropriate permissions to
enable and disable themes on the Appearance administration page. Themes
determine the design and presentation of your site. QuickAppsCMS comes
packaged with two core themes (FrontendTheme and BackendTheme).

Configuring basic site settings
-------------------------------

The System plugin also handles basic configuration options for your
site, including Date and time settings, Site name and other information.

.. meta::
    :title lang=en: System Plugin
    :keywords lang=en: system plugin,system,plugin,plugins,settings,site settings,hooktag,hooktags,core,enable plugin,install plugin, disable plugin
