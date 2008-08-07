<?php

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('myApp', 'cli', true);
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


