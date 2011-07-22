<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(9, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findBySlug()');

  try
  {
    UllFlowAppTable::findBySlug(array('fooBar'));
    $t->fail('doesn\'t throw an exception if slug is not a string');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception if slug is not a string');
  }

  $app = UllFlowAppTable::findBySlug('fooBar');
  $t->is($app, null, 'returns null for an unknown slug');
  
  $app = UllFlowAppTable::findBySlug('trouble_ticket');
  
  $t->isa_ok($app, 'UllFlowApp', 'returns the correct object');
  $t->is($app->label, 'Trouble ticket tool', 'return the correct label');
  
  
$t->diag('findAllOrderByLabel');

  $apps = UllFlowAppTable::findAllOrderByName();

  $t->is(count($apps), 2, 'Returns the correct number of results');
  $t->is($apps[0]['slug'], 'todo', 'Returns the correct result at the correct position');
  $t->is($apps[1]['slug'], 'trouble_ticket', 'Returns the correct result at the correct position');
  
$t->diag('hasLoggedInUserGlobalWriteAccess()');

  $t->loginAs('helpdesk_user');
  $t->is(UllFlowAppTable::hasLoggedInUserGlobalWriteAccess(1), false, 'Helpdesk user has no global write access');
  $t->loginAs('helpdesk_admin_user');
  $t->is(UllFlowAppTable::hasLoggedInUserGlobalWriteAccess(1), true, 'Helpdesk admin user has global write access');
  