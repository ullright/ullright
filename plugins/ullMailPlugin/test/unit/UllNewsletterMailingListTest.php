<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('unsubscribeUsers()');

  $list = Doctrine::getTable('UllNewsletterMailingList')->findOneBySlug('product-news'); 
  
  $users = Doctrine::getTable('UllUser')->findAll();
  
  $list->unsubscribeUsers($users);
  
  $subscriptions = Doctrine::getTable('UllNewsletterMailingListSubscriber')->findAll();
  
  $t->is(count($subscriptions), 1, 'Only one subscription left');
  $t->is($subscriptions[0]->UllUser->username, 'admin', 'which is admin');
  $t->is($subscriptions[0]->UllNewsletterMailingList->name, 'Best practices', 'and for Best practices.');

