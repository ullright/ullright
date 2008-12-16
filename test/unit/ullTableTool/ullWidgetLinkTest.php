<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

//sfLoader::loadHelpers(array('ull'));
//sfLoader::loadHelpers('I18N');

$t = new lime_test(2, new lime_output_color(), $configuration);
sfContext::createInstance($configuration);

$t->diag('__construct()');
//  try
//  {
//    new ullWidgetLink();
//    $t->fail('ullWidgetLink does\'t throw an exception without the option "internal_uri" given');
//  }
//  catch (Exception $e)
//  {
//    $t->pass('ullWidgetLink throws an exception without the option "internal_uri" given');
//  }


//  $w = new ullWidgetLink(array('internal_uri' => 'ullWiki/show?id=4'));
  $w = new ullWidgetLink();
  $t->isa_ok($w, 'ullWidgetLink', 'returns the correct object');
  
$t->diag('->render()');  
  $t->is($w->render('foo', 'bar', array('href' => '/ullWiki/show/id/4')), '<a href="/ullWiki/show/id/4">bar</a>', 'renders the widget as HTML');
