FAQ
###

Which PHP version does QuickApps work with ?
============================================

To run it on your server, you need:

-  Apache with mod\_rewrite
-  **PHP 5.4.19 or higher**
-  mbstring extension installed
-  mcrypt extension installed
-  intl extension installed
-  fileinfo extension installed
-  PHP safe mode disabled
-  Supported database storage engines:

   -  MySQL (5.1.10 or greater)
   -  PostgreSQL
   -  Microsoft SQL Server (2008 or higher)
   -  SQLite 3

-  Write permission in your server

Is it possible to launch QuickApps on PHP ver. < 5.4.19 somehow ?
=================================================================

No it isn't. QuickApps has specific PHP-features which is available only
on PHP v5.4.19 or higher. Moreover it uses CakePHP v3.0 as framework,
which works only on PHP v5.4.19 or higher.

.. meta::
    :title lang=en: FAQ
    :keywords lang=en: faq,developers,php,requirements