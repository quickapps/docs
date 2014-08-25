Themes
------

A theme is a unified set of design elements and color schemes that you can apply
to your Web pages to give them a professional look. Using a theme is a quick and
easy way to make sure your Web pages are visually consistent and appealing.

QuickAppsCMS includes by default a [Frontend](def_frontend) theme named `Frontend`
and a [Backend](def_backend) theme named `Backend`.

As you may notice, there are two type of themes, `Backend` and `Frontend`, which
are managed independently by QuickAppsCMS.

This document describes some of the basics of theme creation and structure.

Recommended reading: http://book.cakephp.org/2.0/en/views.html


### Theme Anatomy

> Themes in CakePHP are simply [plugins][plugins] that focus on providing
> view files. In addition to template files, they can also provide helpers and cells
> if your theming requires that. When using cells and helpers from your theme, you
> will need to continue using the plugin-syntax.
>
> -- CakePHP's book

That means that your theme folder structure is the same
[used by plugins][plugins]. However in QuickAppsCMS there are only two main
difference between plugins and themes:

1. Themes must define some particular keys in the "composer.json" schema
2. Themes must be named using the `Theme` suffix.


#### The "composer.json" File

Themes -same as plugins- *must* define a "composer.json" file containing all the
information about the theme itself, such as name, available regions, author, etc.

In order to distinguish between plugins and themes, themes must suffix their names
with the `Theme` word, so for example if you have a "Blog" plugin, changing its
name to "BlogTheme" will be automatically considered a Theme.

Theme's names -same as plugins- is inflected from the `name` key from composer.json
schema.

Also the must define the `extra.regions` key, a list of all regions your theme
implements. Regions are defined as an associative array `machine-name` => `human name`,
machine-name is used internally when referring to a region, and human name is the
name users will see in the administration panel when assigning blocks to a region.

And optionally, they may define the `extra.admin` key indicating whether your theme
is a Backend theme or not. Defaults to "false" if not given.


Here a full working example:

```json
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
```


### Layouts

Most of the following is an extract from CakePHP's book.

> A layout contains presentation code that wraps around a view.
> Anything you want to see in all of your views should be placed in a layout.

Default layout is located at `/src/Template/Layout/default.ctp` of your themes.
If you want to change the overall look of your application, then this is the right
place to start, because controller-rendered view code is placed inside of the
default layout when the page is rendered.

Other layout files should be placed in `/src/Template/Layout`. When you create a
layout, you need to tell QuickAppsCMS where to place the output of your views.
To do so, make sure your layout includes a place for `$this->fetch('content')`
Here's an example of what a default layout might look like:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= h($title) ?></title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <!-- Include external files and scripts here (See HTML helper for more info.) -->
        <?php echo $this->fetch('meta'); ?>
        <?php echo $this->fetch('css'); ?>
        <?php echo $this->fetch('script'); ?>
    </head>

    <body>
        <!-- If you'd like some sort of menu to show up on all of your views, include it here -->
        <div id="header">
            <div id="menu">...</div>
        </div>

        <!-- Here's where I want my views to be displayed -->
        <?= $this->fetch('content') ?>

        <!-- Add a footer to each displayed page -->
        <div id="footer">...</div>
    </body>
</html>
```

The `script`, `css` and `meta` blocks contain any content defined in the views
using the built-in HTML helper. Useful for including JavaScript and CSS files
from views. The `content` block contains the contents of the rendered view.


Although `default.ctp` layout should be enough for most cases, QuickAppsCMS may
use different layouts depending on the situation, for example when rendering
the user's login form `login.ctp` **will be used if exists**, here is a list
of layouts your theme might define:

- `default.ctp`: This is the default layout, if none of the listed below exists
   this layout will be used instead.
- `login.ctp`: Used when rendering user's login screen.
- `ajax.ctp`: Used when rendering AJAX responses
- `error.ctp`: When an error is reached; 404, 500, etc


#### Layout's Header

Layout's header is where you should place all links to your CSS and JS files,
among other things such as meta-descriptions and so on. To make your life
easier, QuickAppsCMS provides a basic header setup which you can use within
your theme's layouts:

```php
<html>
<head>
    <?php echo $this->Html->head(); ?>
