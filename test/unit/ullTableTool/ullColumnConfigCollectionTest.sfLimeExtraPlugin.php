<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';


//$t = new myTestCase(1, new lime_output_color, $configuration);

$t = new lime_test_simple(1, new lime_output_color, $configuration);

// @BeforeAll
$collection = new ullColumnConfigCollection;

// @Before
$columnConfig = new ullColumnConfiguration;

//Before
//$columnConfig = lime_mock::create('ullColumnConfiguration');

// @Test: __construct()
  $t->isa_ok($collection, 'ullColumnConfigCollection', 'returns the correct object');
  $t->is($collection instanceof ArrayAccess, true, 'implements ArrayAccess');
  
// @Test: offsetSet()
  $t->expect('InvalidArgumentException');
  $collection->offsetSet('my_email', 'foobar');
//  try
//  {
//    $collection->offsetSet('my_email', 'foobar');
//    $t->fail('Allows giving anything else than a ullColumnConfiguration object as value');
//  }
//  catch (Exception $e)
//  {
//    $t->pass('given value is a ullColumnConfiguration object');
//  }


// @Test: offsetSet()
  $t->is($collection->offsetSet('my_email', $columnConfig), null, 'sets an offset');
  




