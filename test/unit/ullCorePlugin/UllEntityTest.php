<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('__toString()');

  $entity = Doctrine::getTable('UllEntity')->find(1);
  $t->is((string)$entity, 'Master Admin', 'returns the correct string for a user');
  
  $entity = Doctrine::getTable('UllEntity')->find(4);
  $t->is((string)$entity, 'Helpdesk (Group)', 'returns the correct string for a group');
