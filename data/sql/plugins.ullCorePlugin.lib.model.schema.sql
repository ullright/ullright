
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- ull_column_info
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_column_info`;


CREATE TABLE `ull_column_info`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`db_table_name` VARCHAR(32),
	`db_column_name` VARCHAR(32),
	`ull_field_id` INTEGER,
	`enabled` INTEGER,
	`show_in_list` INTEGER,
	`mandatory` INTEGER,
	`caption_i18n_default` VARCHAR(64),
	`description_i18n_default` TEXT,
	PRIMARY KEY (`id`),
	INDEX `ull_column_info_FI_1` (`ull_field_id`),
	CONSTRAINT `ull_column_info_FK_1`
		FOREIGN KEY (`ull_field_id`)
		REFERENCES `ull_field` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_column_info_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_column_info_i18n`;


CREATE TABLE `ull_column_info_i18n`
(
	`caption_i18n` VARCHAR(64),
	`description_i18n` TEXT,
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `ull_column_info_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_column_info` (`id`)
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_culture
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_culture`;


CREATE TABLE `ull_culture`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`iso_code` VARCHAR(7) default 'null' NOT NULL,
	`name` VARCHAR(32),
	PRIMARY KEY (`id`),
	KEY `culture_iso_code_index`(`iso_code`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_field
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_field`;


CREATE TABLE `ull_field`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`field_type` VARCHAR(32),
	`caption_i18n_default` VARCHAR(64),
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_field_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_field_i18n`;


CREATE TABLE `ull_field_i18n`
(
	`caption_i18n` VARCHAR(64),
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `ull_field_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_field` (`id`)
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_group
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_group`;


CREATE TABLE `ull_group`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`caption` VARCHAR(128),
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_location
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_location`;


CREATE TABLE `ull_location`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`short` VARCHAR(8),
	`company_id` INTEGER,
	`street` VARCHAR(255),
	`zip` VARCHAR(8),
	`city` VARCHAR(64),
	`country_id` INTEGER,
	`phone_trunk_num` VARCHAR(32),
	`phone_std_ext_num` VARCHAR(8),
	`fax_trunk_num` VARCHAR(32),
	`fax_std_ext_num` VARCHAR(8),
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_table_info
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_table_info`;


CREATE TABLE `ull_table_info`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`db_table_name` VARCHAR(32),
	`caption_i18n_default` VARCHAR(64),
	`description_i18n_default` TEXT,
	`sort_fields` VARCHAR(256),
	`search_fields` VARCHAR(256),
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_table_info_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_table_info_i18n`;


CREATE TABLE `ull_table_info_i18n`
(
	`caption_i18n` VARCHAR(64),
	`description_i18n` TEXT,
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `ull_table_info_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `ull_table_info` (`id`)
		ON DELETE CASCADE
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_user
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_user`;


CREATE TABLE `ull_user`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(64),
	`last_name` VARCHAR(64),
	`email` VARCHAR(64),
	`username` VARCHAR(32),
	`password` VARCHAR(40),
	`location_id` INTEGER,
	`user_type` INTEGER,
	`birthday` DATE,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	KEY `user_FI_1`(`location_id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- ull_user_group
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_user_group`;


CREATE TABLE `ull_user_group`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`ull_user_id` INTEGER  NOT NULL,
	`ull_group_id` INTEGER  NOT NULL,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	KEY `user_group_FI_1`(`user_id`),
	KEY `user_group_FI_2`(`group_id`)
)Type=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
