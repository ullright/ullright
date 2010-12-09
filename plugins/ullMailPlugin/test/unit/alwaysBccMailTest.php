<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

//note: since this test is nearly the same as the mailTest,
//some checks are omitted. also see: mailTest, reroutedMailTest

//enable always-add-debug-to-bcc
sfConfig::set('app_mailing_send_debug_cc', true);
sfConfig::set('app_mailing_debug_address', 'dev@example.com');

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(6, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);

$t->begin('send mail with debug in bcc');

$mailer = sfContext::getInstance()->getMailer();
$message = ullMailTestHelper::createMail();
$mailer->send($message);

$loggedMessages = Doctrine::getTable('UllMailLoggedMessage')->findAll();
$loggedMessage = $loggedMessages[0];

$t->is(count($loggedMessages), 1, 'mail log record is created');
$t->isntSame($loggedMessage, false, 'real mail log record is created');
$t->is($loggedMessage['sender'], 'Example user <user@example.com>', 'log record sender is correct');
foreach (array(
	'to' => 'Test user <test.user@example.com>',
  'cc' => 'CC test user <ccuser@example.com>,ccuser2@example.com',
  'bcc' => 'BCC test user <bccuser@example.com>,dev@example.com') as $recipientType => $expectedContent)
{
  $t->is($loggedMessage[$recipientType . '_list'], $expectedContent, 'log record ' . $recipientType . '-list is correct');
}

