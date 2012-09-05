Multisite Installation
======================

There may be some situations where you wish to place QuickApps CMS's directories on different places on the filesystem.
This may be due to a shared host restriction, or maybe you just want a few of your web sites to share the same QuickApps CMS libraries.  
This section describes how to spread your QuickApps CMS directories across a filesystem.

First, realize that there are two main parts to a QACMS application:

* The core QACMS libraries, in `/QuickApps`.
* Your web site code:
    * `/Config`, database and system configuration.
    * `/Hooks`, custom hooks methods.
    * `/Locale`, custom translation packages
    * `/Modules`, installed modules of your site.
    * `/Themes`, installed themes of your site.
    * `/tmp`, cache and logs of your site created/used by the QACMS core.
    * `/webroot`, your site webroot.
    
To configure your QACMS installation, you'll need to make some changes to following file: 

* webroot/index.php

There is only one constants that you'll need to edit: 

* `QA_PATH`, should be set to the full path of your QuickApps CMS libraries folder.


EXAMPLE
-------

Imagine that you wanted to set up QACMS to work as follows:

* QACMS libraries will be placed in: `/usr/lib/qa_core`
* Peter's website directory will be: `/var/www/peter_site/`
* John's website directory will be: `/var/www/john_site/`


1.- You must modify `/var/www/peter_site/webroot/index.php` & `/var/www/john_site/webroot/index.php` files:

    define('QA_PATH', '/usr/lib/qa_core');

2.- Both `/var/www/peter_site/` and `/var/www/john_site/ directories should looks as follows:

* /var/www/\<peter_site|john_site\>/
    * Config/
    * Hooks/
    * Locale/
    * Modules/
    * Themes/
    * tmp/
    * webroot/
    * .gitignore
    * .htaccess
    * README.mdown

And that is all!.  
Peter and John are using only one copy of QuickApps CMS's core to run their websites. This has several adventages such as
reduced disk space ocupancy or easy updating of QuickApps CMS's core, etc.