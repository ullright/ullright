<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $doc->UllFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug('reject');
  $doc->memoryComment = 'Mmmmhh, bacon!';
//  $doc->save();
//  var_dump($doc->toArray());die;
  $user = Doctrine::getTable('UllUser')->findOneByUserName('helpdesk_user');
  $mail = new ullFlowMailReject($doc, $user);
  $t->isa_ok($mail, 'ullFlowMailReject', 'returns the correct object');
  
$t->diag('prepare()');
  
  $mail->prepare();
  
  $reference = array('admin@example.com' => 'Master Admin');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: addresses');
  
  $t->is($mail->getSubject(), 'Trouble ticket: "My first trouble ticket" has been rejected', 'creates the correct subject');
  
  $reference = 'Hello Master Admin,

Trouble ticket: "My first trouble ticket" has been rejected.

Comment: Mmmmhh, bacon!

Link: http:///ullFlow/edit/doc/1

Kind regards,
Helpdesk User
';
  
  $t->is($mail->getBody(), $reference, 'creates the correct body');
  
  
  
  
  
  