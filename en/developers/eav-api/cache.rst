Cache
#####

In some cases when fetching to many entities per query EAV may become slow, as for
every entity being fetched EAV plugin needs to retrieve all virtual columns related
to that entity, that is, for every **collection of entities** an additional
``SELECT`` query is performed. In order to improve this, EAV allows to cache virtual
values of every entity as a serialized structure under a real column of your
entities. To do so, you must indicate the name of the column where EAV values will
be cached using the ``cache`` option, for example:

Cache all virtual values under the ``eav_cache`` column:

.. code:: php

    $this->addBehavior('Eav.Eav', ['cache' => 'eav_cache']);

Cache custom sets of virtual values under different columns:

.. code:: php

    $this->addBehavior('Eav.Eav', [
        'cache' => [
            'contact_info' => ['user-name', 'user-address'],
            'eav_all' => '*',
        ],
    ]);


Accesing cached values
----------------------

After cache has been enabled, you can access cached EAV values as follow:

.. code:: php

    // controller
    use App\AppController;

    class UsersController extends AppController
    {
        public function index()
        {
            // load the model and fetch ALL USERS AT ONCE.
            $this->loadModel('Users');
            $users = $this->Users->find('all', ['eav' => true])
            $this->set('users', $users);
        }
    }

    // view
    foreach ($users as $user) {
        // physical column `name`
        $name = $user->get('name');

        // virtual columns read from cache, read as follow:
        // $user->get(<cache_column_name>)->get(<virtual_column_name>);
        $age = $user->get('eav_cache')->get('user-age');

        echo sprintf('%s is %s years old', $name, $age);
    }

Limitations
-----------

Caches are automatically updated after every entity update. However, cache may
become out of sync under certain circumstances. In some cases, you will be able to
see cached values for virtual columns that was previously removed/modified if the
entity has not been updated/synced yet.

Updating EAV-cache of every entity after virtual columns are changed is a really
expensive task, that is why EAV plugin **will not** perform this task automatically.

To summarize, you must be aware of the following cases:

- After dropping a virtual column.
- After adding new virtual columns.
- After virtual column's definition is changed (type of value, etc).

.. note::

    You can use the ``updateEavCache()`` method of your table to update EAV cache
    for a single entity:

    .. code:: php

        $this->loadModel('Users');
        $user = $this->Users->get($id),
        $this->Users->updateEavCache($entity);
