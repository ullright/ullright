<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(14, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('getLastNameFirst()');

  $user = Doctrine::getTable('UllUser')->find(1);
  $t->is($user->getLastNameFirst(), 'Admin Master', 'returns the correct string');
  
$t->diag('getEmailTo()');

  $t->is($user->getEmailTo(), 'Master Admin <admin@example.com>', 'Returns the correct email format');

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
  
$t->diag('reset num_email_bounces on email update');

  $user->num_email_bounces = 3;
  $user->save();
  $t->is($user->num_email_bounces, 3, 'num_email_bounces is 3');
  
  $user->email = 'new@example.com';
  $user->save();
  $t->is($user->num_email_bounces, 0, 'num_email_bounces is reseted');
  
$t->diag('createUsername()');

  $user = new UllUser();
  $user->save();
  $t->is($user->username, 'user', 'Creates the correct username');
  
  $user = new UllUser();
  $user->first_name = 'Hugo';
  $user->save();
  $t->is($user->username, 'hugo', 'Creates the correct username');
  
  $user = new UllUser();
  $user->last_name = 'Hakkinen';
  $user->save();
  $t->is($user->username, 'hakkinen', 'Creates the correct username');

  $user = new UllUser();
  $user->first_name = 'Hugo';
  $user->last_name = 'Hakkinen';
  $user->save();
  $t->is($user->username, 'hugo_hakkinen', 'Creates the correct username');

  $user = new UllUser();
  $user->email = 'hugo.hakkinen@example.com';
  $user->save();
  $t->is($user->username, 'hugo_hakkinen1', 'Creates the correct username');    
