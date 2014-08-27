Field API
#########

The Fields API Allows additional fields to be attached to Tables. Any
Table (Nodes, Users, etc.) can use Field API to make itself
``field-able`` and thus allow fields to be attached to it.

The Field API defines two primary data structures, ``FieldInstance`` and
``FieldValue``:

-  FieldInstance: is a Field attached to a single Table. (Schema
   equivalent: column)
-  FieldValue: is the stored data for a particular [FieldInstance,
   Entity] tuple of your Table. (Schema equivalent: cell value)

**Basically, this behavior allows you to add *virtual columns* to your
table schema.**

Making a table "fieldable"
==========================

Simply by attaching the ``FieldableBehavior`` to any table will make it
fieldable.

.. code:: php

    $this->addBehavior('Field.Fieldable');

This behavior modifies each query of your table in order to merge
custom-fields records into each entity under the ``_fields`` property.

**Entity Example:**

.. code:: php

    $user = $this->Users->get(1);

    // $user's properties might look as follows:
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

In the example above, User entity has a custom field named ``user-age``
and its current value is 22. In the other hand, it also has a
``user-phone`` field but no information was given (Schema equivalent:
NULL cell).

As you might see, the ``_field`` key contains an array list of all
fields attached to every entity. Each field (each element under the
``_field`` key) is an object (Field Entity), and it have a number of
properties such as ``label``, ``value``, etc. All properties are
described below:

-  ``name``: Machine-name of this field. ex. ``article-body`` (Schema
   equivalent: column name).
-  ``label``: Human readable name of this field e.g.:
   ``User Last name``.
-  ``value``: Value for this [field, entity] tuple. (Schema equivalent:
   cell value)
-  ``extra``: Extra data for Field Handler.
-  ``metadata``: Metadata (an Entity Object).

   -  ``field_value_id``: ID of the value stored in ``field_values``
      table.
   -  ``field_instance_id``: ID of field instance (``field_instances``
      table) attached to the table.
   -  ``entity_id``: ID of the Entity this field is attached to.
   -  ``table_alias``: Name of the table this field is attached to. e.g:
      ``users``.
   -  ``description``: Something about this field: e.g.:
      ``Please enter your last name``.
   -  ``required``: 0\|1.
   -  ``settings``: Any extra information array handled by this
      particular field.
   -  ``view_modes``: Information about how this field should be
      rendered on each View Mode. Information is stored as
      ``view-mode-name`` => ``rendering-information``.
   -  ``handler``: class name of the Field Handler under ``Field``
      namespace. e.g.: ``TextField`` (namespaced name:
      ``Field\TextField``)
   -  ``errors``: Array of validation error messages, only on edit mode.

**Notes:**

-  The ``metadata`` key on every field is actually an entity object.
-  The ``_field`` key which holds all the fields is actually an instance
   of ``Field/Utility/FieldCollection``, which behaves as an array (so
   you can iterate over it). It adds some utility methods for handling
   fields, for instance it allows you to access an specific field by its
   corresponding numeric index or by its machine-name.

Accessing Field Properties
==========================

Once you have your Entity (e.g. User Entity), you would probably need to
get its attached fields and do fancy thing with them. Following with our
User entity example:

.. code:: php

    // In your controller
    $user = $this->Users->get($id);
    echo $user->_fields[0]->label . ': ' . $user->_fields[0]->value;
    // out: User Age: 22

    echo "This field is attached to '" . $user->_fields[0]->metadata->table_alias . "' table";
    // out: This field is attached to 'users' table;

Searching Over Custom Fields
============================

Fieldable Behavior allows you to perform WHERE clauses using any of the
fields attached to your table. Every attached field has a "machine-name"
(a.k.a. field slug), you should use this "machine-name" prefixed with
``:``, for example:

.. code:: php

    TableRegistry::get('Users')
        ->find()
        ->where(['Users.:first-name LIKE' => 'John%'])
        ->all();

