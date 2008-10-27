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

$t->begin('findByAppIdAndSlug()');
  $cc = UllFlowColumnConfigTable::findByAppIdAndSlug(1, 'my_email');
  $t->isa_ok($cc, 'UllFlowColumnConfig', 'returns the correct object');
  $t->is($cc->id, 3, 'returns the correct id');
  
  try
  {
    UllFlowColumnConfigTable::findByAppIdAndSlug(666, 'my_email');
    $t->fail('doesn\'t throw an exception for an invalid ull_flow_app_id');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for a invalid ull_flow_app_id');
  }
  
  try
  {
    UllFlowColumnConfigTable::findByAppIdAndSlug(1, 'invalid_column_foo_bar');
    $t->fail('doesn\'t throw an exception for an invalid slug');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for a invalid slug');
  }
  