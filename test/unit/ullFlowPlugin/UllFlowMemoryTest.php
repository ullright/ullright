<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

  
$t->begin('__toString()');
  $doc = Doctrine::getTable('UllFlowMemory')->find(9);
  $t->is((string) $doc, 'Trouble ticket "My first trouble ticket"', 'Returns the correct value when casted to string');

  
$t->diag('getEditUri()');
  $doc = Doctrine::getTable('UllFlowMemory')->find(9);
  $t->is($doc->getEditUri(), 'ullFlow/edit?doc=1', 'Returns the correct uri for edit');