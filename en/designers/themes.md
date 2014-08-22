Themes
======

A theme is a unified set of design elements and color schemes that you can apply
to your Web pages to give them a professional look. Using a theme is a quick and
easy way to make sure your Web pages are visually consistent and appealing.

QuickAppsCMS includes by default a
[Frontend](http://en.wikipedia.org/wiki/Front_and_back_ends) theme named `Frontend`
and a [Backend](http://en.wikipedia.org/wiki/Front_and_back_ends) theme named `Backend`.

As you may notice, there are two type of themes, `Backend` and `Frontend`, which
are managed independently by QuickAppsCMS.

This document describes some of the basics of theme creation and structure, but
we highly recommend you to use [QuickApps CLI](../developers/quickapps-cli.md)
to easily create and build new themes.

Recommended reading: http://book.cakephp.org/2.0/en/views.html


Theme Anatomy
=============

> Themes in CakePHP are simply [plugins](plugins.md) that focus on providing
> view files. In addition to template files, they can also provide helpers and cells
> if your theming requires that. When using cells and helpers from your theme, you
> will need to continue using the plugin-syntax.
>
> -- CakePHP's book

That means that your theme folder structure is the same [used by plugins](plugins.md).

In QuickAppsCMS tThere are only two main difference between plugins and themes:

1. Themes must define some particular keys in the "composer.json" schema
2. Themes must be named using the `Theme` suffix.


The "composer.json" File
========================

Themes -same as plugins- *must* define a "composer.json" file containing all the
information about the theme itself, such as name, available regions, author, etc.

In order to distinguish between plugins and themes, themes must suffix their names
with the `Theme` word, so for example if you have a "Blog" plugin, changing its
name to "BlogTheme" will be automatically considered a Theme.

Theme's names -same as plugins- is inflected from the `name` key from composer.json
schema.

Also the must define the `extra.regions` key, a list of all regions your theme
implements.

And optionally, they may define the `extra.admin` key indicating whether your theme
is a Backend theme or not. Defaults to "false" if not given.


Here a full working example:

    {
        "name": "quickapps-themes/basic-theme",
        "description": "QuickApps CMS theme skeleton.",
        "type": "quickapps-plugin",
        "require": {
            "quickapps/cms": "2.0.*-dev"
        },
        "version": "1.0",
        "extra": {
            "admin": false,
            "regions": {
                "main-menu": "Main Menu",
                "right-sidebar": "Right Sidebar",
                "site-footer": "Site Footer"
            }
        }
    }


Setting Up The Layout's Header
==============================

Layout's header is where you should place all links to your CSS and JS files,
among other things such as meta-descriptions and so on. QuickAppsCMS provides
a basic header setup which you can use within your theme's layouts:

    <html>
    <head>
        <?php echo $this->Html->head(); ?>
    </head>


Regions
=======

Theme authors can define and implement any number of regions for content to be
rendered into. Regions are areas of your layout aimed to contain blocks, regions
(by default) may contain an unlimited number of blocks.

For rendering a region's content (blocks) in your layout you must use the
RegionHelper as follow:


    <?php echo $this->Region->create('right-sidebar'); ?>

You can do nifty things such as combine to regions, limit the number of blocks,
etc. For example:

Merge `left-sidebar` and `right-sidebar` regions together, the resulting region
limit the number of blocks it can holds to `3`:

    echo $this->Region
        ->create('left-sidebar')
        ->append($this->Region->create('right-sidebar'))
        ->blockLimit(3);

For more information please check `Block\View\Helper\RegionHelper` documentation.


Rendering The Main Content
==========================

Simply by using the code below in your theme layout you can render out the
main content of each page.

    <?php echo $this->fetch('content'); ?>


Specialized Renders for Nodes
=============================

You can define `specialized-renders` according to your needs as described below.
You must simply create these template elements under within the `Template/Element`
directory of your theme.

---

### Render node per node-type & view-mode

     render_node_[node-type]_[view-mode]

Renders the given node per `node-type` + `view-mode` combination:

    // render for `article` nodes in `full` view-mode
    `render_node_article_full.ctp`

    // render for `article` nodes in `search-result` view-mode
    `render_node_article_search-result.ctp`

    // render for `basic-page` nodes in `search-result` view-mode
    `render_node_basic-page_search-result.ctp`

### Render node per node-type

    render_node_[node-type]

Similar as before, but just per `node-type` and any view-mode:

    // render for `article` nodes
    `render_node_article.ctp`

    // render for `basic-page` nodes
    `render_node_basic-page.ctp`

### Render node per view-mode

    render_node_[view-mode]"

Similar as before, but just per `view-mode` and any `node-type`:

    // render any node (article, page, etc) in `rss` view-mode
    `render_node_rss.ctp`

    // render any node (article, page, etc) in `full` view-mode
    `render_node_full.ctp`

NOTE: To avoid collisions between `view-mode` names and `node-type` names, you
should alway use unique and descriptive names as possible when defining new
content types. By default, Node plugin defines the following view-modes:
`default`, `teaser`, `search-result`, `rss`, `full`.

### Default

    render_node

This is the global render, if none of the above is found we try to use this last.
