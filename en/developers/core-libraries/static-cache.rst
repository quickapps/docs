Static Cache
############

.. php:namespace:: QuickApps\Core

.. php:trait:: StaticCacheTrait

Provides simple cache functionality that allows classes to optimize their methods by
providing a simple `cache()` static method used for reading and writing information.


Usage
=====

.. php:staticmethod:: cache($key = null, $value = null)

This method is used to read, write and search values within class's cache. Some
considerations about this method's behavior:

- When reading if no cache key is found NULL will be returned. e.g. ``$null =
  static::cache('invalid-key');``

- When writing, this method return the value that was written. e.g. ``$value =
  static::cache('key', 'value');``

- Set both arguments to NULL to read the whole cache content at the moment. e.g.
  ``$allCache = static::cache()``

- Set key to null and value to anything to find the first key holding the given
  value. e.g. ``$key = static::cache(null, 'search key for this value')``, if no key
  for the given value is found NULL will be returned.

Examples
--------

Here we'll provide some uses cases for writing, reading and searching cached
information.

Writing
~~~~~~~

.. code:: php

    static::cache('user_name', 'John');
    // returns 'John'

     static::cache('user_last', 'Locke');
    // returns 'Locke'

Reading
~~~~~~~

.. code:: php

    static::cache('user_name');
    // returns: John

    static::cache('unexisting_key');
    // returns: null

    static::cache();
    // Reads the entire cache
    // returns: ['user_name' => 'John', 'user_last' => 'Locke']

Searching
~~~~~~~~~

.. code:: php

    static::cache(null, 'Locke');
    // returns: user_last

    static::cache(null, 'Unexisting Value');
    // returns: null