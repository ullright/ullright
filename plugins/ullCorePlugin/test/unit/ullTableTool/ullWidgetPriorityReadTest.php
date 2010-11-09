<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

#sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));

$t = new lime_test(6, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$w = new ullWidgetPriorityRead();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo',    1),   'Very high', '->render() renders very high correctly.');
$t->is($w->render('foo',    2),   'High', '->render() renders high correctly.');
$t->is($w->render('foo',    3),   'Normal', '->render() renders medium correctly.');
$t->is($w->render('foo',    4),   'Low', '->render() renders low correctly.');
$t->is($w->render('foo',    5),   'Very low', '->render() renders very low correctly.');
try
  {
    $w->render('foo', 6);
    $t->fail('__render() doesn\'t throw an exception if an invalid priority number is given');
  }
  catch (InvalidArgumentException $e)
  {
    $t->pass('__render() throws an exception if an invalid priority number is given');
  }
