<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
$request = sfContext::getInstance()->getRequest();

$t = new lime_test(5, new lime_output_color);

$t->diag('sluggify()');

    $t->is(ullCoreTools::sluggify('foobar'), 'foobar', 'lowercase word stay the same');
    $t->is(ullCoreTools::sluggify('Foo bar'), 'foo_bar', 'correctly transformes spaces and uppercase letters');
    $t->is(ullCoreTools::sluggify('Föo bar#@$'), 'f__o_bar___', 'correctly transformes special chars');
  
    
$t->diag('order_array_by_array()');
  $test = array(
    'apple' => array('type' => 'fruit'),
    'orange' => 'Can be squashed',
    'pear' => array('type' => 'fruit', 'color' => 'yellow-green')
  );
  
  $reference = array(
    'orange' => 'Can be squashed',
    'pear' => array('type' => 'fruit', 'color' => 'yellow-green'),
    'apple' => array('type' => 'fruit')
  );
  
  $t->is(ullCoreTools::orderArrayByArray($test, array('orange', 'pear')), $reference, 'Orders the given array correctly');
  try
  {
    ullCoreTools::orderArrayByArray($test, array('foobar'));
    $t->fail('Doesn\'t throw an exception for an invalid key');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception for an invalid key');
  }