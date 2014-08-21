Themes
======

A theme is a unified set of design elements and color schemes that you can apply to your Web pages to give them a professional look.
Using a theme is a quick and easy way to make sure your Web pages are visually consistent and appealing.

QuickApps CMS includes by default a [Frontend](http://en.wikipedia.org/wiki/Front_and_back_ends) theme named `Default` and a 
[Backend](http://en.wikipedia.org/wiki/Front_and_back_ends) theme named `Admin`.  
As you may notice, there are two type of themes, `Backend` and `Frontend`, which are managed independently
by QuickApps.

This document describes some of the basics of theme creation and structure, but we highly recommend you to use [QuickApps CLI](../developers/quickapps-cli.md)
to easly create and build new themes.

Recommended reading: http://book.cakephp.org/2.0/en/views.html


Theme Names
===========

Same as modules, theme have two names:

* **machine name**: Always be in CamelCase format (used internally by QuickApps CMS). e.g.: `RedBlue`
* **human name**: Human readble name. e.g.: `Red and Blue`


Structure
=========

Below the basic folders/files structure used by themes.
    
    |- MyThemeName
	    |- app/
	    :    |- ThemeMyThemeName/
	    :    :    |- Config/
	    :    :    :    |- bootstrap.php
	    :    :    :    |- routes.php
	    :    :    |- Controller/
	    :    :    :    |- Component/
	    :    :    :    :    |- InstallComponent.php
	    :    :    :    :    |- MyThemeNameHookComponent.php
	    :    :    |- Model/
	    :    :    :    |- Behavior/
	    :    :    :    :    |- MyThemeNameHookBehavior.php
	    :    :    |- View/
	    :    :    :    |- Helper/
	    :    :    :    :    |- MyThemeNameHookHelper.php
	    :    :    :    :    |- MyThemeNameHooktagsHelper.php
	    |- Elements/
	    :    |- theme_menu.ctp
	    :    |- theme_block.ctp
	    :    |- theme_breadcrumb.ctp
	    |- Layouts/
	    :    |- default.ctp
	    |- webroot/
	    :    |- css/
	    :    |- files/
	    :    |- img/
	    :    |- js/
	    |- MyThemeName.yaml
	    |- thumbnail.png


#### IMPORTANT

`app/ThemeMyThemeName`, this is your _theme associated module_, it behave like a regular module and its structure is same [structure used by modules](../developers/modules.md#structure).
It's the responsible for several task related to your theme such as installation/uninstallation or hooktags-handler. 

All **theme associated modules** MUST prefix its name by `Theme` word in order to avoid name collisions between other modules in the system.
In the example above `ThemeMyThemeName` is the associated module to theme `MyThemeName`.


Configuration YAML
==================

Themes -same as modules- *must* define a configuration .yml file containing all the information about it, such as Theme name, available
regions, author, etc. This .yaml file must be named same as your theme machine name.  
For example, if your theme machine name is `BlueSky` then `BlueSky.yaml` should be defined.

    info:
        admin: true
        name: My Theme Name
        description: Brief description about your module
        version: 1.0
        core: 1.x
        author: Your name <your@email.com>
        dependencies:
            ModuleTest (1.x)

    stylesheets:
        all: [reset.css, styles.css]
    
    javascripts:
        file: [some_file.js, shadowbox/shadowbox.js]
        inine: ['alert("this is an inline js code");']

    regions:
        help: Help messages
        toolbar: Toolbar
        theme-region-1: Region 1
        theme-region-2: Region 2
        theme-region-n: Region n

    layout: default
    login_layout: login


##### Explanation

* **admin (optional)**: set to `true` if it is a backend theme, or false (or unset) for frontend theme.
* **name (required)**: human readable name of your theme, example 'Soft Lights'
* **description (optional)**: a brief description about your theme, example: 'Inspired by my dorm lights'
* **version (optional)**: you can give your theme whatever version string makes sense, e.g.: 1.0, 1.0, etc.
* **core (required)**: version of QuickApps CMS, example: 1.x means any branch of QuickApps CMS v1.0
* **author (optional)**: theme's author information
* **dependencies (optional)**: required modules used by your theme.
* **stylesheets (optional)**: css files to load always this theme is used, each css collection must be grouped by media types.

    ###### Example:

    `all: [reset.css, styles.css]` will always produce the HTML below:

        <link rel="stylesheet" type="text/css" href="/theme/MyThemeName/css/reset.css" media="all" />
        <link rel="stylesheet" type="text/css" href="/theme/MyThemeName/css/styles.css" media="all" />

* **javascripts (optional)**: js files/code to include always in your layout head. There are two groups available, `file` and `inline`.

	###### Example:

	`file: [some_file.js, shadowbox/shadowbox.js]` will produce the HTML below (in your layout header):

	```
    <script type="text/javascript" src="/theme/MyThemeName/js/some_file.js"></script>
    <script type="text/javascript" src="/theme/MyThemeName/js/shadowbox/shadowbox.js"></script>
	```

	`inline: ['alert("this is an inline js code");']` will produce the HTML below (in your layout header):
	
	```
    <script type="text/javascript">
    //<![CDATA[
		// ... Other js code

        alert("this is an inline js code");
    //]]>
    </script>
	```

* **regions (required)**: theme authors can define and implement any number of `regions` for content to be rendered into. Backend themes (admin: true) **must** always define both `help` and `toolbar` regions.
* **layout (required)**: default .ctp file to use as layout. This must be located in `View/Layouts` folder of your theme.
* **login_layout (optional)**: valid only for backend themes (admin: true). Layout to use for the login screen, if not set `login.ctp` will be used by default.


Rendering Elements
==================

QuickApps incorporates a number of `default elements` responsible for various rendering tasks, such as Menu, Blocks, etc.  
Themes may overwrite these elements and modify the way they are rendered.  
To overwrite any of this elements simply create adn replicate the element under the `View/Elements` folder of the theme.

* **theme_block.ctp:** Block rendering
* **theme_breadcrumb.ctp:** Breadcrumbs rendering
* **theme_comment.ctp:** Single comment rendering
* **theme_flash_message.ctp:** Flash messages
* **theme_menu.ctp:** Menu rendering
* **theme_node.ctp:** Node snippet & details page
* **theme_node_comments.ctp:** Node's comments list
* **theme_node_comments_form.ctp:** Comment submission form
* **theme_node_edit.ctp:** Node's edit form, used in backend
* **theme_search_form.ctp:** Rendered as part of each search result (Node.View/Node/search.ctp)

_Default elements are located in `QuickApps/View/Elements`_


Toggle Display
==============

With the toggle display options, user can select the elements he wish to display on his site, these include:

* Logo
* Site name
* Site slogan
* Shortcut icon

How these are used depends on your theme. For example, some themes may always ignore site's slogan.


Logo Image Settings
-------------------

Allows to use a custom image as logo by specifying its URL.
Empty value means use QuickApps logo as default.


Shortcut Icon Settings
----------------------

The shortcut icon is the favicon displayed in the address bar of our browser next to the web address.  
By default, this is a small version of QuickApps logo. This option allows you to use a cutom favicon by specifying its URL.


How do I show/hide those elements ?
-----------------------------------

In your theme layout use:

    Configure::read('Theme.settings.OPTION');


Where `OPTION` may be one of:

* site_logo (bool): Display site logo?
* site_name (bool): Display site name?
* site_slogan (bool): Display site slogan?
* site_favicon (bool): Display site favicon ?
* site_logo_url (string): URL to logo
* site_favicon_url (string): URL to favicon


#### Example

    <!-- Show logo image if enabled -->
    <?php if (Configure::read('Theme.settings.site_logo')): ?>
        <?php echo $this->Html->image(Configure::read('Theme.settings.site_logo_url'), array('class' => 'site-logo')); ?>
    <?php endif; ?>


Setting Up The Header
=====================

The code below shows the basic setup for layout header.
For more information about [Layout Helper go here](the-layout-helper.md).

	    <html 
	        xmlns="http://www.w3.org/1999/xhtml"
            xml:lang="<?php echo Configure::read('Variable.language.code'); ?>"
            version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">

        <head>
            <title><?php echo $this->Layout->title(); ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <?php echo $this->Layout->meta(); ?>
            <?php echo $this->Layout->stylesheets(); ?>
            <?php echo $this->Layout->javascripts(); ?>
            <?php echo $this->Layout->header(); ?>
        </head>


Displaying Blocks
=================

The code below will render out all blocks assigned to the region `my-theme-region` of your theme:

    <?php echo $this->Block->region('my-theme-region'); ?>
    
Now, for example you would like to show certain area/region of your theme only if there are blocks availables to show on it, this allows you
for example hide the left column of your layout if there are no blocks to show on it and use all the available width for the rest of your content.

    <?php if ($this->Block->regionCount('my-theme-region')): ?>
        <div class="left-column">
            <?php echo $this->Block->region('my-theme-region'); ?>
        </div>
    <?php endif; ?>


Note: The `regionCount()` method returns the number of blocks assigned to the specified region.


Rendering The Main Content
==========================

Simply by using the code below in your theme layout you can render out the main content.

    <?php echo $this->Layout->content(); ?>


Configurable Style
==================

**New in version 1.1**

If you want your theme to include some configurable CSS choices, you can define which styles can be tweaked through the theme's configuration
panel by adding some special comment-tags on your css files.


Requirements
------------

* Make sure your theme's layout include your stylesheet properly using:

        $this->Layout->stylesheets();

* Your theme's css files must be located in the `/webroot/css` folder of the theme.


Comment Tags
------------

All you have to do is add properly formatted CSS comments into your stylesheets.  
Comment-tag's syntax is very similar to Hooktags syntax:

    [tag_name param1=value1 param2='other value 2' ...] TAG_CONTENT [/tag_name]

The comment-tag should be surrounding the css value to tweak. e.g.:

    div.class-selector {
        color:[color] #ffffff [/color];
    }

The above will allow users to change the text color for the DOM element `div.class-selector`. Also, default text color is white `#ffffff`.

##### Available tags

* font
* color
* size
* miscellaneous


##### Available parameters

* `title`: Name of the selector to display in the customization form.
* `id`: Selector `alias`.
* `group`: Name of the group that selector belongs to. All selectors under the same group will be grouped under the same fieldset.


Basically, there are two types of selectors:

- Color Selectors
- Font Selectors


***


The `miscellaneous` tag (an empty style comment) will allow your theme to incorporate any kind of css you want:

    /*[miscellaneous]*//*[/miscellaneous]*/


***	


##### Example

    body {
        font:/*[font title='Main font']*/normal normal 13px Arial/*[/font]*/;
        background: /*[color title='Body background']*/#777777/*[/color]*/;
    }

    /*[miscellaneous]*//*[/miscellaneous]*/


Aliasing values
---------------

If you need to use the same value (color, font, size, etc) in two or more places of your css sheet.

    body {
        background: /*[color title='Header top' id='body-bg']*/ #282727 /*[/color]*/;
    }

    div.footer {
        background: /*[body-bg]*/ #28ffff /*[/body-bg]*/;
    }


Above, the selected color value for body's background will be used for div.footer's background as well.  
The `#28ffff` value in `div.footer` will be used by default if no value is available for the `body-bg` tag.


Theming Nodes by Content Type
=============================

To **theme individual content types** in different ways, you need to create a file `theme_node_[type].ctp` in your theme's `Elements` folder,
where [type] is the machine readable name of the content type.

### Some examples

* **theme_node_article.ctp**: Theme only `Article` type nodes.
* **theme_node_page.ctp**: Theme only `Basic Page` type nodes.

***

To *theme individual content type per view mode**, you need to create a file `theme_node_[type]_[view_mode].ctp` in your theme's `Elements`
folder, where [view_mode] is any valid view mode, e.g. rss, full, print, etc.

### Some examples

* **theme_node_article_rss.ctp**: Theme only `Article` type nodes when view mode is RSS.
* **theme_node_page_full.ctp**: Theme only `Basic Page` type nodes when view mode is FULL.

***

To use **different layout for individual content types**, you need to create a file `node_[type].ctp` in your theme's `Layouts` folder, where
[type] is the machine readable name of the content type.

### Some examples:

* **node_article.ctp**: Layout for `Article` node type only.
* **node_page.ctp**: Layout for `Basic Page` node type only.


Advanced Theme Settings
=======================

By using your theme's associated Module you can add extra features to your themes.  
For example, allow users to change theme's width.

To add extra fields to your theme settings form, you have to create the following file:


    /MyThemeName/
        app/
            MyThemeName/
                View/
                    Elements/
                        settings.ctp


Themes are registed in the system as Modules. And every module is allowed to store in database their own settings parameters (All modules
information is stored in the `modules` table).  
Module's settings parametters are stored in the `settings` column of the `modules` table.


### Example

    // ROOT/Themes/Themed/MyThemeName/app/MyThemeName/View/Elements/settings.ctp

    echo $this->Form->input('Module.settings.my_theme_color');
    echo $this->Form->input('Module.settings.theme_width');

The code above will create two text boxes where user may introduce values.  
Now you can read these values in any view or layout of your theme:

    Configure::read('Theme.settings.my_theme_color');
    Configure::read('Theme.settings.theme_width');

Now for example, now you may want to adjust layout width based on the width introduced by the user.

    <div id="main-content" style="width:<?php echo Configure::read('Theme.settings.theme_width'); ?> px;">
		<!-- Content here -->
	</div>


Hooktags
========

[Hooktags](../developers/hooktags.md) handlers are special PHP functions which produce (commonly) a HTML result.  
Themes may define especifics [hooktags](../developers/hooktags.md) which are handled by its associated module. 

All theme's hooktags-handler methods should be placed on your **theme associated module**.  
As before, associated module behaves exactly as regular [module](../developers/modules.md), this means that all hooktags handlers should be
placed in the `Hooktags` Helper class, from [the tree above](#structure) `MyThemeNameHooktagsHelper.php`:


    |- MyThemeName
	    |- app/
	    :    |- ThemeMyThemeName/
	    :    :    |- View/
	    :    :    :    |- Helper/
	    :    :    :    :    |- MyThemeNameHooktagsHelper.php


For a more complete example [visit this link](../developers/hooktags.md#example).