Fetching Entities
#################

After behavior is attached to your table and some virtual columns are defined, you
can start fetching entities from your table as usual, using "Table::find()" or
similar; every Entity fetched in this way will have additional attributes as they
were conventional table columns. For example in any controller:

.. code:: php

    $user = $this->Users->get(1);
    debug($user);
    // Produces:

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
in your **WHERE** clauses, create form inputs, save entities, etc:

.. code:: php

    $adults = $this->Users
        ->find()
        ->where(['Users.user-age >' => 18])
        ->all();

.. note::

    EAV API has some limitation, for instance you cannot use virtual attributes in
    ORDER BY clauses, GROUP BY, HAVING or any aggregation function. As well as
    virtual columns cannot be used as foreign keys when associating two tables.
