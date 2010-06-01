<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'ull_flow_step_id',
        'ull_flow_action_id',
        'options',
        'sequence'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllFlowStepAction', 
	'Actions for Steps', 
	'Manage Actions for Steps', 
  9, 
  $selector, 
  $configuration,
  array('order' => 'created_at', 'desc' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'ull_flow_step_id' => array(Doctrine::getTable('UllFlowStep')->findOneBySlug('todo_creator')->id, 'Creator (Todo list)'), 
  'ull_flow_action_id' => array(Doctrine::getTable('UllFlowAction')->findOneBySlug('send')->id, 'Sent'), 
  'options' => 'Foo-Option',
  'sequence' => array('1234', '1234')
);

$editValues = array(
  'ull_flow_step_id' => array(Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_closed')->id, 'Closed (Trouble ticket tool)'), 
  'ull_flow_action_id' => array(Doctrine::getTable('UllFlowAction')->findOneBySlug('save')->id, 'Saved'), 
  'options' => 'Foobar',
  'sequence' => array('543', '543')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


