Model Hooks
===========

### Comment Module
* comment_before_validate()
* comment_before_save()

### Field Module
* field_info()

***

View Hooks
==========

### CORE

*Layour related hooks*

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

*Form element related hooks*

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
===============

### CORE
stylesheets_alter()
javascripts_alter()
blocks_alter()
authenticate_alter()
authorize_alter()

### Node Module
* node_search_scope_alter()
* node_search_keys_alter()

### User Module
* before_login()
* after_login()
* login_failed()
* before_logout()
* after_logout