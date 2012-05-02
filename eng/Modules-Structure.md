### Folders & Files
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

***
**InstallComponent.php**

Each module may define custom logic to be executed before/after module has been installed/uninstalled, or before/after module has been enabled/disabled. All this is performed by using callbacks methods in your InstallComponent:

    beforeInstall($Installer);   // Return a non-true result halt the install operation.
    afterInstall($Installer);    // Called after each successful install operation.

    beforeUninstall($Installer); // Return a non-true result halt the uninstall operation.
    afterUninstall($Installer);  // Called after each successful uninstall operation.

    beforeEnable($Installer);    // Return a non-true result halt the enable operation.
    afterEnable($Installer);     // Called after each successful enable operation.

    beforeDisable($Installer);   // Return a non-true result halt the disable operation.
    afterDisable($Installer);    // Called after each successful disable operation.

***
**MyHotModule.yaml**
Contains information about your module, such as name, description, etc.

    name: MyHotModule
    description: Yeah, a hot module!
    category: Hot Stuff
    version: 1.0
    core: 1.x
    dependencies:
        SoftModule (1.x)
        ColdModule (1.0)

#### Explanation

* **name (required)** human readble name of your module. For example, "Hot Module"
* **description (required)** a brief description about your module
* **category (required)** used to group modules together as fieldsets on the module administration display.
* **version (required)** version of your module. e.g.: 1.0, 1.3.1.
* **core (required)** indicates the minimun QuickApps version required to install your module, for example: 
    * 1.x means any branch of version 1.0, 
    * 1.0 means that your module can only be installed on QuickApps v1.0 
* **dependencies (optional)** indicates that your module depends on other modules to be installed, if any of the listed modules is not installed then your module can not be installed. In the example above:
    * MyHotModule requires SoftModule 1.x (any branch of 1.0), but also module ColdModule 1.0 (exactly 1.0) is required to be installed. You can also specify complex depencencies such as: `ModuleBeta (>=7.x-4.5-beta5, 3.x)` 

***
For more information about [Permissions.yaml](https://github.com/QuickAppsCMS/QuickApps-CMS/wiki/Permissions.yaml)