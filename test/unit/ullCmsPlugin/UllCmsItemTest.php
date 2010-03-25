<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(4, new lime_output_color, $configuration);
$t->setMode('yml_fixtures');
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('getNavigationArray');

  $item = Doctrine::getTable('UllCmsItem')->findOneBySlug('main-menu');
  
  $t->is(count($item->getSubs()), 5, 'Returns the correct number of subs');

  
$t->diag('Create/update full_path cache');

  $page = new UllCmsPage();
  $page->Translation['en']->title = 'foo';
  $page->Translation['de']->title = 'furcht';
  $page->Translation['en']->body = 'bar';
  $page->Translation['en']->body = 'bar, oder?';
  $page->Parent = Doctrine::getTable('UllCmsPage')->findOneBySlug('about-us');
  $page->save();
  
  $t->is($page->full_path, 'Main menu - About us - foo', 'Creates the correct full_path');
  $t->is($page->Translation['en']->full_path, 'Main menu - About us - foo', 'Creates the correct full_path for english');
  $t->is($page->Translation['de']->full_path, 'Hauptmenü - Über uns - furcht', 'Creates the correct full_path for german');
  
