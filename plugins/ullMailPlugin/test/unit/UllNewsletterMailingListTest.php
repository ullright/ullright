<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new sfDoctrineTestCase(10, new lime_output_color, $configuration);
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

  
$t->diag('subscribeUsers()');
  $list = Doctrine::getTable('UllNewsletterMailingList')->findOneBySlug('product-news'); 
  $users = Doctrine::getTable('UllUser')->findAll();
  $count = $list->subscribeUsers($users);
  $subscriptions = Doctrine::getTable('UllNewsletterMailingListSubscriber')->findAll();
  
  $t->is($count, 2, 'Two new subscriptions');
  $t->is(count($subscriptions), 3, 'We now have three subscriptions in total again');

  
$t->diag('subscribeUsers() an existing user');
  $admin = UllUserTable::findByDisplayName('Master Admin');
  
  $count = $list->subscribeUsers($admin);
  $subscriptions = Doctrine::getTable('UllNewsletterMailingListSubscriber')->findAll();
  
  $t->is($count, 0, 'No new subscription');
  $t->is(count($subscriptions), 3, 'We still have three subscriptions in total');  


// Note: this test implies, that no other data is written into the ullUser log field  
$t->begin('ullUser log subscription entry');
  $list = Doctrine::getTable('UllNewsletterMailingList')->findOneBySlug('product-news');

  $user = new UllUser;
  $user->first_name = "Kasimir";
  $user->username = "kasimir";
  $user->save();
  
  $t->is($user->log, '', 'ullUser log is empty before any sub/unsubscriptions');  
    
  
$t->diag('ullUser log entry upon subscription');
  $list->subscribeUsers($user);
  $t->is($user->getLogEntry(), 'Subscribed to newsletter mailing list "Product news"', 'Creates ullUser log entry');
  
  
$t->diag('ullUser log entry upon unsubscription');
  $list->unsubscribeUsers($user);
  $t->is($user->getLogEntry(), 'Unsubscribed from newsletter mailing list "Product news"', 'Creates ullUser log entry');
  
  
  
  

