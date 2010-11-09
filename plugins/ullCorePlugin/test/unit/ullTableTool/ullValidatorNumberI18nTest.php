<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

$t = new lime_test(8, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);

$v = new ullValidatorNumberI18n();
$v->setOption('max_decimals', 3);

$t->ok($v->clean('01234.358') === '01234.358', 'returns correct number - en');
$t->ok($v->clean('-423,342.64') === '-423342.64', 'returns correct number - en');
$t->ok($v->clean('-423,342.') === '-423342.', 'returns correct number - en');

try {
  $v->clean('1.2345');
  $t->fail('Too many decimal places doesn\'t throw exception');
}
catch (sfValidatorError $ve)
{
  $t->pass('Too many decimal places throws exception');
}

$instance->getUser()->setCulture("de");

$t->ok($v->clean('-01234,35') === '-01234.35', 'returns correct number - de');
$t->ok($v->clean('423.342,64') === '423342.64', 'returns correct number - de');
$t->ok($v->clean('423.342.') === '423342', 'returns correct number - de');

try {
  $v->clean('1,2345');
  $t->fail('Too many decimal places doesn\'t throw exception');
}
catch (sfValidatorError $ve)
{
  $t->pass('Too many decimal places throws exception');
}