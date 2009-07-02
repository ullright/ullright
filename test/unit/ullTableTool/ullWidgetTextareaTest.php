<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('Text', 'Tag'));

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$w = new ullWidgetTextarea();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', false), "", '->render() renders the widget as HTML');
$t->is($w->render('foo', 'singleline'), "singleline", '->render() renders the widget as HTML');
$t->is($w->render('foo', "multi\nline"), "multi<br />\nline", '->render() renders the widget as HTML');
$t->is($w->render('foo', "vertebratehttp://www.foobar.com fish"),
      'vertebrate<a href="http://www.foobar.com">http://www.foobar.com</a> fish', '->render() renders the widget as HTML');
