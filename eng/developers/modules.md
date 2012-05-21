Module names
============

Module have two names:

* **machine name**: Used internally by QuickApps CMS, must always be in CamelCase format, e.g.: `ModuleName`.  
	Basically, it's the name of the folder of the module. For example, the machine name of the core module `QuickApps` is actually `System`,
	and its folder can be found in _QuickApps/Plugin/**System**_
* **human name**: Human readable name, e.g.: `My Module Name`


Banned Names
---------------

Modules cannot be named as:

* `Default`
* Three chars length names. e.g.: `One`, `Two`, `Eng`, etc.
* Prefixed by the `Theme` word. e.g.: `ThemeMaker`, `ThemesManager`, etc
* Containing any non-alphanumeric chars. e.g.: `Invalid?Name`, `No(Alphanumeric¿%`


Structure
=========

Basic structure of modules:

    |- MyHotModule/
        |- Config/
        :    |- bootstrap.php
        :    |- routes.php
        |- Controller/
        :    |- Component/
        :    :    |- InstallComponent.php
        :    :    |- MyHotModuleHookComponent.php
        |- Lib/
        |- Locale/
        |- Model/
        :    |- Behavior/
        :    :   |- MyHotModuleHookBehavior.php
        |- View/
        :    |- Helper/
        :    :   |- MyHotModuleHookHelper.php
        |- webroot/
        |- MyHotModule.yaml
        |- Permissions.yaml


Install Component
=================

Each module may define custom logic to be executed before/after module has been installed/uninstalled, or before/after module has been enabled/disabled.
All this is performed by using callbacks methods in the `Install Component`:

    beforeInstall();   // Return a non-true result halt the install operation.
    afterInstall();    // Called after each successful install operation.

    beforeUninstall(); // Return a non-true result halt the uninstall operation.
    afterUninstall();  // Called after each successful uninstall operation.

    beforeEnable();    // Return a non-true result halt the enable operation.
    afterEnable();     // Called after each successful enable operation.

    beforeDisable();   // Return a non-true result halt the disable operation.
    afterDisable();    // Called after each successful disable operation.


You can access the utility class instance **Installer** Component from any part of your **Install** Component by using `$this->Installer`.  
The Installer Component provides a set of useful methods such as SQL query, etc. 


##### For example

    public function afterInstall() {
        // Some other logic here ...

		$this->Installer->sql("
            CREATE TABLE IF NOT EXISTS `#__table_name` 
            (`id` int(11) NOT NULL AUTO_INCREMENT) 
            ENGINE=MyISAM 
            DEFAULT 
            CHARSET=utf8 
            COLLATE=utf8_unicode_ci 
            AUTO_INCREMENT=1;
        ");
	}

The code below will register a new table schema in DB after module is successfully installed.  
(The `#__` word is automatically replaced by DB prefix. Check the API for more information)

Configuration YAML
==================

Each module has a .yaml file which contains information about it such as name, description, etc.  
The name of this file must be the machine name of the module.

Example, for a module named as `MyHotModule` the `MyHotModule.yaml` should be created, and its content may look as follow:

    name: MyHotModule
    description: Yeah, a hot module!
    category: Hot Stuff
    version: 1.0
    core: 1.x
    dependencies:
        SoftModule (1.x)
        ColdModule (1.0)


Explanation
-----------

* **name (required)** human readable name of your module. For example, "Hot Module"
* **description (required)** a brief description about your module
* **category (required)** used to group modules together as fieldsets on the module administration display.
* **version (required)** version of your module. e.g.: 1.0, 1.3.1.
* **core (required)** indicates the minimum QuickApps version required to install your module, for example: 
    * 1.x means any branch of version 1.0, 
    * 1.0 means that your module can only be installed on QuickApps v1.0 
* **dependencies (optional)** indicates that your module depends on other modules to be installed, if any of the listed modules is not installed then your module can not be installed. In the example above:
    * MyHotModule requires SoftModule 1.x (any branch of 1.0), but also module ColdModule 1.0 (exactly 1.0) is required to be installed. You can also specify complex depencencies such as: `ModuleBeta (>=7.x-4.5-beta5, 3.x)` 


Permissions
===========

By default QuickApps CMS generates a permissions tree (/admin/user/permissions/) by parsing each Module's controller folder. Each leaf of this 
tree is named as the name of the module, controller or method that it represents.

For example, if you have a module named `HotModule`, which has a controller class named `HotController` which has two methods on it named `do_hot_stuff` & `do_cold_stuff`.
The following tree will be created by default:

* HotModule
    * Hot
        * do_hot_stuff
        * do_cold_stuff

Well, this structure does not say much. What does `do_hot_stuff` actually do ?  
Whould be nice to write a brief description about what this method does, or even better, change its name for a more descriptive one.

By using the **Permissions.yaml** file you can overwrite names and create descriptions for both controllers and methods.


YAML structure
--------------

    Controller:
        MyControllerName:
            name: "New name for `MyControllerName`"
            description: "Brief description for `MyControllerName`"
            actions:
                my_controller_method_1:
                    name: "new name for `my_controller_method_1`"
                    description: "Brief description for `my_controller_method_1`"
                    hidden: false
                my_controller_method_2:
                    ......
        OtherController:
            hidden:true
        ....

If you set `hidden:true` the leaf (controller or method) will not be displayed in the tree.


Creating permissions presets
----------------------------

Sometimes overwriting the controllers name and method names is not enough. Sometimes the permissions tree may become difficult to understand when 
your module has too many controllers, or too many methods on its controllers.  
To solve this, QuickApps allows you to create permissions presets. A preset is a collection of methods from one or many controllers.

    Preset:
        administer_blocks:
            name: "Administer blocks"
            description: "Grant full access to administer blocks"
            acos:
                Block.admin_index
                Manage.admin_index
                Manage.admin_move
                Manage.admin_clone
                Manage.admin_edit
                Manage.admin_add
                Manage.admin_delete



The Un/Installation Process
===========================

The following describes some of the tasks automatically performed by QuickApps CMS during the un/installation process, as well as some tasks
that modules should consider during these processes.


During Installation
-------------------

#### Tasks automatically performed by QuickApps CMS

* Checks module folder/files consistency.
* Checks version compatibilities.
* Checks dependencies.
* Generate module ACO tree.
* Register module on `modules` table.
* Regenerate related caches.

### Common tasks which modules may do

* Create new tables on Database.
* Add new blocks.
* Add new menus.
* Add links to an existing menu.
* Add new variables to the ´variables´ table

During Uninstallation
---------------------

#### Tasks automatically performed by QuickApps CMS

* Remove all related [ACOs and AROs](http://book.cakephp.org/2.0/en/core-libraries/components/access-control-lists.html#understanding-how-acl-works)
* Remove all menus created by the module during installation.
* Remove all Content Types defined by the module during installation.
* Remove all Blocks defined by the module during installation.
* Unregister module from the `modules` table.
* Regenerate related caches.


### Tasks to consider by module

The following tasks should be performed by the module during the uninstallation process.  
The best place to perform these tasks is on `afterUninstall` or `beforeUninstall` [callbacks](#installcomponentphp).

* Remove all related Database tables.
* Remove all defined variables from the `variables` table.

In general, your module should remove anything that is not automatically removed by QuickApps CMS.



For more information about:
===========================

* [Hooks](hooks.md)
* [Hooktags](hooktags.md)