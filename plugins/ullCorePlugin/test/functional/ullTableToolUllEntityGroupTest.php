<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'ull_entity_id',
        'ull_group_id'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllEntityGroup', 
	'Group memberships', 
	'Manage Group memberships', 
  3, 
  $selector, 
  $configuration,
  array('order' => 'created_at', 'desc' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'ull_entity_id' => array(Doctrine::getTable('UllUser')->findOneByUserName('test_user')->id, 'Test User'), 
	'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('Everyone')->id, 'Everyone')
);
$editValues = array(
	'ull_entity_id' => array(Doctrine::getTable('UllUser')->findOneByUserName('admin')->id, 'Master Admin'), 
	'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup')->id, 'TestGroup')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


