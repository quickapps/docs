Display Modes
=============

Display Modes are an extraordinarily powerful tool for streamlining the theming and development of your site and ensuring consistency of output
for your content. Display Modes give the themer the ability to show the same content in many different contexts and with the right display for
each situation.

---

To understand view modes, we need to first understand Entities.
The idea of an Entity in QuickApps CMS is quite abstract. Basically each Entity represents a single 'thing'.
With a user, an Entity represents a single user on the site, and holds information such as their username, their (encrypted) password, and
their email address. Site builders can also attach fields to a user, collecting more information.

For example, if they want to collect a user's birthday, they will add a 'date' field for the user's birthday.  
Each field in QuickApps CMS provides one or more ways of collecting data from users.

---

Each Entity may define it own set of display modes, by default QuickApps CMS v1.1 includes the following display modes:

Entity		Display Modes
Node		Default, Full, List, RSS, Print
User		Default, User Profile

For example, when a Node (content item) is being rendered as part of a search result (List) the `List` display mode is activated.


### List Available Display Modes

Internally, QuickApps CMS organizes Display Modes grouped by Entities. Each Entity may define its own Display Modes.  
To get a list of all regisreted display-modes for a given Entity (group):

    QuickApps::displayModes('Node');
    // output:
    array(
        'default' => array('label' => 'Default'),
        'full' => array('label' => 'Full'),
        'list' => array('label' => 'List'),
        'rss' => array('label' => 'RSS'),
        'print' => array('label' => 'Print')
    );	

The example above will return an associative array holding information of each display-mode under the `Node` group.  
As you may see, Display Modes information is represented as follow:

    'default' => array('label' => 'Default', 'locked' => true|false, 'other_info' => ...)
	
* **default**: Display Mode machine-name. Must be under_scored
* **label**: Display Mode human-readable name. _(required)_
* **locked**: Set to TRUE for prevent deletion. _(optional)_

---

You may also need get information for a specific Display Mode only in a given group, in that case you should use a Dot-Syntax:

    QuickApps::displayModes('Node.default');
    // output:
    array('label' => 'Default', 'other_info' => , ...)


### Registering Display Modes

You can both register new Display Modes or overwrite existing ones:

    QuickApps::registerDisplayMode('Entity.machine_name', 'Label Human-Readable', array('other_info' => ...));


Examples:

    // register new display-mode under `Node`
    QuickApps::registerDisplayMode('Node.new_mode', 'New Mode');

    // overwriting the `Full` display-mode (label renaming)
    QuickApps::registerDisplayMode('Node.full', 'New Label');

    // unlock of `Node.default`
    QuickApps::registerDisplayMode('Node.default', null, array('locked' => false));


##### NOTES:

* When registering a new display-mode the arguments: `label` is REQUIRED and `options` is OPTIONAL
* When overwriting an existing display-mode the arguments: `label` is OPTIONAL and `options` is REQUIRED.
* Display Modes marked as ´locked´ (TRUE) can not be removed.


### Remove Display Modes

You can unregister any display-mode (if it's not locked) as follow:

    QuickApps::removeDisplayMode('Node.full');