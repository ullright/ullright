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


$query = 'ALTER TABLE `ull_flow_field` ADD `default_value` INT NULL AFTER `sequence`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}
