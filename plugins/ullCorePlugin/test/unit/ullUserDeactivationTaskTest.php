<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$app = 'frontend';

$t->begin('deactivateUsers()');

  $q = new UllQuery('UllUser');
  $q->addWhere('UllUserStatus->is_active = ?', false);
  $t->is($q->count(), 0, 'We have no deactivated user');

  $task = new UserDeactivationTask(new sfEventDispatcher(), new sfFormatter());
  $task->deactivateUsers(array('application' => $app, 'env' => 'test'));
  
  $q = new UllQuery('UllUser');
  $q->addWhere('UllUserStatus->is_active = ?', false);
  $t->is($q->count(), 0, 'No user has been deactivated when running the task');
  
  // set deactivation date on one user
  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $user['deactivation_date'] = date('Y-m-d');
  $user->save();
  
  $task = new UserDeactivationTask(new sfEventDispatcher(), new sfFormatter());
  $task->deactivateUsers(array('application' => $app, 'env' => 'test'));  

  $q = new UllQuery('UllUser');
  $q->addWhere('UllUserStatus->is_active = ?', false);
  $t->is($q->count(), 1, 'One user has been deactivated because of a reached deactivation date');
  
  $user = Doctrine::getTable('UllUser')->findOneByUsername('admin');
  $user['separation_date'] = '2010-01-01';
  $user->save();
  
  $task = new UserDeactivationTask(new sfEventDispatcher(), new sfFormatter());
  $task->deactivateUsers(array('application' => $app, 'env' => 'test'));
  
  $q = new UllQuery('UllUser');
  $q->addWhere('UllUserStatus->is_active = ?', false);  
  $t->is($q->count(), 2, 'A second user has been deactivated because of a reached separation date');