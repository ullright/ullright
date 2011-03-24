<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

$instance = sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(3, new lime_output_color(), $configuration);

$t->diag('__construct()');
  $w = new ullWidgetDateRead();
  $t->isa_ok($w, 'ullWidgetDateRead', 'returns the correct object');
  
$t->diag('->render()');
  $now = time();  
  $t->is($w->render('foo', $now), '<span>' . date("m/d/Y") . '</span>');
  
  $instance->getUser()->setCulture("de");
  
  $t->is($w->render('foo', $now), '<span>' . date("d.m.Y") . '</span>');
