<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$t = new lime_test(2, new lime_output_color(), $configuration);
$instance = sfContext::createInstance($configuration);
sfLoader::loadHelpers(array('Escaping', 'I18N'));

$w = new ullWidgetFloatWrite();

$t->diag('->render() with default culture');
$reference = '<input type="text" name="foo" value="-423,342.64" id="foo" />';
$t->is($w->render('foo', '-423342.64'), $reference, '->render() renders correctly.');


$instance->getUser()->setCulture("de");

$t->diag('->render() with \'de\' culture');
$reference = '<input type="text" name="foo" value="-423.342,64" id="foo" />';
$t->is($w->render('foo', '-423342.64'), $reference, '->render() renders correctly.');
