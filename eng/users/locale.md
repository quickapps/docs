About
=====

The Locale module allows your QuickApps site to be presented in languages other 
than the default English, and to be multilingual.
The Locale module works by maintaining a database of translations, and examining text as it is about to be displayed.
When a translation of the text is available in the language to be displayed,
the translation is displayed rather than the original text. When a translation is unavailable,
the original text is displayed.


Uses
====


Translating interface text
--------------------------

Translations of text in the QuickApps interface may be provided by:

* Translating within your site, using the Locale module's integrated translation interface.
    * **Fuzzy Entries**:
        Since version 1.1, each time QuickApps CMS fails when it tries to translate a text of your site, the text is marked as **fuzzy**.
        Fuzzy entries are suggested translatable entries. You can export and import the list of entries as .pot packages. 

* Importing files from a set of existing translations, known as a translation package files in the Gettext Portable Object (.po) format.

If an existing translation package does not meet your needs, the Gettext Portable Object (.po) files within a package
may be modified, or new .po files may be created, using a desktop Gettext editor.


Configuring a multilingual site
-------------------------------

Language negotiation allows your site to automatically change language based on path used for
each request. Users may (optionally) select their preferred language on their _My account page_,
and your site can be configured to honor a web browser's preferred language settings.