Field Handler Objects
#####################

.. php:namespace:: Field

.. php:class:: Handler

Field Handlers objects are simple classes extending the ``Field\Handler`` base
class; they are responsible of storing, organizing, retrieving and rendering
information for each entity’s virtual-columns (fields).

Field Handlers classes cannot exists by their own, instead they must be defined by
some plugin. Plugins are allowed to define an unlimited number of Field Handlers
classes by placing them under the "Field/" directory of the plugin. For instance:

::

    Blog/
    └── src/
        ├── Controller/
        └── Field/
            ├── MyFieldHandler1.php
            ├── MyFieldHandler2.php
            └── MyFieldHandler3.php


Field Handler Lifecycle Callbacks
=================================

Field Handlers classes must extend ``Field\Handler`` and override its methods
according to its needs. The ``Field\Handler`` provides a number of predefined
methods which are responsible of controlling Field lifecycle:

info
----

.. php:method:: info()

Returns an array of information of this field. Valid options are:

- type (string): Type of data this field stores, possible values are: datetime,
  decimal, int, text, varchar.

- name (string): Human readable name of this field. ex. `Selectbox` Defaults to
  class name.

- description (string): Something about what this field does or allows to do.
  Defaults to class name.

- hidden (bool): If set to false users can not use this field via Field UI.
  Defaults to true, users can use it via Field UI.

- maxInstances (int): Maximum number instances of this field a table can have. Set
  to 0 to indicates no limits. Defaults to 0.

- searchable (bool): Whether this field can be searched using WHERE clauses.

render
------

.. php:method:: render(Field $field, View $view)

Defines how the field will actually display its contents when rendering entities.
You can use ``$view->viewMode();`` to get the view-mode being used when rendering the
entity.

edit
----

.. php:method:: edit(Field $field, View $view)

Renders the field in edit mode.

fieldAttached
-------------

.. php:method:: fieldAttached(Field $field, View $view)

Triggered when custom field is attached to entity under the "_fields" property. This
method is commonly used to alter custom field values before it gets attached to
entity. For instance, set default values.

beforeFind
----------

.. php:method:: beforeFind(Field $field, array $options, $primary)

Triggered on entity's "beforeFind" event. Can be used as preprocessor, as fields can
directly alter the entity's properties before it's returned as part of a find query.
Returning ``NULL`` will cause the entity to be removed from the resulting find
collection. In the other hand, returning ``FALSE`` will halt the entire find
operation. Otherwise you **MUST RETURN TRUE**.

validate
--------

.. php:method:: validate(Field $field, Validator $validator)

After an entity is validated as part of save process. This is where Fields must
validate their information. To do so, they should alter the provided Validator
instance, this instance will be later used to validate the information. If you want
to halt the save and validation process you can return FALSE.

beforeSave
----------

.. php:method:: beforeSave(Field $field, $post)

Triggered before each entity is saved. Returning a ``FALSE`` will halt the save
operation.

afterSave
---------

.. php:method:: afterSave(Field $field, $post)

Triggered after each entity is saved.

beforeDelete
------------

.. php:method:: beforeDelete(Field $field)

Before an entity is deleted from database. Returning FALSE will halt the delete
operation.

afterDelete
-----------

.. php:method:: afterDelete(Field $field)

After an entity was deleted from database.

settings
-----------

.. php:method:: settings(FieldInstance $instance, View $view)

Renders all the form elements to be used on the field's settings form. Field
settings will be the same for all shared instances of the same field and should
define the way the value will be stored in the database.

defaultSettings
---------------

.. php:method:: defaultSettings(FieldInstance $instance)

Returns an array of default values for field settings form's inputs.

validateSettings
----------------

.. php:method:: validateSettings(FieldInstance $instance, array $settings, Validator $validator)

Triggered before instance's settings are changed. Here is where Field Handlers can
apply custom validation rules to their settings.

viewModeSettings
----------------

.. php:method:: viewModeSettings(FieldInstance $instance, View $view, $viewMode)

Renders all the form elements to be used on the field view mode form. Here is where
you should render form elements to hold settings about **how Entities should be
rendered for a particular View-Mode**. You can provide different input elements
depending on the view-mode, you can use ``$viewMode`` to distinct between each view
modes.

defaultViewModeSettings
-----------------------

.. php:method:: defaultViewModeSettings(FieldInstance $instance, $viewMode)

Returns an array of defaults values for each input in the view modes form. You can
provide different default values depending on the view mode, you can use
``$viewMode`` to distinct between view modes.

validateViewModeSettings
------------------------

.. php:method:: validateViewModeSettings(FieldInstance $instance, array $settings, Validator $validator, $viewMode)

Triggered before instance's view-mode settings are changed. Here Field Handlers can
apply custom validation rules to view-mode's settings.

beforeAttach
------------

.. php:method:: beforeAttach(FieldInstance $instance)

Before an new instance of this field is attached to a database table. Returning
FALSE will abort the attach operation.

afterAttach
------------

.. php:method:: afterAttach(FieldInstance $instance)

After an new instance of this field was attached to a database table.

beforeDetach
------------

.. php:method:: beforeDetach(FieldInstance $instance)

Before an instance of this field is detached from a database table. Returning FALSE
will abort the detach operation.

afterDetach
-----------

.. php:method:: afterDetach(FieldInstance $instance)

After an instance of this field was detached from a database table. Here is when you
should remove all the stored data for this instance from the DB. For example, if
your field stores physical files for every entity, then you should delete those
files.

.. note::

    By default QuickAppsCMS automatically removes all related records from the
    `eav_values` table.

Field Information
=================

Fields are allowed to indicate some configuration parameters by implementing the
``info()`` method described before. QuickAppsCMS may asks for such information when
required; you must simply implement the ``info()`` method and return an array as
``option`` => ``value``. Valid options are:

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
=========

Your Field Handlers must provide form elements (inputs, selects, textareas, etc)
when rendering Table’s Entities in ``edit mode``. For this we have the ``edit()``
method, which should return HTML code containing all the form elements for the field
attached to certain entity.

For example, consider a ``TextField`` instance attached to the ``Users`` Table,
we'll use this field instance for holding user's favorite food (field's machine-
name: ``favorite-food``). When editing some specific ``User``, TextField Handler
must provide certain form inputs so users are able yo change the value of our
virtual column ``favorite-food``, that is Field Handler must provide an input
element where users shall type in their favorite food. To do this, our TextField
Handler should print something like this:

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

Creating an edit form
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

Tutorial: Creating Field a Handler
==================================

As we mention early, Field Handlers are just classes extending the ``Field\Handler``
base class. Create a new Field Handler is just as east as creating a new class
extending ``Field\Handler`` and place it under the "Field/" directory of the plugin
defining such Field.

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