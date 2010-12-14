<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Text', 'Tag', 'Escaping'));

$t = new lime_test(6, new lime_output_color(), $configuration);

$w = new ullWidgetTextarea();

$t->diag('->render()');
  $t->is($w->render('foo', false), "", '->render() renders the widget as HTML');
  $t->is($w->render('foo', 'singleline'), "singleline", '->render() renders the widget as HTML');
  $t->is($w->render('foo', "multi\nline"), "multi<br />\nline", '->render() renders the widget as HTML');
  $t->is($w->render('foo', "vertebratehttp://www.foobar.com fish"),
        'vertebrate<a href="http://www.foobar.com">http://www.foobar.com</a> fish', '->render() renders the widget as HTML');
  $t->is($w->render('foo', "<script>bad<"), "&lt;script&gt;bad&lt;", '->render() renders the widget as HTML');
  
$t->diag('decode_mime option');
  $w = new ullWidgetTextarea(array('decode_mime' => true));
  $t->is(
    $w->render('my_fieldname', 'Thomas =?utf-8?Q?Strau=C3=9F?='), 
    'Thomas Strauß', 
    'Decodes imap mime string'
  );   