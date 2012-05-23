Blocks
======

Blocks are the boxes of content visible in various area of your Themes.  
There are two type of blocks in the system:

- Widget Blocks: Those generated on-the-fly (or created) by various modules
- Custom Blocks: Those created in the administer blocks area (/admin/block/manage)

Block may appear on a page depending on both the theme and on administrative block settings. Block settings are controlled from the block
administration screen. From this screen, it is possible to control whether each block is enabled where it will be placed on the page, and
control the visibility of blocks on each page.


The "blocks" table
------------------

Internally, a block is simply a record in the `blocks` table, each time a Custom Blocks is created in the administer blocks area a new records is
added to this table. Modules may create Widget Blocks during the installation process as well by adding new records to this table.  
Also, each menu have an associated block registered in this table.

This table holds the following attributes:


-	title: Custom title for the block.
-	delta: Unique ID for block _within a module_.
-	module: The module from which the block originates. For example: "Taxonomy" for the Categories block, and "Block" for any Custom Blocks.
-	status:	Block enabled status. 1 for enabled, 0 disabled.
-	visibility:	Flag to indicate how to show blocks on pages.
	- 0: Show on all pages except listed pages
	- 1: Show only on listed pages
	- 2: Use custom PHP code to determine visibility
-	pages: List of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.
-	settings: Serialized array of extra information used by the block. Commonly used by Widget Blocks.


Widget Blocks
-------------

The rendering process of both Custom Blocks and Menu Blocks are internally managed by QuickApps CMS. But Widget Blocks rendering must be
handled by the module from which the block originates. This is achieved using _View Hooks_, each time a Widget Block is being
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

If the module has created the view element `[delta]_block` it will be automatically used as block body, in this case there is **no need to define a hook**
method. For the Categories example, if the following file exists it will be used as the body of the block and there is no need to define the
"taxonomy_vocabularies" hook method:

    /Taxonomy/View/Element/vocabularies_block.ctp


Also, if the returning value is a **string instead of array** it will be used as block's body as well, for example:

    public function taxonomy_vocabularies($block_info) {
        return "Content for the Block!";
    }



Creating blocks during module installation
------------------------------------------

A common action performed by modules is the creation of new blocks during the installation process. For example, an User module may
create a new "Who's online" block in the drashboard panel to display a list of all logged in users.  

You can manually create a new block by addind a new record in the `blocks` table, for example:

    public function afterInstall() {
	    ClassRegistry::init('Block.Block')->save(
            array(
                'title' => "Who's online",
                'delta' => 'whos_online',
                'settings' => array(
                    'show_limit' => 10
                )
            )
        );
    }

As you see, we have optionally added the `show_limit` parameter to the block's settings array. This can be used later by the block to
limit the amount of users to display in the list of logged in users.

###### Since 1.1

The Installer Component includes the `createBlock()` method which make easier the block creation process during installations.
Returning to our User module example, this module's [Install Component](modules.md#install-component) now may look as follow:


    public function afterInstall() {
	    $this->Installer->createBlock(
            array(
                'title' => "Who's online",
                'delta' => 'whos_online',
                'settings' => array(
                    'show_limit' => 10
                )
            )
        );
    }

We recommend you to read the API for more information about `createBlock()`


Adding custom options to Widget Blocks
--------------------------------------

Now you may want to add a span of special options related to your block, this options may affect the way block content is rendered or
change its behavior. All these extra-options's information must be stored in the `settings` attribute of the block.  

In our User module and its "WHo's online" block example we had added the `show_limit` option. This option should be accessible by administrator so they can
change the amount of user, for example, from 10 to 5.  
QuickApps uses the following hook callback to ask for block's extra form fields, this hook method should return a HTML string containing
all the form input elements that can be tweaked by administrators:

> module + "_" + delta + "_settings"

In our example we may have:

> "user_whos_online_settings"


Commonly you will place these extra form inputs in an View element, so the hook method above may simply return this element:


    public function user_whos_online_settings($data) {
        return $this->_View->element('User.settings_for_whos_online', $data);
    }

In the code above we are simply rendering an element which contains all the form elements required by the Widget Block, this element should be placed in:

> /User/View/Elements/settings_for_whos_online.ctp


And this element may looks as follow:

    // User/View/Elements/settings_for_whos_online.ctp
    echo $this->Form->input('Block.settings.show_limit');


###### Since 1.1

Since 1.1 you may simply create a View element containing all the form inputs, in this way there is **no need to define a hook callback**, the name of this element
must formatted as follow:

> delta + "_block_settings"

In our example, if the following view element exists it will be used instead of the hook callback:

> /User/View/Elements/whos_online_block_settings.ctp

And its content should be exactly the same as before.
