Field API
=========

Allows additional fields to be attached to Tables. Any Table (Nodes, Users, etc.)
can use Field API to make itself `field-able` and thus allow fields to be
attached to it.

The Field API defines two primary data structures, FieldInstance and FieldValue:

- FieldInstance: is a Field attached to a single Table. (Schema equivalent: column)
- FieldValue: is the stored data for a particular [FieldInstance, Entity]
  tuple of your Table. (Schema equivalent: cell value)

**Basically, this behavior allows you to add _virtual columns_ to your table schema.**


***

Making a Table "fieldable"
==========================

Simply by attaching the `FieldableBehavior` to any table will make it fieldable.

    $this->addBehavior('Field.Fieldable');

This behavior modifies each query of your table in order to merge custom-fields
records into each entity under the `_fields` property.

## Entity Example:

    // $user = $this->Users->get(1);
    // User's properties might look as follows:
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

In the example above, User entity has a custom field named `user-age` and its
current value is 22. In the other hand, it also has a `user-phone` field but
no information was given (Schema equivalent: NULL cell).

As you might see, the `_field` key contains an array list of all fields attached
to every entity. Each field (each element under the `_field` key) is an object
(Field Entity), and it have a number of properties such as `label`, `value`, etc.
All properties are described below:

-  `name`: Machine-name of this field. ex. `article-body` (Schema equivalent: column name).
-  `label`: Human readable name of this field e.g.: `User Last name`.
-  `value`: Value for this [field, entity] tuple. (Schema equivalent: cell value)
-  `extra`: Extra data for Field Handler.
-  `metadata`: Metadata (an Entity Object).
    - `field_value_id`: ID of the value stored in `field_values` table.
    - `field_instance_id`: ID of field instance (`field_instances` table)
       attached to the table.
    - `entity_id`: ID of the Entity this field is attached to.
    - `table_alias`: Name of the table this field is attached to. e.g: `users`.
    - `description`: Something about this field: e.g.: `Please enter your last name`.
    - `required`: 0|1.
    - `settings`: Any extra information array handled by this particular field.
    - `view_modes`: Information about how this field should be rendered on each
       View Mode. Information is stored as `view-mode-name` => `rendering-information`.
    - `handler`: class name of the Field Handler under `Field` namespace.
       e.g.: `TextField` (namespaced name: `Field\TextField`)
    - `errors`: Array of validation error messages, only on edit mode.

**Notes:**

-    The `metadata` key on every field is actually an entity object.
-    The `_field` key which holds all the fields is actually an instance of
     `Field/Utility/FieldCollection`, which behaves as an array
     (so you can iterate over it). It adds some utility methods for handling
     fields, for instance it allows you to access an specific field by its
     corresponding numeric index or by its machine-name.

### Accessing Field Properties

Once you have your Entity (e.g. User Entity), you would probably need to get
its attached fields and do fancy thing with them. Following with our User
entity example:

    // In your controller
    $user = $this->Users->get($id);
    echo $user->_fields[0]->label . ': ' . $user->_fields[0]->value;
    // out: User Age: 22

    echo "This field is attached to '" . $user->_fields[0]->metadata->table_alias . "' table";
    // out: This field is attached to 'users' table;

## Searching over custom fields

This behavior allows you to perform WHERE clauses using any of the fields
attached to your table. Every attached field has a "machine-name"
(a.k.a. field slug), you should use this "machine-name" prefixed with
`:`, for example:

    TableRegistry::get('Users')
        ->find()
        ->where(['Users.:first-name LIKE' => 'John%'])
        ->all();

`Users` table has a custom field attached (first-name), and we are looking for
all the users whose `first-name` starts with `John`.

## Value vs Extra

In the "Entity Example" above you might notice that each field attached to
entities has two properties that looks pretty similar, `value` and `extra`,
as both are intended to store information. Here we explain the "why" of this.

Field Handlers may store complex information or structures. For example,
`AlbumField` handler may store a list of photos for each entity. In those cases
you should use the `extra` property to store your array list of photos, while
`value` property should always store a Human-Readable representation of
your field's value.

