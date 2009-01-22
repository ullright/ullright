CREATE TABLE ull_record (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_wiki_version (id BIGINT, namespace VARCHAR(32), subject VARCHAR(255) NOT NULL, body TEXT, read_counter BIGINT, edit_counter BIGINT, duplicate_tags_for_search LONGTEXT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, deleted TINYINT(1) DEFAULT '0' NOT NULL, version BIGINT, PRIMARY KEY(id, version)) ENGINE = INNODB;
CREATE TABLE ull_wiki (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), subject VARCHAR(255) NOT NULL, body TEXT, read_counter BIGINT, edit_counter BIGINT, duplicate_tags_for_search LONGTEXT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, deleted TINYINT(1) DEFAULT '0' NOT NULL, version BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
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
CREATE TABLE ull_flow_app (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(32) NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_column_config_translation (id BIGINT, label VARCHAR(64), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_flow_column_config (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_app_id BIGINT NOT NULL, slug VARCHAR(32) NOT NULL, sequence BIGINT, ull_column_type_id BIGINT, options LONGTEXT, is_enabled TINYINT(1) DEFAULT '1', is_in_list TINYINT(1) DEFAULT '1', is_mandatory TINYINT(1) DEFAULT '0', is_subject TINYINT(1) DEFAULT '0', default_value VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_app_id_idx (ull_flow_app_id), INDEX ull_column_type_id_idx (ull_column_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_flow_app_permission (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_flow_app_id BIGINT NOT NULL, ull_permission_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_flow_app_id_idx (ull_flow_app_id), INDEX ull_permission_id_idx (ull_permission_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE test_table_translation (id BIGINT, my_string VARCHAR(64) NOT NULL, my_text LONGTEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE test_table (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), my_boolean TINYINT(1), my_email VARCHAR(64), my_select_box BIGINT, my_useless_column VARCHAR(64), ull_user_id BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_user_id_idx (ull_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_select_translation (id BIGINT, label VARCHAR(64), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_select (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(32), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_select_child_translation (id BIGINT, label VARCHAR(64), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_select_child (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_select_id BIGINT, sequence BIGINT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_select_id_idx (ull_select_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_permission (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), slug VARCHAR(64), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_parent_entity (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64) UNIQUE, email VARCHAR(64), password VARCHAR(40), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_entity (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), first_name VARCHAR(64), last_name VARCHAR(64), display_name VARCHAR(64), username VARCHAR(64) UNIQUE, email VARCHAR(64), password VARCHAR(40), type VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_entity_group (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_entity_id BIGINT NOT NULL, ull_group_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_entity_id_idx (ull_entity_id), INDEX ull_group_id_idx (ull_group_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_table_config_translation (id BIGINT, label VARCHAR(64), description LONGTEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_table_config (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), db_table_name VARCHAR(32), sort_columns VARCHAR(255), search_columns VARCHAR(255), created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_column_config_translation (id BIGINT, label VARCHAR(64), description LONGTEXT, lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE ull_column_config (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), db_table_name VARCHAR(32), db_column_name VARCHAR(32), ull_column_type_id BIGINT, options LONGTEXT, is_enabled TINYINT(1) DEFAULT '1', is_in_list TINYINT(1) DEFAULT '1', created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_column_type_id_idx (ull_column_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_group_permission (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), ull_group_id BIGINT NOT NULL, ull_permission_id BIGINT NOT NULL, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), INDEX ull_group_id_idx (ull_group_id), INDEX ull_permission_id_idx (ull_permission_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE ull_column_type (id BIGINT AUTO_INCREMENT, namespace VARCHAR(32), class VARCHAR(32), label VARCHAR(64), description LONGTEXT, created_at DATETIME, updated_at DATETIME, creator_user_id BIGINT, updator_user_id BIGINT, INDEX creator_user_id_idx (creator_user_id), INDEX updator_user_id_idx (updator_user_id), PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE ull_record ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_record ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki_version ADD FOREIGN KEY (id) REFERENCES ull_wiki(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_wiki ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_wiki ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
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
ALTER TABLE ull_select_translation ADD FOREIGN KEY (id) REFERENCES ull_select(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_select ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_select ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_select_child_translation ADD FOREIGN KEY (id) REFERENCES ull_select_child(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_select_child ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_select_child ADD FOREIGN KEY (ull_select_id) REFERENCES ull_select(id);
ALTER TABLE ull_select_child ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_permission ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_permission ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_parent_entity ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_parent_entity ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity_group ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity_group ADD FOREIGN KEY (ull_group_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity_group ADD FOREIGN KEY (ull_entity_id) REFERENCES ull_entity(id);
ALTER TABLE ull_entity_group ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_table_config_translation ADD FOREIGN KEY (id) REFERENCES ull_table_config(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_table_config ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_table_config ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_config_translation ADD FOREIGN KEY (id) REFERENCES ull_column_config(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ull_column_config ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_config ADD FOREIGN KEY (ull_column_type_id) REFERENCES ull_column_type(id);
ALTER TABLE ull_column_config ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_permission ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_permission ADD FOREIGN KEY (ull_permission_id) REFERENCES ull_permission(id);
ALTER TABLE ull_group_permission ADD FOREIGN KEY (ull_group_id) REFERENCES ull_entity(id);
ALTER TABLE ull_group_permission ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_type ADD FOREIGN KEY (updator_user_id) REFERENCES ull_entity(id);
ALTER TABLE ull_column_type ADD FOREIGN KEY (creator_user_id) REFERENCES ull_entity(id);
