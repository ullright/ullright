<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

$t = new lime_test(6, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Escaping', 'I18N'));

$w = new ullWidgetFloatRead();

$t->diag('->render() with default culture');
$t->is($w->render('foo', '01234.358'), '<span>1,234.358</span>', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.64'), '<span>-423,342.64</span>', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.'), '<span>-423,342.</span>', '->render() renders correctly.');


$instance->getUser()->setCulture("de");

$t->diag('->render() with \'de\' culture');
$t->is($w->render('foo', '01234.358'), '<span>1.234,358</span>', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.64'), '<span>-423.342,64</span>', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.'), '<span>-423.342,</span>', '->render() renders correctly.');
