Field API
=========

The Field API allows custom data fields to be attached to Models and takes care of storing, loading, editing, and rendering field data.  
Any Model type (Node, User, etc.) can use the Field API to make itself "fieldable" and thus allow fields to be attached to it.

The Field API defines two primary data structures, Field and Instance. A **Field** defines a particular type of data that can be attached to
Models. A **Field Instance** is a Field attached to a single Model.

Internally, Fields behave -functionally- like modules (cake's plugin), and they are responsible of manage the storing proccess of specific data. 
As before, they behave -functionally- like modules, means they may have hooks and all what a regular module has.  

Fields belongs always to modules, and modules are allowed to define an unlimeted number of fields by placing them on the `Fields` folder.  
For example, the core module `Taxonomy` owns the field `TaxonomyTerms` in `QuickApps/Plugins/Taxonomy/Fields/TaxonomyTerms`.

Most of the Fields included in the core of QuickApps belongs to the `Field` module and you can find them in `QuickApps/Plugins/Field/Fields/`.

***

Field are supposed to store information. **Field data** is usually stored in DB tables, QuickApps CMS provides a basic storage table named 
`field_data`, though, each field is able to define its own storing system (commonly extra tables in DB).  
Also, each field's info-element (record in the storage system) must have an unique ID in that storage system, and such data is associated to
an unique Model record.

An example. The core field `FieldText` uses the `field_data` table to store all the information users write on its instances. Each piece of
information has a unique ID on the `field_data` table.

Anyway, is important you to understand that Fields may implement any kind of storage system, this means using DB tables is not required. For
example, some Field may have decided to implement a storage system using a file-cache based system using CakePHP's Cache system.

***

**Basically**, and in common words, Fields allows you to dynamically "expand" your table columns. For example, if you need to store User's phone,
what you do is simply attach a Field to User entity to hold this information, and in this way there is need to alter User's schema.


###### Glossary

-	Entity: Model, commonly a table in your database. e.g.: an `users` table
-	Field: A particular module responsible of manage the storing proccess of specific data.
-	Field instance: Field attached to a single Entity.
-	Storage system: Where fields stores all the info of its instances.
-	Field data: A paricular piece of data which belongs to a unique entity record and managed by a Field.


Understanding Entity-Field relations
====================================

Entity -> hasMany -> Field Instances:
-------------------------------------

Entities may have multiple instances of the same Field.  
e.g.: The User entity may define two additional fields, `last name` and `age`, both represented by a textbox, means that each field
(last name and age) is an instance of the same Field: `FieldText`.


Field Instance -> hasMany -> Field Data:
----------------------------------------

Obviously each instance may have multiple records of data in its storage system, **BUT** is important to understand that each of this
data records (Field Data) belongs to diferent Entity records.  
e.g.: The `last name` instance in the example above may have many records of data **but each** `last name` actually belong to diferent users.


Entity's Field Instance -> hasOne -> Field Data:
-------------------------------------------------

When retrieving Entity records, the infromation of all its Field instances are captuted.
Therefore each of this instances has ONLY ONE piece data Glossary to each Entity record.  
e.g.: When editing a User, his/her `last name` field must have only one value, even though the Field instance has many data records in its
storage system.


Creating fields
===============

Because Fields behave -functionally- as modules, their names must be prefixed by the name of their parent module in order to avoid name collisions
between other modules in the system.  
As modules, Field names must be always in CamelCase, e.g.:

- `image_album`: invalid, no CamelCase name
- `ImageAlbum`: valid, `Album` field belongs to `Image` module
- `MyModuleNameImageAlbum`: valid, `ImageAlbum` field belongs to `MyModuleName` module

The files/folders structure of Fields is the same [structure used by modules](modules.md#structure).  

Configuration YAML
-------------------

Same as in modules, Fields must define a configuration:

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


Required view elements
-----------------------

Fields must define certain view elements reponsable of several task such as render edit form, render field data, etc. All those elements must
be placed in the View/Elements directory of each Field.

- view.ctp: responsible of render the field data.
- edit.ctp: responsible of render form inputs required when editing an Entity.
- formatter.ctp: form inputs for display modes.
- settings.ctp: form inputs for Field instance settings.


Field Data POST structure
=========================

Field MUST always POST Entity's data following the structure below, after post information is sent several hook callbacks are automatically
fired by QuickApps in order to process this data:

    data[FieldData][<field_name>][<field_instance_id>][data]
    data[FieldData][<field_name>][<field_instance_id>][id]


* **\<field_module\>:** (string) name of the field handler in CamelCase: i.e.: 'FieldTextarea', 'FieldMyField', `ParentModuleFieldName`, etc.
* **\<field_instance_id\>:** (int) ID of the field instance attached to the current Model. (field instances are stored in `fields` table).
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

After POST is sent several hooks are fired to indicate to each Field they must process Entity's data.  
Capturing Field Data and saving process are performed by using Model hooks callbacks (Behaviors Hooks).  
Also you must know that Fields may recibe two receive callbacks types:

- `Entity callbacks`: related to Entities. e.g.: before/after an Entity is saved.
- `Instance callbacks`: related to Field (at/de)tachment process.


Entity callbacks
----------------

This hooks callbacks are fired before/after each `fieldable` Entity's callbacks.  
Consult CakePHP book for more information about "Model callbacks".

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

This hooks callbacks are fired when Field instances are being attached to entities, or when Field are being detached, deleted, etc.

- [field_name]_before_delete_instance(&$Entity) [required/optional]
	- **when**: before field instance is deleted
	- **description**: put any logic that need to be executed before an instance is deleted. e.g.: remove temporaly files/folders
	- **return**: return a non-true value to halt the operation

- [field_name]_after_delete_instance(&$Entity) [required/optional]
	- **when**: after field has been deleted
	- **description**: put any logic that need to be executed after an instance is deleted. e.g.: remove temporaly files/folders
	- **return**: void

- [field_name]_before_validate_instance(&$Entity) [optional]
	- **when**: before validate the field instance
	- **description**: here fields may validate if the entered instance settings are valid
	- **return**: return a non-true value to halt the operation

- [field_name]_before_save_instance(&$Entity) [optional]
	- **when**: before field is attached to entity
	- **description**: any logic to be executed before a field instance is created. e.g: create temporaly files/folders
	- **return**: return a non-true value to halt the operation

- [field_name]_after_save_instance(&$Entity) [optional]
	- **when**: after field has been attached to entity
	- **description**: any logic to be executed after a field instance is created. e.g: create temporaly files/folders
	- **return**: void

- [field_name]_before_move_instance(&$moveParameters) [optional]
	- **when**: before field instance is moved (reordered) within an entity
	- **description**: any logic to be executed, field may alter the moving parameters
	- **return**: void

- [field_name]_after_move_instance(&$move_parametters) [optional]
	- **when**: after field instance was moved (reordered) within an entity
	- **description**: any logic to be executed, alter the moving parameters will have not effect here
	- **return**: void

- [field_name]_before_set_view_modes($FieldInstance) [optional]
	- **when**: before `view modes` are modified within an entity
	- **description**: any logic to be executed
	- **return**: void

- [field_name]_after_set_view_modes(&$FieldInstance) [optional]
	- **when**: after `view modes` were modified within an entity
	- **description**: any logic to be executed
	- **return**: void


Making an Entity fieldable
==========================

Simply by attaching the `Fieldable Behavior` to any model will make it fieldable.

    public $actsAs = array('Field.Fieldable', ...);


Attaching Fields to Entities
----------------------------

After you have attached the Fieldable Behavior to your model, you can start attaching Fields to it by invoking the `attachFieldInstance` of your model:

    // In your model
    $this->attachFieldInstance($data);

    // From controller
    $this->ModelName->attachFieldInstance($data);


**$data**

- `label`: Field input label. e.g.: 'Article Body' for a textarea.
- `name`: Field unique name. **underscored and alphanumeric** characters only. e.g.: 'field_article_body'.
- `field_module`: Name of the field handler that handle this instance. e.g.: 'FiledText'.


Making Field's data searchable
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
So Entity can be located using any of the words (or phrase) passed by the field.  
e.g.: In the example above, the node will be listed when searching the phrase `string to index` (_http://domain.com/search/string to index_)

You can pass full HTML or any kind of formatted string, and QuickApps CMS will automatically extract all the valid words to be
fetched with rest of Entity's words.  
You can invoke the `indexField` on both callbacks `before_save` or `after_save`.