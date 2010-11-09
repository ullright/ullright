<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(1, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findLatest()');

  $taking = new UllVentoryTaking;
  $taking->name = 'TestTaking';
  $taking->save();

  $t->is(UllVentoryTakingTable::findLatest()->name, 'TestTaking', 'Returns the correct inventory taking');
  
