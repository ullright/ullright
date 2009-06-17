<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

//sfLoader::loadHelpers(array('Escaping'));
//sfLoader::loadHelpers('I18N');

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$t->diag('__construct()');

  $w = new ullWidgetCheckboxWrite();
  $t->isa_ok($w, 'ullWidgetCheckboxWrite', 'returns the correct object');
  
$t->diag('->render()');
  $reference = '<input name="foo" id="foo_hidden" type="hidden" /><input type="checkbox" name="foo" checked="checked" id="foo" />';  
  $t->is($w->render('foo', true), $reference, 'renders the widget as HTML');
  $reference = '<input name="foo" id="foo_hidden" type="hidden" /><input name="foo" id="foo" type="checkbox" />';  
  $t->is($w->render('foo', false), $reference, 'renders the widget as HTML');
  
  //test a custom fix for a bug in sfWidgetFormInputCheckbox
  //@see http://trac.symfony-project.org/ticket/5244
  $reference = '<input name="foo" id="foo_hidden" type="hidden" /><input name="foo" id="foo" type="checkbox" />';  
  $t->is($w->render('foo', ''), $reference, 'checkbox is unchecked for value \'\''');