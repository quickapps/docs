Developers FAQ
##############


Which PHP version does QuickApps work with ?
============================================

To run it on your server, you need:

-  Apache with mod_rewrite
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


Can I change user tokens expiration time ?
==========================================

User tokens are random strings used by QuickAppsCMS to provide password recovery
mechanisms, so users can recover their login credentials and then login into the
system again.

For security reasons these tokens are valid for a period of 24 hours since created,
this value can be changed by defining the **USER_TOKEN_EXPIRATION** PHP constant in
your site's **bootstrap.php** file. For instance:

.. code:: php

    define('USER_TOKEN_EXPIRATION', 6 * HOUR);

This will make tokens stand for 6 hours after their are created. Valid time
constants are: **SECOND**, **MINUTE**, **HOUR**, **DAY**, **WEEK**, **MONTH** and
**YEAR**.


Is it possible to launch QuickAppsCMS on PHP ver. < 5.4.19 somehow ?
====================================================================

No it isn't. QuickApps has specific PHP-features which is available only
on PHP v5.4.19 or higher. Moreover it uses CakePHP v3.0 as framework,
which works only on PHP v5.4.19 or higher.


Is there a way to send e-mails using my own SMTP server ?
=========================================================

By default all e-mail messages that are sent by QuickAppsCMS (such as user’s welcome
message, user's password recovery instructions, and so on) are sent using PHP’s
`mail() <http://php.net//manual/en/function.mail.php>`__ function. Although this
should be enough for most cases, you can tell QuickAppsCMS to send those messages
using your own SMTP server, to do so, you must edit your site’s settings file
``/config/settings.php`` as follow:

.. code:: php

    return [
        // ... DB settings and etc,
        'EmailTransport' => [
            'default' => [
                'className' => 'Smtp',
                'host' => 'YOUR_HOST',
                'port' => PORT,
                'username' => 'ACCOUNT',
                'password' => 'SECRET',
            ],
        ],
    ];

For instance you could use **GMAIL** for sending your emails:

.. code:: php

    $config = [
        // ... DB settings and etc,
        'EmailTransport' => [
            'default' => [
                'className' => 'Smtp',
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => 'ACCOUNT@gmail.com',
                'password' => 'SECRET',
            ],
        ],
    ];

.. meta::
    :title lang=en: FAQ
    :keywords lang=en: faq,developers,php,requirements