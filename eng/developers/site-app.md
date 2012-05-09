Site App
========

Sites are allowed to implement custom MVC logic under the `SiteApp` directory.
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

Permission Problems
-------------------

One common issue when using this configuration occurs when users accessing a custom controller under the SiteApp 
directory cannot see the page, and an `access denied` or `404 not found` error screen is rendered instead.

    class CustomControllerNameController extends AppController {
        public function my_action() {
            //
        }
    }

The above will produce the mentioned issue when accessing the url `http://www.domain.com/custom_controller_name/my_action`.

#### Mother of god, Why ? why ?!
Your controller class is extending QACMS's `AppController` class which by default checks access permissions on every request.
Also, as your controller is not a module or any valid script recognizable by QACMS you are not allowd to set permissions to it in the _Permissions section_ of the backoffice (/admin/user/permissions).

#### Solution
There are two soultions,

1- Manually grant permission for your controller's method using Auth component on `beforeFilter` callback:

    class CustomControllerNameController extends AppController {
        public function beforeFilter() {
            $this->Auth->allow('my_action');
            parent::beforeFilter();
        }

        public function my_action() {
            //
        }
    }

2- Do not extend QACMS's `AppController`, extend `Controller` instead. By doing this your controller will be isolated from QACMS and will work completely independent. If your controller does not use any of the QACMS features then this is your solution. Use solution number one otherwise.

    class CustomControllerNameController extends Controller {
        public function my_action() {
            //
        }
    }