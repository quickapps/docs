Field API
#########


Field API is built on top of :doc:`EAV API <eav-api>`. They both work pretty similar
as they both allows to attach additional information to tables. However, the main
difference between this two APIs is that Field API allows you to create more complex
data structures; it was designed to control every aspect of the information being
managed, from how the information is stored in DB to how it is rendered and
presented to final users.

Any table (Nodes, Users, etc.) can use Field API to make itself ``fieldable`` and
thus allow additional columns to be attached to it. To do this, the Field API
defines two primary data structures, ``FieldInstance`` and ``FieldValue``:

-  FieldInstance: is a "Field" attached to a single Table. (Schema equivalent: column)
-  FieldValue: is the stored data for a particular [FieldInstance, Entity] tuple of
   your Table. (Schema equivalent: cell value)

.. note::

    Field API is built on top of EAV API, so please consider reading :doc:`EAV API
    documentation <eav-api>` before continue.


Making a table "fieldable"
==========================

Simply by attaching the ``FieldableBehavior`` to any table will make it fieldable.

.. code:: php

    $this->addBehavior('Field.Fieldable');

This behavior modifies each entity fetched from DB and merges custom fields records
into each entity's ``_fields`` property.

**Entity Example:**

.. code:: php

    $user = $this->Users->get(1);

    // $user’s properties might look as follows:
    [id] => 1,
    [password] => e10adc3949ba59abbe56e057f20f883e,
    ...
    [_fields] => [
        [0] => [
            [name] => user-age,
            [label] => User Age,
            [value] => 22,
            [extra] => null,
            [metadata] => [ ... ]
        ],
        [1] => [
            [name] => user-phone,
            [label] => User Phone,
            [value] => null, // no data stored
            [extra] => null, // no data stored
            [metadata] => [ ... ]
        ],
        ...
        [n] => [ ... ]
    )

In the example above, User entity has a custom field named ``user-age`` and its
current value is 22. In the other hand, it also has a ``user-phone`` field but no
information was given (Schema equivalent: NULL cell).

As you might see, the ``_field`` key contains an array list of all fields attached
to every entity. Each field (each element under the ``_field`` key) is an object
(Field Entity), and it have a number of properties such as ``label``, ``value``,
etc. All properties are described below:

-  name: Machine-name (a.k.a slug). Schema equivalent: column name.
-  label: Human readable name of this field e.g.: ``User Last name``.
-  value: Value for this [field, entity] tuple. (Schema equivalent: cell value)
-  extra: Extended value information.
-  metadata: Metadata (an Entity Object).

   -  value_id: ID of the value stored in ``eav_values`` table.
   -  instance_id: ID of field instance (``field_instances`` table) attached to the
      table.
   -  instance_name: Same as "name" key.
   -  table_alias: Name of the table this field is attached to. e.g: ``users``.
   -  bundle: Bundle name within "table_alias" to which this field belongs to.
   -  handler: Name of the Field Handler.
   -  entity_id: ID of the Entity (User, Article, etc) this field is attached to.
   -  type: value's data type (datetime, decimal, int, text or varchar)
   -  required: true or false.
   -  description: Something about this field: e.g.: "Type in your name".
   -  settings: Any extra information array handled by this particular field.
   -  view_modes: Information about how this field should be rendered on each View
      Mode. Information is stored as ``view-mode-name`` => ``rendering-
      information``.
   -  entity: Entity reference to which field is attached to.
   -  errors: Array of validation error messages, only on edit mode.

.. note::

    -  The ``metadata`` key on every field is actually an entity object. So you
       should access its properties as ``$field->metadata->get('settings')``.

    -  The ``_field`` key which holds all the fields is actually an instance of
       ``Field/Utility/FieldCollection``, which behaves as an array (so you can
       iterate over it). It adds some utility methods for handling fields, for
       instance, it allows you to access an specific field by its corresponding
       numeric index or by its machine-name.

Accessing Field Properties
==========================

Once you have your Entity (e.g. User Entity), you would probably need to get its
attached fields and do fancy thing with them. Following with our User entity
example:

