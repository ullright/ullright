<?php

require_once(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('Escaping'));
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(8, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$t->diag('__construct()');

  $w = new ullWidget();
  $t->isa_ok($w, 'ullWidget', 'returns the correct object');

  
$t->diag('->render()');  
  $t->is($w->render('foo', 'bar'), '<span>bar</span>', 'renders the widget as HTML');
  // why do escaping in the widget?!?
  $t->is($w->render('foo', 'Schüßel'), '<span>Sch&uuml;&szlig;el</span>', 'correctly escapes umlauts');
  $t->is($w->render('foo', 'Welcome to <i>Italy</i>'), '<span>Welcome to &lt;i&gt;Italy&lt;/i&gt;</span>', 'escapes html entities');

$t->diag('nowrap option');
  $w = new ullWidget(array('nowrap' => true));
  $t->is(
    $w->render('my_fieldname', 'very long long long long string'), 
    '<span><span style="white-space: nowrap;">very long long long long string</span></span>', 
    'Wraps value in span "nowrap" tags'
  );  
  
$t->diag('decode_mime option');
  $w = new ullWidget(array('decode_mime' => true));
  $t->is(
    $w->render('my_fieldname', 'Thomas =?utf-8?Q?Strau=C3=9F?='), 
    '<span>Thomas Strauß</span>', 
    'Decodes imap mime string'
  );   
  
$t->diag('enclose in span tag');
  $w = new ullWidget(array(), array('class' => 'foo'));
  $t->is(
    $w->render('my_fieldname', 'baz'), 
    '<span class="foo">baz</span>', 
    'Renders allowed attribute'
  );  

  $w = new ullWidget(array(), array('foo' => 'bar'));
  $t->is(
    $w->render('my_fieldname', 'baz'), 
    '<span>baz</span>', 
    'Ignores invalid attribute'
  );

 
  
  
  