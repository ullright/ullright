<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('ull'));
//sfLoader::loadHelpers('I18N');

$t = new lime_test(3, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);

$t->diag('__construct()');
  $w = new ullWidgetDateTimeRead();
  $t->isa_ok($w, 'ullWidgetDateTimeRead', 'returns the correct object');
  
$t->diag('->render()');
  $now = time();  
  $t->is($w->render('foo', $now), date("m/d/Y H:i:s"));
  
  $instance->getUser()->setCulture("de");
  
  $t->is($w->render('foo', $now), date("d.m.Y H:i:s"));