In our `AlbumField` example, we could store an array list of file names and titles
for a given entity under the `extra` property. And we could save photo's titles as
space-separated values under `value` property:

    // extra:
    [photos] => [
        ['title' => 'OMG!', 'file' => 'omg.jpg'],
        ['title' => 'Look at this, lol', 'file' => 'cats-fighting.gif'],
        ['title' => 'Fuuuu', 'file' => 'fuuuu-meme.png'],
    ]

    // value:
    OMG! Look at this lol Fuuuu

In our example when rendering an entity with `AlbumField` attached to it,
`AlbumField` should use `extra` information to create a representation of
itself, while `value` information would acts like some kind of `words index`
when using `Searching over custom fields` feature described above.

**Important:**

- FieldableBehavior automatically serializes & unserializes the `extra`
  property for you, so you should always treat `extra` as an array.
- `Search over fields` feature described above uses the `value` property
   when looking for matches. So in this way your entities can be found when
   using Field's machine-name in WHERE clauses.
- Using `extra` is not mandatory, for instance your Field Handler could use
  an additional table schema to store entities information and leave `extra`
  as NULL. In that case, your Field Handler must take care of joining entities
  with that external table of information.

**Summarizing:** `value` is intended to store `plain text` information suitable
for searches, while `extra` is intended to store sets of complex information.

***

## Using this behavior

Just like any other behavior, in your Table constructor attach this behavior
as usual:

    $this->attachBehavior('Field.Fieldable');

## Enable/Disable Field Attachment

If for some reason you don't need custom fields to be fetched under the `_field`
of your entities you should use the unbindFieldable(). Or bindFieldable() to
enable it again.

    // there wont be a "_field" key on your User entity
    $this->User->unbindFieldable();
    $this->Users->get($id);

## About Field-Handlers & Hooks

Field Handler are "Listeners" classes which must take care of storing,
organizing and retrieving information for each entity's field. This is
archived using Hook callbacks.

Similar to Hooks and Hooktags, Field-Handlers must define a series of hook event.
This hook events has been organized in two groups or "event subspaces":

-    `Field.<FieldHandler>.Entity`: For handling Entity's related events such
     as `entity save`, `entity delete`, etc.
-    `Field.<FieldHandler>.Instance`: Related to Field Instances events, such as
     "instance being detached from table", "new instance attached to table", etc.

Below, a list of available hook events:

- `Field.<FieldHandler>.Entity.display`: When an entity is being rendered
- `Field.<FieldHandler>.Entity.edit`: When an entity is being rendered in `edit` mode. (backend usually)
- `Field.<FieldHandler>.Entity.beforeFind`: Before an entity is retrieved from DB
- `Field.<FieldHandler>.Entity.beforeValidate`: Before entity is validated as part of save operation
- `Field.<FieldHandler>.Entity.afterValidate`: After entity is validated as part of save operation
- `Field.<FieldHandler>.Entity.beforeSave`: Before entity is saved
- `Field.<FieldHandler>.Entity.afterSave`: After entity was saved
- `Field.<FieldHandler>.Entity.beforeDelete`: Before entity is deleted
- `Field.<FieldHandler>.Entity.afterDelete`: After entity was deleted

- `Field.<FieldHandler>.Instance.info`: When QuickAppsCMS asks for information about each registered Field
- `Field.<FieldHandler>.Instance.settingsForm`: Additional settings for this field. Should define the way the values will be stored in the database.
- `Field.<FieldHandler>.Instance.settingsDefaults`: Default values for field settings form's inputs
- `Field.<FieldHandler>.Instance.viewModeForm`: Additional formatter options. Show define the way the values will be rendered for a particular view mode.
- `Field.<FieldHandler>.Instance.viewModeDefaults`: Default values for view mode settings form's inputs
- `Field.<FieldHandler>.Instance.beforeValidate`: Before field is validated as part of attach operation
- `Field.<FieldHandler>.Instance.afterValidate`: After field is validated as part of attach operation
- `Field.<FieldHandler>.Instance.beforeAttach`: Before field is attached to Tables
- `Field.<FieldHandler>.Instance.afterAttach`: After field is attached to Tables
- `Field.<FieldHandler>.Instance.beforeDetach`: Before field is detached from Tables
- `Field.<FieldHandler>.Instance.afterDetach`: After field is detached from Tables