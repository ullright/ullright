<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
}

$t = new myTestCase(1, new lime_output_color, $configuration);

$v = new ullValidatorPassword();

$t->diag('->clean()');
  $t->is($v->clean('test'), '098f6bcd4621d373cade4e832627b4f6', 'returns md5 password');
