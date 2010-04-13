<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$b = new ullTableToolTestBrowser(
	'UllGroupPermission', 
	'Group permissions', 
	'Manage Group permissions', 
  10, 
  'getDgsUllTableToolUllGroupPermissionList', 
  $configuration,
  'created_at',
  false
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array( 
	'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('Everyone')->id, 'Everyone'),
	'ull_permission_id' => array(Doctrine::getTable('UllPermission')->findOneBySlug('testPermission')->id, 'testPermission'),
);
$editValues = array(
	'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup')->id, 'TestGroup'),
	'ull_permission_id' => array(Doctrine::getTable('UllPermission')->findOneBySlug('ull_user_show')->id, 'ull_user_show'),
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


