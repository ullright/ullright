<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

#sfLoader::loadHelpers(array('ull'));

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$w = new ullWidgetCountryRead();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 'AT'),   'Austria', '->render() renders AT correctly.');
$t->is($w->render('foo', 'DE'),   'Germany', '->render() renders DE correctly.');
$t->is($w->render('foo', 'CH'),   'Switzerland', '->render() renders CH correctly.');
try
  {
    $w->render('foo', -1);
    $t->fail('__render() doesn\'t throw an exception if an invalid country code is given');
  }
  catch (InvalidArgumentException $e)
  {
    $t->pass('__render() throws an exception if an invalid country code is given');
  }
