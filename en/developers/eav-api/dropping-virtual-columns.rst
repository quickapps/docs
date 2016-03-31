Dropping Virtual Columns
########################

You can also drop existing virtual columns previously defined using ``addColumn()``,
to do this you can use the ``dropColumn()`` method:

.. code:: php

    use Cake\ORM\Table;

    class UsersTable extends Table
    {
        public function initialize(Table $table)
        {
            $this->addBehavior('Eav.Eav');
            $this->dropColumn('user-age');
            $this->dropColumn('user-address', 'admin');
        }
    }

Optionally the second argument can be used to indicate the bundle where the column
can be found.

.. warning::

    This method will **remove any stored information** associated to the column
    being dropped, so use with extreme caution.
