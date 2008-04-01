
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- ull_flow_action
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_action`;


CREATE TABLE `ull_flow_action`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`slug` VARCHAR(32),
	`caption_i18n_default` VARCHAR(32),
	`status_only` INTEGER,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_action_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_action_i18n`;


CREATE TABLE `ull_flow_action_i18n`
(
	`caption_i18n` VARCHAR(32),
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `ull_flow_action_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_action` (`id`)
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_app
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_app`;


CREATE TABLE `ull_flow_app`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`slug` VARCHAR(32),
	`caption_i18n_default` VARCHAR(32),
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_app_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_app_i18n`;


CREATE TABLE `ull_flow_app_i18n`
(
	`caption_i18n` VARCHAR(32),
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `ull_flow_app_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_app` (`id`)
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_doc
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_doc`;


CREATE TABLE `ull_flow_doc`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ull_flow_app_id` INTEGER,
	`title` VARCHAR(256),
	`ull_flow_action_id` INTEGER,
	`assigned_to_ull_user_id` INTEGER,
	`assigned_to_ull_group_id` INTEGER,
	`assigned_to_ull_flow_step_id` INTEGER,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `ull_flow_doc_FI_1` (`ull_flow_app_id`),
	CONSTRAINT `ull_flow_doc_FK_1`
		FOREIGN KEY (`ull_flow_app_id`)
		REFERENCES `ull_flow_app` (`id`),
	INDEX `ull_flow_doc_FI_2` (`ull_flow_action_id`),
	CONSTRAINT `ull_flow_doc_FK_2`
		FOREIGN KEY (`ull_flow_action_id`)
		REFERENCES `ull_flow_action` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_field
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_field`;


CREATE TABLE `ull_flow_field`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ull_flow_app_id` INTEGER,
	`ull_field_id` INTEGER,
	`caption_i18n_default` VARCHAR(32),
	`sequence` INTEGER,
	`enabled` INTEGER,
	`mandatory` INTEGER,
	`is_title` INTEGER,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `ull_flow_field_FI_1` (`ull_flow_app_id`),
	CONSTRAINT `ull_flow_field_FK_1`
		FOREIGN KEY (`ull_flow_app_id`)
		REFERENCES `ull_flow_app` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_field_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_field_i18n`;


CREATE TABLE `ull_flow_field_i18n`
(
	`caption_i18n` VARCHAR(32),
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `ull_flow_field_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_field` (`id`)
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_memory
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_memory`;


CREATE TABLE `ull_flow_memory`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ull_flow_doc_id` INTEGER,
	`ull_flow_step_id` INTEGER,
	`ull_flow_action_id` INTEGER,
	`comment` VARCHAR(255),
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `ull_flow_memory_FI_1` (`ull_flow_doc_id`),
	CONSTRAINT `ull_flow_memory_FK_1`
		FOREIGN KEY (`ull_flow_doc_id`)
		REFERENCES `ull_flow_doc` (`id`),
	INDEX `ull_flow_memory_FI_2` (`ull_flow_step_id`),
	CONSTRAINT `ull_flow_memory_FK_2`
		FOREIGN KEY (`ull_flow_step_id`)
		REFERENCES `ull_flow_step` (`id`),
	INDEX `ull_flow_memory_FI_3` (`ull_flow_action_id`),
	CONSTRAINT `ull_flow_memory_FK_3`
		FOREIGN KEY (`ull_flow_action_id`)
		REFERENCES `ull_flow_action` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_step
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_step`;


CREATE TABLE `ull_flow_step`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ull_flow_app_id` INTEGER,
	`slug` VARCHAR(32),
	`caption_i18n_default` VARCHAR(32),
	`is_start` INTEGER,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `ull_flow_step_FI_1` (`ull_flow_app_id`),
	CONSTRAINT `ull_flow_step_FK_1`
		FOREIGN KEY (`ull_flow_app_id`)
		REFERENCES `ull_flow_app` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_step_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_step_i18n`;


CREATE TABLE `ull_flow_step_i18n`
(
	`caption_i18n` VARCHAR(32),
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `ull_flow_step_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_flow_step` (`id`)
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_step_action
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_step_action`;


CREATE TABLE `ull_flow_step_action`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ull_flow_step_id` INTEGER,
	`ull_flow_action_id` INTEGER,
	`options` VARCHAR(255),
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `ull_flow_step_action_FI_1` (`ull_flow_step_id`),
	CONSTRAINT `ull_flow_step_action_FK_1`
		FOREIGN KEY (`ull_flow_step_id`)
		REFERENCES `ull_flow_step` (`id`),
	INDEX `ull_flow_step_action_FI_2` (`ull_flow_action_id`),
	CONSTRAINT `ull_flow_step_action_FK_2`
		FOREIGN KEY (`ull_flow_action_id`)
		REFERENCES `ull_flow_action` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_flow_value
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_flow_value`;


CREATE TABLE `ull_flow_value`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ull_flow_doc_id` INTEGER,
	`ull_flow_field_id` INTEGER,
	`ull_flow_memory_id` INTEGER,
	`current` INTEGER,
	`value` TEXT,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	INDEX `ull_flow_value_FI_1` (`ull_flow_doc_id`),
	CONSTRAINT `ull_flow_value_FK_1`
		FOREIGN KEY (`ull_flow_doc_id`)
		REFERENCES `ull_flow_doc` (`id`),
	INDEX `ull_flow_value_FI_2` (`ull_flow_field_id`),
	CONSTRAINT `ull_flow_value_FK_2`
		FOREIGN KEY (`ull_flow_field_id`)
		REFERENCES `ull_flow_field` (`id`),
	INDEX `ull_flow_value_FI_3` (`ull_flow_memory_id`),
	CONSTRAINT `ull_flow_value_FK_3`
		FOREIGN KEY (`ull_flow_memory_id`)
		REFERENCES `ull_flow_memory` (`id`)
)Type=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
