<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';



$b = new ullTableToolTestBrowser(
	'UllProjectManager', 
	'Project Managers', 
	'Manage Project Managers', 
  0, 
  'getDgsUllTableToolUllProjectManagerList', 
  $configuration,
  'created_at',
  false
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'ull_project_id' => array(Doctrine::getTable('UllProject')->findOneBySlug('introduce-ullright')->id, 'Introduce ullright'),  
	'ull_user_id' => array(Doctrine::getTable('UllUser')->findOneByUserName('test_user')->id, 'Test User')
);
$editValues = array(
	'ull_project_id' => array(Doctrine::getTable('UllProject')->findOneBySlug('meeting-room-furniture')->id, 'Meeting room furniture'),
	'ull_user_id' => array(Doctrine::getTable('UllUser')->findOneByUserName('admin')->id, 'Master Admin')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();

	
