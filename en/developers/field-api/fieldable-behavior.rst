Fieldable Behavior
##################

.. php:namespace:: Field\Model\Behavior

.. php:class:: FieldableBehavior

The Fieldable Behavior is the core of Field API, this behavior can be attached to
any table to make it "fiedable" and thus allow additional virtual columns to be
attached to it.

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

Accessing Virtual Properties
============================

Once you have your Entity (e.g. User Entity), you would probably need to get its
virtual fields and do fancy thing with them. Following with our User entity example:

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


Virtual Fields & WHERE Clauses
==============================

Same as in EAV API, you can construct SQL's WHERE clauses using any of the virtual
fields attached to your table. Every attached field has a "machine-name" (a.k.a.
field slug), you must use that machine-name when referring to a virtual column:

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