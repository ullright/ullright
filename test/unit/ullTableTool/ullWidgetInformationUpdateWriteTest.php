<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

#sfLoader::loadHelpers(array('ull'));

$t = new lime_test(1, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$w = new ullWidgetInformationUpdateWrite();
// ->render()
$t->diag('->render()');
$t->is($w->render('foo', 'foobar'), 'foobar<br /><textarea rows="4" cols="58" name="foo" id="foo"></textarea>',
            '->render() works correctly.');

