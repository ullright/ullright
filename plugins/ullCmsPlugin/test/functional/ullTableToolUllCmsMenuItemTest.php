<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name_translation_en',
        'is_active'
      )
    );

$b = new ullTableToolTestBrowser(
  'UllCmsMenuItem', 
  'Menu entries', 
  'Manage Menu entries', 
  20, 
  $selector, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'name_translation_en' => 'Test page', 
  'is_active' => array(true, 'Checkbox_checked')
);
$editValues = array(
  'name_translation_en' => 'Next Page', 
  'is_active' => array(false, 'Checkbox_unchecked')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


