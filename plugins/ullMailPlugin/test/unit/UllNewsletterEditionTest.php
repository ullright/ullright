<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(3, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('getRecipients()');
  $edition = Doctrine::getTable('UllNewsletterEdition')->findOneById(1);
  
  $recipients = $edition->getRecipients();
  
  $t->is(count($recipients), 2, 'Returns two recipients:');
  $t->is($recipients[0]->username, 'admin', 'admin');
  $t->is($recipients[1]->username, 'test_user', 'and testuser.');
