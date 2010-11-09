<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name_translation_en'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllEmploymentType', 
	'Employment types', 
	'Manage Employment types', 
  4, 
  $selector, 
  $configuration,
  array('order' => 'name')
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array('name_translation_en' => 'Foo');
$editValues = array('name_translation_en' => 'Bar');

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


