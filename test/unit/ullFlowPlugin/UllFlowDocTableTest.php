<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->diag('queryAccess()');
  $q = new Doctrine_Query;
  $t->isa_ok(UllFlowDocTable::queryAccess($q, new UllFlowApp), 'Doctrine_Query', 'returns the correct object');
  
  try
  {
    UllFlowDocTable::queryAccess($q, new UllFlowDoc);
    $t->fail('doesn\'t throw an exception if any other object than UllFlowApp is given');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception if any other object than UllFlowApp is given');
  }
  
$t->diag('hasId()');
  $t->ok(UllFlowDocTable::hasId(1), 'returns true for an existing id');
  $t->ok(!UllFlowDocTable::hasId(666), 'returns false for a non existing id');
  
$t->diag('hasVirtualColumn()');
  $t->ok(UllFlowDocTable::hasVirtualColumn(1, 'my_email'), 'returns true for an existing slug');
  $t->ok(!UllFlowDocTable::hasVirtualColumn(1, 'foobar'), 'returns false for a non existing slug');  

