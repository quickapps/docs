Rendering Nodes
###############

A QuickAppsCMS site is composed by multiple contents, these contents are handled by
the ``Node`` plugin, in combination with the ``Field`` plugin which provides the
ability to define new "Content Types".

All content on a QuickAppsCMS website is stored and treated as a "Node" Entity
object (Node\Model\Entity\Node). A Node represents any piece of individual content,
such as a page, an article, forum topic, etc.

Each content (node object) has an unique ID called "slug" (a.k.a "machine-name"), it
also stores a node-type ID. These IDs are used to generate an URL to that piece
of content, these URLs follows the pattern:

::

    http://www.example.com/node-type/node-slug.html

For instance an Article (node-type ID: "article") with the title "Hello World"
(node-slug: "hello-world") should be accessible trough the following URL:

::

    http://www.example.com/article/hello-world.html


Specialized Renders
===================

You can define ``specialized-renders`` when each content is being rendered (nodes)
according to your needs as described below. You must simply create these template
elements within the ``/src/Template/Element`` directory of your :doc:`theme
<themes>`.


Render node based on node-type and view-mode
--------------------------------------------

Renders the given node based on ``node-type`` and ``view-mode``. Follows the
pattern:

::

    render_node_[node-type]_[view-mode].ctp

Examples:

::

    // render for "article" nodes in "full" view-mode
    render_node_article_full.ctp

    // render for "article" nodes in "search-result" view-mode
    render_node_article_search-result.ctp

    // render for "basic-page" nodes in "search-result" view-mode
    render_node_basic-page_search-result.ctp


Render node based on node-type
------------------------------

Similar as before, but based on ``node-type`` and any view-mode. Follows the
pattern:

::

    render_node_[node-type].ctp

Examples:

::

    // render for "article" nodes
    render_node_article.ctp

    // render for "basic-page" nodes
    render_node_basic-page.ctp


Render node based view-mode
----------------------------

Similar as before, but based ``view-mode`` and any ``node-type``. Follows the
pattern:

::

    render_node_[view-mode].ctp

Examples:

::

    // render any node (article, page, etc) in "rss" view-mode
    render_node_rss.ctp

    // render any node (article, page, etc) in "full" view-mode
    render_node_full.ctp


NOTE
    To avoid collisions between ``view-mode`` names and ``node-type`` names, you
    should alway use unique and descriptive names as possible when defining new
    content types. By default, Node plugin defines the following view-modes:
    ``default``, ``teaser``, ``search-result``, ``rss``, ``full``.


Default
-------

This is the global render, if none of the above is found QuickAppsCMS will use this
last:

::

    render_node.ctp

.. meta::
    :title lang=en: Rendering Contents
    :keywords lang=en: content,fetch,block,view mode,nodes,specialized render,view mode
