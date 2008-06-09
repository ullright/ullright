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

$query = 'ALTER TABLE `ull_flow_doc` ADD `priority` INTEGER AFTER `assigned_to_ull_flow_step_id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_doc` ADD `deadline` DATETIME AFTER `priority`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_doc` ADD `custom_field1` VARCHAR(255) AFTER `deadline`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_field` ADD `is_priority` BOOLEAN AFTER `is_title`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_field` ADD `is_deadline` BOOLEAN AFTER `is_priority`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_field` ADD `is_custom_field1` BOOLEAN AFTER `is_deadline`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


