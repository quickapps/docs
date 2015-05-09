Themes
######

A theme is an unified set of design elements and color schemes that you can apply to
your Web pages to give them a professional look. Using a theme is a quick and easy
way to make sure your Web pages are visually consistent and appealing.

QuickAppsCMS includes by default a `Frontend
<http://en.wikipedia.org/wiki/Front_and_back_ends>`__ theme named ``Frontend`` and a
`Backend <http://en.wikipedia.org/wiki/Front_and_back_ends>`__ theme named
``Backend``.

As you may notice, there are two type of themes, ``Backend`` and ``Frontend``, which
are managed independently by QuickAppsCMS. This document describes some of the
basics of theme creation and structure.


.. note::

    Recommended reading: http://book.cakephp.org/2.0/en/views.html

Theme Anatomy
=============

    Themes in CakePHP are simply :doc:`plugins </developers/plugins>` that focus on
    providing view files. In addition to template files, they can also provide
    helpers and cells if your theming requires that. When using cells and helpers
    from your theme, you will need to continue using the plugin-syntax.

    -- CakePHP’s book

That means that your theme folder structure is the same :doc:`used by plugins
</developers/plugins>`. However in QuickAppsCMS there are only two main difference
between plugins and themes:

1. Themes must define some particular keys in the "composer.json" schema
2. Themes must be named using the ``Theme`` suffix. e.g. "BlueTheme"

The "composer.json" File
------------------------

Themes *must* define a "composer.json" file containing all the information about the
theme itself, such as name, available regions, author, etc.

In order to distinguish between plugins and themes, themes must suffix their names
with the ``Theme`` word, so for example if you have a "Blog" plugin, changing its
name to "BlogTheme" will be automatically considered as a Theme by QuickAppsCMS.

Theme’s names is inflected from the ``name`` key from composer.json schema. For
instance, for the package name ``my-vendor-name/blue-theme`` the inflected name will
be ``BlueTheme``.

Also they must define the ``extra.regions`` key, a list of all regions your theme
implements. Regions are defined as an associative array ``machine-name`` => ``human
name``, machine-name is used internally when referring to a region, and human name
is the name users will see in the administration panel when assigning blocks to a
particular region.

And optionally, they may define the ``extra.admin`` key indicating whether your
theme is a Backend theme or not. Defaults to "false" if not provided.

A full working example:

