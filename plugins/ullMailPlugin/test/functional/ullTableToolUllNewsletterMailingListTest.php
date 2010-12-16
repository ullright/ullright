<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name',
        'description',
        'is_active'
      )
    );

$b = new ullTableToolTestBrowser(
  'UllNewsletterMailingList', 
  'Newsletter mailing list', 
  'Manage Newsletter mailing list', 
  2, 
  $selector, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'name' => 'Test mailing list', 
  'description' => 'This is a test',
  'is_active' => array(true, 'Checkbox_checked')
);
$editValues = array(
  'name' => 'Test mailing list 2', 
  'description' => 'Another test',
  'is_active' => array(false, 'Checkbox_unchecked')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


