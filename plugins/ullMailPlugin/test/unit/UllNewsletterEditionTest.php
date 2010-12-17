<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(8, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('getRecipients()');
  $edition = Doctrine::getTable('UllNewsletterEdition')->findOneById(1); // 1 = ullright presents "ullMail"
  
  $recipients = $edition->getRecipients();
  
  $t->is(count($recipients), 2, 'Returns two recipients:');
  $t->is($recipients[0]->username, 'admin', 'admin');
  $t->is($recipients[1]->username, 'test_user', 'and testuser.');

  
$t->diag('getMailingListsForUser()');

  $lists = $edition->getMailingListsForUser(1); // 1 = Master Admin
  
  $t->is(count($lists), 2, 'Returns 2 lists for Master Admin');
  $t->is($lists[0]['name'], 'Best practices', 'Returns the correct list');
  $t->is($lists[1]['name'], 'Product news', 'Returns the correct list');
  
  $lists = $edition->getMailingListsForUser(2); // 1 = Test user
  
  $t->is(count($lists), 1, 'Returns 1 list for Test User');
  $t->is($lists[0]['name'], 'Product news', 'Returns the correct list');
  