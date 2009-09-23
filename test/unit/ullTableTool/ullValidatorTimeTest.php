<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(3, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);

$v = new ullValidatorTime;

$t->diag('clean()');
  try
  {
    $v->clean('25:01:00');
    $t->fail('Doesn\' throw an exception for invalid input');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception for invalid input');
  }
  
  try
  {
    $v->clean('11:61:00');
    $t->fail('Doesn\' throw an exception for invalid input');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception for invalid input');
  }  
 
  $t->is($v->clean('1:00'), '01:00:00', 'returns correct result');
