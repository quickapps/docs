Field API
=========

The Field API allows custom data fields to be attached to Models and takes care of storing,
loading, editing, and rendering field data.
Any Model type (Node, User, etc.) can use the Field API to make itself "fieldable" and thus allow 
fields to be attached to it.

The Field API defines two primary data structures, Field and Instance. A **Field** defines a
particular type of data that can be attached to Models. A **Field Instance** is a Field attached to 
a single Model.

Internally, fields behave -functionally- like modules (cake's plugin), and they are responsible of 
manage the storing proccess of specific data. As before, they behave -functionally- like modules,
this means they may have hooks and all what a regular module has.

Fields belongs always to modules, and modules are allowed to define an unlimeted number of fields by
placing them on the `Fields` folder. For example, the core module `Taxonomy` owns the field `TaxonomyTerms`
in `QuickApps/Plugins/Taxonomy/Fields/TaxonomyTerms`.

Most of the Fields included in the core of QuickApps belongs to the `Fields` module and you can find them in
`QuickApps/Plugins/Field/Fields/`.

***

Field are supposed to store information. **Field data** is usually stored in DB tables, QuickApps CMS
provides a basic storage table called `field_data`, though, each field is able to define its own 
storing system (usually extra tables in DB).
Also, each field's data-element (row in the table) must have an unique ID in that storing system,
and such data is associated to an unique Model record.

For example, the core field `FieldText` uses the `field_data` table to store all the information users write
on its instances. Each piece of information has a unique ID on the `field_data` table.


Creating Fields
===============

Because Fields behave -functionally- as modules, their names must be prefixed by the name of their parent
module in order to avoid name collisions between other modules in the system.
As modules, Field names must be always in CamelCase, e.g.:

- `image_album`: invalid, no CamelCase name
- `ImageAlbum`: valid, `Album` field belongs to `Image` module
- `MyModuleNameImageAlbum`: valid, `ImageAlbum` field belongs to `MyModuleName` module

The files/folders structure of Fields is the same [structure used by modules](modules.md#structure).
**The only difference** is on the YAML file:


YAML file structure
-------------------

    name: Human readable name
    description: Brief description about your Field
    max_instances: false
    entity_types:
        - Node
        - User

* **name (string):** Readable name of your Field.
* **description (string):** Description about your Field.
* **max_instances (mixed):** _Optional_ parameter. Indicates how many instances of this field that Entities may have.
    * unset or `false` value: Indicates unlimited.
    * Positive integer value: Indicates the max number of instances.
    * Zero (0): Indicates that field can not be attached to any Entity.
* **entity_types (array):** _Optional_ list of entity types that may hold instances of this field. If empty or not specified, the field can have instances in any entity type.


Understanding Entity-Field relations
====================================


Entity -> hasMany -> Field Instances:
-------------------------------------

Entities (models) may have multiple instances of the same field handler.
e.g.: User model may define two fields, `last name` and `age`, both represented by a textbox, means that each
field (last name and age) is an instance of the same Field handler `FieldText`.


Field Instance -> hasMany -> Field Data:
----------------------------------------

Obviously each instance may have multiple data records in its storage system, **BUT** each of this data
records (Field Data) belongs to diferent Entity records.
e.g.: the instance `last name` for User entity may have many records of data **but each** `last name` actually
belong to diferent users.


Entity -> Field Instance -> hasOne -> Field Data:
-------------------------------------------------

When retrieving Entity records, all its extra fields are captured (instances data).
Therefore each of this instances has ONLY ONE related data to each Entity record.
e.g.: when editing a User, his/her `last name` field must have only one value, even though the field instance has many data records in its storage system. (explanation above).


Field POST structure
====================

Each field MUST always POST its information following the structure below:

    data[FieldData][<field_name>][<field_instance_id>][data]
    data[FieldData][<field_name>][<field_instance_id>][id]


* **<field_module>:** (string) name of the field handler in CamelCase: i.e.: 'FieldTextarea', 'FieldMyField', `ParentModuleFieldName`, etc.
* **<field_instance_id>:** (int) ID of the field instance attached to the current Model. (field instances are stored in `fields` table).
* **[data]:** (mixed) Field data to store. It can be simple information such as plain text or even complex arrays of mixed data.
* **[id]:** (int) Storage ID. Unique ID for the data in the storage system implemented by the Field. **null** ID means that there is no data stored yet for this Model record and this Field instance.


#### EXAMPLE

    <input name="data[FieldData][FieldName][2][data]" value="This info has an ID=153 and belongs to the instance ID=2 of `FieldName`" type="text" />
    <input name="data[FieldData][FieldName][2][id]" value="153" type="hidden" />

    <input name="data[FieldData][FieldName][3][data]" value="This is other instance (3) of `FieldName`" type="text" />
    <input name="data[FieldData][FieldName][3][id]" value="154" type="hidden" />


debug($this->data) should looks:

    array(
        ... // other entity's native fields (table columns)
        'FieldData' => array(
            'FieldName' => array(
                2 => array(
                  'id' => 153,
                  'data' => 'This info has an ID=153 and belongs to the instance ID=2 of `FieldName`'
                ),
                3 => array(
                  'id' => 154,
                  'data' => 'This is other instance (3) of `FieldName`'
                )
                ... // more instances of `FieldName`
            ),
            ... // other field instances attached to entity
        )
    );


Capturing POST and saving data
==============================

Capturing field's data and saving process are performed by using Model hooks callbacks (Behaviors Hooks).
In this process there are two diferent callbacks types, `Entity callbacks`, related to Model entities (User, Node, etc).
And `Instance callbacks`, related to Field (at/de)tachment process.


Entity callbacks
----------------

This hooks callbacks are fired before/after each `fieldable` entity's callbacks.

- `[field_name]_before_find($info)` [optional]
    - **when**: after entity find query
    - **description**: allow fields to modify entity's query parameters
    - **return**: void

- `[field_name]_after_find($info)` [optional]
    - **when**: after entity find query
    - **description**: allow fields to attach their data to each entity record
    - **return**: void

- `[field_name]_before_save($info)` [optional]
    - **when**: before entity record is saved
    - **description**: allow fields to perform any kind of logic before entity record is saved
    - **return**: return a non-true value to halt entity's save operation

- `[field_name]_after_save($info)` [required]
    - **when**: after entity record has been saved
    - **description**: here is where fields should save their data
    - **return**: void

- `[field_name]_before_validate($info)` [optional]
    - **when**: before validate entity record
    - **description**: allow fields to perform validation logic over their POST'ed data
    - **return**: return a non-true value to halt entity's saving operation

- `[field_name]_before_delete($info)` [optional] 
    - **when**: before entity record deletion
    - **description**: allow fields to perform any kind logic before entity record is deleted
    - **return**: return a non-true value to halt entity's deletion operation

- `[field_name]_after_delete($info)` [requited]
    - **when**: after entity record has been deleted.
    - **description**: here is where fields should remove from their storage system all the data related to deleted entity record
    - **return**: void


**$info:** Possible keys and values

    $info = array(
        [entity] => ...,
        [query] => ...,
        [field] => ...,
        [field_id] => ...,
        [created] => ...,
        [result] => ...,
        [settings] => ...
    );


 * (Model) **entity**: Instance of Model that Field is attached to.
 * (array) **query**: SQL query (only on `before_find`)
 * (array) **field**: Field instance information
 * (integer) **field_id**: Field instance ID (only on `before_validate`, `after_save`, `before_delete`, `after_delete`)
 * (boolean) **created**: TRUE if entity record has been created. FALSE if it was updated. (only on `after_save`)
 * (boolean) **result**: Entity row of array results (only on `after_find`)
 * (array) **settings**: Entity fieldable-settings array


IMPORTANT
---------

Field data **MUST** always be **saved after Entity** record has been saved, that is on `after_save` callback.
e.g: When updating/creating a new User, all field's data must be saved after the User native data has been updated/created


Instance callbacks
------------------

This hooks callbacks are fired when field instances are being attached to entities, or when field is being detached, deleted, etc.

- [field_name]_before_delete_instance(&$FieldModel) [required/optional]: (at least one of (before/after) must be defined).
- [field_name]_after_delete_instance(&$FieldModel) [required/optional]: (at least one of (before/after) must be defined).
- [field_name]_before_validate_instance(&$FieldModel) [optional]: before validate the field instance being saved (attached to entity).
- [field_name]_before_save_instance(&$FieldModel) [optional]: before field is attached to entity.
- [field_name]_after_save_instance(&$FieldModel) [optional]: after field has been attached to entity.
- [field_name]_before_move_instance(&$move_parametters) [optional]: before field instance is moved (reordered) within an entity.
- [field_name]_after_move_instance(&$move_parametters) [optional]: after field instance was moved (reordered) within an entity.
- [field_name]_before_set_view_modes(&$field_record) [optional]: before `view modes` are modified within an entity.
- [field_name]_after_set_view_modes(&$field_record) [optional]: after `view modes` were modified within an entity.


Making an Entity Fieldable
==========================

Simply by attaching the `Fieldable Behavior` to any model will make it fieldable.

    public $actsAs = array('Field.Fieldable', ...);


Attaching Fields to Entities
----------------------------

After you have attached the Fieldable Behavior to your model, you can start attaching Fields to it by
invoking the `attachFieldInstance` of your model:

    // In your model
    $this->attachFieldInstance($data);

    // From controller
    $this->ModelName->attachFieldInstance($data);


**$data**

- `label`: Field input label. e.g.: 'Article Body' for a textarea.
- `name`: Field unique name. **underscored and alphanumeric** characters only. e.g.: 'field_article_body'.
- `field_module`: Name of the field handler that handle this instance. e.g.: 'FiledText'.


Making Field's Data Searchable
==============================

Indexing Field's content allow nodes to be located by any of the words in any of its fields.
If you want your field's information to be searchable by QuickApps CMS's search engine you must use the `indexField`
method as show below:

    public function field_name_after_save(&$info) {
        // Saving logic
        ...
        
        // append this words to Entity's index.
        $info['entity']->indexField('Field content as string to index');
        
        ...
    }

The above will append an index of words to the Entity that Field belongs to.
So Entity can be located using any of the words (or phrase) passed by the field. e.g.:
In the example above, the node will be listed when searching the phrase `string to index`
(_http://domain.com/search/string to index_)

You can pass full HTML or any kind of formatted string, and QuickApps CMS will automatically
extract all the valid words to be fetched with rest of Entity's words.
You can invoke the `indexField` on both callbacks `before_save` or `after_save`.