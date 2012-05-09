### Theme name
Theme names must always be in CamelCase format. Also there are two type of themes, `Backend` and `Frontend` which are managed independently by QuickApps.

***

### Folders & Files
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

####IMPORTANT
All **themes modules** MUST prefix its name by `Theme` word, In the example above: `ThemeMyThemeName` is the associated module to theme `MyThemeName`

***

### MyThemeName.yaml

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

***

### Elements
QuickApps incorporates a number of `default elements` responsible for various rendering tasks, such as Menu, Blocks, etc.
Themes may overwrite this elements and modify the way they are rendered. To overwrite any of this elements simply create the element under `View/Elements` folder of your theme.

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