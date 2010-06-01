<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'ull_flow_app_id',
        'ull_permission_id',
      )
    );

$b = new ullTableToolTestBrowser(
	'UllFlowAppPermission', 
	'Workflow access rights', 
	'Manage Workflow access rights', 
  1, 
  $selector, 
  $configuration,
  array('order' => 'created_at', 'desc' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'ull_flow_app_id' => array(Doctrine::getTable('UllFlowApp')->findOneBySlug('trouble_ticket')->id, 'Trouble ticket tool'), 
	'ull_permission_id' => array(Doctrine::getTable('UllPermission')->findOneBySlug('testpermission')->id, 'testPermission') 
);

$editValues = array(
  'ull_flow_app_id' => array(Doctrine::getTable('UllFlowApp')->findOneBySlug('todo')->id, 'Todo list'), 
  'ull_permission_id' => array(Doctrine::getTable('UllPermission')->findOneBySlug('ull_photo')->id, 'ull_photo')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


