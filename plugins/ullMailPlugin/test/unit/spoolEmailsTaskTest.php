<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(9, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('Check environment');

$t->is(count(Doctrine::getTable('UllMailQueuedMessage')->findAll()), 0, 'No queued messages');
$t->is(count(Doctrine::getTable('UllMailLoggedMessage')->findAll()), 2, '2 logged messages from fixtures');

$t->diag('Create and send newsletter');

// Create invalid email address
$testUser = Doctrine::getTable('UllUser')->findOneByEmail('test.user@example.com');
$testUser->email = 'invalid.@example.com';
$testUser->save();

$msg = new UllNewsletterEdition();
$msg->subject = 'Spool test';
$msg->body = 'Body count';
$msg->submitted_at = new Doctrine_Expression('NOW()');
$msg->Sender = $testUser;
$msg->UllNewsletterEditionMailingLists[] = 
  Doctrine::getTable('UllNewsletterMailingList')->findOneBySlug('product-news');
$msg->save();

$t->diag('Spooling...');

echo shell_exec('php symfony ull_mail:spool-emails frontend test');

$t->diag('Checking results');

  $t->is(count(Doctrine::getTable('UllMailQueuedMessage')->findAll()), 1, 'Only one queued message as one address is invalid');
  $t->is(empty($msg->queued_at), false, 'The edition is now set as queued');
  
  $t->is(count(Doctrine::getTable('UllMailLoggedMessage')->findAll()), 3, '3 logged messages from fixtures, 2 from fixtures, one failed with invalid email address');
  $failedMessage = Doctrine::getTable('UllMailLoggedMessage')->findOneBySubject('Spool test');
  $t->is(empty($failedMessage->failed_at), false, 'Failed message has a failed_at date set');
  $t->is($failedMessage->UllMailError->slug, 'invalid-email-address', 'Failed messag has the correct error set');
  $t->is($failedMessage->last_error_message, 'spoolEmailsTask: invalid address: invalid.@example.com', 'Failed message has the correct error msg set');
  
  $t->is($msg->num_failed_emails, 1, 'UllMailEdition::num_failed_emails is set to 1');


