Helpers
#######

QuickAppsCMS provides some basic Helpers that overwrites CakePHP's to allow a better
integration with QuickAppsCMS's core.

Provided Helpers
================

By default the following helpers are included in every QuickAppsCMS installation,
they behave just as described in CakePHP documentation:

- FormHelper: CMS\\View\\Helper\\FormHelper
- HtmlHelper: CMS\\View\\Helper\\HtmlHelper

These helpers are automatically attached to your view templates, for example to use
FormHelper you must proceed as usual: ``$this->Form->input(...)``.