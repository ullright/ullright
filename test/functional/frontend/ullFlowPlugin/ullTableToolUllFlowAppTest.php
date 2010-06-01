<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'label_translation_en',
        'doc_label_translation_en',
        'list_columns',
        'is_public',
        'enter_effort'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllFlowApp', 
	'Workflows', 
	'Manage Workflows', 
  2, 
  $selector, 
  $configuration,
  array('order' => 'label')
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'label_translation_en' => 'Test', 
	'doc_label_translation_en' => 'Foo',
	'list_columns' => 'Bar',
  'is_public' => array(true, 'Checkbox_checked'),
  'enter_effort' => array(true, 'Checkbox_checked')
);

$editValues = array(
  'label_translation_en' => 'Tester', 
  'doc_label_translation_en' => 'Bar',
  'list_columns' => 'Foo',
  'is_public' => array(false, 'Checkbox_unchecked'),
  'enter_effort' => array(true, 'Checkbox_checked')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


