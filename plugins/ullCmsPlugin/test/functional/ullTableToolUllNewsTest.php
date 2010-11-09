<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'title_translation_en',
        'link_name_translation_en',
        'link_url',
        'activation_date'
      )
    );

$b = new ullTableToolTestBrowser(
  'ullNews', 
  'News', 
  'Manage News entries', 
  2, 
  $selector, 
  $configuration,
  array('order' =>' title', 'no_delete' => true)
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'title_translation_en' => 'Test page', 
  'abstract_translation_en' => array('Test body', false),
  'link_name_translation_en' => 'Test link name',
  'link_url' => 'http://example.com',
  'activation_date' => array('05/15/2010', '05/15/2010')
);
$editValues = array(
  'title_translation_en' => 'Next Page', 
  'abstract_translation_en' => array('Next test body', false),
  'link_name_translation_en' => 'Test link name',
  'link_url' => 'http://example.com',
  'activation_date' => array('07/17/2010', '07/17/2010')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


