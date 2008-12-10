<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

#sfLoader::loadHelpers(array('ull'));

$t = new lime_test(3, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$w = new ullWidgetPassword();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo',    false),         '', '->render() renders the widget as HTML');
$t->is($w->render('foo',       ''),         '', '->render() renders the widget as HTML');
$t->is($w->render('foo', 'geheim'), '********', '->render() renders the widget as HTML');
