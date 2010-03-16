<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(1, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('getNavigationArray');

  $item = Doctrine::getTable('UllNavigationItem')->findOneBySlug('main-navigation');
  
  $t->is(count($item->getSubs()), 3, 'Returns the correct number of subs');

  
