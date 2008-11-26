<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(4, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('has()');

  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $group = Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup');

  // "login as test_user"
  sfContext::getInstance()->getUser()->setAttribute('user_id', 3);
  
  try
  {
    UllEntityTable::has($user, 'foobar');
    $t->fail('doesn\'t throw an exception for an invalid UllUser');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for an invalid UllUser');
  }
  
  $t->ok(UllEntityTable::has($user), 'returns true for a given UllUser when logged in as this user');
  $t->ok(UllEntityTable::has($group), 'returns true for a given UllGroup when the logged in user is member of this group');
  
  $t->ok(UllEntityTable::has($group, $user), 'returns true for a given UllGroup and a given UllUser who is member of this group');