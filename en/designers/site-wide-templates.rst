Site-Wide Templates
###################

Although theming is the proper way to change your site's visual aspect, QuickAppsCMS
offers a simple and quick solution for override any theme template. This allows you to
change instantly the look & feel of any page of your site without touching your theme's
structure. This is extremely useful when working with third-party themes, as it allows you
to keep your dependencies clean and untouched without loosing customization capabilities.

By default, every QuickAppsCMS site comes with the ``ROOT/templates/`` directory, which
structure look as follow:

- templates/
   - Common/
      - Element/
      - Email/
      - Error/
      - Layout/
      - Plugin/
   - Back/
      - Element/
      - Email/
      - Error/
      - Layout/
      - Plugin/
   - Front/
      - Element/
      - Email/
      - Error/
      - Layout/
      - Plugin/

Each subdirectory - ``Common``, ``Back`` and ``Front``- mimics the ``Template`` directory
every theme comes with and they behave like some sort of high priority theme. The `Back`
and `Front` represents backend and frontend themes respectively. Whereas ``Common`` is
aimed to hold templates shared across both back and frontend themes, this is the
subdirectory with the highest priority when looking for template files.

For instance, suppose you're using a third-party frontend theme which `404` error page
does not fit your need. Instead of hacking this theme and modify the corresponding
template you can override this template as follow:

If the the original theme's template is located at
**ROOT/themes/MyThirdPartyTheme/src/Template/Error/error400.ctp** then you can override it
by creating a new template file at **ROOT/templates/Front/Error/error400.ctp**.


If you want to override using the same template for both back and frontend then you must
place your custom template at **ROOT/templates/Common/Error/error400.ctp**. Note that
``Common`` container has the highest priority, so having both templates files:

- ROOT/templates/Front/Error/error400.ctp
- ROOT/templates/Common/Error/error400.ctp

Then **ROOT/templates/Common/Error/error400.ctp** will be used.
