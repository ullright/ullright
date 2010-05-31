<?php

include dirname(__FILE__) . '/../../../bootstrap/functional.php';

$s = new ullDomGridSelector('table.list_table > tbody', 'tr', 'td', array(),
      array(
        'edit_delete', 
        'ull_ventory_software_id',
        'license_key',
        'quantity',
        'supplier',
        'delivery_date',
        'comment',
      )
    );

$b = new ullTableToolTestBrowser(
	'UllVentorySoftwareLicense', 
	'Software licenses', 
	'Manage Software licenses', 
  2, 
  $s, 
  $configuration,
  array('order' => 'license_key')
);
$path = dirname(__FILE__);
$b->setFixturesPath($path);
$b->resetDatabase();

$createValues = array(
  'ull_ventory_software_id' => array(Doctrine::getTable('UllVentorySoftware')->findOneByName('Adobe Writer 6.0')->id, 'Adobe Writer 6.0'),
  'license_key' => '123_abc',
  'quantity' => array('5', '5'),
  'supplier' => 'supplier',
  'delivery_date' => array('01/01/2010', '01/01/2010'),
  'comment' => 'foo'
);
$editValues = array(
  'ull_ventory_software_id' => array(Doctrine::getTable('UllVentorySoftware')->findOneByName('Microsoft Windows 7')->id, 'Microsoft Windows 7'),
  'license_key' => 'abc_123',
  'quantity' => array('15', '15'),
  'supplier' => 'supplier_B',
  'delivery_date' => array('01/01/2009', '01/01/2009'),
  'comment' => 'foo-bar'
);

$b->setCreateValues($createValues);
$b->setEditValues($editValues);
$b->executeTest();


