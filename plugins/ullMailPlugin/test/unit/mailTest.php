<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(12, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('send mail');

$testUser = Doctrine::getTable('UllUser')->findOneByEmail('test.user@example.com');
$mailer = sfContext::getInstance()->getMailer();
$message = ullMailTestHelper::createMail();
$mailer->send($message);

$loggedMessages = Doctrine::getTable('UllMailLoggedMessage')->findAll();
$loggedMessage = $loggedMessages[2];

$t->is(count($loggedMessages), 3, 'mail log record is created');
$t->isntSame($loggedMessage, false, 'real mail log record is created');
$t->like($loggedMessage['headers'], '/Content-Type: multipart\/alternative/', 'log record headers contain correct content-type');
$t->is($loggedMessage['sender'], 'Example user <user@example.com>', 'log record sender is correct');
foreach (array(
	'to' => 'Test user <test.user@example.com>',
  'cc' => 'CC test user <ccuser@example.com>,ccuser2@example.com',
  'bcc' => 'BCC test user <bccuser@example.com>') as $recipientType => $expectedContent)
{
  $t->is($loggedMessage[$recipientType . '_list'], $expectedContent, 'log record ' . $recipientType . '-list is correct');
}
$t->is($loggedMessage['subject'], 'Example subject', 'log record subject is correct');
$t->is($loggedMessage['plaintext_body'], 'I am a boring plaintext body.', 'log record plaintext body is correct');
$t->is($loggedMessage['html_body'], 'I have an absolutely <em>amazing</em> body!', 'log record html body is correct');
$t->is($loggedMessage['transport_sent_status'], 0, 'transport sent status is correct');
$t->is($loggedMessage['main_recipient_ull_user_id'], $testUser['id'], 'main recipient\'s UllUser id is correct');
