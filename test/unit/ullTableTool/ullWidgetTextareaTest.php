<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

#sfLoader::loadHelpers(array('ull'));

$t = new lime_test(3, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$w = new ullWidgetTextarea();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', false), "", '->render() renders the widget as HTML');
$t->is($w->render('foo', 'einzeilig'), "einzeilig", '->render() renders the widget as HTML');
$t->is($w->render('foo', "mehr\nzeilig"), "mehr<br />\nzeilig", '->render() renders the widget as HTML');