.. code:: json

    {
        "name": "quickapps-themes/basic-theme",
        "description": "QuickApps CMS theme skeleton.",
        "type": "cakephp-plugin",
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


Layouts
=======

Most of the following is an extract from CakePHP’s book.

    A layout contains presentation code that wraps around a view. Anything you want
    to see in all of your views should be placed in a layout.

    -- CakePHP’s book

Default layout is located at ``/src/Template/Layout/default.ctp`` of your themes. If
you want to change the overall look of your application, then this is the right
place to start, because controller-rendered view code is placed inside of the
default layout when the page is rendered.

Other layout files should be placed in ``/src/Template/Layout``. When you create a
layout, you need to tell QuickAppsCMS where to place the output of your views. To do
so, make sure your layout includes a place for **$this->fetch('content')** Here’s an
example of what a default layout might look like:

.. code:: html

    <!-- /MyTheme/src/Template/Layout/default.ctp
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <title><?php echo h($title) ?></title>
            <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

            <!-- Include external files and scripts
            here (See HTML helper for more info.) -->

            <?php echo $this->fetch('meta'); ?>
            <?php echo $this->fetch('css'); ?>
            <?php echo $this->fetch('script'); ?>
        </head>

        <body>
            <!-- If you'd like some sort of menu to
            show up on all of your views, include it
            here (See Menu helper for more details.) -->
            <div id="header">
                <div id="menu">...</div>
            </div>

            <!-- Here’s where I want my views to be displayed -->
            <?php echo $this->fetch('content') ?>

            <!-- Add a footer to each displayed page -->
            <div id="footer">...</div>
        </body>
    </html>

The ``script``, ``css`` and ``meta`` blocks contain any content defined in the views
using the built-in HTML helper. Useful for including JavaScript and CSS files from
views. The ``content`` block contains the contents of the rendered view.


Predefined Layouts
------------------

Although ``default.ctp`` layout should be enough for most cases, QuickAppsCMS may
use different layouts depending on the situation, for example when rendering the
user’s login form the ``login.ctp`` layout **will be used if exists**, here is a
list of layouts your theme might define:

-  **default.ctp**: This is the default layout, if none of the listed below exists
   this layout will be used by default.

-  **login.ctp**: Used when rendering user’s login screen.

-  **ajax.ctp**: Used when rendering AJAX responses.

-  **error.ctp**: When an error is reached; 404, 500, etc.

Layout’s Header
---------------

Layout’s header is where you should place all links to your CSS and JS files, among
other things such as meta-descriptions and so on. To make your life easier,
QuickAppsCMS provides a basic header setup which you can use within your theme’s
layouts:

.. code:: php

    <html>
    <head>
        <?php echo $this->Html->head(); ?>
    </head>

The ``head()`` methods accepts a series of options which you may tweak depending on
your needs. For more information please check ``QuickApps\View\View::head()``


Regions
=======

.. image:: ../../themes/quickapps/static/layout-regions.png
  :alt: Color picker widget example
  :align: left
  :width: 480

Regions are areas of your layout aimed to contain blocks, regions may contain an
unlimited number of blocks (although it can be limited). Theme authors can define
and implement any number of regions for content to be rendered into.

For rendering region’s blocks in your layout you must use the ``View::region()``
method as follow:

.. code:: php

    <?php echo $this->region('right-sidebar'); ?>

You can do nifty things such as combine two or more regions, limit the number of
blocks a region can hold, etc. For example, we'll merge ``left-sidebar`` and
``right-sidebar`` regions together; the resulting region limits the number of blocks
it can holds to three (3):

.. code:: php

    <?php
        echo $this->region('left-sidebar')
            ->merge($this->region('right-sidebar'))
            ->blockLimit(3);

As you may have noticed, we always use region’s machine-name when referring to a
particular region; ``left-sidebar`` (human name: Left Sidebar).

.. note::

    For more information please check ``QuickApps\View\View::region()``
    documentation.


Theme Settings
==============

Themes are allowed to define a series of customizable parameters, this parameters
can be tweaked on the administration section by users with proper permissions.

For example, a "BlueTheme" theme could allow users to change site’s background color
by providing a series of form inputs where users may pick the desired color.

Themes can provide these form inputs by placing them into
``/src/Tempalte/Element/settings.ctp``, here is where you should render all form
elements that users will be able to teak. For our "BlueTheme" example, this file
could look as follow:

.. code:: php

    // /MyTheme/src/Template/Element/settings.ctp
    echo $this->Form->input('logo', [
        'type' => 'checkbox',
        'label' => 'Display Logo',
    ]);

    echo $this->Form->input('slogan', [
        'type' => 'checkbox',
        'label' => 'Display Slogan',
    ]);

Color and Font inputs
---------------------

In addition to standard form inputs such as text boxes, check boxes, etc;
QuickAppsCMS provides two handy form inputs as described below.

Color Picker
~~~~~~~~~~~~

.. image:: ../../themes/quickapps/static/color-picker.png
  :alt: Color picker widget example
  :align: center

Provides a simple HEX color picker. Useful when you want allow users to change some
colors of your theme (background color, font color, etc). To provide this form input
you should do as follow:

.. code:: php

    <?php
        echo $this->Form->input('background_color', [
            'type' => 'color_picker',
            'label' => 'Background Color',
        ]);


Font Panel
~~~~~~~~~~

.. image:: ../../themes/quickapps/static/font-panel.png
  :alt: Font panel widget example
  :align: center

Provides a simple panel for configuring CSS font styles (font family, size, etc). To
provide this form input you should do as follow:

.. code:: php

    <?php
        echo $this->Form->input('body_font', [
            'type' => 'font_panel',
            'label' => 'Font Style',
        ]);


Reading theme settings
----------------------

Once you have provided certain configurable values, you may need to read those
values in order to change your theme’s aspect, in our "BlueTheme" example we want to
know which the "background color" should be used when rendering each page. To read
these values you should use the ``theme()`` function as follow:

.. code:: php

    <style>
        body {
           background-color: #<?php echo theme()->settings['background_color']; ?>;
       }
    </style>

.. note::

    In some cases you will encounter that no values has been set for a setting key,
    for example if user has not indicated any value for your settings yet. This can
    be solved using the "Default Setting Values" feature described the
    :doc:`plugins </developers/plugins>` documentation.

.. meta::
    :title lang=en: Themes
    :keywords lang=en: block,blocks,regions,layout,theme,header,region
