Blocks API
##########

Blocks are chunks of information that can be placed into the regions provided by a
theme and re-used throughout your site. In QuickAppsCMS there are two type of
blocks:

-  Custom Blocks: Those created using the administer blocks area.
-  Widget Blocks: Those generated on-the-fly (or created) by third-party plugins

Block may appear on a page depending on both the theme or administrative block
settings. Block settings are controlled from the block administration screen, from
this screen, it is possible to control whether each block is enabled where it will
be placed on the page, and control the visibility of blocks on each page.

Blocks Anatomy
==============

Block objects are simple ORM Entities within the "blocks" table. Each time a Custom
Block is created in the blocks administration area a new entity is added to this
table. Plugins may create Widget Blocks during their installation process by
manually inserting new records to this table.

A Block entity objects holds the following properties:

- title (string): Custom title for the block.
- delta (string): Unique ID for block within a handler.
- handler (string): The plugin from which the block originates.
- status (bool): Block enabled status.
- visibility (string): Indicates how to show blocks on pages, possible values are:
  - except: Show on all pages except listed pages
  - only: Show only on listed pages
  - php: Use custom PHP code to determine visibility
- pages (string): List of paths to be used by "visibility" property
- settings (array): Extra information used by the block. Commonly used by Widget Blocks.

Each block has a ``handler`` property which must identify the plugin that created
that Block (by default all blocks created using backend's administration page
defines ``Block`` has their handler). This handler name is used to generate all the
events names that could be triggered during block's life cycle, blocks event names
follows the pattern:

.. code::

    Block.<handler>.<event-name>

In this way plugins are able to "handle" an unlimited amount blocks. For instance
when rendering a block the ``Block.<handler>.display`` event is automatically
triggered and the Plugin which defined that block should catch the event and render
the given block.

As the same event names are triggered for different blocks within the same
"handler", you should use block's ``delta`` property for distinguish between each
block being handled. For example, a "Forum" plugin has registered two Widget Blocks
in the system:

- Block 1:
  - title: Connected Users
  - delta: "connected_users"
  - handler: "Forum"

and

- Block 2
  - title: Latest Threads
  - delta: "latest_threads"
  - handler: "Forum"

When rendering either "Connected Users" or "Latest Threads" block the same event
name will be triggered: ``Block.Forum.display``, event handler method should use the
``delta`` property of the given block to property distinguish between both blocks,
for instance:

.. code:: php

    // Forum/Event/ForumHook.php
    namespace Forum\Event;

    use Block\Model\Entity\Block;
    use Cake\Event\Event;
    use Cake\Event\EventListenerInterface;

    class ForumHook implements EventListenerInterface
    {
        public function implementedEvents()
        {
            return [
                'Block.Forum.display' => 'displayForumBlock',
            ];
        }

        public function displayForumBlock(Event $event, Block $block)
        {
            $view = $event->subject();
            if ($block->delta == 'connected_users') {
                // Rendering logic for "Connected Users" block
            } elseif ($block->delta == 'latest_threads') {
                // Rendering logic for "Latest Threads" block
            }
        }
    }

Blocks Life Cycle
=================

Like everything in QuickAppsCMS, block's life cycle is controlled by the events
system, several events are triggered during all different states whereby a Block
might pass through. Event names should be descriptive enough to let you know what
they do, however you can check the API documentation for further detail.

- Block.<handler>.display: When block is being rendered in some View ("__toString()" equivalent)
- Block.<handler>.settings: For rendering Widget Block settings inputs
- Block.<handler>.validate: Used to validate Widget Block setting values
- Block.<handler>.beforeSave: Before block entity is persisted in DB
- Block.<handler>.afterSave: After block entity was persisted in DB
- Block.<handler>.beforeDelete: Before block entity is removed from the system
- Block.<handler>.afterDelete: Before block entity was removed from the system


Tutorial: Creating a Block
==========================

This tutorial will walk you through the creation of a simple Widget Block (Latest
Articles). To start with, we’ll creating our block entity, and using the tools the
Blocks API provides to get our block working properly.


Registering Block Information
-----------------------------

First you must notice that blocks are always defined by plugins; a block cannot
exists by its own. So the very first step is to create a plugin for which we’ll be
creating this block, please check the Plugins documentation for further information.

For this example, we’ll consider **Blog** as our plugin, and we’ll be creating a
block which should display the latest X articles created in our Blog plugin.

A block is just an Entity object within the "blocks" (Block.Block) table,
registering a new block is just as easy as creating a new entity in this table, for
instance:

.. code:: php

    use Cake\ORM\TableRegistry;

    $newBlock = TableRegistry::get('Block.Block')->newEntity([
        'title' => 'Latest Articles',
        'handler' => 'Blog',
        'delta' => 'latest_articles',
        'settings' => [
            'articles_limit' => 5, // show latest 5 threads created
        ]
    ]);

    TableRegistry::get('Block.Block')->save($newBlock);

