Blocks API
##########

Blocks are chunks of information that can be placed into the regions provided by a
theme and re-used throughout your site. In QuickAppsCMS there are two type of
blocks:

-  Custom Blocks: Those created using the administer blocks area.
-  Widget Blocks: Those generated on-the-fly (or created) by third-party plugins

Blocks may appear on a page depending on both the theme or administrative block
settings. Block settings are controlled from the block administration screen, from
this screen, it is possible to control whether each block is enabled where it will
be placed on the page, and control the visibility of blocks on each page.

Blocks Anatomy
==============

Blocks are internally threated as Entity objects within of the "blocks" table
(Block\Model\Entity\Block). Each time a Custom Block is created in the blocks
administration area a new entity is added to this table. Plugins may create Widget
Blocks during their installation process by manually inserting new records to this
table.

A Block entity objects holds the following properties:

- title (string): Custom title for the block.

- handler (string): The fully-qualified name of the class responsible of controlling
  block's life cycle.

- status (bool): Block enabled status.

- visibility (string): Indicates how to show blocks on pages, possible values are:

  - except: Show on all pages except listed pages
  - only: Show only on listed pages
  - php: Use custom PHP code to determine visibility

- pages (string): List of paths to be used by "visibility" property
- settings (array): Extra information used by the block. Commonly used by Widget Blocks.

Each block has a ``handler`` property which identifies the name of class that will
handle that Block (by default all blocks created using backend's administration page
defines ``Block\Wiget\CustomBlockWidget`` has their handler). This handler name will
be used to control the entire block life cycle.

Blocks Life Cycle
=================

Block's life cycle is controlled by callback methods invoked automatically by
QuickAppsCMS when required. All blocks have an associated "handler" class which
defines such callbacks, and in turn these class all extends from ``Block\Widget``.
This class defines a series of methods that are used to control block's life cycle;
each handler class should extend ``Block\Widget`` and override its methods to
provided any logic required by the block/widget.

Below a list of all method defined by ``Block\Widget`` and a brief description about
what are they intended for, you can check the API documentation for further detail.

- **render**: This method should return the rendered widget to be presented to end-
  users.

- **settings**: This method should return all the Form input elements that user will
  be able to tweak in the widget configuration page at Backend.

- **validateSettings**: This method should alter the provided Validator object and
  add custom validation rules, these rules will be applied when saving the values
  provided by all the Form input elements rendered by the "settings()" method.

- **defaultSettings**: This method should return an associative array hold default
  values for the Form input elements provided by the "settings()" method.

- **beforeSave**: This callback is invoked before widget information is persisted in
  DB. Returning FALSE will halt the save operation. Anything else will be ignored.

- **afterSave**: This callback is invoked after widget information was persisted in
  DB.

- **beforeDelete**: This callback is invoked before widget is removed from DB.
  Returning FALSE will halt the delete operation. Anything else will be ignored.

- **afterDelete**: This callback is invoked after widget was removed from DB.


Tutorial: Creating a Widget
===========================

This tutorial will walk you through the creation of a simple Widget Block (Latest
Articles). To start with, we’ll creating our block entity object, and using the
tools the Blocks API provides to get our block working properly.


Registering Widget Information
------------------------------

First you must notice that widgets are always defined by plugins; a widget cannot
exists by its own. So the very first step is to create a plugin for which we’ll be
creating this widget, please check the Plugins documentation for further
information.

For this example, we’ll consider **Blog** as our plugin, and we’ll be creating a
widget which should display the latest X articles created in our Blog plugin, where
X is a configurable integer value that users can tweak in the administration area.

As mention before, a widget is just an Entity object within the "blocks" table
(Block.Blocks), registering a new widget is just as easy as creating a new entity in
this table, below we'll describe two ways of registering blocks:

You can manually insert a new record into the "blocks" table as follow:

.. code:: php

    use Cake\ORM\TableRegistry;

    $newWidget = TableRegistry::get('Block.Blocks')->newEntity([
        'title' => 'Latest Articles',
        'handler' => 'Blog\Widget\LatestPostsWidget',
        'delta' => 'latest_articles',
        'settings' => [
            'articles_limit' => 5, // show latest 5 threads created
        ]
    ]);
    $success = TableRegistry::get('Block.Blocks')->save($newBlock);

    if ($success) {
        // widget registered
    } else {
        $errors = $newWidget->errors();
    }

Or you can use the global function ``registerWidget()``, you can set the second
argument to TRUE for returning an array of errors. If not provided (or set to false)
a boolean response will be returned:

