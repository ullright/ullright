<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

#sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull'));

$t = new lime_test(4, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$w = new ullWidgetSexRead();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 'm'), 'Male', '->render() renders male correctly.');
$t->is($w->render('foo', 'f'), 'Female', '->render() renders female correctly.');
$t->is($w->render('foo', ''), '', '->render() renders not specified correctly.');
try
{
	$w->render('foo', 'u');
	$t->fail('__render() doesn\'t throw an exception if an invalid sex is given');
}
catch (InvalidArgumentException $e)
{
	$t->pass('__render() throws an exception if an invalid sex is given');
}
