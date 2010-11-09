<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'label_translation_en',
        'ull_flow_app_id',
        'is_start'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllFlowStep', 
	'Steps', 
	'Manage Steps', 
  7, 
  $selector, 
  $configuration,
  array('order' => 'label')
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'label_translation_en' => 'Test', 
  'ull_flow_app_id' => array(Doctrine::getTable('UllFlowApp')->findOneBySlug('trouble_ticket')->id, 'Trouble ticket tool'), 
  'is_start' => array(true, 'Checkbox_checked')
);

$editValues = array(
  'label_translation_en' => 'Foo', 
  'ull_flow_app_id' => array(Doctrine::getTable('UllFlowApp')->findOneBySlug('todo')->id, 'Todo list'), 
  'is_start' => array(false, 'Checkbox_unchecked')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


