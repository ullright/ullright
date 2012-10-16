<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(12, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('process bounced emails');

$test_user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
$admin = Doctrine::getTable('UllUser')->findOneByUsername('admin');

$t->diag('Check initialisation state');

  $t->is($test_user->num_email_bounces, null, 'Bounce counter is NULL for test user before running the task');
  
  
$t->diag('Simulate temporary error and run task');  
  
  $undeliverableAddresses = array(
    'all' =>  $test_user->email,
    'by_severity' => array(
      'temporary' => array($test_user->email),
    ),
  );
  runTask($undeliverableAddresses);
    
  //refreshes the ullUser object
  $test_user->refresh();
  $t->is($test_user->num_email_bounces, null, 'Bounce counter is still NULL, because of temporary error (e.g. mailbox full)');
  
  
$t->diag('Simulate permanent error and run task');  
  
  $undeliverableAddresses = array(
    'all' =>  $test_user->email,
    'by_severity' => array(
      'permanent' => array($test_user->email),
    ),
  );
  runTask($undeliverableAddresses);
    
  //refreshes the ullUser object
  $test_user->refresh();
  $t->is($test_user->num_email_bounces, 1, 'Bounce counter is now 1, because of permanent error');
  
  runTask($undeliverableAddresses);
    
  //refreshes the ullUser object
  $test_user->refresh();
  $t->is($test_user->num_email_bounces, 2, 'Bounce counter is now 2, because of one more permanent error');
  
  
  
$t->diag('Test deleteMailAddressesOnBounceMax()');

  runTask($undeliverableAddresses);
  
  $test_user->refresh();
  $t->is($test_user->num_email_bounces, '0', 'Bounce exceeded limit of 3 - counter was reseted');
  $t->is($test_user->email, '', 'And the email address was deleted');
  
  
$t->diag('Test two users with the same mail address');

  // reset test_user
  $test_user->num_email_bounces = 0;
  $test_user->email = 'test@example.com';
  $test_user->save();
  
  // clone test_user
  $foo_user = $test_user->copy();
  $foo_user->username = 'foo_user';
  $foo_user->save();
  
  // Simulate permanent error for test user and run task
  $undeliverableAddresses = array(
    'all' =>  $test_user->email,
    'by_severity' => array(
      'permanent' => array($test_user->email),
    ),
  );
  runTask($undeliverableAddresses);
    
  $test_user->refresh();
  $foo_user->refresh();
  $t->is($test_user->num_email_bounces, '1', 'A failed email increased bounce counter for user 1...');
  $t->is($foo_user->num_email_bounces, '1', '...and also for user 2');

  runTask($undeliverableAddresses);
  runTask($undeliverableAddresses);
  
  $test_user->refresh();
  $foo_user->refresh();
  
  $t->is($test_user->num_email_bounces, '0', 'Exceeding the bounce counter resets the counter for user 1...');
  $t->is($foo_user->num_email_bounces, '0', '...and the same for user 2');
  
  $t->is($test_user->email, '', 'Also, the email address for user 1 was deleted...');
  $t->is($foo_user->email, '', '...and also for user 2');
  

  
// Simulate the run of the bounce task  
function runTask($undeliveredMailAddresses = array())
{
  $task = new ProcessBouncedEmailsTask(new sfEventDispatcher(), new sfFormatter());
  $task->increaseBounceCounter($undeliveredMailAddresses);
  $task->deleteMailAddressesOnBounceMax();
}
  
  
// write a record in the UllMailLoggedMessage table  
function fakeMailSending(UllUser $sender, UllUser $recipient, $subject, $failed = false)
{
  $fakeMail = new UllMailLoggedMessage();
  $fakeMail['sender'] = $sender->getEmailTo();
  $fakeMail['main_recipient_ull_user_id'] = $recipient->id;
  $fakeMail['to_list'] = $recipient->getEmailTo();
  $fakeMail['subject'] = $subject;
  $fakeMail['sent_at'] = new Doctrine_Expression('NOW()');
  if($failed)
  {
    $fakeMail['failed_at'] = new Doctrine_Expression('NOW()');
  }
  $fakeMail->save();
}