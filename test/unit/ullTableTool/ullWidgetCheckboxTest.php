<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

sfLoader::loadHelpers(array('ull'));
sfLoader::loadHelpers('I18N');

$t = new lime_test(2, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$w = new ullWidgetCheckbox();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 1), '<img alt="Checkbox_checked" title="Checkbox_checked" src="/ullCoreThemeNGPlugin/images/action_icons/checkbox_checked_9x9.png" />', '->render() renders the widget as HTML');
$t->is($w->render('foo', 0), '<img alt="Checkbox_unchecked" title="Checkbox_unchecked" src="/ullCoreThemeNGPlugin/images/action_icons/checkbox_unchecked_9x9.png" />', '->render() renders the widget as HTML');
