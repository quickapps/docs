Field GUI
#########

Now you know how Field API works you might need an easy way to attach, and manage
fields for your tables. Field plugin provides an GUI (graphical user-interface) for
handling all this tasks, Field API GUI is packaged as a trait:
**Field\Controller\FieldUIControllerTrait**, you must simply attach this trait to an
empty controller and you are ready to go.

With this trait, Field plugin provides an user friendly GUI for manage entity’s
fields by attaching a series of actions over a ``clean`` controller.

**Usage:**

Beside adding ``use FieldUIControllerTrait;`` to your controller you MUST also
indicate the name of the table being managed using the ``$_manageTable`` property,
you must set this property to any valid table alias within your system (dot notation
is also allowed). For example:

.. code:: php

    namespace MyPlugin\Controller;

    use MyPlugin\Controller\MyPluginAppController;
    use Field\Controller\FieldUIControllerTrait;

    class MyCleanController extends MyPluginAppController
    {
        use FieldUIControllerTrait;
        protected $_manageTable = 'User.UserPhotos';
    }

In the example above, ``MyCleanController`` will be used to manage all fields
attached to the ``User.UserPhotos`` table. You can now access your controller as
usual and you will see Field API GUI in action.

.. note::

    In order to avoid trait collision you MUST always ``extend`` Field GUI using
    this trait over a ``clean`` controller. That is, an empty controller class with
    no methods (actions) defined.

Requirements
============

-  This trait should only be used over a clean controller.
-  You must define ``$_manageTable`` property in your controller.
-  Your Controller must be a backend-controller (under ``Controller\Admin`` namespace).

An exception will be raised if any of the requirements described above has not
accomplished.