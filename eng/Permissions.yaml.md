By default QuickApps CMS generate permissions tree (/admin/user/permissions/) by parsing each Module's controller folder, each leaf of this tree is named as the name of the module, controller or method that represent.

For example, if you have a module named `HotModule`, which has a controller class named `HotController` which has two methods on it named `do_hot_stuff` & `do_cold_stuff`. The following tree will be created by default:

* HotModule
    * Hot
        * do_hot_stuff
        * do_cold_stuff

Well, this structure does not say much... What does `do_hot_stuff` actually do ?.
Whould be nice to write a brief description about what this method do, or even better, change its name for a more descriptive one.

By using **Permissions.yaml** you can overwrite names and create descriptions for both controllers and methods.

####YAML structure
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

If you set `hidden:true` the leaf (controller or method) will not display on the tree.

## Creating permissions presets
Some times overwriting controller's name and method names is not enough. Some times the permissions tree may become difficult to understand when your module has to many controllers, or to many methods on its controllers. To solve this QuickApps allows you to create permissions presets. A preset is a collection of methods from one or many controllers.

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