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


$query = 'ALTER TABLE `ull_flow_memory` ADD `creator_group_id` INTEGER AFTER `comment`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_step_action` ADD `sequence` INTEGER AFTER `options`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}

$query = 'ALTER TABLE `ull_flow_action` ADD `comment_is_mandatory` INTEGER AFTER `show_assigned_to`';
$result = mysql_query($query);
if (!$result) {
   echo "error: ", mysql_error();
}
