
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- ull_wiki
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ull_wiki`;


CREATE TABLE `ull_wiki`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`docid` INTEGER  NOT NULL,
	`current` INTEGER,
	`culture` VARCHAR(7),
	`body` TEXT,
	`subject` VARCHAR(255),
	`changelog_comment` VARCHAR(255),
	`read_counter` INTEGER,
	`edit_counter` INTEGER,
	`locked_by_user_id` INTEGER,
	`locked_at` DATETIME,
	`creator_user_id` INTEGER,
	`created_at` DATETIME,
	`updator_user_id` INTEGER,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`),
	KEY `wiki_docid_index`(`docid`)
)Type=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
