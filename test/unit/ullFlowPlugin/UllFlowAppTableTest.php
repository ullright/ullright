<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findBySlug()');

  try
  {
    UllFlowAppTable::findBySlug(array('fooBar'));
    $t->fail('doesn\'t throw an exception if slug is not a string');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception if slug is not a string');
  }

  $app = UllFlowAppTable::findBySlug('fooBar');
  $t->is($app, null, 'returns null for an unknown slug');
  
  $app = UllFlowAppTable::findBySlug('trouble_ticket');
  
  $t->isa_ok($app, 'UllFlowApp', 'returns the correct object');
  $t->is($app->label, 'Trouble ticket tool', 'return the correct label');