About
=====

The Field module allows custom data fields to be defined for entity types (entities include content items, comments, user accounts or any Model
in general).

The Field module takes care of storing, loading, editing, and rendering field data.
Most users will not interact with the Field module directly, but will instead use a _Field GUI_.
Module developers can use the _[Field API](../developers/field-api.md)_ to make new entity types "fieldable" and thus allow fields to be
attached to them.


Uses
====


Enabling field types
--------------------

The Field module provides the infrastructure for fields and field attachment; the field types and input are provided by additional modules.
Some of the modules are required; the optional modules can be enabled from the Modules administration page.  
_QuickApps_ core includes the following field type modules: 

* Text box
* Text area
* List
* Terms

_Additional fields may be provided by contributed modules._


Managing field data storage
---------------------------

Developers of field modules can either use the default QuickApps storage table to store data for their fields, or a contributed or custom
storage system developed using QuickApps Field API. 