</head>
```

The `head()` methods accepts a series of options which you may tweak depending
on your needs. For more information please check `QuickApps\View\View::head()`


#### Regions

Regions are areas of your layout aimed to contain blocks, regions may contain an
unlimited number of blocks (by default). Theme authors can define and implement
any number of regions for content to be rendered into.

For rendering a region's content (blocks) in your layout you must use the
RegionHelper as follow:

```php
<?php echo $this->Region->create('right-sidebar'); ?>
```

You can do nifty things such as combine two or more regions, limit the
number of blocks a region can hold, etc. For example:

Merge `left-sidebar` and `right-sidebar` regions together, the resulting region
limits the number of blocks it can holds to three (3):

```php
echo $this->Region
    ->create('left-sidebar')
    ->append($this->Region->create('right-sidebar'))
    ->blockLimit(3);
```

As you may have noticed, we always use region's machine-name when referring to a
particular region; `left-sidebar` (human name: Left Sidebar)

For more information please check `Block\View\Helper\RegionHelper` documentation.


### Rendering The Main Content

Simply by using the code below in your theme layout you can render out the
main content of each page. For example, in your layout's body:

```php
<body>
    <?php echo $this->fetch('content'); ?>
</body>
```


### View Modes

How a content is displayed depends on the context in which it is rendered, such
as the difference between `full` nodes and `teasers`.

In a standard QuickAppsCMS installation, the following view modes are available:

- **Default** (default): Default is used as a generic view mode if no other view
  mode has been specified.
- **Teaser** (teaser): Teaser is a really short format that is typically used in
  main the main page, such as "last news", etc.
- **Search Result** (search-result): Search Result is a short format that is
  typically used in lists of multiple content items such as search results.
- **RSS** (rss): Similar to "Search Result" but intended to be used when
  rendering content as part of a RSS feed list.
- **Full** (full): Full content is typically used when the content is displayed
  on its own page.

**NOTE:** Between parenthesis are the "machine-names" of each view mode, these
names are used internally when referring to a specific view mode.

These context are automatically set by QuickAppsCMS during the rendering process
of every content, however other [plugins][plugins] may change this on the fly
and define their own.


### Specialized Renders for Nodes

You can define `specialized-renders` according to your needs as described below.
You must simply create these template elements within the `/src/Template/Element`
directory of your theme.


#### Render node per node-type and view-mode

> render_node_[node-type]_[view-mode]

Renders the given node per `node-type` + `view-mode` combination:

    // render for `article` nodes in `full` view-mode
    `render_node_article_full.ctp`

    // render for `article` nodes in `search-result` view-mode
    `render_node_article_search-result.ctp`

    // render for `basic-page` nodes in `search-result` view-mode
    `render_node_basic-page_search-result.ctp`


#### Render node per node-type

> render_node_[node-type]

Similar as before, but just per `node-type` and any view-mode:

    // render for `article` nodes
    `render_node_article.ctp`

    // render for `basic-page` nodes
    `render_node_basic-page.ctp`

#### Render node per view-mode

> render_node_[view-mode]"

Similar as before, but just per `view-mode` and any `node-type`:

    // render any node (article, page, etc) in `rss` view-mode
    `render_node_rss.ctp`

    // render any node (article, page, etc) in `full` view-mode
    `render_node_full.ctp`


NOTE: To avoid collisions between `view-mode` names and `node-type` names, you
should alway use unique and descriptive names as possible when defining new
content types. By default, Node plugin defines the following view-modes:
`default`, `teaser`, `search-result`, `rss`, `full`.


#### Default

> render_node

This is the global render, if none of the above is found we try to use this last.

[def_frontend]: http://en.wikipedia.org/wiki/Front_and_back_ends
[def_backend]: http://en.wikipedia.org/wiki/Front_and_back_ends
[plugins]: 03_Plugins.md
