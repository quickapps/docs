Blocks
======

Blocks are the boxes of content visible in various area of your Themes.  
There are two type of blocks in the system:

- Widget Blocks: Those generated on-the-fly (or created) by various modules
- Custom Blocks: Those created in the administer blocks area (/admin/block/manage)

Block may appear on a page depending on both the theme and on administrative block settings. Block settings are controlled from the block
administration screen. From this screen, it is possible to control whether each block is enabled where it will be placed on the page, and
control the visibility of blocks on each page.


The Blocks Table
----------------

Internally, a block is simply a record in the `blocks` table, each time a Custom Blocks is created in the administer blocks area a new records is
added to this table. Modules may create Widget Blocks during the installation process as well by adding new records to this table.  
Also, each menu have an associated block registered in this table.

This table holds the following attributes:


-	module:
	The module from which the block originates. For example: "Taxonomy" for the Categories block, and "Block" for any Custom Blocks.
-	delta:
	Unique ID for block _within a module_.
-	status:
	Block enabled status. 1 for enabled, 0 disabled.
-	visibility:
	Flag to indicate how to show blocks on pages.
	- 0: Show on all pages except listed pages
	- 1: Show only on listed pages
	- 2: Use custom PHP code to determine visibility
-	pages: List of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.
-	title: Custom title for the block.
-	settings: Serialized array of extra information used by the block. Commonly used by Widget Blocks.


Widget Blocks
-------------

The rendering process for both Custom Blocks and Menu Blocks are internally managed by QuickApps CMS. But Widget Blocks rendering must be
handled by the module from which the block originates. This is handled by QuickApps using _View Hooks_, each time a Widget Block is being
rendered QuickApps ask for its content using a hook callback which must be aswered by the module which registered the block.  
The name of this hook is formatted as follow:

> module + "_" + delta


#### Example

For the Categories block we have:

- module: "Taxonomy"
- delta: "vocabularies"


Means that the Taxonomy module must define the following hook method:

> "Taxonomy" + "_" + "vocabularies" = "Taxonomy_vocabularies"

But as you know, hook names must always be **under_scored**:

> "taxonomy_vocabularies"


And this hook method may look as follow:

    class TaxonomyHookHelper extends Apphelper {
        public function taxonomy_vocabularies($block_info) {
			return array(
			    'title' => 'Block Title',
			    'body' => 'Block Content'
			);
        }
    }

The $block_info variable is automatically passed by QuickApps to the hook handler method, it contains several information related to the block
such as title, weight, description, etc.  
The returning value of this method may be an associative array with two keys:

- title: **Optional** new title for the block.
- body: Block's content body.


***


###### Since 1.1

If the the module has created the view element `block_[delta]` it will be automatically used as block body, in this case there is **no need to define a hook**
method. For the Categories example, if the following file exists it will be used as the body of the block and there is no need to define the
"taxonomy_vocabularies" hook method:

    /Taxonomy/View/Element/block_vocabularies.ctp


Also, if the returning value is a **string instead of array** it will be used as block's body as well, for example:

    public function taxonomy_vocabularies($block_info) {
        return "Content for the Block!";
    }
