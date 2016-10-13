Defining Attributes
###################

Once EAV behavior is attached to your table, you can now start defining virtual
columns. There are two ways of defining virtual columns, CLI based or php-script
based. We'll explain how to define such columns using both methods.

Using EAV CLI (Recommended)
---------------------------

EAV plugin provides a simple management command-line-interface (CLI) which allows
you to easily add or drop virtual columns.

You need to tell which table is being altered, what action you wish to perform (add
new virtual column, or drop existing one). And if you are adding new column you must
provide column information (column name, data type, etc). Below an example on how to
add new virtual column named `user_age`:

.. code:: bash

    user@name:/path/to/bin/$ cake Eav.table schema --use UsersPlugin.UsersTable --action add --name user_age --type integer --searchable


The ``searchable`` indicates that this virtual column can be in ``WHERE`` clauses.
If you want to drop an existing column:

.. code:: bash

    user@name:/path/to/bin/$ cake Eav.table schema --use UsersPlugin.UsersTable --action drop --name user_age


Check EAV CLI help for more options available.


Using PHP Script
----------------

.. warning::

    You should do this step just once, otherwise you will end unnecessary updating
    columns every time the script is executed.

You can create new virtual columns definitions using the ``addColumn()`` method of
your table, this method will **update column information if already exists**:

.. code:: php

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(Table $table)
        {
            $this->addBehavior('Eav.Eav');
            // WARNING: just run once these two lines
            $this->addColumn('user-age', ['type' => 'integer']);
            $this->addColumn('user-address', ['type' => 'string', 'bundle' => 'admin']);
        }
    }

The first argument is the name of the column your are defining, you **must use lower
case letters, numbers or "-" symbol**. For instance, ``user-age`` is a valid column
name but ``user_age`` or ``User-Age`` are not.

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

