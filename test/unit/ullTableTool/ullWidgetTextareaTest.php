<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfContext::getInstance()->getConfiguration()->loadHelpers(array('Text', 'Tag', 'Escaping'));

$t = new lime_test(5, new lime_output_color(), $configuration);

$w = new ullWidgetTextarea();

$t->diag('->render()');
  $t->is($w->render('foo', false), "", '->render() renders the widget as HTML');
  $t->is($w->render('foo', 'singleline'), "singleline", '->render() renders the widget as HTML');
  $t->is($w->render('foo', "multi\nline"), "multi<br />\nline", '->render() renders the widget as HTML');
  $t->is($w->render('foo', "vertebratehttp://www.foobar.com fish"),
        'vertebrate<a href="http://www.foobar.com">http://www.foobar.com</a> fish', '->render() renders the widget as HTML');
  $t->is($w->render('foo', "<script>bad<"), "&lt;script&gt;bad&lt;", '->render() renders the widget as HTML');