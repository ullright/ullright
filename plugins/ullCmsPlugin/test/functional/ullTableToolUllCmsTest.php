<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

//TODO: write normal test, too specific for tabletool test

/*
$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'title_translation_en',
        'parent_ull_cms_item_id',
        'is_active'
      )
    );

$b = new ullTableToolTestBrowser(
  'ullCms', 
  'Pages', 
  'Manage Pages', 
  10, 
  $selector, 
  $configuration,
  array('order' =>' title', 'no_delete' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'title_translation_en' => 'Test page', 
  'body_translation_en' => array('Test body', false),
  'name_translation_en' => array('Test name', false),
  'is_active' => array(true, 'Checkbox_checked'),
  'parent_ull_cms_item_id' => array(1, false),
);
$editValues = array(
  'title_translation_en' => 'Next Page', 
  'body_translation_en' => array('Test body', false),
  'name_translation_en' => array('Next name', false),
  'is_active' => array(false, 'Checkbox_unchecked'),
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();

*/
