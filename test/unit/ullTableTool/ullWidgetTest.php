<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('Escaping'));
//sfLoader::loadHelpers('I18N');

$t = new lime_test(5, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$t->diag('__construct()');

  $w = new ullWidget();
  $t->isa_ok($w, 'ullWidget', 'returns the correct object');
  
$t->diag('->render()');  
  $t->is($w->render('foo', 'bar'), 'bar', 'renders the widget as HTML');
  // Do NOT encode html entities
//  $t->is($w->render('foo', 'Schüßel'), 'Sch&uuml;&szlig;el', 'correctly escapes umlauts');
//  $t->is($w->render('foo', 'Welcome to <i>Italy</i>'), 'Welcome to &lt;i&gt;Italy&lt;/i&gt;', 'escapes html entities');
  $t->is($w->render('foo', 'Schüßel'), 'Schüßel', 'Does not html entity decode umlauts');
  $t->is($w->render('foo', 'Welcome to <i>Italy</i>'), 'Welcome to <i>Italy</i>', 'Does not html entity tags (e.g. "<" chars)');  

$t->diag('nowrap option');
  $w = new ullWidget(array('nowrap' => true));
  $t->is(
    $w->render('my_fieldname', 'very long long long long string'), 
    '<span style="white-space: nowrap;">very long long long long string</span>', 
    'Wraps value in span "nowrap" tags'
  );  