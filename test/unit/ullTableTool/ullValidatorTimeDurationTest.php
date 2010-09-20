<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfLoader::loadHelpers(array('I18N'));

$t = new lime_test(11, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);

$v = new ullValidatorTimeDuration;

$t->diag('clean()');
  $t->is($v->clean('1:00'), 3600, 'returns correct result');
  $t->is($v->clean('1'), 3600, 'returns correct result');
  $t->is($v->clean('1,00'), 3600, 'returns correct result');
  $t->is($v->clean('1,0'), 3600, 'returns correct result');
  $t->is($v->clean('1.00'), 3600, 'returns correct result');
  $t->is($v->clean('1.0'), 3600, 'returns correct result');
  
  $t->is($v->clean('1:30'), 5400, 'returns correct result');
  $t->is($v->clean('1,5'), 5400, 'returns correct result');
  $t->is($v->clean('1,50'), 5400, 'returns correct result');
  $t->is($v->clean('1.5'), 5400, 'returns correct result');
  $t->is($v->clean('1,5'), 5400, 'returns correct result');
