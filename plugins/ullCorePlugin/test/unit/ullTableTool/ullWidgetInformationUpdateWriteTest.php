<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Text', 'Tag', 'I18N'));

$t = new lime_test(1, new lime_output_color(), $configuration);

$w = new ullWidgetInformationUpdateWrite();
// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 'foobar http://www.foobar.com foobar'),
'<div class="ull_flow_fieldtype_information_update">' .
'foobar <a href="http://www.foobar.com">http://www.foobar.com</a> foobar' .
'</div><textarea rows="5" cols="58" name="foo" id="foo"></textarea>', '->render() works correctly.');

