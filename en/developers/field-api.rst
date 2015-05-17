Field API
#########

Field API is built on top of :doc:`EAV API <eav-api>`. They both work pretty similar
as they both allows to attach additional information to tables. However, the main
difference between this two APIs is that Field API allows you to create more complex
data structures; it was designed to control every aspect of the information being
managed, from how the information is stored in DB to how it is rendered and
presented to final users.

Any table (Nodes, Users, etc.) can use Field API to make itself ``fieldable`` and
thus allow additional columns to be attached to it. To do this, the Field API
defines two primary data structures, ``FieldInstance`` and ``FieldValue``:

-  FieldInstance: is a "Field" attached to a single Table. (Schema equivalent: column)
-  FieldValue: is the stored data for a particular [FieldInstance, Entity] tuple of
   your Table. (Schema equivalent: cell value)

.. note::

    Field API is built on top of EAV API, so please consider reading :doc:`EAV API
    documentation <eav-api>` before continue.

Table Of Contents
=================

.. toctree::
    :maxdepth: 1

    field-api/fieldable-behavior
    field-api/field-handlers
    field-api/field-gui

.. meta::
    :title lang=en: Field API
    :keywords lang=en: api,fields,field,behavior,cck,eav,fieldable,entity,custom field,search,render field,form input
