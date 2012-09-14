Packaging
=========

The next step after you have created your theme or module is to create a installable `.zip` package that can be installed through
the installation form panel.


Custom ZIP packages
-------------------

Simply compress your theme/module folder as zip. Your module/theme folder should be on the root of your ZIP.

e.g. If you have created a theme named `HotTheme` then your ZIP should looks as follows:

    HotTheme.zip/
        HotTheme/
            Config/
            ....
            HotTheme.yaml
            thumbnail.png


GitHub ZIP packages
-------------------

If you want to share your module/theme code, you can easly create a quickapps-compliant `GitHub Repository`.
By doing this, GitHub will automatically generate valid ZIP packages that can be installed on QuickApps CMS using the corresponding form.

#### STEP 1

Name your GitHub Repo following this pattern:

    QACMS-{YourThemeOrModuleName}

e.g.: If you have created a module named `MyModule`, the name of your GitHub Repo should be: `QACMS-MyModule`


#### STEP 2

Your GitHub Repo represent your module/theme root folder.  
This means your Repo root must contain the files and folders of your module/theme.

e.g. If you have created a module named `MyModule`, your GitHub Repository should looks:

    // https://github.com/your_account_name/QACMS-MyModule

    QACMS-MyModule/ 
    name                    age                    message                    history

    Config/                .....                 ...........
    Controller/            .....                 ...........
    ...
    MyModule.yaml          .....                 ...........


And that is all!.  
Now you are able to download your module/theme repo as ZIP by using GitHub's zipball functionality. Or even easier, you can simply
copy the download URL and use to install directly in the module installation section of your site ("Install From URL").