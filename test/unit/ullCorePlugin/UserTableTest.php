<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';


class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by getSkiRentalRoute() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(8, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/fixtures.yml';
$t->setFixturesPath($path);


$t->begin('hasGroupByName() returns correct result');

  $t->is(
        UserTable::hasGroupByName('MasterAdmins', 1)
      , true
      , 'returns true for admin (id 1) is member of group Masteradmins'
      );
      
  $t->is(
        UserTable::hasGroupByName('MasterAdmins', 2)
      , false
      , 'returns false for test (id 2) is member of group Masteradmins'
      );
      
  sfContext::getInstance()->getUser()->setAttribute('user_id', 1);
  
  $t->is(
        UserTable::hasGroupByName('MasterAdmins')
      , true
      , 'returns true without passing the user_id'
      );
      
  $t->is(
        UserTable::hasGroupByName('Helpdesk')
      , false
      , 'returns false without passing the user_id'
      );  
  