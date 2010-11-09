<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
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
  
$t->diag('findSubjectColumnSlug()');
  $t->is(UllFlowColumnConfigTable::findSubjectColumnSlug(1), 'my_subject', 'Returns the correct slug');  
  
$t->diag('findPriorityColumnSlug()');
  $t->is(UllFlowColumnConfigTable::findPriorityColumnSlug(1), 'my_priority', 'Returns the correct slug');  
  