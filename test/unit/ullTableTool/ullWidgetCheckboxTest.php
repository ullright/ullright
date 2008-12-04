<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('ull'));

$t = new lime_test(5, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$w = new ullWidgetCheckbox();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 1), '<img alt="Checkbox_checked" title="Checkbox_checked" src="/ullCoreThemeNGPlugin/images/action_icons/checkbox_checked_9x9.png" />', '->render() renders the widget as HTML');
$t->is($w->render('foo', 0), '<img alt="Checkbox_unchecked" title="Checkbox_unchecked" src="/ullCoreThemeNGPlugin/images/action_icons/checkbox_unchecked_9x9.png" />', '->render() renders the widget as HTML');

$w = new sfWidgetFormInputCheckbox(array(), array('value' => 'bar'));
$t->is($w->render('foo', null), '<input value="bar" type="checkbox" name="foo" id="foo" />', '->render() renders the widget as HTML');
$t->is($w->render('foo', null, array('value' => 'baz')), '<input value="baz" type="checkbox" name="foo" id="foo" />', '->render() renders the widget as HTML');
$t->is($w->render('foo', 'bar'), '<input value="bar" type="checkbox" name="foo" checked="checked" id="foo" />', '->render() renders the widget as HTML');
