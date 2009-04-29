<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findByDocAndSlug()');
  $value = UllFlowValueTable::findByDocIdAndSlug(1,'my_subject');
  $t->is($value->value, 'My first trouble ticket', 'returns the correct value');
  
  try
  {
    UllFlowValueTable::findByDocIdAndSlug(666, 'my_email');
    $t->fail('doesn\'t throw an exception for an invalid ull_flow_doc_id');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for a invalid ull_flow_doc_id');
  }
  
  try
  {
    UllFlowValueTable::findByDocIdAndSlug(2, 'invalid_column_foo_bar');
    $t->fail('doesn\'t throw an exception for an invalid slug');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for a invalid slug');
  }  