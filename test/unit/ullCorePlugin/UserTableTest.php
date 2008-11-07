<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(12, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('hasGroup()');
  $t->is(
        UllUserTable::hasGroup('MasterAdmins', 1)
      , true
      , 'returns true for a given group and user_id'
      );      
  $t->is(
        UllUserTable::hasGroup('MasterAdmins', 3)
      , false
      , 'returns false for a given group and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(2, 1)
      , true
      , 'returns true for a given group_id and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(2, 3)
      , false
      , 'returns false for a given group_id and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(array(2,4), 1)
      , true
      , 'returns true for a given array of group_ids and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(array('Masteradmins', 'Helpdesk'), 1)
      , true
      , 'returns true for a given array of group names and user_id'
      );
  $t->is(
        UllUserTable::hasGroup(array('Masteradmins', 'FooBarGroup'), 3)
      , false
      , 'returns false for a given array of invalid group names and user_id'
      );                            
  $t->is(
        UllUserTable::hasGroup('Masteradmins')
      , false
      , 'returns false for a given group and using the sessions user_id and not logged in'
      );      
  sfContext::getInstance()->getUser()->setAttribute('user_id', 1);
  $t->is(
        UllUserTable::hasGroup('MasterAdmins')
      , true
      , 'returns true for a given group and using the sessions user_id'
      );
  $t->is(
        UllUserTable::hasGroup('Helpdesk')
      , false
      , 'returns false for a given group and using the sessions user_id'
      );
        
$t->begin('hasPermission()');
  $t->is(
        UllUserTable::hasPermission('testPermission', 3)
      , true
      , 'returns true for a given permission and user_id'
      );
  $t->is(
        UllUserTable::hasPermission('invalidPermission', 3)
      , false
      , 'returns false for an invalid permission'
      );                