**New in version 1.1**

If you want your theme to include some configurable CSS choices, you can define which styles can be tweaked through the theme's configuration panel by adding some special comment-tags on your css files.

## Requirements
* Make sure your theme's layout include your stylesheet properly using:

        $this->Layout->stylesheets();

* Your theme's css files must be located in the `css` folder of the theme.

## Comment tags
All you have to do is add properly formatted CSS comments into your stylesheets.
Comment-tag's structure is the same used by Hooktags:

    [tag_name param1=value1 param2='other value 2' ...] TAG_CONTENT [/tag_name]

The comment-tag should be surrounding the css value to tweak. e.g.:

    div.class-selector {
        color:[color] #ffffff [/color];
    }


Available tags:

* font
* color
* size
* miscellaneous

Available parameters:

* `title`: Name of the selector to display in the customization form.
* `id`: Selector `alias`.
* `group`: Name of the group that selector belongs to. All selectors under the same group will be grouped in the same fieldset.

***

Basically, there are two types of selectors:

- Color Selectors
- Font Selectors

The `miscellaneous` tag (an empty style comment) will allow your theme to incorporate any kind of css you want:

       /*[miscellaneous]*/ /*[/miscellaneous]*/


##### Example

        body {
            font:/*[font title='Main font']*/normal normal 13px Arial/*[/font]*/;
            background: /*[color title='Body background']*/#777777/*[/color]*/;
        }

       /*[miscellaneous]*/ /*[/miscellaneous]*/

## Aliasing values
If you need to use the same value (color, font, size, etc) in two or more places of your css file.

        body {
            background: /*[color title='Header top' id='body-bg']*/#282727/*[/color]*/;
        }

        div.footer {
            background: /*[body-bg]*/#28ffff/*[/body-bg]*/;
        }

Above, the selected color value for body's background will be used for div.footer's background as well.
The `#28ffff` value in `div.footer` will be used by default if no value is available for the `body-bg` tag.


