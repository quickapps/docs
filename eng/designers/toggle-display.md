With the toggle display options, we can select the elements we wish to display on our site, these include:

* Logo
* Site name
* Site slogan
* Shortcut icon

#### Logo Image Settings
Allows to use a custom image as logo by specifying its URL. Empty value means use QuickApps logo as default.

#### Shortcut Icon Settings
The shortcut icon is the favicon displayed in the address bar of our browser next to the web address. By default, this is a small version of QuickApps logo. This option allows you to use a cutom favicon by specifying its URL.

***

### How do I show/hide those elements ?

In your theme layout .ctp file use:

    Configure::read('Theme.settings.OPTION');

Where OPTION may be one of:

* site_logo (bool): Display site logo?
* site_name (bool): Display site name?
* site_slogan (bool): Display site slogan?
* site_favicon (bool): Display site favicon ?
* site_logo_url (string): URL to logo
* site_favicon_url (string): URL to favicon

#### Example
    <!-- Show logo image if has been enabled -->
    <?php if (Configure::read('Theme.settings.site_logo')): ?>
        <img src="<?php echo Configure::read('Theme.settings.site_logo_url'); ?>" />
    <?php endif; ?>