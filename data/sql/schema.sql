CREATE TABLE ull_record (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_wiki_access_level_translation (id BIGINT, name VARCHAR(128), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_wiki_access_level (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(64), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_wiki_version (id BIGINT, namespace VARCHAR(32), subject VARCHAR(255) NOT NULL, body TEXT, read_counter BIGINT, edit_counter BIGINT, duplicate_tags_for_search LONGTEXT, ull_wiki_access_level_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, deleted TINYINT(1) DEFAULT '0' NOT NULL, version BIGINT, PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_wiki (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), subject VARCHAR(255) NOT NULL, body TEXT, read_counter BIGINT, edit_counter BIGINT, duplicate_tags_for_search LONGTEXT, ull_wiki_access_level_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, deleted TINYINT(1) DEFAULT '0' NOT NULL, version BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_wiki_access_level_id_idx (ull_wiki_access_level_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_wiki_access_level_access (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_group_id BIGINT, ull_privilege_id BIGINT, model_id BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_group_id_idx (ull_group_id), INDEX ull_privilege_id_idx (ull_privilege_id), INDEX model_id_idx (model_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE tag (id BIGINT AUTO_INCREMENT, name VARCHAR(100), is_triple TINYINT(1), triple_namespace VARCHAR(100), triple_key VARCHAR(100), triple_value VARCHAR(100), INDEX name_idx (name), INDEX triple1_idx (triple_namespace), INDEX triple2_idx (triple_key), INDEX triple3_idx (triple_value), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE tagging (id BIGINT AUTO_INCREMENT, tag_id BIGINT NOT NULL, taggable_model VARCHAR(30), taggable_id BIGINT, INDEX tag_idx (tag_id), INDEX taggable_idx (taggable_model, taggable_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_value (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_doc_id BIGINT NOT NULL, ull_flow_column_config_id BIGINT NOT NULL, ull_flow_memory_id BIGINT, value TEXT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_doc_id_idx (ull_flow_doc_id), INDEX ull_flow_column_config_id_idx (ull_flow_column_config_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_doc (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_app_id BIGINT NOT NULL, subject VARCHAR(255), ull_flow_action_id BIGINT, assigned_to_ull_entity_id BIGINT NOT NULL, assigned_to_ull_flow_step_id BIGINT NOT NULL, priority BIGINT DEFAULT '3' NOT NULL, duplicate_tags_for_search LONGTEXT, dirty BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_app_id_idx (ull_flow_app_id), INDEX ull_flow_action_id_idx (ull_flow_action_id), INDEX assigned_to_ull_entity_id_idx (assigned_to_ull_entity_id), INDEX assigned_to_ull_flow_step_id_idx (assigned_to_ull_flow_step_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_action_translation (id BIGINT, label VARCHAR(32) NOT NULL, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_flow_action (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(32) NOT NULL, is_status_only TINYINT(1) DEFAULT '0', is_enable_validation TINYINT(1) DEFAULT '1', is_notify_creator TINYINT(1) DEFAULT '0', is_notify_next TINYINT(1) DEFAULT '0', is_in_resultlist TINYINT(1) DEFAULT '1', is_show_assigned_to TINYINT(1) DEFAULT '0', is_comment_mandatory TINYINT(1) DEFAULT '0', created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_step_translation (id BIGINT, label VARCHAR(32) NOT NULL, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_flow_step (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_app_id BIGINT NOT NULL, slug VARCHAR(32) NOT NULL, is_start TINYINT(1), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_app_id_idx (ull_flow_app_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_step_action (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_step_id BIGINT, ull_flow_action_id BIGINT, options TEXT, sequence BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_step_id_idx (ull_flow_step_id), INDEX ull_flow_action_id_idx (ull_flow_action_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_memory (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_doc_id BIGINT NOT NULL, ull_flow_step_id BIGINT NOT NULL, ull_flow_action_id BIGINT NOT NULL, assigned_to_ull_entity_id BIGINT NOT NULL, comment VARCHAR(255), creator_ull_entity_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_doc_id_idx (ull_flow_doc_id), INDEX ull_flow_step_id_idx (ull_flow_step_id), INDEX ull_flow_action_id_idx (ull_flow_action_id), INDEX assigned_to_ull_entity_id_idx (assigned_to_ull_entity_id), INDEX creator_ull_entity_id_idx (creator_ull_entity_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_app_translation (id BIGINT, label VARCHAR(64), doc_label VARCHAR(64), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_flow_app (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(32) NOT NULL, list_columns VARCHAR(255), is_public TINYINT(1), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_column_config_translation (id BIGINT, label VARCHAR(64), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_flow_column_config (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_app_id BIGINT NOT NULL, slug VARCHAR(32) NOT NULL, sequence BIGINT, ull_column_type_id BIGINT, options LONGTEXT, is_enabled TINYINT(1) DEFAULT '1', is_in_list TINYINT(1) DEFAULT '1', is_mandatory TINYINT(1) DEFAULT '0', is_subject TINYINT(1) DEFAULT '0', default_value VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_app_id_idx (ull_flow_app_id), INDEX ull_column_type_id_idx (ull_column_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_app_permission (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_app_id BIGINT NOT NULL, ull_permission_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_app_id_idx (ull_flow_app_id), INDEX ull_permission_id_idx (ull_permission_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE test_table_translation (id BIGINT, my_string VARCHAR(64) NOT NULL, my_text LONGTEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE test_table (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), my_boolean TINYINT(1), my_email VARCHAR(64), my_select_box BIGINT, my_useless_column VARCHAR(64), ull_user_id BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_user_id_idx (ull_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_job_title (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), name VARCHAR(100) NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_select_translation (id BIGINT, label VARCHAR(64), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_select (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(32), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_select_child_translation (id BIGINT, label VARCHAR(64), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_select_child (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_select_id BIGINT, sequence BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_select_id_idx (ull_select_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_company (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), name VARCHAR(100) NOT NULL, short_name VARCHAR(15), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_permission_version (id BIGINT, namespace VARCHAR(32), slug VARCHAR(64), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, reference_version BIGINT, scheduled_update_date DATE, done_at DATETIME, INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_permission (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(64), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_parent_entity_version (id BIGINT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64), email VARCHAR(64), password VARCHAR(40), sex VARCHAR(255), entry_date DATE, deactivation_date DATE, separation_date DATE, ull_employment_type_id BIGINT, ull_job_title_id BIGINT, ull_company_id BIGINT, ull_department_id BIGINT, ull_location_id BIGINT, superior_ull_user_id BIGINT, phone_extension BIGINT, is_show_extension_in_phonebook TINYINT(1), fax_extension BIGINT, is_show_fax_extension_in_phonebook TINYINT(1), comment TEXT, ull_user_status_id BIGINT DEFAULT '1' NOT NULL, is_virtual_group TINYINT(1) DEFAULT '0', created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, reference_version BIGINT, scheduled_update_date DATE, done_at DATETIME, INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_parent_entity (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64) UNIQUE, email VARCHAR(64), password VARCHAR(40), sex VARCHAR(255), entry_date DATE, deactivation_date DATE, separation_date DATE, ull_employment_type_id BIGINT, ull_job_title_id BIGINT, ull_company_id BIGINT, ull_department_id BIGINT, ull_location_id BIGINT, superior_ull_user_id BIGINT, phone_extension BIGINT, is_show_extension_in_phonebook TINYINT(1), fax_extension BIGINT, is_show_fax_extension_in_phonebook TINYINT(1), comment TEXT, ull_user_status_id BIGINT DEFAULT '1' NOT NULL, is_virtual_group TINYINT(1) DEFAULT '0', created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_entity_version (id BIGINT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64), email VARCHAR(64), password VARCHAR(40), sex VARCHAR(255), entry_date DATE, deactivation_date DATE, separation_date DATE, ull_employment_type_id BIGINT, ull_job_title_id BIGINT, ull_company_id BIGINT, ull_department_id BIGINT, ull_location_id BIGINT, superior_ull_user_id BIGINT, phone_extension BIGINT, is_show_extension_in_phonebook TINYINT(1), fax_extension BIGINT, is_show_fax_extension_in_phonebook TINYINT(1), comment TEXT, ull_user_status_id BIGINT DEFAULT '1' NOT NULL, is_virtual_group TINYINT(1) DEFAULT '0', type VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, reference_version BIGINT, scheduled_update_date DATE, done_at DATETIME, INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_entity (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64) UNIQUE, email VARCHAR(64), password VARCHAR(40), sex VARCHAR(255), entry_date DATE, deactivation_date DATE, separation_date DATE, ull_employment_type_id BIGINT, ull_job_title_id BIGINT, ull_company_id BIGINT, ull_department_id BIGINT, ull_location_id BIGINT, superior_ull_user_id BIGINT, phone_extension BIGINT, is_show_extension_in_phonebook TINYINT(1), fax_extension BIGINT, is_show_fax_extension_in_phonebook TINYINT(1), comment TEXT, ull_user_status_id BIGINT DEFAULT '1' NOT NULL, is_virtual_group TINYINT(1) DEFAULT '0', type VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_user_version (id BIGINT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64), email VARCHAR(64), password VARCHAR(40), sex VARCHAR(255), entry_date DATE, deactivation_date DATE, separation_date DATE, ull_employment_type_id BIGINT, ull_job_title_id BIGINT, ull_company_id BIGINT, ull_department_id BIGINT, ull_location_id BIGINT, superior_ull_user_id BIGINT, phone_extension BIGINT, is_show_extension_in_phonebook TINYINT(1), fax_extension BIGINT, is_show_fax_extension_in_phonebook TINYINT(1), comment TEXT, ull_user_status_id BIGINT DEFAULT '1' NOT NULL, is_virtual_group TINYINT(1) DEFAULT '0', type VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, reference_version BIGINT, scheduled_update_date DATE, done_at DATETIME, INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_department (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), name VARCHAR(100) NOT NULL, short_name VARCHAR(15), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_privilege (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(64), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_entity_group (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_entity_id BIGINT NOT NULL, ull_group_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_entity_id_idx (ull_entity_id), INDEX ull_group_id_idx (ull_group_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_table_config_translation (id BIGINT, label VARCHAR(64), description LONGTEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_table_config (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), db_table_name VARCHAR(32), sort_columns VARCHAR(255), search_columns VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_column_config_translation (id BIGINT, label VARCHAR(64), description LONGTEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_column_config (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), db_table_name VARCHAR(32), db_column_name VARCHAR(64), ull_column_type_id BIGINT, options LONGTEXT, is_enabled TINYINT(1) DEFAULT '1', is_in_list TINYINT(1) DEFAULT '1', created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_column_type_id_idx (ull_column_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_group_permission_version (id BIGINT, namespace VARCHAR(32), ull_group_id BIGINT NOT NULL, ull_permission_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, reference_version BIGINT, scheduled_update_date DATE, done_at DATETIME, INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_group_permission (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_group_id BIGINT NOT NULL, ull_permission_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_group_id_idx (ull_group_id), INDEX ull_permission_id_idx (ull_permission_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_user_status_translation (id BIGINT, name VARCHAR(50) NOT NULL, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_user_status (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(64), is_active TINYINT(1), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_column_type (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), class VARCHAR(32), label VARCHAR(64), description LONGTEXT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_group_version (id BIGINT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64), email VARCHAR(64), password VARCHAR(40), sex VARCHAR(255), entry_date DATE, deactivation_date DATE, separation_date DATE, ull_employment_type_id BIGINT, ull_job_title_id BIGINT, ull_company_id BIGINT, ull_department_id BIGINT, ull_location_id BIGINT, superior_ull_user_id BIGINT, phone_extension BIGINT, is_show_extension_in_phonebook TINYINT(1), fax_extension BIGINT, is_show_fax_extension_in_phonebook TINYINT(1), comment TEXT, ull_user_status_id BIGINT DEFAULT '1' NOT NULL, is_virtual_group TINYINT(1) DEFAULT '0', type VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, version BIGINT, reference_version BIGINT, scheduled_update_date DATE, done_at DATETIME, INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_employment_type_translation (id BIGINT, name VARCHAR(100) NOT NULL, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_employment_type (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_location (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), name VARCHAR(100) NOT NULL, short_name VARCHAR(15), street VARCHAR(200), city VARCHAR(100), country VARCHAR(10), post_code VARCHAR(10), phone_base_no VARCHAR(20), phone_default_extension BIGINT, fax_base_no VARCHAR(20), fax_default_extension BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_attribute_translation (id BIGINT, name VARCHAR(128) NOT NULL, help TEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_attribute (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_column_type_id BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_column_type_id_idx (ull_column_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_attribute_value (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_ventory_item_id BIGINT NOT NULL, ull_ventory_item_type_attribute_id BIGINT NOT NULL, value TEXT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_ventory_item_id_idx (ull_ventory_item_id), INDEX ull_ventory_item_type_attribute_id_idx (ull_ventory_item_type_attribute_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_type_attribute (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_ventory_item_type_id BIGINT NOT NULL, ull_ventory_item_attribute_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_ventory_item_type_id_idx (ull_ventory_item_type_id), INDEX ull_ventory_item_attribute_id_idx (ull_ventory_item_attribute_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_model (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), name VARCHAR(128) NOT NULL, ull_ventory_item_manufacturer_id BIGINT NOT NULL, ull_ventory_item_type_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_ventory_item_manufacturer_id_idx (ull_ventory_item_manufacturer_id), INDEX ull_ventory_item_type_id_idx (ull_ventory_item_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), serial_number VARCHAR(128), comment TEXT, ull_ventory_item_model_id BIGINT NOT NULL, ull_user_id BIGINT NOT NULL, ull_location_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_ventory_item_model_id_idx (ull_ventory_item_model_id), INDEX ull_user_id_idx (ull_user_id), INDEX ull_location_id_idx (ull_location_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_manufacturer (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), name VARCHAR(128) NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_type_translation (id BIGINT, name VARCHAR(128) NOT NULL, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_ventory_item_type (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(128), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE ull_record ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_record ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki_access_level_translation ADD FOREIGN KEY (id) REFERENCES ull_wiki_access_level(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_wiki_access_level ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki_access_level ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki_version ADD FOREIGN KEY (id) REFERENCES ull_wiki(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_wiki ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki ADD FOREIGN KEY (ull_wiki_access_level_id) REFERENCES ull_wiki_access_level(id);
ALTER TABLE ull_wiki ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki_access_level_access ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki_access_level_access ADD FOREIGN KEY (ull_privilege_id) REFERENCES ull_privilege(id);
ALTER TABLE ull_wiki_access_level_access ADD FOREIGN KEY (ull_group_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki_access_level_access ADD FOREIGN KEY (model_id) REFERENCES ull_wiki_access_level(id);
ALTER TABLE ull_wiki_access_level_access ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_value ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_value ADD FOREIGN KEY (ull_flow_doc_id) REFERENCES ull_flow_doc(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_value ADD FOREIGN KEY (ull_flow_column_config_id) REFERENCES ull_flow_column_config(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_value ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_doc ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_doc ADD FOREIGN KEY (ull_flow_app_id) REFERENCES ull_flow_app(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_doc ADD FOREIGN KEY (ull_flow_action_id) REFERENCES ull_flow_action(id);
ALTER TABLE ull_flow_doc ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_doc ADD FOREIGN KEY (assigned_to_ull_flow_step_id) REFERENCES ull_flow_step(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_doc ADD FOREIGN KEY (assigned_to_ull_entity_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_action_translation ADD FOREIGN KEY (id) REFERENCES ull_flow_action(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_flow_action ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_action ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_step_translation ADD FOREIGN KEY (id) REFERENCES ull_flow_step(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_flow_step ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_step ADD FOREIGN KEY (ull_flow_app_id) REFERENCES ull_flow_app(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_step ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_step_action ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_step_action ADD FOREIGN KEY (ull_flow_step_id) REFERENCES ull_flow_step(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_step_action ADD FOREIGN KEY (ull_flow_action_id) REFERENCES ull_flow_action(id);
ALTER TABLE ull_flow_step_action ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_memory ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_memory ADD FOREIGN KEY (ull_flow_step_id) REFERENCES ull_flow_step(id);
ALTER TABLE ull_flow_memory ADD FOREIGN KEY (ull_flow_doc_id) REFERENCES ull_flow_doc(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_memory ADD FOREIGN KEY (ull_flow_action_id) REFERENCES ull_flow_action(id);
ALTER TABLE ull_flow_memory ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_memory ADD FOREIGN KEY (creator_ull_entity_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_memory ADD FOREIGN KEY (assigned_to_ull_entity_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_app_translation ADD FOREIGN KEY (id) REFERENCES ull_flow_app(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_flow_app ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_app ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_column_config_translation ADD FOREIGN KEY (id) REFERENCES ull_flow_column_config(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_flow_column_config ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_column_config ADD FOREIGN KEY (ull_flow_app_id) REFERENCES ull_flow_app(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_column_config ADD FOREIGN KEY (ull_column_type_id) REFERENCES ull_column_type(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_column_config ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_app_permission ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_flow_app_permission ADD FOREIGN KEY (ull_permission_id) REFERENCES ull_permission(id);
ALTER TABLE ull_flow_app_permission ADD FOREIGN KEY (ull_flow_app_id) REFERENCES ull_flow_app(id) ON DELETE CASCADE;
ALTER TABLE ull_flow_app_permission ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE test_table_translation ADD FOREIGN KEY (id) REFERENCES test_table(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE test_table ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE test_table ADD FOREIGN KEY (ull_user_id) REFERENCES ull_entity(id);
ALTER TABLE test_table ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_job_title ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_job_title ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_select_translation ADD FOREIGN KEY (id) REFERENCES ull_select(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_select ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_select ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_select_child_translation ADD FOREIGN KEY (id) REFERENCES ull_select_child(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_select_child ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_select_child ADD FOREIGN KEY (ull_select_id) REFERENCES ull_select(id);
ALTER TABLE ull_select_child ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_company ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_company ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_permission_version ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_permission_version ADD FOREIGN KEY (id) REFERENCES ull_permission(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_permission ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_permission ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_parent_entity_version ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_parent_entity_version ADD FOREIGN KEY (id) REFERENCES ull_parent_entity(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_entity_version ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity_version ADD FOREIGN KEY (id) REFERENCES ull_entity(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_user_version ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_user_version ADD FOREIGN KEY (id) REFERENCES ull_entity(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_department ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_department ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_privilege ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_privilege ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity_group ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity_group ADD FOREIGN KEY (ull_group_id) REFERENCES ull_entity(id) ON DELETE CASCADE;
ALTER TABLE ull_entity_group ADD FOREIGN KEY (ull_entity_id) REFERENCES ull_entity(id) ON DELETE CASCADE;
ALTER TABLE ull_entity_group ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_table_config_translation ADD FOREIGN KEY (id) REFERENCES ull_table_config(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_table_config ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_table_config ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_config_translation ADD FOREIGN KEY (id) REFERENCES ull_column_config(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_column_config ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_config ADD FOREIGN KEY (ull_column_type_id) REFERENCES ull_column_type(id);
ALTER TABLE ull_column_config ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_permission_version ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_permission_version ADD FOREIGN KEY (id) REFERENCES ull_group_permission(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_group_permission ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_permission ADD FOREIGN KEY (ull_permission_id) REFERENCES ull_permission(id) ON DELETE CASCADE;
ALTER TABLE ull_group_permission ADD FOREIGN KEY (ull_group_id) REFERENCES ull_entity(id) ON DELETE CASCADE;
ALTER TABLE ull_group_permission ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_user_status_translation ADD FOREIGN KEY (id) REFERENCES ull_user_status(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_user_status ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_user_status ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_type ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_type ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_version ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_version ADD FOREIGN KEY (id) REFERENCES ull_entity(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_employment_type_translation ADD FOREIGN KEY (id) REFERENCES ull_employment_type(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_employment_type ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_employment_type ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_location ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_location ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_attribute_translation ADD FOREIGN KEY (id) REFERENCES ull_ventory_item_attribute(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_ventory_item_attribute ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_attribute ADD FOREIGN KEY (ull_column_type_id) REFERENCES ull_column_type(id);
ALTER TABLE ull_ventory_item_attribute ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_attribute_value ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_attribute_value ADD FOREIGN KEY (ull_ventory_item_type_attribute_id) REFERENCES ull_ventory_item_type_attribute(id);
ALTER TABLE ull_ventory_item_attribute_value ADD FOREIGN KEY (ull_ventory_item_id) REFERENCES ull_ventory_item(id);
ALTER TABLE ull_ventory_item_attribute_value ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_type_attribute ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_type_attribute ADD FOREIGN KEY (ull_ventory_item_type_id) REFERENCES ull_ventory_item_type(id);
ALTER TABLE ull_ventory_item_type_attribute ADD FOREIGN KEY (ull_ventory_item_attribute_id) REFERENCES ull_ventory_item_attribute(id);
ALTER TABLE ull_ventory_item_type_attribute ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_model ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_model ADD FOREIGN KEY (ull_ventory_item_type_id) REFERENCES ull_ventory_item_type(id);
ALTER TABLE ull_ventory_item_model ADD FOREIGN KEY (ull_ventory_item_manufacturer_id) REFERENCES ull_ventory_item_manufacturer(id);
ALTER TABLE ull_ventory_item_model ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item ADD FOREIGN KEY (ull_ventory_item_model_id) REFERENCES ull_ventory_item_model(id);
ALTER TABLE ull_ventory_item ADD FOREIGN KEY (ull_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item ADD FOREIGN KEY (ull_location_id) REFERENCES ull_location(id);
ALTER TABLE ull_ventory_item ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_manufacturer ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_manufacturer ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_type_translation ADD FOREIGN KEY (id) REFERENCES ull_ventory_item_type(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_ventory_item_type ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_ventory_item_type ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
