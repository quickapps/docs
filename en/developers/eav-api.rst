EAV API
#######

    Entity–attribute–value model (EAV) is a data model to describe entities where
    the number of attributes (properties, parameters) that can be used to describe
    them is potentially vast, but the number that will actually apply to a given
    entity is relatively modest. In mathematics, this model is known as a sparse
    matrix. EAV is also known as object–attribute–value model, vertical database
    model and open schema.

    -- Wikipedia


Introduction
------------

You will typically use an EAV container when you have an entity (a record in ORM
terms) that has a number of related attributes as it's children. But for every
record, these attibutes can be different. This makes it impossible to define these
attributes as column in the entity's table, because there would be too many, most of
them will not have data, and you can't deal with dynamic attributes at all (because
columns need to be pre-defined).

To solve this issue 'relational' style, you would create a child table, and relate
that to the 'entity' table using a One-to-Many relation, where every attribute would
become a record in the child table. Downside of this approach however is that to be
able to get a specific attribute value, you'll have to loop over all related
records, compare the value of the attribute column with the attribute you look for,
and if a match is found, get the contents of the value column.

The EAV API uses this same implementation, but allows you to merge the attributes
with the entity, so the attributes become properties of the entity record, thus
emulating the variable number of columns that is required for an EAV implementation.


Usage
-----

To use the EAV API you must attach the ``Eav.EavBehavior`` to the table you wish to
"extend", for example:

.. code:: php

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(Table $table)
        {
            $this->addBehavior('Eav.Eav');
        }
    }

Defining Attributes
-------------------

Once EAV behavior is attached to your table, you can now start defining virtual
columns using the method ``addColumn()``, **you should do this just once**,
otherwise you will end adding new columns every time the script is executed:

.. code:: php

    $this->addColumn('my-column-name', $options);

The first argument is the name of the column your are defining, and second argument
support the following keys:

- type (string): Type of data for that attribute, supported values are:
  ``datetime``, ``integer`` (or "int"), ``decimal`` or ("dec"), `text` and
  ``varchar`` (or "string"). Defaults to **varchar**

- bundle (string): Indicates the attribute belongs a that bundle name within the
  table, check the "Bundles" section for further information. Defaults to **null**
  (no bundle).

- searchable (bool): Whether this attribute can be used in SQL's "WHERE" clauses.
  Defaults to **true**

- extra (array): Any additional information given as an array or serialiable
  element. Defaults to NULL.


Fetching Entities
-----------------

After behavior is attached to your table and some virtual columns are defined, you
can start fetching entities from your table as usual, using "Table::find()" or
similar; every Entity fetched entity in this way will have additional attributes as
they were conventional table columns, for example in any controller:

.. code:: php

    $user = $this->Users->get(1);
    debug($user)

    [
        // ...
        'properties' => [
            'id' => 1, // real table column
            'name' => 'John', // real table column
            'user-age' => 15 // EAV attribute
            'user-phone' => '+34 256 896 200' // EAV attribute
        ]
    ]

You can use your EAV attributes as usual; you can apply validation rules, use them
in your **WHERE** clauses, etc:

.. code:: php

    $adults = $this->Users
        ->find()
        ->where(['Users.user_age >' => 18])
        ->all();

.. note::

    EAV API has some limitation, for instance you cannot use virtual attributes in
    ORDER BY clauses, GROUP BY, HAVING or any aggregation function.


Bundles
-------

Bundles are sub-sets of attributes within the same table. For example, we could have
"articles pages", "plain pages", etc; all of them are Page entities but they might
have different attributes depending to which bundle they belongs to:

.. code:: php

    $this->addColumn('article-body', ['type' => 'text', 'bundle' => 'article']);
    $this->addColumn('page-body', ['type' => 'text', 'bundle' => 'page']);

We have defined two different columns for two different bundles, ``article`` and
``plain``, now we can find Page entities of certain type by using the special option
``bundle`` in your "find()" method:

.. code:: php

    $firstArticle = $this->Pages
        ->find('all', ['bundle' => 'article'])
        ->where(['article-body LIKE' => 'Lorem ipsum%'])
        ->limit(1)
        ->first();

    $firstPage = $this->Pages
        ->find('all', ['bundle' => 'page'])
        ->where(['page-body LIKE' => '%massa quis enim%'])
        ->limit(1)
        ->first();

    debug($firstArticle);
    // out:
    [
        // ...
        'properties' => [
            'id' => 1,
            'article-body' => 'Lorem ipsum dolor sit amet ...',
        ]
    ]


    debug($firstPage);
    // out:
    [
        // ...
        'properties' => [
            'id' => 5,
            'page-body' => 'Nulla consequat massa quis enim. Donec pede.',
        ]
    ]