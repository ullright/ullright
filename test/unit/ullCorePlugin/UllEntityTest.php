<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('__toString()');

  $masterAdmin = Doctrine::getTable('UllEntity')->find(1);
  $t->is((string) $masterAdmin, 'Master Admin', 'returns the correct string for a user');
  
  $entity = Doctrine::getTable('UllEntity')->findOneByDisplayName('MasterAdmins');
  $t->is((string) $entity, 'MasterAdmins', 'returns the correct string for a group');

  
$t->diag('getSubordinates()');

  $subordinates = $masterAdmin->getSubordinates();

  $t->is(count($subordinates), 1, 'Returns one subordinate for Master Admin');
  $t->is($subordinates->getFirst()->username, 'test_user', 'Returns the correct user');
    