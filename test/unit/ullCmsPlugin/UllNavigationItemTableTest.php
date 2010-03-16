<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('getNavigationTree()');

  $navigation = UllNavigationItemTable::getNavigationTree('main-navigation', 'about-us')->getSubnodes();
  
  $t->is(count($navigation), 3, 'Returns the correct number of results');
  $t->is(reset($navigation)->getData()->slug, 'about-us', 'Returns the correct data for the first entry');
  $t->is(reset($navigation)->getMeta('is_current'), true, 'Returns the correct data for the first entry');
  $t->is(next($navigation)->getData()->slug, 'contact', 'Returns the correct data for the second entry');
  $t->is(end($navigation)->getData()->slug, 'home', 'Returns the correct data for the last entry');
  $t->is(end($navigation)->hasMeta('is_current'), false, 'Returns the correct data for the first entry');
  
