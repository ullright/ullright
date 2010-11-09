<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';


class myTestCase extends lime_test
{
}
// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(4, new lime_output_color);

$validator = new ullValidatorUsername();

$t->diag('__construct()');
  $t->isa_ok($validator, 'ullValidatorUsername', 'Returns the correct object');

$t->diag('clean()');
  $t->is($validator->clean('test_user'), 'test_user', 'Find correct username');
  $t->is($validator->clean('admin'), 'admin', 'Find correct username');
  
  try
  {
    $validator->clean('fail-username');
    $t->fail('Doesn\'t throw an exeption for an invalid username');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Throws an exeption for an invalid username');
  }
  