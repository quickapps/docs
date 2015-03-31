Blocks API
##########

Blocks are chunks of information that can be placed into the regions provided by a
theme and re-used throughout your site. In QuickAppsCMS there are two type of
blocks:

-  Custom Blocks: Those created using the administer blocks area.
-  Widget Blocks: Those generated on-the-fly (or created) by third-party plugins

Block may appear on a page depending on both the theme or administrative block
settings. Block settings are controlled from the block administration screen, from
this screen, it is possible to control whether each block is enabled where it
will be placed on the page, and control the visibility of blocks on each page.

Blocks Anatomy
==============

Block objects are simple ORM Entities within the "blocks" table. Each time a
Custom Block is created in the blocks administration area a new entity is added to
this table. Plugins may create Widget Blocks during their installation process
by manually inserting new records to this table.

A Block entity objects holds the following properties:

- title (string): Custom title for the block.
- delta (string): Unique ID for block within a handler.
- handler (string): The plugin from which the block originates. For example: "Taxonomy" for the Categories block, and "Block" for any Custom Blocks.
- status (bool): Block enabled status.
- visibility (string): Indicates how to show blocks on pages, possible values are:
  - except: Show on all pages except listed pages
  - only: Show only on listed pages
  - php: Use custom PHP code to determine visibility
- pages (string): List of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.
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
``delta`` property of the given block to property distinguish between
both blocks, for instance:

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