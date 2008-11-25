<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
//sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
sfLoader::loadHelpers('I18N');

$t = new myTestCase(5, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);  
  // "log in" as Testuser (id 3)
  $user = Doctrine::getTable('UllUser')->findOneByUserName('helpdesk_user');
  $mail = new ullFlowMail($doc, $user);
  $t->isa_ok($mail, 'ullFlowMail', 'returns the correct object');
  $t->is($mail->getUser()->id, 7, 'sets the correct given user');
  
  sfContext::getInstance()->getUser()->setAttribute('user_id', 3);
  $mail = new ullFlowMail($doc);
  $t->is($mail->getUser()->id, 3, 'sets the correct user if logged in');
  
  sfContext::getInstance()->getUser()->getAttributeHolder()->remove('user_id');
  $mail = new ullFlowMail($doc);
  $t->is($mail->getUser()->id, 1, 'sets the correct user if not logged in');
  
$t->diag('getEditLink()');

  $t->is($mail->getEditLink(), 'Link: http:///ullFlow/edit/doc/1', 'returns the correct URL');
  
  
  
  
  