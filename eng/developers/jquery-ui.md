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
	$this->jQuery->definePreset('preset_name', array('ui.core', 'ui.widget'));

	// You can pass libraries as an array list or as arguments:
	$this->jQuery->definePreset('preset_name', 'ui.core', 'ui.widget');

The first argument, `preset_name`, is the underscored name of your preset that you will use to attach it when using it:

    $this->jQuery->attach('preset_name');
	
### Attaching UI themes