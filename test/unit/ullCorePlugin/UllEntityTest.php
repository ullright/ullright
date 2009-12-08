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


$masterAdmin = $testUser = UllUserTable::findByDisplayName('Master Admin');
$testUser = UllUserTable::findByDisplayName('Test User');

$t->begin('__toString()');
  
  $t->is((string) $masterAdmin, 'Master Admin', 'returns the correct string for a user');
  
  $entity = Doctrine::getTable('UllEntity')->findOneByDisplayName('MasterAdmins');
  $t->is((string) $entity, 'MasterAdmins', 'returns the correct string for a group');

  
$t->diag('getSubordinates()');

  $subordinates = $masterAdmin->getSubordinates();

  $t->is(count($subordinates), 1, 'Returns one subordinate for Master Admin');
  $t->is($subordinates->getFirst()->username, 'test_user', 'Returns the correct user');
    
  
$t->diag('isSuperior()');
  $t->is($masterAdmin->isSuperior(), true, 'Returns true for Masteradmins');  
  $t->is($testUser->isSuperior(), false, 'Returns false for Testuser');