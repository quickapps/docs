Installation
============

Before continue make sure you your server meets the following requirements.



Requirements
------------

* Apache with mod_rewrite
* PHP 5.4.19 or higher
* mbstring extension installed
* mcrypt extension installed
* intl extension installed
* fileinfo extension installed
* PHP safe mode disabled
* Supported database storage engines:
   * MySQL (5.1.10 or greater)
   * PostgreSQL
   * Microsoft SQL Server (2008 or higher)
   * SQLite 3
* Write permission in your server



Installing QuickApps CMS
------------------------

You must install QuickAppsCMS using [composer](http://getcomposer.org).
QuickApps CMS is designed to run as a stand alone application, so you must
use the [website skeleton](https://github.com/QuickAppsCMS/website) as
starting point.

Installing the web skeleton is fast and easy:

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update composer self-update.
2. Run php composer.phar create-project -s dev quickapps/website [your_website_name].

If Composer is installed globally, run:

> composer create-project -s dev quickapps/website [website_name]

After composer is done visit http://example.com/ and start QuickAppsCMS installation.



Getting Help
------------

If you're stuck, there are a number of places you can [get help](getting-started/help.md).
