View Modes
##########

How a content is displayed depends on the context in which it is
rendered, such as the difference between ``full`` nodes and ``teasers``.

In a standard QuickAppsCMS installation, the following view modes are
available:

-  **Default** (default): Default is used as a generic view mode if no
   other view mode has been specified.
-  **Teaser** (teaser): Teaser is a really short format that is
   typically used in main the main page, such as "last news", etc.
-  **Search Result** (search-result): Search Result is a short format
   that is typically used in lists of multiple content items such as
   search results.
-  **RSS** (rss): Similar to "Search Result" but intended to be used
   when rendering content as part of a RSS feed list.
-  **Full** (full): Full content is typically used when the content is
   displayed on its own page.

**NOTE:** Between parenthesis are the "machine-names" of each view mode,
these names are used internally when referring to a specific view mode.

These context are automatically set by QuickAppsCMS during the rendering
process of every content, however other :doc:`plugins </developers/plugins>`
may change this on the fly and define their own.