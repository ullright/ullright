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

$query = 'ALTER TABLE `ull_flow_action` ADD `notify_creator` BOOLEAN AFTER `status_only`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

// add options to field/column infos
$query = 'UPDATE `ull_flow_action` SET notify_creator=true WHERE slug="close"';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


$query = 'ALTER TABLE `ull_flow_action` ADD `notify_next` BOOLEAN AFTER `notify_creator`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

// add options to field/column infos
$query = 'UPDATE `ull_flow_action` SET notify_next=true WHERE slug in ("send", "assign_to_user", "assign_to_group")';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


$query = 'ALTER TABLE `ull_flow_action` ADD `show_assigned_to` BOOLEAN AFTER `in_resultlist_by_default`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

// add options to field/column infos
$query = 'UPDATE `ull_flow_action` SET show_assigned_to=true WHERE slug in ("assign_to_user", "assign_to_group")';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}




$query = 'ALTER TABLE `ull_flow_action` ADD `disable_validation` BOOLEAN AFTER `status_only`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

// add options to field/column infos
$query = 'UPDATE `ull_flow_action` SET disable_validation=true WHERE slug="save_close"';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


$query = 'ALTER TABLE `ull_flow_app` ADD `doc_caption_i18n_default` VARCHAR(32) AFTER  `caption_i18n_default`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_app_i18n` ADD `doc_caption_i18n` VARCHAR(32) AFTER  `caption_i18n`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


$query = 'ALTER TABLE `ull_flow_memory` ADD `assigned_to_ull_user_id` VARCHAR(32) AFTER  `ull_flow_action_id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_memory` ADD `assigned_to_ull_group_id` VARCHAR(32) AFTER  `assigned_to_ull_user_id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}


$query = 'ALTER TABLE `ull_group` ADD `email` VARCHAR(64) AFTER  `caption`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

