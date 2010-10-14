<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$instance = sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(3, new lime_output_color(), $configuration);


$t->diag('__construct()');
  $w = new ullWidgetDateTimeRead();
  $t->isa_ok($w, 'ullWidgetDateTimeRead', 'returns the correct object');
  
$t->diag('->render()');
  $now = time();  
  $t->is($w->render('foo', $now), date("m/d/Y H:i:s"), 'renders default culture correctly');
  
  $instance->getUser()->setCulture("de");
  
  $t->is($w->render('foo', $now), date("d.m.Y H:i:s"), 'renders german culture correctly');
