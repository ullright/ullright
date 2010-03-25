<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(8, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('getMenuTree()');

  $menu = UllCmsItemTable::getMenuTree('main-menu', 'homepage')->getSubnodes();
  
  $t->is(count($menu), 5, 'Returns the correct number of results');
  $t->is(reset($menu)->getData()->slug, 'homepage', 'Returns the correct data for the first entry');
  $t->is(reset($menu)->getMeta('is_current'), true, 'Returns the correct data for the first entry');
  $t->is(next($menu)->getData()->slug, 'about-us', 'Returns the correct data for the second entry');
  $t->is(next($menu)->getData()->slug, 'courses', 'Returns the correct data for the third entry');
  $t->is(next($menu)->getData()->slug, 'contact', 'Returns the correct data for the fourth entry');
  $t->is(end($menu)->getData()->slug, 'wiki', 'Returns the correct data for the last entry');
  $t->is(end($menu)->hasMeta('is_current'), false, 'Returns the correct data for the last entry');
  
