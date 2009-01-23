<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('Escaping'));
//sfLoader::loadHelpers('I18N');

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$t->diag('__construct()');

  $w = new ullWidgetLink();
  $t->isa_ok($w, 'ullWidgetLink', 'returns the correct object');
  
$t->diag('->render()');  
  $t->is($w->render('foo', 'bar', array('href' => '/ullWiki/show/id/4')), '<b><a href="/ullWiki/show/id/4">bar</a></b>', 'renders the widget as HTML');
  $t->is($w->render('foo', 'Schüßel', array('href' => '/ullWiki/show/id/4')), '<b><a href="/ullWiki/show/id/4">Sch&uuml;&szlig;el</a></b>', 'correctly escapes umlauts');
  $t->is($w->render('foo', 'Welcome to <i>Italy</i>', array('href' => '/ullWiki/show/id/4')), '<b><a href="/ullWiki/show/id/4">Welcome to &lt;i&gt;Italy&lt;/i&gt;</a></b>', 'escapes html entities');