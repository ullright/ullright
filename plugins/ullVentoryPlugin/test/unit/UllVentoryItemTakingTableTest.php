<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findByItemAndTaking()');

  $t->is(UllVentoryItemTakingTable::findByItemAndTaking(new UllVentoryItem, new UlLVentoryTaking), false, 'Returns false for inexistant params');
  
  $item = Doctrine::getTable('UllVentoryItem')->findOneByInventoryNumber('1701');
  $taking = Doctrine::getTable('UllVentoryTaking')->findOneByName('Summer 2009');
  
  $item->UllVentoryItemTaking[]->UllVentoryTaking = $taking;
  $item->UllVentoryItemTaking->save();
  
  $t->is(UllVentoryItemTakingTable::findByItemAndTaking($item, $taking)->id, 1, 'Returns one entry');
  
