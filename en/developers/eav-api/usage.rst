Usage
#####

To use the EAV API you must attach the ``Eav.Eav`` behavior to the table you wish to
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
