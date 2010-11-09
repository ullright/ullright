<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

$t = new lime_test(16, new lime_output_color);

$validator = new ullValidatorPhoneNumber(array('default_country_code' => '+43'));

$t->diag('__construct()');
  $t->isa_ok($validator, 'ullValidatorPhoneNumber', 'Returns the correct object');

$t->diag('clean()');
  $t->is($validator->clean('+43 664 1235678'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('+43 664 123 56 78'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('+43 664/1235678'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('+43 (664) 1235678'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('0043 (664) 1235678'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('(+43) (664)/123 56 78'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('(0664)/(123 56 78)'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('0664-123 56 78'), '+43 664 1235678', 'Returns the correct value');
  
  try
  {
    $validator->clean('0664 ab1235678');
    $t->fail('Doesn\'t throw an exeption for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Throws an exeption for an invalid value');
  }
  
  try
  {
    $validator->clean('+436641235678');
    $t->fail('Doesn\'t throw an exeption for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Throws an exeption for an invalid value');
  }
  
$validator = new ullValidatorPhoneNumber();

$t->diag('__construct()');
  $t->isa_ok($validator, 'ullValidatorPhoneNumber', 'Returns the correct object');

$t->diag('clean()');
  $t->is($validator->clean('+43 664 1235678'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('0043 (664) 1235678'), '+43 664 1235678', 'Returns the correct value');
  $t->is($validator->clean('(+43) (664)/123 56 78'), '+43 664 1235678', 'Returns the correct value');
  
  try
  {
    $validator->clean('0664 1235678');
    $t->fail('Doesn\'t throw an exeption for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Throws an exeption for an invalid value');
  }
  