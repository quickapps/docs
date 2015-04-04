Notifications API
#################

The Notification API allows you to send emails to users. QuickAppsCMS may
automatically sends messages, for example, welcoming message, password recovery
instructions, etc.

These email messages are sent using the ``User\Notification\NotificationManager``
class, this class provides a simple mechanism for sending emails to users, its basic
usage is described as follow:

.. code:: php

    $user = $this->Users->get($id);
    $result = NotificationManager::welcome($user)->send();

QuickAppsCMS comes with a few built-in messages:

- welcome
- activated
- blocked
- cancelRequest
- canceled
- passwordRequest

You can send messages of these types by invoking as they were class methods:
"NotificationManager::welcome()", "NotificationManager::activated()", etc.


Registering new messages
------------------------

More messages can be registered (or overwritten) to this class using the
`addMessage()` method as follows:

.. code:: php

    NotificationManager::addMessage('bye', 'ClassName\Extending\BaseMessage');

After registered you can start sending messages of `bye` type as below:

.. code:: php

    NotificationManager::bye($user, $optionsArray)->send();


Creating Message Types
----------------------

Messages types are handled by classes extending the base class
``User\Notification\Message\BaseMessage``. You can create your own messages by
extending this class and overwrite the ``send()`` method as described below:

.. code:: php

    use User\Notification\Message\BaseMessage;

    class UsersOffersMessage extends BaseMessage
    {

        /**
         * {@inheritDoc}
         */
        public function send()
        {
            $this
                ->subject('SET EMAIL SUBJECT')
                ->body('SET EMAIL BODY');
            return true;
        }
    }

Similar to controllers, you can load and use any Model within your message class
methods:


.. code:: php

        /**
         * {@inheritDoc}
         */
        public function send()
        {
            $this->loadModel('Shopping.Offers');
            $body = $this->_prepareOffersMessage($this->Offers->find()->all());

            $this
                ->subject('Check this offers!')
                ->body($body);
            return true;
        }


Message Variables
~~~~~~~~~~~~~~~~~

Message's subject and body are allowed to contain special variables (a.k.a.
placeholders), these variables will be replaced by dynamic information when the
message is send to the user. Variables looks as follow:

::

    {{my-variable-name}}


Notification API comes with some built-in variables:

- {{user:name}}
- {{user:username}}
- {{user:email}}
- {{user:activation-url}}
- {{user:one-time-login-url}}
- {{user:cancel-url}}
- {{site:name}}
- {{site:url}}
- {{site:description}}
- {{site:slogan}}
- {{site:login-url}}

For example, the subject "Hello {{user:name}}!" will be converted to "Hello John!"
when message is sent to John user.

If you need to provide customized variables you must overwrite the
``_parseVariables()`` method as follow:

.. code:: php

        /**
         * {@inheritDoc}
         */
        public function _parseVariables($text)
        {
            // parse built-in variables
            $text = parent::_parseVariables($text);

            // parse custom variables and return resulting text
            return str_replace([
                '{{user:age}}',
                '{{user:favorite-food}}',
            ], [
                $this->_user->get('age'),
                $this->_user->get('favorite-food'),
            ]);
        }

Check ``User\Notification\Message\BaseMessage`` class documentation for more
details.