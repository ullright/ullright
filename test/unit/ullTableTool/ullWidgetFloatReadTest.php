<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$t = new lime_test(6, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);
sfLoader::loadHelpers(array('Escaping', 'I18N'));

$w = new ullWidgetFloatRead();

$t->diag('->render() with default culture');
$t->is($w->render('foo', '01234.358'), '1,234.358', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.64'), '-423,342.64', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.'), '-423,342.', '->render() renders correctly.');


$instance->getUser()->setCulture("de");

$t->diag('->render() with \'de\' culture');
$t->is($w->render('foo', '01234.358'), '1.234,358', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.64'), '-423.342,64', '->render() renders correctly.');
$t->is($w->render('foo', '-423342.'), '-423.342,', '->render() renders correctly.');
