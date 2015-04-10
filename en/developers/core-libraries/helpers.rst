Helpers
#######

QuickAppsCMS provides some basic Helpers that overwrites CakePHP's to allow a better
integration with QuickAppsCMS's events system. This integration is mostly aimed to
provide method-altering: every helper method triggers an ``alter`` event to allow
other plugins modify method's behavior by altering its arguments. For example, when
using ``HtmlHelper`` in any view template:

.. code:: php

    $this->Helper->link('My Title', '/link/to/my-title.html', $options);
    // triggers the alter-event "Alter.HtmlHelper.link"

Some plugin in the system could catch this event an change link URL, title or
options. For instance:


.. code:: php

    MyPlugin\Event;

    use Cake\Event\Event;
    use Cake\Event\EventListenerInterface;

    class AlterLinksHook implements EventListenerInterface
    {

        public function implementedEvents()
        {
            return [
                'Alter.HtmlHelper.link' => 'alterHtmlLink',
            ];
        }

        public function alterHtmlLink(Event $event, $title, &$url, &$options)
        {
            // Remember the & symbol for referencing
            if ($url == '/link/to/my-title.html') {
                $url = '/redirect/to/this-url.html';
            }
        }
    }

Provided Helpers
================

By default the following helpers are included in every QuickAppsCMS installation,
they behave just as described in CakePHP documentation:

- FormHelper: QuickApps\\View\\Helper\\FormHelper
- HtmlHelper: QuickApps\\View\\Helper\\HtmlHelper
- PaginatorHelper: QuickApps\\View\\Helper\\PaginatorHelper
- UrlHelper: QuickApps\\View\\Helper\\UrlHelper

These helpers are automatically attached to your view templates, for example to use
FormHelper you must proceed as usual: ``$this->Form->input(...)``.