.. code:: php

    // In your controller
    $user = $this->Users->get($id);
    echo $user->_fields[0]->label . ': ' . $user->_fields[0]->value;
    // out: User Age: 22

    echo "This field is attached to '" . $user->_fields[0]->metadata->table_alias . "' table";
    // out: This field is attached to 'users' table;

You can either get an specific field by its numeric index or by its machine-name.
The above example and the one below are equivalents:

.. code:: php

    // In your controller
    $user = $this->Users->get($id);
    echo $user->_fields['user-age']->label . ': ' . $user->_fields['user-age']->value;
    // out: User Age: 22

    echo "This field is attached to '" . $user->_fields['user-age']->metadata->table_alias . "' table";
    // out: This field is attached to 'users' table;


Searching Over Custom Fields
============================

Same as in EAV API, you can create WHERE clauses using any of the fields attached to
your table. Every attached field has a "machine-name" (a.k.a. field slug):

.. code:: php

    TableRegistry::get('Users')
        ->find()
        ->where(['Users.first-name LIKE' => 'John%'])
        ->all();

In this example the ``Users`` table has a custom field attached (first-name), and we
are looking for all the users whose ``first-name`` starts with ``John``.

You can use any conjunction operator valid for your Field's data type (see "Field
Data Types" section).


Field Data Types
================

Field must store information using basic data types such as (int, decimal, etc),
field information will be stored in table cells specific to that data type.
Supported data types are:

- datetime: For storage of date or datetime values.
- decimal: For storage of floating values.
- int: For storage of integer values.
- text: For storage of long strings.
- varchar: For storage of strings maximum to 255 chars length.

In some cases Field Handlers may store complex information or structures not
supported by the basic types listed above and out of the scope of relational
databases, for instance collections of values, objects, etc. In those cases you can
use the ``extra`` property as described in sections below.


Indicating field's data type
----------------------------

When creating Field Handlers (see "Field Handlers" section below) you must indicate
which type of data your field will handle (listed above), to do this you must simply
implement the ``info()`` method and return an array indicating basic information
about the field itself, including its type among other information. For example, for
TextField handler:

.. code:: php

    use Field\Handler;

    class TextField extend Handler
    {
        public function info()
        {
            return [
                'type' => 'datetime',
                // .. other options
            ];
        }
    }

See "Field Information" to see a list of all supported options.

Value vs Extra
==============

You might notice that each field attached to entities has two properties that looks
pretty similar, ``value`` and ``extra``, as both are intended to store information.
Here we explain the "why" of this.

Example: Using the "extra" property
-----------------------------------

For example, an ``AlbumField`` handler may store a list of photos for each entity.
In those cases you should use the ``extra`` property to store your array list of
photos. We could store an array list of file names and titles for a given entity
under the ``extra`` property, and we could save photo’s titles as space-separated
values under ``value`` property:

.. code:: php

    // extra:
    [photos] => [
        ['title' => 'OMG!', 'file' => 'omg.jpg'],
        ['title' => 'Look at this, lol', 'file' => 'cats-fighting.gif'],
        ['title' => 'Fuuuu', 'file' => 'fuuuu-meme.png'],
    ]

    // value:
    "OMG! Look at this lol Fuuuu"

In our example when rendering an entity with ``AlbumField`` attached to it,
``AlbumField`` should use ``extra`` information to create a representation of
itself, while ``value`` information would acts like some kind of ``words index``
when using ``Searching over custom fields`` feature described above.

IMPORTANT
  -  FieldableBehavior automatically serializes & unserializes the ``extra``
     property for you, so you should always treat ``extra`` as an array or object
     (or any serializable structure).

  -  ``Search over custom fields`` feature described above uses the ``value``
     property when looking for matches. So in this way your entities can be found
     when using Field’s machine-name in WHERE clauses.

SUMMARIZING
    ``value`` is intended to store basic typed information suitable for searches,
    while ``extra`` CAN be used to store sets of complex information.


Enable/Disable Field Attachment
===============================

If for some reason you don't need custom fields to be fetched under the ``_field``
of your entities you should use the unbindFieldable(). Or bindFieldable() to enable
it again.

.. code:: php

    // there wont be a "_field" key on your User entity
    $this->User->unbindFieldable();
    $this->Users->get($id);

Field Handlers
==============

