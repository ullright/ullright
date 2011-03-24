<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Escaping'));
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(2, new lime_output_color(), $configuration);

$t->diag('__construct()');

  $w = new ullWidgetLink();
  $t->isa_ok($w, 'ullWidgetLink', 'returns the correct object');
  
$t->diag('->render()');  
  $t->is($w->render('foo', 'bar', array('href' => '/ullWiki/show/id/4')), '<b><a href="/ullWiki/show/id/4"><span>bar</span></a></b>', 'renders the widget as HTML');
