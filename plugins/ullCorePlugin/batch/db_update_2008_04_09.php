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

