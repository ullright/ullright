<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(1, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('findChoices()');
  $t->is(
      UllCloneUserTable::findChoices(),
      array(
        array('name' => 'Admin Master', 
          'attributes' => 
          array ('class' => 'color_clone_user_bg_ull_entity_widget'),
        ),
      ),
      'returns the correct choices for UllUser'
  );
  