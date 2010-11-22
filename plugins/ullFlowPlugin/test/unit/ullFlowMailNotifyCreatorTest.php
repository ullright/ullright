<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull', 'I18N'));

ullCoreTools::fixRoutingForCliAbsoluteUrls();

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $mail = new ullFlowMailNotifyCreator($doc);
  $t->isa_ok($mail, 'ullFlowMailNotifyCreator', 'returns the correct object');
  
$t->diag('prepare()');
  
  $mail->prepare();
  
  $reference = array('test.user@example.com' => 'Test User');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: addresses');
  
  $t->is($mail->getSubject(), 'Trouble ticket "My first trouble ticket" has been created', 'sets the correct subject');
  
  $reference = 'Hello Test User,

Trouble ticket "My first trouble ticket" has been created.

Kind regards,
Master Admin

Link: http://www.example.com/ullFlow/edit/doc/1
';
  
  $t->is($mail->getBody(), $reference, 'creates the correct body');
  
  
  
  
  
  