Field Handler are simple classes extending from ``Field\Handler``; they are
responsible of storing, organizing, rendering and retrieving information for each
entity’s virtual-columns (fields).

Field handlers are always defined by some plugin, which means they cannot exists by
their own. Plugins are allowed to define an unlimited number of Field Handler
classes by placing them under the "Field/" directory. For instance:

::

    Blog/
    └── src/
        ├── Controller/
        └── Field/
            ├── MyFieldHandler1.php
            ├── MyFieldHandler2.php
            └── MyFieldHandler3.php

Field Handlers classes must extend ``Field\Handler`` and override its methods
according to its needs. The ``Field\Handler`` provides a number of predefined method
which names should be descriptive enough to let you what they do. Below a list of
all this methods:



Creating Field Handlers
-----------------------

As we mention early, Field Handler are just classes extending the ``Field\Handler``
class. To create a new Field Handler you must simply create a new class extending
``Field\Handler`` and place it under the "Field/" directory of the plugin defining
such Field.

In this example we'll be creating a ``Date`` Field Handler aimed to provide a date
picker for every entity this field is attached to. To start with, we'll create the
following class:

.. code:: php

    // Blog/src/Field/DatePicker.php
    namespace Blog\Field;


    use Field\Handler;
    use Field\Model\Entity\Field;
    use Field\Model\Entity\FieldInstance;

    class DatePicker extends Handler
    {
    }

Once created we must start overriding predefined methods provided by
``Field\Handler`` according to our needs.

.. note::

    Check ``Field\Handler`` API documentation for deeper information.


Field Information
-----------------

Fields are allowed to indicate some configuration parameters by implementing the
``info()`` method. QuickAppsCMS may asks for such information when required; you
must simply implement the ``info()`` method and return an array as ``option`` =>
``value``. Valid options are:

- type (string): The type of value this field will handle (defaults to ``varchar``).
  Valid types are (see "Field Data Types" for more information):

  - datetime
  - decimal
  - int
  - text
  - varchar

- name (string): The name of the handler this field will respond to. e.g.
  ``TextField`` for handling the storage of plain text information. Defaults to the
  name of the class **excluding** name space.

- description (string): Brief description about the field itself. Defaults to the
  name of the class **excluding** name space.

- hidden (string): True indicates that users cannot configure this field trough the
  administration section (Field UI). Defaults to ``false`` (users can configure).

- maxInstances (int): Maximum number instances of this field a table can have. Set
  to **zero (0) to indicates no limits**. Defaults to 0.

- searchable (bool): Whether this field can be used in SQL's WHERE clauses.


**EXAMPLE:**

.. code:: php

    // Blog/src/Field/DatePicker.php
    namespace Blog\Field;

    use Field\Handler;
    use Field\Model\Entity\Field;
    use Field\Model\Entity\FieldInstance;

    class DatePicker extends Handler
    {

        /**
         * {@inheritDoc}
         */
        public function info()
        {
            return [
                'type' => 'datetime',
                'name' => 'BlogDate',
                'description' => 'Provides date picker to blogs.',
                'hidden' => false,
                'maxInstances' => 0,
                'searchable' => true,
            ];
        }
    }


Edit Mode
---------

