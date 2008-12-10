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

$t->begin('__toString()');

  $app = Doctrine::getTable('UllFlowApp')->find(1);
  $t->is((string)$app, 'Trouble ticket tool', 'returns the correct string');


$t->diag('findStartStep()');

  $t->is($app->findStartStep()->id, 1, 'returns the correct start step');
  
$t->diag('findStepBySlug()');

  $t->is($app->findStepBySlug('creator')->id, 1, 'returns the correct step');