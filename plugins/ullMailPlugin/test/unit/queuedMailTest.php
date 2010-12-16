<?php
include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

//for added fun, enable mail rerouting and always-add-debug-to-bcc
sfConfig::set('app_mailing_reroute', true);
sfConfig::set('app_mailing_send_debug_cc', true);
sfConfig::set('app_mailing_debug_address', 'dev@example.com');

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(18, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('send queued mail');

$mailer = sfContext::getInstance()->getMailer();
$message = ullMailTestHelper::createMail();
$mailer->sendQueue($message);

$loggedMessages = Doctrine::getTable('UllMailLoggedMessage')->findAll();

$t->is(count($loggedMessages), 2, 'no mail log record is created');

$queuedMessages = Doctrine::getTable('UllMailQueuedMessage')->findAll();
$queuedMessage = $queuedMessages->getFirst();
$t->is(count($queuedMessages), 1, 'one queued mail record is created');
$t->isntSame($queuedMessage, false, 'one real queued mail record is created');

$storedMessage = unserialize($queuedMessage['message']);
$expectedFromHeader = implode($storedMessage->getHeaders()->get('from')->getNameAddressStrings(), ',');
$t->is($expectedFromHeader, 'Example user <user@example.com>', 'stored message header \'from\' is correct');
$t->is($storedMessage->getTo(), array('test.user@example.com' => 'Test user'),
	'stored message to-list is original and correct');
$t->is($storedMessage->getCc(), array('ccuser@example.com' => 'CC test user', 'ccuser2@example.com' => null),
	'stored message cc-list is original and correct');
$t->is($storedMessage->getBcc(), array('bccuser@example.com' => 'BCC test user'),
	'stored message bcc-list is original and correct');

$t->diag('flushing queue');
$mailer->flushQueue();

$loggedMessages = Doctrine::getTable('UllMailLoggedMessage')->findAll();
$loggedMessage = $loggedMessages[2];

$t->is(count($loggedMessages), 3, 'after flushing, mail log record is created');
$t->isntSame($loggedMessage, false, 'after flushing, real mail log record is created');

$t->like($loggedMessage['headers'], '/Content-Type: multipart\/alternative/', 'log record headers contain correct content-type');
$t->like($loggedMessage['headers'], '/X-Swift-To: Test user <test.user@example.com>/', 'log record headers contain correct original recipients');
$t->like($loggedMessage['headers'], '/X-Swift-Cc: CC test user <ccuser@example.com>, ccuser2@example.com/', 'log record headers contain correct original CCs');
$t->like($loggedMessage['headers'], '/X-Swift-Bcc: BCC test user <bccuser@example.com>, dev@example.com/', 'log record headers contain correct original BCCs');
$t->is($loggedMessage['sender'], 'Example user <user@example.com>', 'log record sender is correct');
foreach (array(
	'to' => 'dev@example.com',
  'cc' => null,
  'bcc' => null) as $recipientType => $expectedContent)
{
  $t->is($loggedMessage[$recipientType . '_list'], $expectedContent, 'log record ' . $recipientType . '-list is correct');
}
$t->is($loggedMessage['main_recipient_ull_user_id'], null, 'main recipient\'s UllUser id is not set');
