Rendering Content
#################

A QuickAppsCMS site is composed by multiple contents, these contents are handled by
the ``Content`` plugin, in combination with the ``Field`` plugin which provides the
ability to define new "Content Types".

All content on a QuickAppsCMS website is stored and treated as a "Content" Entity
object (Content\Model\Entity\Content). A Content object represents any piece of
individual content, such as a page, an article, forum topic, etc.

Each content (content object) has an unique ID called "slug" (a.k.a "machine-name"),
and it also stores a content-type ID. These IDs are used to generate an URL to that
piece of content, these URLs follows the pattern:

::

    http://www.example.com/content-type-slug/content-slug.html

For instance an Article (content-type-slug ID: "article") with the title "Hello
World" (content-slug: "hello-world") should be accessible trough the following URL:

::

    http://www.example.com/article/hello-world.html


Custom Content Extension
========================

In QuickAppsCMS every content has its own URL that follows the pattern described
before which ends by default with ".html". QuickAppsCMS allows you to change the
default .html extension used when generating such URLs. To do this you must simply
define the CONTENT_EXTENSION PHP constant in your site's bootstrap
(/config/bootstrap.php). For example, changing extension from ".html" to "/":


.. code:: php

    define('CONTENT_EXTENSION', '/');

Now every content's URL will look as: /article/my-first-article/


Specialized Renders
===================

You can define ``specialized-renders`` when each content is being rendered according
to your needs as described below. You must simply create these template elements
within the ``/src/Template/Element`` directory of your :doc:`theme <themes>`.

Render content based on content's slug
--------------------------------------

::

    [other-render]_[content-slug].ctp


Where `[other-render]` is any of the ones described below, for instance when
rendering an article which slug is `hello-world` the following templates will be
used if exists (in this exact order of priority):

- ``render_content_[content-type]_[view-mode]_[hello-world].ctp``
- ``render_content_[content-type]_[hello-world].ctp``
- ``render_content_[view-mode]_[hello-world].ctp``
- ``render_content_[hello-world].ctp``

If none of these exists, then it will try to use one of renders described below.


Render content based on content-type and view-mode
--------------------------------------------------

Renders the given content based on ``content-type`` and ``view-mode``. Follows the
pattern:

::

    render_content_[content-type]_[view-mode].ctp

Examples:

::

    // render for "article" contents in "full" view-mode
    render_content_article_full.ctp

    // render for "article" contents in "search-result" view-mode
    render_content_article_search-result.ctp

    // render for "basic-page" contents in "search-result" view-mode
    render_content_basic-page_search-result.ctp


Render content based on content-type
------------------------------------

Similar as before, but based on ``content-type`` and any view-mode. Follows the
pattern:

::

    render_content_[content-type].ctp

Examples:

::

    // render for "article" contents
    render_content_article.ctp

    // render for "basic-page" contents
    render_content_basic-page.ctp


Render content based view-mode
------------------------------

Similar as before, but based ``view-mode`` and any ``content-type``. Follows the
pattern:

::

    render_content_[view-mode].ctp

Examples:

::

    // render any content (article, page, etc) in "rss" view-mode
    render_content_rss.ctp

    // render any content (article, page, etc) in "full" view-mode
    render_content_full.ctp


.. note::

    To avoid collisions between ``view-mode`` names and ``content-type`` names, you
    should alway use unique and descriptive names as possible when defining new
    content types. By default, Content plugin defines the following view-modes:
    ``default``, ``teaser``, ``search-result``, ``rss``, ``full``.


Default
-------

This is the global render, if none of the above is found QuickAppsCMS will use this
last:

::

    render_content.ctp

.. meta::
    :title lang=en: Rendering Contents
    :keywords lang=en: content,fetch,block,view mode,contents,specialized render,view mode