**NOTE**: This step is usually performed on plugin installation process. Check the
Plugin API for more details on this process.


Controlling Block Life Cycle
----------------------------

Once our block is registered on "blocks" it will appear in your site's Blocks
Management page (/admin/block/manage); it will be placed under the "Unused or
Unassigned Blocks" tab so users can assign it to some theme's region.

The most important phases (events) whereby a Block can pass through are ``display``
(Block.<handler>.display) and ``settings`` (Block.<handler>.settings). The first
aimed to render the block as HTML, the second aimed to provide configurable form
elements (textboxes, selectboxes, etc) that can be tweaked by users in the block
editing page. Both will be described below.

Block Settings
~~~~~~~~~~~~~~

Blocks settings are handled by the ``Block.<handler>.settings`` event, this event is
aimed to provide additional form input elements that users can tweak in the Block's
editing page. You must simply catch this event and return all inputs elements you
want to provide to users.

In our example, we want to allow users to indicate how many articles should be
displayed in the block when it is rendered. To do so, we must simply catch the event
and return all the form inputs we want to provide to users:

.. code:: php

    // Blog/Event/BlogHook.php
    namespace Blog\Event;

    use Block\Model\Entity\Block;
    use Cake\Event\Event;
    use Cake\Event\EventListenerInterface;

    class BlogHook implements EventListenerInterface
    {
        public function implementedEvents()
        {
            return [
                'Block.Blog.settings' => 'blockSettings',
            ];
        }

        public function blockSettings(Event $event, Block $block)
        {
            $view = $event->subject();
            if ($block->delta == 'latest_articles') {
                return $view->element('Blog.block_latest_articles_settings', compact('block'));
            }
        }
    }

    // Blog/Template/Element/block_latest_articles_settings.ctp
    <?php
        echo $this->Form->input('articles_limit', [
            'label' => 'How may articles to show?',
            'type' => 'select',
            'options' => [
                '3' => 'Latest 3 articles',
                '5' => 'Latest 5 articles',
                '8' => 'Latest 8 articles',
                '10' => 'Latest 10 articles',
            ]
        ]);


Block Rendering
~~~~~~~~~~~~~~~

Now the final and most important step is the block rendering process, this is the
part when a block object is "converted" into HTML code to be presented to users in
any view. A block object can be rendered at any time within a view by using the the
``View::render()`` method, for instance:

.. code:: php

    // some_view.ctp
    <?php
        use Cake\ORM\TableRegistry;

        $block = TableRegistry::get('Block.Block')
            ->find()
            ->where(['handler' => 'Blog', 'delta' => 'latest_articles'])
            ->limit(1)
            ->first();
        echo $this->render($block);

Although this is possible, blocks are usually rendered as part of theme regions as
described in the :doc:`designers <designers/themes>` guide:

.. code:: php

    <?php
        // renders all blocks within this region (and current theme)
        echo $this->region('some-region-name');


Whatever the method is used to render the block, this process is completed using the
``Block.<handler>.display`` event, this event is automatically triggered when
rendering a block as described before. You must catch this event and render the
given block as HTML, we’ll add an event handler method this our ``BlogHook`` class:

.. code:: php

    // Blog/Event/BlogHook.php
    namespace Blog\Event;

    use Block\Model\Entity\Block;
    use Cake\Event\Event;
    use Cake\Event\EventListenerInterface;
    use Cake\ORM\TableRegistry;

    class BlogHook implements EventListenerInterface
    {
        public function implementedEvents()
        {
            return [
                'Block.Blog.display' => 'blockDisplay',
                'Block.Blog.settings' => 'blockSettings',
            ];
        }

        public function blockDisplay(Event $event, Block $block, $options = [])
        {
            $view = $event->subject();
            if ($block->delta == 'latest_articles') {
                // find the latest created articles and pass them to view-element
                $articles = TableRegistry::get('Articles.Articles')
                    ->find()
                    ->limit($block->settings['articles_limit'])
                    ->order(['Articles.created' => 'DESC'])
                    ->all();
                return $view->element('Articles.block_latest_articles_display', compact('block', 'options', 'articles'));
            }
        }

        public function blockSettings(Event $event, Block $block)
        {
            $view = $event->subject();
            if ($block->delta == 'latest_articles') {
                return $view->element('Blog.block_latest_articles_settings', compact('block'));
            }
        }
    }

    // Forum/Template/Element/block_latest_articles_display.ctp
    <h2>Latest Articles</h2>
    <ul>
    <?php foreach ($articles as $article): ?>
        <li><?php $article->get('title'); ?></li>
    <?php endforeach; ?>
    </ul>