``Users`` table has a custom field attached (first-name), and we are
looking for all the users whose ``first-name`` starts with ``John``.

Value vs Extra
==============

In the "Entity Example" above you might notice that each field attached
to entities has two properties that looks pretty similar, ``value`` and
``extra``, as both are intended to store information. Here we explain
the "why" of this.

Field Handlers may store complex information or structures. For example,
``AlbumField`` handler may store a list of photos for each entity. In
those cases you should use the ``extra`` property to store your array
list of photos, while ``value`` property should always store a
Human-Readable representation of your field's value.

In our ``AlbumField`` example, we could store an array list of file
names and titles for a given entity under the ``extra`` property. And we
could save photo's titles as space-separated values under ``value``
property:

.. code:: php

    // extra:
    [photos] => [
        ['title' => 'OMG!', 'file' => 'omg.jpg'],
        ['title' => 'Look at this, lol', 'file' => 'cats-fighting.gif'],
        ['title' => 'Fuuuu', 'file' => 'fuuuu-meme.png'],
    ]

    // value:
    "OMG! Look at this lol Fuuuu"

In our example when rendering an entity with ``AlbumField`` attached to
it, ``AlbumField`` should use ``extra`` information to create a
representation of itself, while ``value`` information would acts like
some kind of ``words index`` when using ``Searching over custom fields``
feature described above.

**Important:**

-  FieldableBehavior automatically serializes & unserializes the
   ``extra`` property for you, so you should always treat ``extra`` as
   an array.
-  ``Search over custom fields`` feature described above uses the
   ``value`` property when looking for matches. So in this way your
   entities can be found when using Field's machine-name in WHERE
   clauses.
-  Using ``extra`` is not mandatory, for instance your Field Handler
   could use an additional table schema to store entities information
   and leave ``extra`` as NULL. In that case, your Field Handler must
   take care of joining entities with that external table of
   information.

**Summarizing:** ``value`` is intended to store ``plain text``
information suitable for searches, while ``extra`` is intended to store
sets of complex information.


Enable/Disable Field Attachment
===============================

If for some reason you don't need custom fields to be fetched under the
``_field`` of your entities you should use the unbindFieldable(). Or
bindFieldable() to enable it again.

.. code:: php

    // there wont be a "_field" key on your User entity
    $this->User->unbindFieldable();
    $this->Users->get($id);

Field Handlers
==============

Field Handler are "Listeners" classes which must take care of storing,
organizing and retrieving information for each entity's field. All this
is archived using QuickAppsCMS's :doc:`events system events-system>`.

Similar to :doc:`Event Listeners <events-system>` and Hooktags,
Field Handlers classes must define a series of events, which has been
organized in two groups or "event subspaces":

-  ``Field.<FieldHandler>.Entity``: For handling Entity's related events
   such as ``entity save``, ``entity delete``, etc.
-  ``Field.<FieldHandler>.Instance``: Related to Field Instances events,
   such as "instance being detached from table", "new instance attached
   to table", etc.

Below, a list of available events:

**Entity events:**

**NOTE:** In order to make reading more comfortable the
``Field.<FieldHandler>.Entity.`` prefix has been trimmed from each event
name listed below. For example, ``display`` is actually
``Field.Field.<FieldHandler>.Entity.info``

-  ``display``: When an entity is being rendered.
-  ``edit``: When an entity is being rendered in ``edit`` mode. (backend
   usually).
-  ``beforeFind``: Before an entity is retrieved from DB.
-  ``beforeValidate``: Before entity is validated as part of save
   operation.
-  ``afterValidate``: After entity is validated as part of save
   operation.
-  ``beforeSave``: Before entity is saved.
-  ``afterSave``: After entity was saved.
-  ``beforeDelete``: Before entity is deleted.
-  ``afterDelete``: After entity was deleted.


**Instance events:**

