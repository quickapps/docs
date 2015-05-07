Requirements
############

Before continue make sure you your server meets the following requirements:

-  Apache with mod_rewrite
-  PHP 5.4.19 or higher
-  mbstring extension installed
-  mcrypt extension installed
-  intl extension installed
-  fileinfo extension installed
-  Supported database storage engines:

   -  MySQL (5.1.10 or greater)
   -  PostgreSQL
   -  Microsoft SQL Server (2008 or higher)
   -  SQLite 3

-  Writing permission in your server

.. warning::

    Although PHP 5.4.19 is the minimum required version, we strongly recommend an
    higher version, such as PHP 5.5 or 5.6. These version are not only safer, but
    your website will be significantly faster as well.


Also the PHP installation has a few additional requirements. Which on most servers
these are default settings:

- A minimum of 32MB of memory allocated to PHP
- The PDO extension for handling DB connections
- The CURL extension
- The GD Extension


Browser and device support
==========================

QuickAppsCMS's default backend was designed and built on top of Twitter Bootstrap 3,
which is guaranteed to work optimally in any modern browser:


+------------+------------+-----------+-------------------+---------------+---------------+
|            | Chrome     | Firefox   | Internet Explorer | Opera         | Safari        |
+============+============+===========+===================+===============+===============+
| Android    | Supported  | Supported |                   | Not Supported | N/A           |
+------------+------------+-----------+                   +---------------+---------------+
| iOS        | Supported  | N/A       |        N/A        | Not Supported | Supported     |
+------------+------------+-----------+                   +---------------+---------------+
| Mac OS X   | Supported  | Supported |                   | Supported     | Supported     |
+------------+------------+-----------+-------------------+---------------+---------------+
| Windows    | Supported  | Supported |      Supported    | Supported     | Not Supported |
+------------+------------+-----------+-------------------+---------------+---------------+

