<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new myTestCase(12, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('toggleInventoryTaking()');

  $item = Doctrine::getTable('UllVentoryItem')->findOneByInventoryNumber(1701);
  
  $t->is(count($item->UllVentoryItemTaking), 0, 'Item was not audited');
  $t->is(count($item->UllVentoryItemMemory), 4, 'Correct number of memories');
  $t->is($item->hasLatestInventoryTaking(), false, 'Was not verified for the latest Inventory taking');
  
  $item->toggleInventoryTaking();
  
  $t->is(count($item->UllVentoryItemTaking), 1, 'Item was audited');
  $t->is(count($item->UllVentoryItemMemory), 5, 'Correct number of memories');
  $t->is($item->UllVentoryItemMemory[4]->comment, 'Audited during inventory taking "Summer 2009"', 'Added memory with correct comment');
  $t->is($item->hasLatestInventoryTaking(), true, 'Was verified for the latest Inventory taking');
  
  $item->toggleInventoryTaking();
  
  $t->is(count($item->UllVentoryItemTaking), 0, 'Item auditing was removed');  
  $t->is(count($item->UllVentoryItemMemory), 6, 'Correct number of memories');
  $t->is($item->UllVentoryItemMemory[5]->comment, 'Audit for inventory taking "Summer 2009" withdrawn', 'Added memory with correct comment');
  $t->is($item->hasLatestInventoryTaking(), false, 'Was not verified for the latest Inventory taking');  

$t->diag('__toString');

  $item = Doctrine::getTable('UllVentoryItem')->findOneByInventoryNumber(1701);
  $t->is((string) $item, 'Notebook Apple MacBook (1701)', '__toString returns the correct String');