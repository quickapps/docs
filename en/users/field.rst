About
=====

The Field plugin allows custom data fields to be defined for entity
types (entities include content items, comments, user accounts or any
table entity in general). The Field plugin takes care of storing,
loading, editing, and rendering field data.

Most users will not interact with the Field plugin directly, but will
instead use a Field UI. Plugin developers can use the Field API to make
new entities "fieldable" and thus allow fields to be attached to them.

Uses
====

Enabling field types
--------------------

The Field plugin provides the infrastructure for fields and field
attachment; QuickApps CMS includes the following field plugins: Date,
File, List, Text and Terms. Additional fields may be provided by other
plugins.

Managing field data storage
---------------------------

Developers of field plugins can either use the default QuickApps storage
table to store data for their fields, or a contributed or custom storage
system developed using QuickApps's Field API.
