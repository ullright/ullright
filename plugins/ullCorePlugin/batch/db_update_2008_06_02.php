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


$query = 'ALTER TABLE `ull_flow_app` ADD `default_list_columns` VARCHAR(255) AFTER `ull_access_group_id`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}