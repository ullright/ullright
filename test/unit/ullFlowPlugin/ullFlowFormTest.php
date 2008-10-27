<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);



$t->begin('__construct');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $form = new UllFlowForm($doc);
  $t->isa_ok($form, 'UllFlowForm', 'returns the correct object type');
  
  $defaults = $form->getDefaults();
  $t->is($defaults['my_email'], 'quasimodo@ull.at', 'The form returns the correct defaults');
  
  $request = array(
    'my_email'  => 'luke.skywalker@ull.at'
  );
  
  $form->bindAndSave($request);
  
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is($doc->my_email, 'luke.skywalker@ull.at', 'The form saves the virtual column values correctly');
  
 