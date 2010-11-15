<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull', 'I18N'));

ullCoreTools::fixRoutingForAbsoluteUrls();

$t = new myTestCase(5, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);  
  $user = Doctrine::getTable('UllUser')->findOneByUserName('helpdesk_user');
  $mail = new ullFlowMail($doc, $user);
  $t->isa_ok($mail, 'ullFlowMail', 'returns the correct object');
  $t->is($mail->getUser()->id, $user->id, 'sets the correct given user');
  
  $testUser = Doctrine::getTable('UllUser')->findOneByUserName('test_user');
  sfContext::getInstance()->getUser()->setAttribute('user_id', $testUser->id);
  $mail = new ullFlowMail($doc);
  $t->is($mail->getUser()->id, $testUser->id, 'sets the correct user if logged in');
  
  sfContext::getInstance()->getUser()->getAttributeHolder()->remove('user_id');
  $mail = new ullFlowMail($doc);
  $t->is($mail->getUser()->id, 1, 'sets the correct user if not logged in');
  
$t->diag('getEditLink()');

  $t->is($mail->getEditLink(), 'Link: http://www.example.com/ullFlow/edit/doc/1', 'returns the correct URL');
  
  
  
  
  