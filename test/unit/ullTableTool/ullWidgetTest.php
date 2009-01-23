<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('Escaping'));
//sfLoader::loadHelpers('I18N');

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$t->diag('__construct()');

  $w = new ullWidget();
  $t->isa_ok($w, 'ullWidget', 'returns the correct object');
  
$t->diag('->render()');  
  $t->is($w->render('foo', 'bar'), 'bar', 'renders the widget as HTML');
  $t->is($w->render('foo', 'Schüßel'), 'Sch&uuml;&szlig;el', 'correctly escapes umlauts');
  $t->is($w->render('foo', 'Welcome to <i>Italy</i>'), 'Welcome to &lt;i&gt;Italy&lt;/i&gt;', 'escapes html entities');
