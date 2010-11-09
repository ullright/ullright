<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(5, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$item = Doctrine::getTable('UllVentoryItem')->findOneByInventoryNumber('1701');
$software = Doctrine::getTable('UllVentorySoftware')->findOneByName('Adobe Writer 6.0');

$t->begin('findByItemIdAndSoftwareId()');

  $t->is(UllVentoryItemSoftwareTable::findByItemIdAndSoftwareId($item->id, $software->id)->comment, 'Remember to update in October 2009', 'Returns the correct item software');
  $t->is(UllVentoryItemSoftwareTable::findByItemIdAndSoftwareId(666, 777), null, 'Returns null for invalid params');
  
$t->begin('findByItemIdAndSoftwareIdOrCreate()');
  
  $itemSoftware = UllVentoryItemSoftwareTable::findByItemIdAndSoftwareIdOrCreate(666, 777);
  $t->isa_ok($itemSoftware, 'UllVentoryItemSoftware', 'Returns the correct object');
  $t->is($itemSoftware->exists(), false, 'It\'s a new record');
  $t->is($itemSoftware->ull_ventory_software_id, 777, 'Sets the given software_id correctly');
