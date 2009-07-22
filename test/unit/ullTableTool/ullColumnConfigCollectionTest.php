<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
}

$t = new myTestCase(27, new lime_output_color, $configuration);

$collection = new ullColumnConfigCollection;

$columnConfig = new ullColumnConfiguration;
$columnConfig->setColumnName('my_email');

$t->diag('__construct()');

  $t->isa_ok($collection, 'ullColumnConfigCollection', 'Returns the correct object');
  $t->is($collection instanceof ArrayAccess, true, 'Implements ArrayAccess');
  $t->is($collection->getAction(), 'edit', 'Sets the correct default action');
  
$t->diag('offsetSet()');
  try
  {
    $collection->offsetSet('my_email', 'foobar');
    $t->fail('Doesn\'t throw an exception when giving anything else than a ullColumnConfiguration object as value');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when giving anything else than a ullColumnConfiguration object as value');
  }
  $t->is($collection->offsetSet('my_email', $columnConfig), null, 'Sets an offset');
  
  
$t->diag('offsetExists()');
  $t->is($collection->offsetExists('my_email'), true, 'Returns true for an existing offset');  
  $t->is($collection->offsetExists('blubb'), false, 'Returns false for an non-existing offset');

  
$t->diag('offsetGet()');
  $t->is($collection->offsetGet('my_email')->getColumnName(), 'my_email', 'Returns the correct offset');
  try
  {
    $collection->offsetGet('blubb');
    $t->fail('Doesn\'t throw an exception when trying to get an invalid offset');    
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when trying to get an invalid offset');
  }  
  
  
$t->diag('offsetUnset()');
  $t->is($collection->offsetUnset('my_email'), null, 'Unsets an offset');
  $t->is($collection->offsetExists('my_email'), false, 'Offset was really removed');
  try
  {
    $collection->offsetUnset('my_email');
    $t->fail('Doesn\'t throw an exception when trying to unset an invalid offset');    
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when trying to unset an invalid offset');
  } 


$t->diag('Iterator');
  $collection = new ullColumnConfigCollection;
  $collection['one'] = new UllColumnConfiguration;
  $collection['one']->setColumnName('one');
  $k = null;
  $v = null;
  foreach($collection as $key => $value)
  {
    $k = $key;
    $v = $value;
  }
  $t->is($k, 'one', 'Checking key - the collection is iterable');
  $t->is($v->getColumnName(), 'one', 'Checking value - the collection is iterable');  
    
  
$t->diag('order()');
  $collection = new ullColumnConfigCollection;
  $collection['three'] = new UllColumnConfiguration; 
  $collection['one'] = new UllColumnConfiguration;
  $collection['one']->setColumnName('one');
  $collection['two'] = new UllColumnConfiguration;
  
  $order = array('one', 'two');
  
  $collection->order($order);
  
  $t->is($collection->getKeys(), array('one', 'two', 'three'), 'Orders the collection correctly');

$t->diag('Countable');
  $t->is(count($collection), 3 , 'count() returns the correct number');  
  
$t->diag('orderBottom()');
  $collection = new ullColumnConfigCollection;
  $collection['three'] = new UllColumnConfiguration; 
  $collection['one'] = new UllColumnConfiguration;
  $collection['two'] = new UllColumnConfiguration;    

  $order = array('two', 'three');
  
  $collection->orderBottom($order);
  
  $t->is($collection->getKeys(), array('one', 'two', 'three'), 'Orders the collection correctly');
  
  $t->diag('disable()');
  $collection->disable(array('three'));
  $t->is($collection['three']->getAccess(), false, 'Sets access to null');

$t->diag('create()');
  $collection->create('four');
  $t->is($collection['four']->getColumnName(), 'four', 'Creates a new columnConfig correctly');  
  
$t->diag('isXXXAction()');
  $cc = new ullColumnConfigCollection;
  $t->is($cc->isEditAction(), true, 'Default is edit action');
  $t->is($cc->isCreateAction(), false, 'Default is not create action');
  $t->is($cc->isListAction(), false, 'Default is not list action');
  $t->is($cc->isCreateOrEditAction(), true, 'True for create or edit');
  $cc->setAction('list');
  $t->is($cc->isEditAction(), false, 'List is not edit action');
  $t->is($cc->isCreateAction(), false, 'List is not create action');
  $t->is($cc->isListAction(), true, 'Is list action');
  $t->is($cc->isCreateOrEditAction(), false, 'List is neither create or edit action');
  