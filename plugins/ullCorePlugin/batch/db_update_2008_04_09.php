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

$query = 'ALTER TABLE `ull_flow_action` ADD `in_resultlist_by_default` BOOLEAN AFTER `status_only`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

// add options to field/column infos
$query = 'UPDATE `ull_flow_action` SET in_resultlist_by_default=true WHERE slug <> "close"';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