Your Field Handler should somehow render some form elements (inputs, selects,
textareas, etc) when rendering Table’s Entities in ``edit mode`. For this we have
the ``edit()`` method, which should return HTML code containing all the form
elements for the field attached to certain entity.

For example, lets suppose we have a ``TextField`` attached to ``Users`` Table for
storing their ``favorite-food``. When editing some specific ``User`` Entity (i.e.
User.id = 4) we should see some form inputs for change values like ``username`` or
``password``, but also we should be able to change the value of our virtual column
``favorite-food``, that is Field Handler must provide an input element where users
shall type in their favorite food. To do this, our TextField Handler should print
something like this:

.. code:: html

    <input name="favorite-food" value="<current_value>" />

To accomplish this task, our Field Handler must properly implement the ``edit()``
method, example:

.. code:: php

    public function edit(Field $field, View $view)
    {
        return '<input name="' . $field->name . '" value="' . $field->value . '" />";
    }

As usual, the first argument ``$field`` contains all the information you will need
to properly render your form inputs. You may also create complex data structures
like so:

.. code:: html

    <input name="album.name" value="<current_value>" />
    <input name="album.photo.0" value="<current_value>" />
    <input name="album.photo.1" value="<current_value>" />
    <input name="album.photo.2" value="<current_value>" />

The above may produce a $_POST array like below:

.. code:: php

    'album' => [
        'name' => 'Album Name',
        'photo' => [
            0 => 'url_image1.jpg',
            1 => 'url_image2.jpg',
            2 => 'url_image3.jpg',
        ]
    ],

.. note::

    You should always rely on ``View::element()`` when rendering HTML code. Instead
    printing HTML code directly from PHP you should place your HTML code into a view
    element and render it using ``View::element()`` method using the second argument
    **$view**, which is the View instance being used at that time. For example::

        public function edit(Field $field, View $view)
        {
            return $view->element('Blog.text_field_edit', ['field' => $field]);
        }

Creating an Edit Form
---------------------

In previous example we had an User edit form. When rendering User’s form-inputs
usually you would do something like so:

.. code:: php

    <?php echo $this->Form->input('id', ['type' => 'hidden']); ?>
    <?php echo $this->Form->input('username'); ?>
    <?php echo $this->Form->input('password'); ?>

When rendering virtual fields you can pass the whole Field Object to
``FormHelper::input()`` method. So instead of passing the input name as first
argument (as above example) you can do as follow:

.. code:: php

    <!-- Remember, custom fields are under the `_fields` property of your entity -->
    <?php echo $this->Form->input($user->_fields[0]); ?>
    <?php echo $this->Form->input($user->_fields[1]); ?>

That will render the first and second virtual field attached to your entity. But
usually you'll end creating some loop structure and render all of them at once:

.. code:: php

    <?php foreach ($user->_fields as $field): ?>
        <?php echo $this->Form->input($field); ?>
    <?php endforeach; ?>

The``Form::input()`` method **automagically invokes** the ``edit()`` method of the
corresponding Field Handler asking for its HTML form elements. Passing the Field
object to ``Form::input()`` is not mandatory, you can manually generate your input
elements:

.. code:: html

    <input name="<?php echo $field->name; ?>" value="<?php echo $field->value; ?>" />

.. note::

    The ``$user`` variable used in these examples assumes you used
    ``Controller::set()`` method in your controller.

A more complete example:

.. code:: php

    // UsersController.php
    public function edit($id)
    {
        $this->set('user', $this->Users->get($id));
    }

.. code:: php

    <!-- edit.ctp -->
    <?php echo $this->Form->create($user); ?>
        <?php echo $this->Form->hidden('id'); ?>
        <?php echo $this->Form->input('username'); ?>
        <?php echo $this->Form->input('password'); ?>

        <!-- Custom Fields -->
        <?php foreach ($user->_fields as $field): ?>
            <!-- This triggers "{$field->metadata->handler}.Entity.edit" -->
            <?php echo $this->Form->input($field); ?>
        <?php endforeach; ?>
        <!-- /Custom Fields -->

        <?php echo $this->Form->submit('Save User'); ?>
    <?php echo $this->Form->end(); ?>


Field API UI
============

Now you know how Field API works you might need an easy way to attach, and manage
fields for your tables. Field plugin provides an UI (user-interface) for handling
all this tasks, Field API UI is packaged as a trait:
**Field\Controller\FieldUIControllerTrait**, you must simply attach this trait to an
empty controller and you are ready to go.

With this trait, Field plugin provides an user friendly UI for manage entity’s
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
usual and you will see Field API UI in action.

.. note::

    In order to avoid trait collision you MUST always ``extend`` Field UI using
    this trait over a ``clean`` controller. That is, an empty controller class with
    no methods (actions) defined.

Requirements
------------

-  This trait should only be used over a clean controller.
-  You must define ``$_manageTable`` property in your controller.
-  Your Controller must be a backend-controller (under ``Controller\Admin`` namespace).

An exception will be raised if any of the requirements described above has not
accomplished.

.. meta::
    :title lang=en: Field API
    :keywords lang=en: api,fields,field,behavior,cck,eav,fieldable,entity,custom field,search,render field,form input
