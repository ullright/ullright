<?php

//deactivated because it isn't really necessary and consumes testing performance

//include dirname(__FILE__) . '/../../bootstrap/unit.php';
//
//class myTestCase extends sfDoctrineTestCase
//{
//}
//
//// create context since it is required by ->getUser() etc.
//sfContext::createInstance($configuration);
//
//$t = new myTestCase(1, new lime_output_color, $configuration);
//$path = dirname(__FILE__);
//$t->setFixturesPath($path);
//
//$t->begin('__toString()');
//
//  $entity = Doctrine::getTable('UllVentoryStatusDummyUser')->findOneByUsername('repair');
//  $t->is((string) $entity, 'Repair', 'returns the correct string representation');
//  
