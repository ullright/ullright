<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

class myTestCase extends lime_test
{
}

$t = new myTestCase(2, new lime_output_color, $configuration);

$v = new ullValidatorTaggable();

$t->diag('->clean()');
  $t->is($v->clean('bar, foo'), 'bar, foo', 'returns the string unmodified');
  $t->is($v->clean('foo,bar  , 120   ,12'), '12, 120, bar, foo', 'orders and trims the string correctly');
