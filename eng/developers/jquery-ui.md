jQuery UI
========

jQuery UI (http://ui.jquery.com/) is a set of cool widgets and effects that developers add swooshy, swishy effects to their code.

QuickApps CMS provides both a Helper and a Component, which are more-or-less an utility class that help developers to load and attach effects/themes
to their applications.  
_It doesn't do anything on its own_, but you can use it in combination with CakePHP's Jquery helper.


API
---

Both Helper and Component are automatically loaded by QuickApps CMS.  
Developers who wish to use them need only to access the corresponding object instance:

    // in your controller or view
    $this->jQueryUI

---

IMPORTANT:
jQueryUI attachments process work by adding to the js/stylesheets stack each effect or theme.  
This means that attaching an effect or theme after the stack has been rendered in your theme's header **WILL NOT WORK**.

### Example (will not work):

    // YourThemeName/Layout/default.ctp
	<header>
		<?php echo $this->Layout->stylesheets(); ?>
		<?php echo $this->Layout->javascripts(); ?>
	</header>
	<body>
		<?php $this->jQueryUI->attach('effects.blind'); ?>
	</body>

The example above **will not work** because the effect is being attached after the javascript stack has been rendered in layout's header.

---

	
### Attaching individual libraries

For attaching a jQueryUI library you must simply do as follow:

    $this->jQueryUI->attach('effects.blind');
	
You can load multiple libraries at once by passing them as arguments:

    $this->jQueryUI->attach('effects.blind', 'effects.explode', 'effects.pulsate');
	
---

To load all the libraries at once you can use both the special word `all` or simply use the attach() method with no arguments:

    $this->jQueryUI->attach('all');
	// or
	$this->jQueryUI->attach();

---

See the contents of the _QuickApps/Plugin/System/webroot/js/ui/_ sub-directory for a list of available files that may be included, and see
http://ui.jquery.com/docs for details on how to use them.  
The required ui.core file is automatically included, as is effects.core if you include any effects files.


### Attaching presets

The jQueryUI class includes by default some useful UI presets. A preset is basically a collection of individual libraries that can be
attached at the same time. Included presets are:

*Interactions*:

- draggable: ui.core, ui.widget, ui.mouse
- droppable: ui.core, ui.widget, ui.mouse, ui.raggable
- resizable: ui.core, ui.widget, ui.mouse
- selectable: ui.core, ui.widget, ui.mouse
- sortable: ui.core, ui.widget, ui.mouse


*Widgets*:

- accordion: ui.core, ui.widget, effects.core
- autocomplete: ui.core, ui.widget, ui.position
- button: ui.core, ui.widget
- datepicker: ui.core
- dialog: ui.core, ui.position, ui.widget, ui.mouse, ui.draggable, ui.resizable
- progressbar: ui.core, ui.widget
- slider: ui.core, ui.widget, ui.mouse
- tabs: ui.core, ui.widget


Loading presets works similar as loading individual libraries:

    $this->jQueryUI->attach('draggable');
    $this->jQueryUI->attach('droppable');
	
NOTE: Loading multiple presets at once is *not allowed*


### Defining presets

You can both define new presets or overwrite existing ones. To do this should use the `definePreset` method as follow:

    // Passing libraries as an array list
	$this->jQueryUI->definePreset('preset_name', array('ui.core', 'ui.widget'));

	// You can pass libraries as an array list or as arguments:
	$this->jQueryUI->definePreset('preset_name', 'ui.core', 'ui.widget');

The first argument, `preset_name`, is the underscored name of your preset that you will use to attach it when using it:

    $this->jQueryUI->attach('preset_name');
	

### Attaching UI themes

Similar as libraries, you can attach themes to your applications. It works in a similar: it loads in the css stack all the CSS styles for the specified
jQueryUI theme.

jQuery themes can be placed in your site webroot directory, or module's webroot directory. For instance, the System module owns the
´ui-lightness´ jQueryUI theme, and it can be found at `QuickApps/Plugin/System/webroot/css/ui/ui-lightness`.  
Any module is allowed to hold jQuery themes, to do this you must simply place them in `webroot/css/ui` directory of your module.

Example, lets suppose you have created a module named `MyModule`, and you want it to hold the ´ui-darkness´ jQueryUI theme.  
Then the following directory must be added to your module's webroot:

    /MyModule/webroot/css/ui/ui-darkness/

In a similar way, you can add jQueryUI themes to your site's webroot by placing them in the corresponding directory. In the previous
example, you may place the `ui-darkness` theme at:

    ROOT/webroot/css/ui/ui-darkness


#### Attaching themes owned by modules

For attaching a jQueryUI theme owned by some module you must simply do as follow:

    $this->jQueryUI->theme('ModuleName.ui_theme_name');

As you may see, you should use a Dot-Syntax to specify the theme to load from the specified module. In the example above, it will try
to load the `ui_theme_name` owned by the `ModuleName` module. And theme's files, should be stored in:

    /ModuleName/webroot/css/ui/ui_theme_name/

This allows modules to implement same named UI themes (Two or more modules may own the `ui_theme_name` UI theme).


#### Attaching themes owned by site's webroot

To attach a UI theme that is placed at site's webroot, you must simply do as follow:

    $this->jQueryUI->theme('ui_theme_name');

The example above will load the `ui_theme_name` UI theme, **BUT** this time theme's files must be stored in:

    /ROOT/webroot/css/ui/

#### Theme auto-detect

    $this->jQueryUI->theme();

When invoking the `theme()` method with no parameters (as above), it will try to:

1. Use global parameter `jQueryUI.default_theme`.
2. Use `System.ui-lightness` otherwise.


#### Default theme

You can define the global parameter `jQueryUI.default_theme` in your site's bootstrap.php to indicate the default theme to use.

    Configure::write('jQueryUI.default_theme', 'flick');

In the example above. The `flick` theme will be used by default if no arguments is passed:

    // will load `jQueryUI.default_theme`, as it was defined as default before
    $this->jQueryUI->theme();