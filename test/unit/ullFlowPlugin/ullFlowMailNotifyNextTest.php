<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers('I18N');

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $user = Doctrine::getTable('UllUser')->findOneByUserName('helpdesk_user');
  $mail = new ullFlowMailNotifyNext($doc, $user);
  $t->isa_ok($mail, 'ullFlowMailNotifyNext', 'returns the correct object');
  
$t->diag('prepare()');
  
  $mail->prepare();
  
  $reference = array('admin@example.com' => 'Master Admin');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: addresses');
  
  $t->is($mail->getSubject(), 'Trouble ticket: "My first trouble ticket"', 'creates the correct subject');
  
  $reference = 'Hello Master Admin,

Please take care of trouble_ticket "My first trouble ticket".

Link: http:///ullFlow/edit/doc/1

Kind regards,
Helpdesk User
';
  
  $t->is($mail->getBody(), $reference, 'creates the correct body');
  
  
  
  
  
  