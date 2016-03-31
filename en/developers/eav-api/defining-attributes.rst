Defining Attributes
###################

Once EAV behavior is attached to your table, you can now start defining virtual
columns using the method ``addColumn()``, this method will **update column
information if already exists**:

.. code:: php

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(Table $table)
        {
            $this->addBehavior('Eav.Eav');
            $this->addColumn('user-age', ['type' => 'integer']);
            $this->addColumn('user-address', ['type' => 'string', 'bundle' => 'admin']);
        }
    }

The first argument is the name of the column your are defining, you **must use lower
case letters, numbers or hyphen (-) or underscore (_) symbols**. For instance,
``user-age`` is a valid column name but ``user_age`` or ``User-Age`` are not.

And second argument is used to define column's metadata and supports the following
keys:

- type (string): Type of data for that attribute, note that using any other type not
  listed here will throw an exception. Supported values are:

  - **biginteger**
  - **binary**
  - **date**
  - **float**
  - **decimal**
  - **integer**
  - **time**
  - **datetime**
  - **timestamp**
  - **uuid**
  - **string**
  - **text**
  - **boolean**

- bundle (string): Indicates the attribute belongs to a bundle name within the
  table, check the "Bundles" section for further information. Defaults to **null**
  (no bundle).

- searchable (bool): Whether this attribute can be used in SQL's "WHERE" clauses.
  Defaults to **true**

- extra (array): Any additional information given as an array or serialiable
  element. Defaults to NULL.

.. warning::

    You should do this just once otherwise you will end unnecessary updating columns
    every time the script is executed.
