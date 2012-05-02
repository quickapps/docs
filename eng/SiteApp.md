Sites are allowed implement custom MVC logic under the `SiteApp` directory.
This allows you to use (in certain way) QuickApps CMS as an extension of your CakePHP project.
The `SiteApp` folder behaves similar to the [app](http://book.cakephp.org/2.0/en/getting-started/cakephp-folder-structure.html#the-app-folder) folder included by default on each CakePHP project.

* ROOT/
    * Config/
    * ....
    * SiteApp/
        * Controller/
            * Component/
        * Model/
            * Behavior/
        * View/
            * Helper/
    * ....
    * tmp/
    * webroot/
    * ....