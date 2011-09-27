<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'ull_group_id',
        'ull_permission_id'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllGroupPermission', 
	'Group permissions', 
	'Manage Group permissions', 
  26, 
  $s, 
  $configuration,
  array('order' => 'created_at', 'desc' => true)
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


