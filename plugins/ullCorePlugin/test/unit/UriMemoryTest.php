<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

$context = sfContext::createInstance($configuration);

$t = new lime_test(16, new lime_output_color);

$t->diag('__construct()');

  $context->getRequest()->setParameter('module', 'myModule');
  $context->getRequest()->setParameter('action', 'list');

  $uriMemory = new UriMemory();
  $t->isa_ok($uriMemory, 'UriMemory', 'creates the correct object');
  
$t->diag('get()');

  $t->is($uriMemory->get(), '@homepage', 'returns the correct default URI without setting anything');
  

$t->diag('setDefault()');

  $uriMemory->setDefault('myModule/index');
  $t->is($uriMemory->get(), 'myModule/index', 'returns the correct given default URI');
  

$t->diag('setUri()');

  $uriMemory->setUri(null, null, true, 'myModule/list?search=foobar');
  $t->is($uriMemory->get(), 'myModule/list?search=foobar', 'sets the correct URI for the current module/action');
  
  $uriMemory->setUri('show', null, true, 'myModule/list?search=quasimodo');
  $t->is($uriMemory->get('show'), 'myModule/list?search=quasimodo', 'sets the correct URI for the current module and a given action');
  
  $uriMemory->setUri('show', 'ullUser', true, 'myModule/list?search=benjamin_bluemchen');
  $t->is($uriMemory->get('show', 'ullUser'), 'myModule/list?search=benjamin_bluemchen', 'sets the correct URI for a given action/module');  
  

$t->diag('setReferer()');

  $uriMemory->setReferer(null, null, true, 'http://www.webmozarts.com');
  $t->is($uriMemory->get(), 'http://www.webmozarts.com', 'sets the correct referer URL for the current module/action');
  
  $uriMemory->setReferer('edit', 'fooModule', false, 'http://www.ull.at');
  $t->is($uriMemory->get('edit', 'fooModule'), 'http://www.ull.at', 'sets the correct referer URL with flag "overwrite" set to false');  
  
  $uriMemory->setReferer('edit', 'fooModule', false, 'http://www.ullright.at');
  $t->is($uriMemory->get('edit', 'fooModule'), 'http://www.ull.at', 'doesn\t overwrite the referer URL with flag "overwrite" set to false');  
  

$t->diag('has()');

  $t->is($uriMemory->has(), true, 'returns true for the current module/action');
  $t->is($uriMemory->has('show', 'ullUser'), true, 'returns true for a given module/action');
  $t->is($uriMemory->has('foobar'), false, 'returns false for an invalid module/action');
  

$t->diag('getAndDelete()');

  $t->is($uriMemory->getAndDelete(), 'http://www.webmozarts.com', 'gets the correct URI for the current module/action');
  $t->is($uriMemory->has(), false, '...and removes it from the user session');
  
  $t->is($uriMemory->getAndDelete('show', 'ullUser'), 'myModule/list?search=benjamin_bluemchen', 'gets the correct URI for a given module/action');
  $t->is($uriMemory->has('show', 'ullUser'), false, '...and removes it from the user session');
  
  

