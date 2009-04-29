<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');
  $doc = new UllWiki;
  $t->isa_ok($doc, 'UllWiki', 'returns the correct object');
  
$t->diag('create');
  $doc->subject = 'Foobar subject';
  $doc->addTag('foobar tag');
  $doc->UllWikiAccessLevel = Doctrine::getTable('UllWikiAccessLevel')->findOneBySlug('public_readable');
  $doc->save();
  
  $t->is($doc->subject, 'Foobar subject', 'sets the subject correctly');
  $t->is($doc->getTags(), array('foobar tag' => 'foobar tag'), 'sets the subject correctly');
