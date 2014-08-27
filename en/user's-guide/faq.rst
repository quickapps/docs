FAQ
===

What does QuickApps cost? If it is free, how do you make a profit?
------------------------------------------------------------------

QuickAppsCMS is free and doesn't cost a penny (it is distributed under
the `GNU GENERAL PUBLIC LICENSE <http://www.gnu.org/copyleft/gpl.html>`__.
We make profit just by using this application in our private projects.

What about contributions ?
--------------------------

We accept any contributions useful for the project such as:

-  `Bug-fixes <https://github.com/QuickAppsCMS/QuickApps-CMS/issues?sort=updated&direction=desc&state=closed>`__
   and improvements
-  :doc:`Programming </developers>` free plugins or themes

How do I create my own theme ?
------------------------------

Check the :doc:`designer documentation </designers>`.

Is there a way to disable the ``mailto:`` autolink feature ?
------------------------------------------------------------

By default QuickApps will try to make linkable every email address
present in your contents. To disable this feature you must simply escape
your email address by using a backslash:

::

    \demo@mail.com

.. meta::
    :title lang=en: FAQ
    :keywords lang=en: faq,themes,help,contributions,designer


Is there a way to send e-mail using SMTP ?
------------------------------------------

By default all e-mail messages that are sent by QuickAppsCMS (such as user's
welcome message, user's password recovery instructions and so on) are sent using
PHP's `mail() <http://php.net//manual/en/function.mail.php>`__
function. Although this should be enough for most cases, you can tell
QuickAppsCMS to send those messages using your own SMTP server, to do this
you must edit your site's settings file ``/config/settings.php`` as follow:

.. code:: php

    $config = [
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

For instance you could use **Gmail** for sending your emails:

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
