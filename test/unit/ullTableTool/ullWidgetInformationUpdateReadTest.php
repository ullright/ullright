<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('Text', 'Tag'));

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$w = new ullWidgetInformationUpdateRead();

//atm, this is the same as the ullWidgetTextarea test
// ->render()
$t->diag('->render()');
$t->is($w->render('foo', false), "", '->render() renders the widget as HTML');
$t->is($w->render('foo', 'einzeilig'), "einzeilig", '->render() renders the widget as HTML');
$t->is($w->render('foo', "mehr\nzeilig"), "mehr<br />\nzeilig", '->render() renders the widget as HTML');
$t->is($w->render('foo', "blubhttp://www.a.b.c fisch"),
      'blub<a href="http://www.a.b.c">http://www.a.b.c</a> fisch', '->render() renders the widget as HTML');

