By using your theme's associated Module you can add extra features to your themes.
For example, allow to users change theme's color.

To add extra fields to your theme settings form, you have to create the following file:
    
    ROOT/Themes/Themed/MyThemeName/app/MyThemeName/View/Elements/settings.ctp

Themes are registed in the system as Modules. And every module is allowed to store in database their own settings parameters.
(All modules information is stored in the `modules` table). Module's settings parametters are stored in the `settings` column of the `modules` table.

      // ROOT/Themes/Themed/MyThemeName/app/MyThemeName/View/Elements/settings.ctp
      echo $this->Form->input('Module.settings.my_theme_color');
      echo $this->Form->input('Module.settings.theme_width');

Now you can read this settings values in any view:

     Configure::read('Theme.settings.my_theme_color');
     Configure::read('Theme.settings.theme_width');
