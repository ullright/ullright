<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('toggleInventoryTaking()');

  $item = Doctrine::getTable('UllVentoryItem')->findOneByInventoryNumber(1701);
  
  $t->is(count($item->UllVentoryItemTaking), 0, 'Item was not audited');
  
  $item->toggleInventoryTaking();
  
  $t->is(count($item->UllVentoryItemTaking), 1, 'Item was audited');
  
  
