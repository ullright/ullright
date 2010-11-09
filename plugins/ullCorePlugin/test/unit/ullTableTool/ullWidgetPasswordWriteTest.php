<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

$t = new lime_test(6, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$t->diag('render() with default options');
  $w = new ullWidgetPasswordWrite();
  
  $t->is($w->render('foo', false), '<input type="password" name="foo" id="foo" />', 'Renders the correct HTML for null value');
  $t->is($w->render('foo', ''), '<input type="password" name="foo" id="foo" />', 'Renders the correct HTML for empty string value');
  $t->is($w->render('foo', 'iloveyou'), '<input type="password" name="foo" id="foo" />', 'Returns the correct HTML for a value');


$t->diag('render() with render_pseudo_password option');
  $w = new ullWidgetPasswordWrite(array('render_pseudo_password' => true));
  
  $t->is($w->render('foo', false), '<input type="password" name="foo" id="foo" />', 'Renders the correct HTML for null value');
  $t->is($w->render('foo', ''), '<input type="password" name="foo" value="" id="foo" />', 'Renders the correct HTML for empty string value');
  $t->is($w->render('foo', 'iloveyou'), '<input type="password" name="foo" value="********" id="foo" />', 'Returns the correct HTML for a value');  
