<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'name'
      )
    );

$b = new ullTableToolTestBrowser(
  'UllNewsletterLayout', 
  'Newsletter layout', 
  'Manage Newsletter layout', 
  2, 
  $selector, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'name' => 'Test layout', 
  'html_body' => array('This is a test', false),
  'html_head' => array('<style>h1 {color: red};</style>"', false)
);
$editValues = array(
  'name' => 'Test layout update', 
  'html_body' => array('Another test', false),
  'html_head' => array('<style>h1 {color: green};</style>"', false)
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


