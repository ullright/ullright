<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
//sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!
//sfLoader::loadHelpers('I18N');

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $mail = new ullsfMail();
  $t->isa_ok($mail, 'ullsfMail', 'returns the correct object');

$t->diag('addAddress()');

  $entity = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $mail->addAddress($entity);
  $reference = array('test.user@example.com' => 'Test User');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: address for a UllUser');
  
  $mail = new ullsfMail();
  $entity = Doctrine::getTable('UllGroup')->findOneByDisplayName('MasterAdmins');
  $mail->addAddress($entity);
  $reference = array('admin@example.com' => 'Master Admin');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: address for a UllGroup without a group email address');

  $mail = new ullsfMail();
  $entity = Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup');
  $mail->addAddress($entity);
  $reference = array('test.group@example.com' => 'TestGroup');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: address for a UllGroup with a group email address');  

  