<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Text', 'Tag'));

$t = new lime_test(4, new lime_output_color(), $configuration);

$w = new ullWidgetInformationUpdateRead();

//atm, this is the same as the ullWidgetTextarea test
// ->render()
$t->diag('->render()');
$t->is($w->render('foo', false), "", '->render() renders the widget as HTML');
$t->is($w->render('foo', 'singleline'), "singleline", '->render() renders the widget as HTML');
$t->is($w->render('foo', "multi\nline"), "multi<br />\nline", '->render() renders the widget as HTML');
$t->is($w->render('foo', "vertebratehttp://www.foobar.com fish"),
      'vertebrate<a href="http://www.foobar.com">http://www.foobar.com</a> fish', '->render() renders the widget as HTML');
