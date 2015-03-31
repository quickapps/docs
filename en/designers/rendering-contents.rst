Rendering Contents
##################


Rendering The Main Content
==========================

Simply by using the code below in your theme layout you can render out the main
content of each page. For example, in your layout’s body:

.. code:: php
    <body>
        <?php echo $this->fetch('content'); ?>
    </body>



Specialized Renders
===================

You can define ``specialized-renders`` when rendering each content (nodes)
according to your needs as described below. You must simply create these
template elements within the ``/src/Template/Element`` directory of your theme.

Render node per node-type and view-mode
---------------------------------------

    render\_node\_[node-type]\_[view-mode]

Renders the given node per ``node-type`` + ``view-mode`` combination:

::

    // render for "article" nodes in "full" view-mode
    render_node_article_full.ctp

    // render for "article" nodes in "search-result" view-mode
    render_node_article_search-result.ctp

    // render for "basic-page" nodes in "search-result" view-mode
    render_node_basic-page_search-result.ctp

Render node per node-type
-------------------------

    render\_node\_[node-type]

Similar as before, but just per ``node-type`` and any view-mode:

::

    // render for "article" nodes
    render_node_article.ctp

    // render for "basic-page" nodes
    render_node_basic-page.ctp

Render node per view-mode
--------------------------

    render\_node\_[view-mode]

Similar as before, but just per ``view-mode`` and any ``node-type``:

::

    // render any node (article, page, etc) in "rss" view-mode
    render_node_rss.ctp

    // render any node (article, page, etc) in "full" view-mode
    render_node_full.ctp

NOTE: To avoid collisions between ``view-mode`` names and ``node-type``
names, you should alway use unique and descriptive names as possible
when defining new content types. By default, Node plugin defines the
following view-modes: ``default``, ``teaser``, ``search-result``,
``rss``, ``full``.

Default
-------

    render\_node

This is the global render, if none of the above is found we try to use
this last.

.. meta::
    :title lang=en: Rendering Contents
    :keywords lang=en: content,fetch,block,view mode,nodes,specialized render,view mode
