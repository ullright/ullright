<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/functional.php';

$selector = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete',      
        'label_translation_en',
        'is_status_only',
        'is_enable_validation',
        'is_notify_creator',
        'is_notify_next',
        'is_in_resultlist',
        'is_show_assigned_to',
        'is_comment_mandatory'
      )
    );

$b = new ullTableToolTestBrowser(
	'UllFlowAction', 
	'Actions', 
	'Manage Actions', 
  12, 
  $selector, 
  $configuration
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
	'label_translation_en' => 'Foo',
  'is_status_only' => array(true, 'Checkbox_checked'),
	'is_enable_validation' => array(true, 'Checkbox_checked'),
  'is_notify_creator' => array(false, 'Checkbox_unchecked'),
  'is_notify_next' => array(true, 'Checkbox_checked'),
  'is_in_resultlist' => array(false, 'Checkbox_unchecked'),
  'is_show_assigned_to' => array(false, 'Checkbox_unchecked'),
  'is_comment_mandatory' => array(true, 'Checkbox_checked')
);

$editValues = array(
  'label_translation_en' => 'Bar',
  'is_status_only' => array(false, 'Checkbox_unchecked'),
  'is_enable_validation' => array(false, 'Checkbox_unchecked'),
  'is_notify_creator' => array(false, 'Checkbox_unchecked'),
  'is_notify_next' => array(false, 'Checkbox_unchecked'),
  'is_in_resultlist' => array(true, 'Checkbox_checked'),
  'is_show_assigned_to' => array(true, 'Checkbox_checked'),
  'is_comment_mandatory' => array(false, 'Checkbox_unchecked')
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


