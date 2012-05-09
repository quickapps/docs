Theme Name
==========

Theme names must always be in CamelCase format.
Also there are two type of themes, `Backend` and `Frontend` which are managed independently by QuickApps.


Theme Folders & Files
===============

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

_`app/ThemeMyThemeName`, this is your theme associated module, this behave like a regular module and its structure is same [structure used by modules](../developers/modules/structure.md)._


#### IMPORTANT
All **theme modules** MUST prefix its name by `Theme` word, In the example above: `ThemeMyThemeName` is the associated module to theme `MyThemeName`


MyThemeName.yaml
================

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
        embed: ['alert("embed code");']

    regions:
        help: Help messages
        toolbar: Toolbar
        theme-region-1: Region 1
        theme-region-2: Region 2
        theme-region-n: Region n

    layout: default
    login_layout: login


##### Explanation

* **admin (optional)** Set to `true` if it is a backend theme, or false (or unset) for frontend theme.
* **name (required)** Human readable name of your theme, example 'Soft Lights'
* **description (optional)** a brief description about your theme, example: 'Inspired by my dorm lights'
* **version (optional)** you can give your theme whatever version string makes sense, e.g.: 1.0, 1.0, etc.
* **core (required)** version of QuickApps CMS, example: 1.x means any branch of QuickApps CMS v1.0
* **author (optional)** theme's author information
* **dependencies (optional)** required modules used by your theme. (see [modules dependencies](.))
* **stylesheets (optional)** css files to load always this theme is used, each css collection must be grouped by media types.
    Example:

    `all: [reset.css, styles.css]` will always produce the HTML below:

        <link rel="stylesheet" type="text/css" href="/theme/MyThemeName/css/reset.css" media="all" />
        <link rel="stylesheet" type="text/css" href="/theme/MyThemeName/css/styles.css" media="all" />

* **javascripts (optional)** js files/code to include always in your layout head.
* **regions (required)** Theme authors can define and implement any number of `regions` for content to be rendered into. Backend themes (admin: true) **must** always define both `help` and `toolbar` regions.
* **layout (required)** Default .ctp file to use as layout. This must be located in `View/Layouts` folder of your theme.
* **login_layout (optional)** Valid only for backend themes (admin: true). Layout to use for the login screen, if not set `login.ctp` will be used by default.


Elements
--------

QuickApps incorporates a number of `default elements` responsible for various rendering tasks,
such as Menu, Blocks, etc.
Themes may overwrite this elements and modify the way they are rendered.
To overwrite any of this elements simply create the element under `View/Elements` folder of your theme.

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

With the toggle display options, we can select the elements we wish to display on our site, these include:

* Logo
* Site name
* Site slogan
* Shortcut icon

Logo Image Settings
-------------------

Allows to use a custom image as logo by specifying its URL.
Empty value means use QuickApps logo as default.


Shortcut Icon Settings
----------------------

The shortcut icon is the favicon displayed in the address bar of our browser next to the web address.
By default, this is a small version of QuickApps logo. This option allows you to use a cutom favicon
by specifying its URL.


How do I show/hide those elements ?
-----------------------------------

In your theme layout .ctp file use:

    Configure::read('Theme.settings.OPTION');

Where OPTION may be one of:

* site_logo (bool): Display site logo?
* site_name (bool): Display site name?
* site_slogan (bool): Display site slogan?
* site_favicon (bool): Display site favicon ?
* site_logo_url (string): URL to logo
* site_favicon_url (string): URL to favicon

#### Example
    <!-- Show logo image if has been enabled -->
    <?php if (Configure::read('Theme.settings.site_logo')): ?>
        <img src="<?php echo Configure::read('Theme.settings.site_logo_url'); ?>" />
    <?php endif; ?>


Setting Up The Header
=====================


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


Showing Blocks
==============

The code below will render out all blocks assigned to the region 'my-theme-region' of your theme

    <?php echo $this->Layout->blocks('my-theme-region'); ?>
    

