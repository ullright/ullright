<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('getLastNameFirst()');

  $user = Doctrine::getTable('UllUser')->find(1);
  $t->is($user->getLastNameFirst(), 'Admin Master', 'returns the correct string');
  
$t->diag('isLoggedIn()');

  $t->is($user->isLoggedIn(), false, 'No one is currently logged in');
  $t->loginAs('admin');
  $t->is($user->isLoggedIn(), true, 'Admin is currently logged in');
  $t->loginAs('test_user');
  $t->is($user->isLoggedIn(), false, 'Testuser is currently logged in');
  
  $t->logout();
  
$t->diag('set/isInactive()');

  $t->is($user->isActive(), true, 'User is active');
  $user->setInactive();
  $t->is($user->isActive(), false, 'User is now inactive');