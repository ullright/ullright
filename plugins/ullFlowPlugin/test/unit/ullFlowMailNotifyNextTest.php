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
  $user = Doctrine::getTable('UllUser')->findOneByUserName('helpdesk_user');
  $mail = new ullFlowMailNotifyNext($doc, $user);
  $t->isa_ok($mail, 'ullFlowMailNotifyNext', 'returns the correct object');
  
$t->diag('prepare()');
  
  $mail->prepare();
  
  $reference = array('admin@example.com' => 'Master Admin');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: addresses');
  
  $t->is($mail->getSubject(), 'Trouble ticket: "My first trouble ticket"', 'creates the correct subject');
  
  $serverName = sfConfig::get('app_server_name', 'www.example.com');
  $reference = "Hello Master Admin,

Please take care of Trouble ticket \"My first trouble ticket\".

Link: http://$serverName/ullFlow/edit/doc/1

Kind regards,
Helpdesk User
";
  
  $t->is($mail->getBody(), $reference, 'creates the correct body');
  
  
  
  
  
  