.. code:: php

    $errors = registerWidget([
        'title' => 'Latest Articles',
        'handler' => 'Blog\Widget\LatestPostsWidget',
        'delta' => 'latest_articles',
        'settings' => [
            'articles_limit' => 5, // show latest 5 threads created
        ]
    ], true);

    if (empty($errors)) {
        // widget registered
    } else {
        // something went wrong, print $errors
    }

As you can see we have defined **Blog\\Widget\\LatestPostsWidget** has our block's
handler class, the next step is to create this class and bring our widget to life.

.. note::

    This step is usually performed on plugin installation process. Check the
    Plugin API for more details on this process.


Controlling Widget Life Cycle
-----------------------------

Once our widget is registered on the "blocks" table it will appear in your site's
Blocks Management page (/admin/block/manage); it will be placed under the "Unused or
Unassigned Blocks" tab so users can assign it to theme regions.

The most important callbacks whereby a Widget can pass through are ``render()`` and
``settings()``. The first aimed to render the widget as HTML, the second aimed to
provide configurable form elements (textboxes, selectboxes, etc) that can be tweaked
by users in the widget editing page. Both will be described below.

Widget Settings
~~~~~~~~~~~~~~~

Widget settings are handled by the ``settings()`` method, this method is aimed to
provide additional form input elements that users can tweak in the Widget's editing
page. You must simply implement this method and return all the form inputs elements
you want to provide to users. This method receives the block entity object from DB
as first argument, and an instance of View class as second.

In our example, we want to allow users to indicate how many articles should be
displayed in the widget when it gets rendered. To do so, we must simply implements
the method and return all the form inputs we want to provide to users:

.. code:: php

    // Blog/Widget/LatestPostsWidget.php
    namespace Blog\Widget;

    use Block\Model\Entity\Block;
    use Block\Widget;
    use QuickApps\View\View;

    class LatestPostsWidget extends Widget
    {
        public function settings(Block $block, View $view)
        {
            return $view->element('Blog.latest_articles_widget_settings', compact('block'));
        }
    }

.. code:: php

    <?php
        // Blog/Template/Element/latest_articles_widget_settings.ctp
        echo $this->Form->input('articles_limit', [
            'label' => 'How many articles to show?',
            'type' => 'select',
            'options' => [
                '3' => 'Latest 3 articles',
                '5' => 'Latest 5 articles',
                '8' => 'Latest 8 articles',
                '10' => 'Latest 10 articles',
            ]
        ]);

.. note::

    In other to keep things dry we placed all HTML code in separated view-elements.


Widget Rendering
~~~~~~~~~~~~~~~~

Now the final and most important step is the widget rendering process, this is the
part when a block entity object is "converted" into HTML code to be presented to
users as part of some view template. A block object can be rendered at any time
within a view template by using the the ``View::render()`` method or the
``render()`` method provided by the block object itself, for instance:

.. code:: php

    // some_view.ctp
    use Cake\ORM\TableRegistry;

    // fetch block object from DB
    $block = TableRegistry::get('Block.Blocks')->get($id);

    // render the block
    echo $this->render($block);

    // or just using Block::render()
    echo $block->render();

Although this is possible, blocks are usually rendered as part of theme regions as
described in the :doc:`designers </designers/themes>` guide:

.. code:: php

    // renders all blocks within this region (and current theme)
    echo $this->region('some-region-name');

Whatever the method is used to render the block, this process is completed using the
``render()`` method of the handler class defined on each block, this method is
automatically invoked when rendering a widget as described before. You must
implement this method and render the given widget as HTML:

.. code:: php

    // Blog/Widget/LatestPostsWidget.php
    namespace Blog\Widget;

    use Block\Model\Entity\Block;
    use Block\Widget;
    use QuickApps\View\View;

    class LatestPostsWidget extends Widget
    {
        public function render(Block $block, View $view)
        {
            // find the latest created articles and pass them to view-element
            $articles = TableRegistry::get('Blog.Articles')
                ->find()
                ->limit($block->settings['articles_limit'])
                ->order(['Articles.created' => 'DESC'])
                ->all();
            return $view->element('Blog.latest_articles_widget_render', compact('block', 'options', 'articles'));
        }

        public function settings(Block $block, View $view)
        {
            return $view->element('Blog.latest_articles_widget_settings', compact('block'));
        }
    }

Now, the final step is to create a view-template for actually rendering our block:

.. code:: php

    <!-- Blog/Template/Element/latest_articles_widget_render.ctp -->

    <h2>Latest Articles</h2>
    <ul>
        <?php foreach ($articles as $article): ?>
        <li><?php $article->get('title'); ?></li>
        <?php endforeach; ?>
    </ul>