Now, for example you would like to show certain area/region of your theme only if there are blocks
availables to show on it.
This allows you for example hide the left column of your layout if there are no blocks to show on
it and use all the available width for the rest of your content.
     
    <?php if (!$this->Layout->emptyRegion('my-theme-region')): ?>
        <div class="left-column">
            <?php echo $this->Layout->blocks('my-theme-region'); ?>
        </div>
    <?php endif; ?>



Rendering The Main Content
==========================

Simply by using the code below in your theme layout you can render out the main content.

    <?php echo $this->Layout->content(); ?>
	


Configurable Style
==================

**New in version 1.1**

If you want your theme to include some configurable CSS choices, you can define which styles
can be tweaked through the theme's configuration panel by adding some special comment-tags on
your css files.


Requirements
------------

* Make sure your theme's layout include your stylesheet properly using:

        $this->Layout->stylesheets();

* Your theme's css files must be located in the `css` folder of the theme.


Comment tags
------------

All you have to do is add properly formatted CSS comments into your stylesheets.
Comment-tag's structure is the same used by Hooktags:

    [tag_name param1=value1 param2='other value 2' ...] TAG_CONTENT [/tag_name]

The comment-tag should be surrounding the css value to tweak. e.g.:

    div.class-selector {
        color:[color] #ffffff [/color];
    }


**Available tags:**

* font
* color
* size
* miscellaneous


**Available parameters:**

* `title`: Name of the selector to display in the customization form.
* `id`: Selector `alias`.
* `group`: Name of the group that selector belongs to. All selectors under the same group will be grouped in the same fieldset.

***

Basically, there are two types of selectors:

- Color Selectors
- Font Selectors

The `miscellaneous` tag (an empty style comment) will allow your theme to incorporate any kind of css you want:

       /*[miscellaneous]*/ /*[/miscellaneous]*/


##### Example

        body {
            font:/*[font title='Main font']*/normal normal 13px Arial/*[/font]*/;
            background: /*[color title='Body background']*/#777777/*[/color]*/;
        }

       /*[miscellaneous]*/ /*[/miscellaneous]*/


Aliasing values
---------------

If you need to use the same value (color, font, size, etc) in two or more places of your css file.

        body {
            background: /*[color title='Header top' id='body-bg']*/#282727/*[/color]*/;
        }

        div.footer {
            background: /*[body-bg]*/#28ffff/*[/body-bg]*/;
        }

Above, the selected color value for body's background will be used for div.footer's background as well.
The `#28ffff` value in `div.footer` will be used by default if no value is available for the `body-bg` tag.


Theming Nodes by Content Type
=============================

To **theme individual content types** in different ways,
you need to create a file `theme_node_[type].ctp` in your theme's `Elements` folder,
where [type] is the machine readable name of the content type.

### Some examples:

* **theme_node_article.ctp**: Theme only `Article` type nodes.
* **theme_node_page.ctp**: Theme only `Basic Page` type nodes.

***

To use **different layout for individual content types**,
you need to create a file `node_[type].ctp` in your theme's `Layouts` folder,
where [type] is the machine readable name of the content type.

### Some examples:

* **node_article.ctp**: Layout for `Article` node type only.
* **node_page.ctp**: Layout for `Basic Page` node type only.


Advanced Themes
===============

By using your theme's associated Module you can add extra features to your themes.
For example, allow to users change theme's color.

To add extra fields to your theme settings form, you have to create the following file:

    ROOT/Themes/Themed/MyThemeName/app/MyThemeName/View/Elements/settings.ctp

Themes are registed in the system as Modules. And every module is allowed to store in database
their own settings parameters.
(All modules information is stored in the `modules` table).
Module's settings parametters are stored in the `settings` column of the `modules` table.

    // ROOT/Themes/Themed/MyThemeName/app/MyThemeName/View/Elements/settings.ctp
    echo $this->Form->input('Module.settings.my_theme_color');
    echo $this->Form->input('Module.settings.theme_width');

Now you can read this settings values in any view:

    Configure::read('Theme.settings.my_theme_color');
    Configure::read('Theme.settings.theme_width');