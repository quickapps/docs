Bundles
#######

Bundles are sub-sets of attributes within the same table. For example, we could have
"articles pages", "plain pages", etc; all of them are Page entities but they might
have different attributes depending to which bundle they belongs to:

.. code:: php

    $this->addColumn('article-body', ['type' => 'text', 'bundle' => 'article']);
    $this->addColumn('page-body', ['type' => 'text', 'bundle' => 'page']);

We have defined two different columns for two different bundles, ``article`` and
``page``, now we can find Page Entities and fetch attributes only of certain
``bundle``:

.. code:: php

    $firstArticle = $this->Pages
        ->find('all', ['bundle' => 'article'])
        ->limit(1)
        ->first();

    $firstPage = $this->Pages
        ->find('all', ['bundle' => 'page'])
        ->limit(1)
        ->first();

    debug($firstArticle);
    // Produces:
    [
        // ...
        'properties' => [
            'id' => 1,
            'article-body' => 'Lorem ipsum dolor sit amet ...',
        ]
    ]


    debug($firstPage);
    // Produces:
    [
        // ...
        'properties' => [
            'id' => 5,
            'page-body' => 'Nulla consequat massa quis enim. Donec pede.',
        ]
    ]

If no ``bundle`` option is given when retrieving entities EAV behavior will fetch
all attributes regardless of the bundle they belong to:

.. code:: php

    $firstPage = $this->Pages
        ->find()
        ->limit(1)
        ->first();

    debug($firstPage);
    // Produces:
    [
        // ...
        'properties' => [
            'id' => 5,
            'article-body' => 'Lorem ipsum dolor sit amet ...',
            'page-body' => null
        ]
    ]


.. warning::

    Please be aware that using the ``bundle`` option you are telling EAV behavior to
    fetch only attributes within that bundle, this may produce ``column not found``
    SQL errors when using incorrectly::

        $this->Pages
            ->find('all', ['bundle' => 'page'])
            ->where(['article-body LIKE' => '%massa quis enim%'])
            ->limit(1)
            ->first();

    As ``article-body`` attribute exists only on ``article`` bundle you will get an
    SQL error as described before.
