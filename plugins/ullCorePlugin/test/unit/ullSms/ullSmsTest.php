<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

//sfContext::createInstance($configuration);
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
//$request = sfContext::getInstance()->getRequest();

$t = new lime_test(8, new lime_output_color);

$sms = new ullSms();

$t->isa_ok($sms, 'ullSms', 'Creates the correct class');

$sms->setFrom('+43 (098) 7654321');
$t->is($sms->getFrom(), '00430987654321', 'Gets/sets the sender correctly');

$sms->setTo('00431234567890');
$t->is($sms->getTo(), '00431234567890', 'Gets/sets the recipient correctly');

$sms->setTo('+43 123/45-678-90');
$t->is($sms->getTo(), '00431234567890', 'Overwrited the to number correctly, and correctly converts the number');

$sms->setText('Hi, this is a test message. Much too long  long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long');
$t->is($sms->getText(), 'Hi, this is a test message. Much too long  long long long long long long long long long long long long long long long long long long long long long long long lo', 'Limits the text messag to 160 characters');
$t->is(strlen($sms->getText()), 160, 'Returned exactly 160 characters');

$sms->setText('Hi, this is a test message. Here are also a few umlauts: äÄß');
$t->is($sms->getText(), 'Hi, this is a test message. Here are also a few umlauts: äÄß', 'Gets/sets the message correctly');

$reference = '00430987654321
00431234567890
Hi, this is a test message. Here are also a few umlauts: äÄß';

$t->is($sms->__toString(), $reference, 'Returns the correct "as string" result');

// Note: the send() shortcut function is tested in ullSmsTransportTest.php


