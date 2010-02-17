<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(2, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('findChoices()');
  $choices = UllGroupTable::findChoices();

  $t->is(
      $choices[4],
      array('name' => 'MasterAdmins', 'attributes' => array ('class' => 'color_light_bg_ull_core')),
      'returns the correct choices for UllGroup'
  );
  
  $t->is(
      $choices[5],
      array('name' => 'TestGroup', 'attributes' => array ('class' => 'color_light_bg_ull_core')),
      'returns the correct choices for UllGroup'
  );  