<?php

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'cli', true);
sfContext::createInstance($configuration);

$databaseManager = new sfDatabaseManager($configuration);
$databaseManager->loadConfiguration();
$connection = Propel::getConnection();

/* db update */

//$query = '';
//$result = mysql_query($query);
//if (!$result) {
//   echo "error: ", mysql_error();
//}


$query = 'CREATE TABLE `ull_access_group`
(
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `useless_flag` BOOLEAN, 
  PRIMARY KEY (`id`)
)Type=MyISAM;';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'CREATE TABLE `ull_access_group_group`
(
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `ull_access_group_id` INTEGER,
  `ull_group_id` INTEGER,
  `read_flag` INTEGER,
  `write_flag` INTEGER,
  PRIMARY KEY (`id`),
  INDEX `ull_access_group_group_FI_1` (`ull_access_group_id`),
  CONSTRAINT `ull_access_group_group_FK_1`
    FOREIGN KEY (`ull_access_group_id`)
    REFERENCES `ull_access_group` (`id`),
  INDEX `ull_access_group_group_FI_2` (`ull_group_id`),
  CONSTRAINT `ull_access_group_group_FK_2`
    FOREIGN KEY (`ull_group_id`)
    REFERENCES `ull_group` (`id`)
)Type=MyISAM;';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_group` ADD `system` BOOLEAN AFTER `email`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_doc` ADD `read_ull_group_id` INTEGER AFTER `custom_field1`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_doc` ADD `write_ull_group_id` INTEGER AFTER `read_ull_group_id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_app` ADD `icon` VARCHAR(256) AFTER `doc_caption_i18n_default`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}



$query = 'ALTER TABLE `ull_flow_app` ADD `ull_access_group_id` INTEGER AFTER `icon`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_field` ADD `ull_access_group_id` INTEGER AFTER `is_custom_field1`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}