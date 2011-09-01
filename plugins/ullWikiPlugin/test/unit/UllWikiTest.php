<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
$instance = sfContext::createInstance($configuration);

$t = new myTestCase(5, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');
  $doc = new UllWiki;
  $t->isa_ok($doc, 'UllWiki', 'returns the correct object');
  
$t->diag('create');
  $doc->subject = 'Foobar subject';
  $doc->addTag('foobar tag');
  $doc->UllWikiAccessLevel = Doctrine::getTable('UllWikiAccessLevel')->findOneBySlug('public_readable');
  $doc->save();
  
  $t->is($doc->subject, 'Foobar subject', 'sets the subject correctly');
  $t->is($doc->getTags(), array('foobar tag' => 'foobar tag'), 'sets the tags correctly');

$t->diag('checkAccess() for a special case: public read, but one user can edit');

  $user = new UllUser;
  $user['username'] = 'foo_user';
  $user->save();

  $group = new UllGroup();
  $group->display_name = 'Special group';
  $group->save();
  
  $membership = new UllEntityGroup();
  $membership->UllEntity = $user;
  $membership->UllGroup = $group;
  $membership->save();
  
  $level = new UllWikiAccessLevel();
  $level->name = 'Special';
  $level->save();
  
  // Public read access
  $access = new UllWikiAccessLevelAccess();
  $access->UllGroup = Doctrine::getTable('UllGroup')->findOneByDisplayName('EveryOne');
  $access->UllPrivilege = Doctrine::getTable('UllPrivilege')->findOneBySlug('read');
  $access->UllWikiAccessLevel = $level;
  $access->save();    
  
  // Special group members can edit
  $access = new UllWikiAccessLevelAccess();
  $access->UllGroup = $group;
  $access->UllPrivilege = Doctrine::getTable('UllPrivilege')->findOneBySlug('write');
  $access->UllWikiAccessLevel = $level;
  $access->save();
  
  $doc = new UllWiki();
  $doc->subject = 'Special test';
  $doc->UllWikiAccessLevel = $level;
  $doc->save();

  $t->is($doc->checkAccess(), 'r', 'Returns read access for public');
  
  // login as special user
  $instance->getUser()->setAttribute('user_id', $user->id);
  
  $t->is($doc->checkAccess(), 'w', 'Returns write access for our special user');




  