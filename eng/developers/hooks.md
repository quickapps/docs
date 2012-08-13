Hooks
=====

A hook is a PHP method that is named **underscored** which allow modules, themes or fields to interact with the QACMS core.

As CakePHP is a MVC framework, hooks method are separated in three groups:

* Model Hooks: Packaged as [Behaviors](http://book.cakephp.org/2.0/en/models/behaviors.html)
* View Hooks: Packaged as [Helpers](http://book.cakephp.org/2.0/en/views/helpers.html)
* Controller Hooks: Packaged as [Components](http://book.cakephp.org/2.0/en/controllers/components.html)

Hook methods can be invoked by using the `hook` method located in each HookCollection class:

* HookCollectionBehavior::hook()
    * AppModel::hook() shorcut for use in Model classes.
* HookCollectionHelper::hook()
    * AppHelper::hook() shorcut for use in Helper classes.
* HookCollectionComponent::hook()
    * AppController::hook() shorcut for use in Controller actions.

***
	
Hook methods may accept only **one parameter**, e.g.:

* `public function my_hook_method($param_1, $params_2);` Invalid, second parameter will be always unset.
* `public function my_hook_method($param_1);` Correct.
* `public function my_hook_method(&$param_1);` Correct, reference parameter for alter purposes.
* `public function my_hook_method();` Correct, no parameter expected.


hook($hook, &$data, $options);
==============================


$hook
-----

**underscored** name of the hook to call. e.g.: `foo_bar`, `hook_name`


$data
-----

Data for the triggered hook. **Must be a reference**, some examples:

* hook('foo_bar', array('data_for_hook')); **Invalid** will produce fatal error.
* hook('foo_bar', $data = array('data_for_hook')); **Valid**


$option
-------

Array of options.

- `breakOn` Set to the value or values you want the callback propagation to stop on.
   Can either be a scalar value, or an array of values to break on.
   Defaults to `false`.

- `break` Set to true to enabled breaking. When a trigger is broken, the last returned value
   will be returned.  If used in combination with `collectReturn` the collected results will be returned.
   Defaults to `false`.

- `collectReturn` Set to true to collect the return of each object into an array.
   This array of return values will be returned from the hook() call. Defaults to `false`.

   
return
------

Either the last result or all results if collectReturn is on. Or **null** in case of no response.


Core Hooks
==========

Model Hooks
-----------

The following hooks can be invoked from any **model** using the `hook()` method.

### Comment Module
* comment_before_validate()
* comment_before_save()

### Field Module
* field_info()


***


View Hooks
----------

The following hooks can be invoked from any **helper** using the `hook()` method.  
If you need to invoke a hook from the view layer you can access to the `hook()` method from any Helper instance, e.g.: `$this->Layout->hook()`


### CORE

*Layout related hooks*

* stylesheets_alter()
* javascripts_alter()
* layout_title_alter()
* layout_content_alter()
* layout_header_alter()
* layout_meta_alter()
* layout_footer_alter()
* before_render_node()
* after_render_node()
* toolbar_alter()
* after_render_blocks()
* block_alter()
* after_render_block()
* special_tags_alter()
* breadcrumb_alter()

*Form elements related hooks*

* form_file_alter()
* form_create_alter()
* form_end_alter()
* form_secure_alter()
* form_unlock_field_alter()
* form_is_field_error_alter()
* form_error_alter()
* form_label_alter()
* form_inputs_alter()
* form_input_alter()
* form_checkbox_alter()
* form_radio_alter()
* form_textarea_alter()
* form_hidden_alter()
* form_file_alter()
* form_button_alter()
* form_post_button_alter()
* form_post_link_alter()
* form_submit_alter()
* form_select_alter()
* form_day_alter()
* form_year_alter()
* form_month_alter()
* form_hour_alter()
* form_minute_alter()
* form_meridian_alter()
* form_date_time_alter()
* form_set_entity_alter()

*HTML related hooks*

* html_table_alter()
* html_meta_alter()
* html_doc_type_alter()
* html_meta_alter()
* html_charset_alter()
* html_link_alter()
* html_css_alter()
* html_script_alter()
* html_script_block_alter()
* html_script_start_alter()
* html_script_end_alter()
* html_style_alter()
* html_get_crumbs_alter()
* html_get_crumb_list_alter()
* html_image_alter()
* html_table_headers_alter()
* html_table_cells_alter()
* html_tag_alter()
* html_useTag_alter()
* html_before_use_tag()
* html_after_use_tag()
* html_div_alter()
* html_para_alter()
* html_nested_list_alter()
* html_load_config_alter()

### Block Module
* block_form_params()


***


Controller Hooks
----------------

The following hooks can be invoked from any **controller** using the `hook()` method.


### CORE
* stylesheets_alter()
* javascripts_alter()
* blocks_alter()
* authenticate_alter()
* authorize_alter()

### Node Module
* node_search_criteria_alter()
* node_search_post_alter()

### User Module
* before_login()
* after_login()
* login_failed()
* before_logout()
* after_logout