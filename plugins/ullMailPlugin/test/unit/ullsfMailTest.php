<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');


$t = new myTestCase(31, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $mail = new ullsfMail();
  $t->isa_ok($mail, 'ullsfMail', 'returns the correct object');

$t->diag('addAddress() for a UllUser');

  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $mail->addAddress($user);
  $reference = array('test.user@example.com' => 'Test User');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: address for a UllUser');
  $t->is($mail->getRecipientUllUserId(), $user->id, 'Also sets the correct recipient UllUserId');
  
$t->diag('addAddress() for a UllGroup having a group email address');  
  
  $mail = new ullsfMail();
  $group = Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup');
  $mail->addAddress($group);
  $reference = array('test.group@example.com' => 'TestGroup');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: address for a UllGroup with a group email address');

$t->diag('addAddress() for a UllGroup without a group email address');  
  
  $mail = new ullsfMail();
  $group = Doctrine::getTable('UllGroup')->findOneByDisplayName('MasterAdmins');
  
  // Add an inactive user to the group
  $inactiveUser = new UllUser;
  $inactiveUser->display_name = "Foo Long";
  $inactiveUser->email = "foo@example.com";
  $inactiveUser->username = "foolong";
  $inactiveUser->setInactive();
  $inactiveUser->save();
  $group->UllUser[] = $inactiveUser;
  $group->save();    
  
  $mail->addAddress($group);
  $reference = array('admin@example.com' => 'Master Admin');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: addresses for a UllGroup without a group email address while ignoring inactive users.');  

  
$t->diag('set/getBody()');
  $mail = new ullsfMail();
  
  $mail->setBody('moooh');
  $t->is($mail->getBody(), 'moooh', 'Returns the correct body');
  $t->is(count($mail->getChildren()), 0, 'No child mime parts');
  $t->is($mail->getContentType(), 'text/plain', 'We have a plaintext email');
  
  
$t->diag('set/getHtmlBody()');
  $mail = new ullsfMail();
  
  $mail->setHtmlBody('<b>moooh</b>');
  $t->is($mail->getHtmlBody(), '<b>moooh</b>', 'Returns the correct body');
  $t->is($mail->getBody(), '<b>moooh</b>', 'Also getBody() returns the same body');
  $t->is(count($mail->getChildren()), 0, 'No child mime parts');
  $t->is($mail->getContentType(), 'text/html', 'We have a html only email');
  $t->is($mail->getIsHtml(), true, 'Set as html');  

  $mail->setPlaintextBody('miaouuuu');
  $t->is(count($mail->getChildren()), 1, '1 Child parts');
  $t->is($mail->getContentType(), 'multipart/alternative', 'We have a multipart email');
  
  
$t->diag('set/getPlaintextBody()');
  // reuses mail object from test above
  $mail->setPlaintextBody('foo bar');
  $t->is($mail->getPlaintextBody(), 'foo bar', 'Returns the correct plaintext body');
  $t->is(count($mail->getChildren()), 1, '1 child mime part');
  
  $mail->setPlaintextBody('foo bar again');
  $t->is($mail->getPlaintextBody(), 'foo bar again', 'Changes and returns the correct plaintext body');
  $t->is(count($mail->getChildren()), 1, 'Still 1 child mime parts therefore the existing part was updated not a new one added');
    
  
$t->diag('setBodies()');
  // reuses mail object from test above
  $mail->setBodies('<h1>new html</h1>', 'new foo bar');
  $t->is($mail->getBody(), '<h1>new html</h1>', 'Returns the correct body');
  $t->is($mail->getPlaintextBody(), 'new foo bar', 'Changes and returns the correct plaintext body');  

  
$t->diag('set/getIsHtml()');
  $mail = new ullsfMail();
  
  $t->is($mail->getIsHtml(), false, 'A fresh mail is not an html mail');
  $mail->setIsHtml(true);
  $t->is($mail->getIsHtml(), true, 'Now marked as html mail');  

  
$t->diag('set/getNewsletterEditionId()');
  $mail = new ullsfMail();
  
  $t->same($mail->getNewsletterEditionId(), null, 'No newsletter edition id by default');
  $mail->setNewsletterEditionId(99);
  $t->same($mail->getNewsletterEditionId(), 99, 'Newsletter correctly returned');
  
  
$t->diag('set/getRecipientUllUserId()');
  $mail = new ullsfMail();
  
  $t->same($mail->getRecipientUllUserId(), null, 'No recipient UllUser id by default');
  $mail->setRecipientUllUserId(2);
  $t->same($mail->getRecipientUllUserId(), 2, 'recipient UllUser id correctly returned');  
  
  
$t->diag('set/getIsQueued()');
  $mail = new ullsfMail();
  
  $t->same($mail->getIsQueued(), false, 'false by default');
  $mail->setIsQueued(true);
  $t->same($mail->getIsQueued(), true, 'now true');  
  
  
$t->diag('clearRecipients()');
  $mail = new ullsfMail();
  $mail->addAddress(Doctrine::getTable('UllUser')->findOneByUsername('test_user'));
  $mail->clearRecipients();
  $t->is($mail->getAddresses(), array(), 'No more recipients');  
  $t->is($mail->getRecipientUllUserId(), null, 'Also the recipient ull_user_id is unset');