**NOTE:** In order to make reading more comfortable the
``Field.<FieldHandler>.Instance.`` prefix has been trimmed from each
event name listed below. For example, ``info`` is actually
``Field.<FieldHandler>.Instance.info``

-  ``info``: When QuickAppsCMS asks for information about each
   registered Field.
-  ``settingsForm``: Additional settings for this field, should define
   the way the values will be stored in the database.
-  ``settingsDefaults``: Default values for field settings form's
   inputs.
-  ``settingsValidate``: Before instance's settings are changed, here
   you can apply your own validation rules.
-  ``viewModeForm``: Additional view mode settings, should define the
   way the values will be rendered for a particular view mode.
-  ``viewModeDefaults``: Default values for view mode settings form's
   inputs.
-  ``viewModeValidate``: Before view-mode's settings are changed, here
   you can apply your own validation rules.
-  ``beforeAttach``: Before field is attached to Tables.
-  ``afterAttach``: After field is attached to Tables.
-  ``beforeDetach``: Before field is detached from Tables.
-  ``afterDetach``: After field is detached from Tables.

Creating Field Handlers
-----------------------

As we mention early, Field Handler are simply Event Listeners classes
which should respond to the enormous list of event names described
above. In order to make this task easy you can simply create an new
Event Listener class and extend ``Field\Core\FieldHandler``, so instead
of implementing the EvenListener interface you should simply extend this
class.

For instance, we could create a ``Date`` Field Handler, aimed to provide
a date picker for every entity this field is attached to. You must
create a new Event Listener class under the ``Event`` directory of the
plugin defining this field.

.. code:: php

    // MyPlugin/src/Event/DateField.php
    namespace MyPlugin\Event;
    use Field\Core\FieldHandler;

    class DateField extends FieldHandler {

    }

``FieldHandler`` is a simple base class which automatically registers
all the events names a Field could handle (listed above), it has empty
methods which you should override with your own logic:

.. code:: php

    namespace MyPlugin;
    use Field\Core\FieldHandler;
    class DateField extends FieldHandler {

        public function entityDisplay(Event $event, $field, $options = []) {
            return 'HTML representation of $field';
        }

        public function entityBeforeSave(Event $event, $entity, $field, $options) {
            return true;
        }

        ...
    }

Check this class's documentation for deeper information.

Preparing Field Inputs
----------------------

Your Field Handler should somehow render some form elements (inputs,
selects, textareas, etc) when rendering Table's Entities in
``edit mode``. For this we have the ``Field.<FieldHandler>.Entity.edit``
event, which should return a HTML containing all the form elements for
[entity, field\_instance] tuple.

For example, lets suppose we have a ``TextField`` attached to ``Users``
Table for storing their ``favorite_food``, and now we are editing some
specific ``User`` Entity (i.e.: User.id = 4), so in the editing form
page we should see some inputs for change some values like ``username``
or ``password``, and also we should see a ``favorite_food`` input where
Users shall type in their favorite food. Well, your TextField Handler
should print something like this:

.. code:: html

    // note the `:` prefix
    <input name=":favorite_food" value="<current_value_from_entity>" />

To accomplish this, your Field Handler should properly catch the
``Field.<FieldHandler>.Entity.edit`` event, example:

.. code:: php

    public function entityEdit(Event $event, $field) {
      return '<input name=":' . $field->name . '" value="' . $field->value . '" />";
    }

As usual, the second argument ``$field`` contains all the information
you will need to properly render your form inputs.

You must tell to QuickAppsCMS that the fields you are sending in your
POST action are actually virtual fields. To do this, all your input's
``name`` attributes **must be prefixed** with ``:`` followed by its
machine (a.k.a. ``slug``) name:

.. code:: html

    <input name=":<machine-name>" ... />

You may also create complex data structures like so:

.. code:: html

    <input name=":album.name" value="<current_value>" />
    <input name=":album.photo.0" value="<current_value>" />
    <input name=":album.photo.1" value="<current_value>" />
    <input name=":album.photo.2" value="<current_value>" />

