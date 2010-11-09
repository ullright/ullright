<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('save()');

  $page = new UllCmsPage;
  $page->Translation['en']->title = 'My foobar title';
  $page->Translation['de']->title = 'Mein foobar Titel';
  $page->save();
  
  $t->is($page->name, 'My foobar title', 'Uses the title as name when no name is given');
  $t->is($page->Translation['en']->name, 'My foobar title', 'Uses the title as name when no name is given for english');
  $t->is($page->Translation['de']->name, 'Mein foobar Titel', 'Uses the title as name when no name is given for german');

  
