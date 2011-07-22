<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'ull_flow_app_id',
        'ull_group_id',
        'ull_privilege_id',
      )
    );

$b = new ullTableToolTestBrowser(
	'UllFlowAppAccess', 
	'Workflow access rights', 
	'Manage Workflow access rights', 
  3, 
  $selector, 
  $configuration,
  array('order' => 'created_at', 'desc' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'ull_flow_app_id' => array(Doctrine::getTable('UllFlowApp')->findOneBySlug('trouble_ticket')->id, 'Trouble ticket tool'), 
  'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup')->id, 'TestGroup'),
	'ull_privilege_id' => array(Doctrine::getTable('UllPrivilege')->findOneBySlug('read')->id, 'read') 
);

$editValues = array(
  'ull_flow_app_id' => array(Doctrine::getTable('UllFlowApp')->findOneBySlug('todo')->id, 'Todo list'),
  'ull_group_id' => array(Doctrine::getTable('UllGroup')->findOneByDisplayName('MasterAdmins')->id, 'MasterAdmins'),
  'ull_privilege_id' => array(Doctrine::getTable('UllPrivilege')->findOneBySlug('write')->id, 'write')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


