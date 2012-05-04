## About
The QuickApps module is integral to the site, and provides basic but extensible functionality for use by other modules and themes. Some integral elements of QuickApps are contained in and managed by the QuickApps module, including caching, enabling and disabling modules and themes and configuring fundamental site settings. 

## Uses
### Hooktags
A Hooktag is a QuickApps-specific code that lets you do nifty things with very little effort. hookTag can for example print current language code/name/nativeName or call especifics modules/themes functions. For example, block module has the 'block' hook wich will print out the indicated block by id:

    [block id=1/]
    
    This will render out the block with identifier equal to 1 

You are able to define your own custom Hooktags functions in: APP/View/Helper/CustomHooktagssHelper.php
Also modules and themes are able to define their own Hooktags

***

Some useful built-in Hooktags are: 

    [language.OPTION]
    
    Possible values of OPTION are: code, name, native or direction.
    code: Returns language's ISO 639-3 code (eng, spa, fre, etc)
    name: Returns language's english name (English, Spanish, German, French, etc)
    native: Returns language's native name (English, Español, Deutsch, Fraçais, etc)
    direction: Returns direction that text is presented. lft (Left To Right) or rtl (Right to Left)
***
    [language]
    
    Shortcut for [language.code] wich return current language code (eng, spa, etc). 
***    
    [t=domain@@text to translate by domain]
    
    Search for translation in specified domain, e.g: [t=system@@Help] will try to find translation for `Help` in 
    `system` module's translation table.

***
    [t=text to translate using default domain]
    
    Search for translation in (in the following order, if one fails then try the next method):
    - active runing module domain. 
    - default domain ([t=default@@...]). 
    - translatable entries table. (see `Locale` module)
***
    [url=/relative_url/image.jpg] or [url]relative url/image.jpg[/url]
    
    Return well formatted url. URL can be an relative url (/article/my-post.html) or external (http://www.domain.com/my-url).
***
    [date=FORMAT@@TIME_STAMP_OR_ENGLISH_DATE]
    
    Returns php result of date(FORMAT, TIME_STAMP_OR_ENGLISH_DATE). (http://www.php.net/manual/function.date.php)
    It accepts both: numeric time stamp or english formatted date 
    (Year-month-day Hours:Mins:Secs) as second parameter.
***
    [date=FORMAT] 
    
    Returns php result of date(FORMAT). (http://www.php.net/manual/function.date.php)


### Managing modules
The QuickApps module allows users with the appropriate permissions to enable and disable modules on the Modules administration page. QuickApps CMS comes with a number of core modules, and each module provides a discrete set of features and may be enabled or disabled depending on the needs of the site.

### Managing themes
The QuickApps module allows users with the appropriate permissions to enable and disable themes on the Appearance administration page. Themes determine the design and presentation of your site. QuickApps CMS comes packaged with one core theme (Default).

### Configuring basic site settings
The QuickApps module also handles basic configuration options for your site, including Date and time settings, Site name and other information. 