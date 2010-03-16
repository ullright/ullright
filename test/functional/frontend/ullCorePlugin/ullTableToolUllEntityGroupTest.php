<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$createValues = array(
	'ull_entity_id' => array(Doctrine::getTable('UllUser')->findOneByUserName('test_user')->id, 'Test User'), 
	'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('Everyone')->id, 'Everyone')
);
$editValues = array(
	'ull_entity_id' => array(Doctrine::getTable('UllUser')->findOneByUserName('admin')->id, 'Master Admin'), 
	'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup')->id, 'TestGroup')
);

$b = new ullTableToolTestBrowser(
	'UllEntityGroup', 
	'Group memberships', 
	'Manage Group memberships', 
  $createValues, 
  $editValues, 
  3, 
  'getDgsUllTableToolUllEntityGroupList', 
  $configuration,
  'created_at',
  false
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();
$b->executeTest();


