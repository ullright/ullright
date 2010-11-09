<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(7, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__toString()');

  $app = Doctrine::getTable('UllFlowApp')->find(1);
  $t->is((string)$app, 'Trouble ticket tool', 'returns the correct string');


$t->diag('findStartStep()');

  $t->is($app->findStartStep()->id, 1, 'returns the correct start step');
  
$t->diag('findStepBySlug()');

  $t->is($app->findStepBySlug('trouble_ticket_creator')->id, 1, 'returns the correct step');
  
$t->diag('findStepIdBySlug()');

  $t->is($app->findStepIdBySlug('trouble_ticket_creator'), 1, 'returns the correct stepId');
  $t->is($app->findStepIdBySlug('foobar'), null, 'returns null for a non-existing step');  
  
$t->diag('findOrderedColumns()');

  $columns = $app->findOrderedColumns();
  
  $t->is($columns[0]->slug, 'my_subject', 'returns the correct column');
  $t->is($columns[1]->slug, 'my_information_update', 'returns the correct column');