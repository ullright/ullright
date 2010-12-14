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

$t->begin('findActiveNews()');
  $news = UllNewsTable::findActiveNews();
  $t->is(count($news), 2, 'Returns the correct number of news entries');  
  $t->is($news->getFirst()->slug, 'welcome-i-hope-you-re-ullright', 'Returns the correct news');
  $t->is($news->getLast()->slug, 'first-news', 'Returns the correct news');

$t->diag('findLatestActiveNews()');
  $news = UllNewsTable::findLatestActiveNews();
  $t->is(count($news), 2, 'Returns the correct number of news entries');  
  $t->is($news->getFirst()->slug, 'welcome-i-hope-you-re-ullright', 'Returns the correct news');
  $t->is($news->getLast()->slug, 'first-news', 'Returns the correct news');

$t->begin('findLatestNews()');

  $news = UllNewsTable::findLatestNews();
  $t->is($news->slug, 'welcome-i-hope-you-re-ullright', 'Returns the correct news');