<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$context = sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull', 'I18N'));

$t = new lime_test(2, new lime_output_color(), $configuration);

$w = new ullWidgetCheckbox();

// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 1), '<img alt="Checkbox_checked" title="Checkbox_checked" class="checkbox_checked" src="/ullCoreThemeNGPlugin/images/action_icons/checkbox_checked_9x9.png" />', '->render() renders the widget as HTML');
$t->is($w->render('foo', 0), '<img alt="Checkbox_unchecked" title="Checkbox_unchecked" class="checkbox_unchecked" src="/ullCoreThemeNGPlugin/images/action_icons/checkbox_unchecked_9x9.png" />', '->render() renders the widget as HTML');


//foreach ($context->getEventDispatcher()->getListeners('request.filter_parameters') as $key => $value)
//{
//  var_dump(get_class($value[0]));
//  var_dump($value[1]);
//}