The above may produce a $\_POST array like below:

.. code:: php

    :album => [
        name => Album Name,
        photo => [
            0 => url_image1.jpg,
            1 => url_image2.jpg,
            2 => url_image3.jpg
        ]
    ],
    ...
    :other_field => ...,

**Remember**, you should always rely on View::elements() for rendering
HTML code:

.. code:: php

    public function editTextField(Event $event, $field) {
        $view = $event->subject;
        return $View->element('text_field_edit', ['field' => $field]);
    }

Creating an Edit Form
---------------------

In previous example we had an User edit form. When rendering User's
form-inputs usually you would do something like so:

.. code:: php

    <?php echo $this->Form->input('id', ['type' => 'hidden']); ?>
    <?php echo $this->Form->input('username'); ?>
    <?php echo $this->Form->input('password'); ?>

When rendering virtual fields you can pass the whole Field Object to
``FormHelper::input()`` method. So instead of passing the input name as
first argument (as above) you can do as follow:

.. code:: php

    <!-- Remember, custom fields are under the `_fields` property of your entity -->
    <?php echo $this->Form->input($user->_fields[0]); ?>
    <?php echo $this->Form->input($user->_fields[1]); ?>

That will render the first and second virtual field attached to your
entity. But usually you'll end creating some loop structure and render
all of them at once:

.. code:: php

    <?php foreach ($user->_fields as $field): ?>
        <?php echo $this->Form->input($field); ?>
    <?php endforeach; ?>

As you may see, ``Form::input()`` **automagically fires** the
``Field.<FieldHandler>.Entity.edit`` event asking to the corresponding
Field Handler for its HTML form elements. Passing the Field object to
``Form::input()`` is not mandatory, you can manually generate your input
elements:

.. code:: html

    <input name=":<?= $field->name; ?>" value="<?= $field->value; ?>" />

The ``$user`` variable used in these examples assumes you used
``Controller::set()`` method in your controller.

A more complete example:

.. code:: php

    // UsersController.php

    public function edit($id) {
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
            <?php echo $this->Form->input($field); ?>
        <?php endforeach; ?>
        <!-- /Custom Fields -->
        <?php echo $this->Form->submit('Save User'); ?>
    <?php echo $this->Form->end(); ?>


Field API UI
============

Now you know how Field API works you might need an easy way to attach,
and manage fields for your tables. Field plugin provides an UI
(user-interface) for handling all this tasks, Field API UI is packaged
as a trait piece of code: ``Field\Utility\FieldUIControllerTrait``, you
must simply attach this trait to an empty controller and you are ready
to go.

With this trait, Field plugin provides an user friendly UI for manage
entity's custom fields. It provides a field-manager user interface (UI)
by attaching a series of actions over a ``clean`` controller.

**Usage:**

Beside adding ``use FieldUIControllerTrait;`` to your controller you
MUST also indicate the name of the Table being managed using the
``$_manageTable`` property. For example:

.. code:: php

    namespace MyPlugin\Controller;

    use MyPlugin\Controller\MyPluginAppController;
    use Field\Utility\FieldUIControllerTrait;

    class MyCleanController extends MyPluginAppController {
        use FieldUIControllerTrait;

        protected $_manageTable = 'user_photos';
    }

In the example above, ``MyCleanController`` will be used to manage all
fields attached to ``user_photos`` table. You can now access your
controller as usual and you will see Field API UI in action.

**IMPORTANT:** In order to avoid trait collision you should always
``extend`` Field UI using this trait over a ``clean`` controller. This
is, a empty controller class with no methods defined. For instance,
create a new controller class
``MyPlugin\Controller\MyTableFieldManagerController`` and use this trait
to handle custom fields for "MyTable" database table.

Requirements
------------

-  This trait should only be used over a clean controller.
-  You must define ``$_manageTable`` property in your controller.
-  Your Controller must be a backend-controller (under
   ``Controller\Admin`` namespace).

