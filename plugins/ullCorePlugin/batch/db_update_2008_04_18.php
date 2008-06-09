<?php

define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/../../..'));
define('SF_APP',         'myApp');
define('SF_ENVIRONMENT', 'cli');
define('SF_DEBUG',       true);

// get configuration
require_once SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';

// get database connection
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();
$connection = Propel::getConnection();

/* db update */

//$query = '';
//$result = mysql_query($query);
//if (!$result) {
//   echo "error: ", mysql_error();
//}

$query = 'CREATE TABLE `sf_tag`
(
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100),
  `is_triple` INTEGER,
  `triple_namespace` VARCHAR(100),
  `triple_key` VARCHAR(100),
  `triple_value` VARCHAR(100),
  PRIMARY KEY (`id`),
  KEY `name`(`name`),
  KEY `triple1`(`triple_namespace`),
  KEY `triple2`(`triple_key`),
  KEY `triple3`(`triple_value`)
)Type=MyISAM;';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'CREATE TABLE `sf_tagging`
(
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `tag_id` INTEGER  NOT NULL,
  `taggable_model` VARCHAR(30),
  `taggable_id` INTEGER,
  PRIMARY KEY (`id`),
  KEY `tag`(`tag_id`),
  KEY `taggable`(`taggable_model`, `taggable_id`),
  CONSTRAINT `sf_tagging_FK_1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `sf_tag` (`id`)
    ON DELETE CASCADE
)Type=MyISAM;';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}