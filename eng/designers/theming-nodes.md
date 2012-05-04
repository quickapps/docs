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