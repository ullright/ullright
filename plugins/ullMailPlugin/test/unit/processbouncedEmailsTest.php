<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(18, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('process bounced emails');

$test_user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
$admin = Doctrine::getTable('UllUser')->findOneByUsername('admin');

$t->diag('send mail with delivery error');
  //send a fake mail with an delivery error
  fakeMailSending($admin, $test_user, 'test1', true);
  $t->is(
    Doctrine::getTable('UllMailLoggedMessage')->findLatestEntryByUser($test_user)->subject,
    'test1',
    'Check for latest entry ok'
  );
  
$t->diag('check if bounce counter increases for test_user');

  $t->is($test_user->num_email_bounces, null, 'Bounce counter is NULL');
  
  $undeliveredMailAddresses[] = $test_user->email;
  //increase two times (fake two delivery errors)
  runTask($undeliveredMailAddresses);
  runTask($undeliveredMailAddresses);
  
  //refreshes the ullUser object
  $test_user->refresh();
  $t->is($test_user->num_email_bounces, '2', 'Bounce counter is 2');
  
$t->diag('send mail which resets the bounce counter');
  
  //send a mail without a delivery error
  fakeMailSending($admin, $test_user, 'test2');
  
  //should reset the bounce counter
  runTask();
  
  //refresh
  $test_user->refresh();
  $t->is($test_user->num_email_bounces, '0', 'Bounce counter is reset');
  
$t->diag('set counter to max and delete mail address');
  
  //set the bounce counter to limit
  fakeMailSending($admin, $test_user, 'test1', true);
  for ($i = 0; $i < sfConfig::get('app_ull_mail_bounce_deactiation_threshold', 3); $i++)
  {
    runTask($undeliveredMailAddresses);
  }
  
  $test_user->refresh();
  $t->is($test_user->email, '', 'The email address is deleted');
  $t->is($test_user->num_email_bounces, '0', 'Bounce counter is reset');
    
$t->diag('two users have the same mail address (increase counter, reset, delete address)');
  // reset test_user
  $test_user->num_email_bounces = 0;
  $test_user->email = 'test@example.com';
  $test_user->save();
  
  // clone test_user
  $foo_user = $test_user->copy();
  $foo_user->username = 'foo_user';
  $foo_user->save();
  
  //send message to test_user with a delivery error
  fakeMailSending($admin, $test_user, 'test3', true);
  
  $undeliveredMailAddresses = array();
  $undeliveredMailAddresses[] = 'test@example.com';
  //should increase the bounce counter
  runTask($undeliveredMailAddresses);
  
  //refreshes the ullUser objects
  $test_user->refresh();
  $foo_user->refresh();
  $t->is($test_user->num_email_bounces, '1', 'Bounce counter for test_user is 1');
  $t->is($foo_user->num_email_bounces, '1', 'Bounce counter for foo_user is 1');
  
  //send message to foo_user without an error
  fakeMailSending($admin, $foo_user, 'test4');
  
  //should reset the bounce counter
  runTask();
  
  $test_user->refresh();
  $foo_user->refresh();
  $t->is($test_user->num_email_bounces, '0', 'Bounce counter for test_user is reset');
  $t->is($foo_user->num_email_bounces, '0', 'Bounce counter for foo_user is reset');
  
   //send message to test_user with a delivery error and increase bounce counter to limit
  fakeMailSending($admin, $test_user, 'test5', true);
  for ($i = 0; $i < sfConfig::get('app_ull_mail_bounce_deactiation_threshold', 3); $i++)
  {
    runTask($undeliveredMailAddresses);
  }
  
  
  $test_user->refresh();
  $foo_user->refresh();
  
  $t->is($test_user->email, '', 'The email address for test_user is deleted');
  $t->is($test_user->num_email_bounces, '0', 'Bounce counter for test_user is reset');
  
  $t->is($foo_user->email, '', 'The email address for foo_user is deleted');
  $t->is($foo_user->num_email_bounces, '0', 'Bounce counter for test_user is reset');
  
$t->diag('two users have the same mail address, increase counter, change one mail address, reset counter');
  // reset test_user
  $test_user->num_email_bounces = 0;
  $test_user->email = 'test@example.com';
  $test_user->save();
  
  // reset foo_user
  $foo_user->num_email_bounces = 0;
  $foo_user->email = 'test@example.com';
  $foo_user->save();
  
  //send message to test_user with a delivery error
  fakeMailSending($admin, $test_user, 'test3', true);
  
  $undeliveredMailAddresses = array();
  $undeliveredMailAddresses[] = 'test@example.com';
  //should increase the bounce counter
  runTask($undeliveredMailAddresses);
  
  //refreshes the ullUser objects
  $test_user->refresh();
  $foo_user->refresh();
  $t->is($test_user->num_email_bounces, '1', 'Bounce counter for test_user is 1');
  $t->is($foo_user->num_email_bounces, '1', 'Bounce counter for foo_user is 1');
  
  //change mail address
  $foo_user->email = 'foo@example.com';
  $foo_user->save();
  
  //send mail to foo_user
  fakeMailSending($admin, $foo_user, 'test3');
  
  //should reset the bounce counter
  runTask();
  
  $test_user->refresh();
  $foo_user->refresh();
  $t->is($test_user->num_email_bounces, '1', 'Bounce counter for test_user is not reset');
  $t->is($foo_user->num_email_bounces, '0', 'Bounce counter for foo_user is reset');
  

  
  
function runTask($undeliveredMailAddresses = array())
{
  $task = new ProcessBouncedEmailsTask(new sfEventDispatcher(), new sfFormatter());
  $task->increaseBounceCounter($undeliveredMailAddresses);
  $task->resetBounceCounter();
  $task->deleteMailAddressesOnBounceMax();
}
  
  
// write a record in the UllMailLoggedMessage table  
function fakeMailSending($sender, $recipient, $subject, $failed = false)
{
  $fakeMail = new UllMailLoggedMessage();
  $fakeMail['sender'] = getMailAddress($sender);
  $fakeMail['main_recipient_ull_user_id'] = $recipient->id;
  $fakeMail['to_list'] = getMailAddress($recipient);
  $fakeMail['subject'] = $subject;
  $fakeMail['sent_at'] = new Doctrine_Expression('NOW()');
  if($failed)
  {
    $fakeMail['failed_at'] = new Doctrine_Expression('NOW()');
  }
  $fakeMail->save();
}

function getMailAddress($user)
{
  return $user->display_name . ' <' . $user->email . '>';
}