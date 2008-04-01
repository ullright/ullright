/* old definition: int(10) unsigned default NULL
   new definition: INTEGER */
ALTER TABLE `schema_info` CHANGE `version` `version` INTEGER;
/* old definition: varchar(7) NOT NULL
   new definition: VARCHAR(7) default 'null' NOT NULL */
ALTER TABLE `ull_culture` CHANGE `iso_code` `iso_code` VARCHAR(7) default 'null' NOT NULL;
/* old definition: tinyint(1) default NULL
   new definition: INTEGER */
ALTER TABLE `ull_flow_action` CHANGE `status_only` `status_only` INTEGER;
ALTER TABLE `ull_flow_doc` DROP INDEX ull_flow_instance_FI_1;
ALTER TABLE `ull_flow_field` DROP INDEX ull_flow_element_FI_1;
/* old definition: tinyint(1) default NULL
   new definition: INTEGER */
ALTER TABLE `ull_flow_step` CHANGE `is_start` `is_start` INTEGER;
/* old definition: (`ull_user_id`)
   new definition: (`user_id`) */
ALTER TABLE `ull_user_group` DROP INDEX user_group_FI_1;
ALTER TABLE `ull_user_group` ADD  INDEX `user_group_FI_1` (`user_id`);
/* old definition: (`ull_group_id`)
   new definition: (`group_id`) */
ALTER TABLE `ull_user_group` DROP INDEX user_group_FI_2;
ALTER TABLE `ull_user_group` ADD  INDEX `user_group_FI_2` (`group_id`);
/* old definition: int(11) default NULL
   new definition: INTEGER  NOT NULL */
ALTER TABLE `ull_user_group` CHANGE `ull_user_id` `ull_user_id` INTEGER  NOT NULL;
/* old definition: int(11) default NULL
   new definition: INTEGER  NOT NULL */
ALTER TABLE `ull_user_group` CHANGE `ull_group_id` `ull_group_id` INTEGER  NOT NULL;
ALTER TABLE `ull_flow_action_i18n` ADD CONSTRAINT `ull_flow_action_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_action` (`id`)
		ON DELETE CASCADE;
ALTER TABLE `ull_flow_app_i18n` ADD CONSTRAINT `ull_flow_app_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_app` (`id`)
		ON DELETE CASCADE;
ALTER TABLE `ull_flow_doc` ADD  INDEX `ull_flow_doc_FI_1` (`ull_flow_app_id`);
ALTER TABLE `ull_flow_doc` ADD  INDEX `ull_flow_doc_FI_2` (`ull_flow_action_id`);
ALTER TABLE `ull_flow_doc` ADD CONSTRAINT `ull_flow_doc_FK_1`
		FOREIGN KEY (`ull_flow_app_id`)
		REFERENCES `ull_flow_app` (`id`);
ALTER TABLE `ull_flow_doc` ADD CONSTRAINT `ull_flow_doc_FK_2`
		FOREIGN KEY (`ull_flow_action_id`)
		REFERENCES `ull_flow_action` (`id`);
ALTER TABLE `ull_flow_field` ADD  INDEX `ull_flow_field_FI_1` (`ull_flow_app_id`);
ALTER TABLE `ull_flow_field` ADD CONSTRAINT `ull_flow_field_FK_1`
		FOREIGN KEY (`ull_flow_app_id`)
		REFERENCES `ull_flow_app` (`id`);
ALTER TABLE `ull_flow_field_i18n` ADD CONSTRAINT `ull_flow_field_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_field` (`id`)
		ON DELETE CASCADE;
ALTER TABLE `ull_flow_memory` ADD CONSTRAINT `ull_flow_memory_FK_1`
		FOREIGN KEY (`ull_flow_doc_id`)
		REFERENCES `ull_flow_doc` (`id`);
ALTER TABLE `ull_flow_memory` ADD CONSTRAINT `ull_flow_memory_FK_2`
		FOREIGN KEY (`ull_flow_step_id`)
		REFERENCES `ull_flow_step` (`id`);
ALTER TABLE `ull_flow_memory` ADD CONSTRAINT `ull_flow_memory_FK_3`
		FOREIGN KEY (`ull_flow_action_id`)
		REFERENCES `ull_flow_action` (`id`);
ALTER TABLE `ull_flow_step` ADD CONSTRAINT `ull_flow_step_FK_1`
		FOREIGN KEY (`ull_flow_app_id`)
		REFERENCES `ull_flow_app` (`id`);
ALTER TABLE `ull_flow_step_i18n` ADD CONSTRAINT `ull_flow_step_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_step` (`id`)
		ON DELETE CASCADE;
ALTER TABLE `ull_flow_step_action` ADD CONSTRAINT `ull_flow_step_action_FK_1`
		FOREIGN KEY (`ull_flow_step_id`)
		REFERENCES `ull_flow_step` (`id`);
ALTER TABLE `ull_flow_step_action` ADD CONSTRAINT `ull_flow_step_action_FK_2`
		FOREIGN KEY (`ull_flow_action_id`)
		REFERENCES `ull_flow_action` (`id`);
ALTER TABLE `ull_flow_value` ADD  INDEX `ull_flow_value_FI_3` (`ull_flow_memory_id`);
ALTER TABLE `ull_flow_value` ADD CONSTRAINT `ull_flow_value_FK_1`
		FOREIGN KEY (`ull_flow_doc_id`)
		REFERENCES `ull_flow_doc` (`id`);
ALTER TABLE `ull_flow_value` ADD CONSTRAINT `ull_flow_value_FK_2`
		FOREIGN KEY (`ull_flow_field_id`)
		REFERENCES `ull_flow_field` (`id`);
ALTER TABLE `ull_flow_value` ADD CONSTRAINT `ull_flow_value_FK_3`
		FOREIGN KEY (`ull_flow_memory_id`)
		REFERENCES `ull_flow_memory` (`id`);
ALTER TABLE `schema_info` ADD `id` INTEGER  NOT NULL AUTO_INCREMENT;
ALTER TABLE `schema_info` ADD PRIMARY INDEX `` (`id`);
ALTER TABLE `ull_column_info` ADD  INDEX `ull_column_info_FI_1` (`ull_field_id`);
ALTER TABLE `ull_column_info` ADD CONSTRAINT `ull_column_info_FK_1`
		FOREIGN KEY (`ull_field_id`)
		REFERENCES `ull_field` (`id`);
ALTER TABLE `ull_column_info_i18n` ADD CONSTRAINT `ull_column_info_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_column_info` (`id`)
		ON DELETE CASCADE;
ALTER TABLE `ull_field_i18n` ADD CONSTRAINT `ull_field_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_field` (`id`)
		ON DELETE CASCADE;
ALTER TABLE `ull_table_info_i18n` ADD CONSTRAINT `ull_table_info_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_table_info` (`id`)
		ON DELETE CASCADE;
