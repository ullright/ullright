<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');


$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $mail = new ullsfMail();
  $t->isa_ok($mail, 'ullsfMail', 'returns the correct object');

$t->diag('addAddress()');

  $user = Doctrine::getTable('UllUser')->findOneByUsername('test_user');
  $mail->addAddress($user);
  $reference = array('test.user@example.com' => 'Test User');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: address for a UllUser');
  
  $mail = new ullsfMail();
  $group = Doctrine::getTable('UllGroup')->findOneByDisplayName('TestGroup');
  $mail->addAddress($group);
  $reference = array('test.group@example.com' => 'TestGroup');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: address for a UllGroup with a group email address');

  $mail = new ullsfMail();
  $group = Doctrine::getTable('UllGroup')->findOneByDisplayName('MasterAdmins');
  
  // Add an inactive user to the group
  $inactiveUser = new UllUser;
  $inactiveUser->display_name = "Foo Long";
  $inactiveUser->email = "foo@example.com";
  $inactiveUser->username = "foolong";
  $inactiveUser->setInactive();
  $inactiveUser->save();
  $group->UllUser[] = $inactiveUser;
  $group->save();    
  
  $mail->addAddress($group);
  $reference = array('admin@example.com' => 'Master Admin');
  $t->is($mail->getAddresses(), $reference, 'sets the correct to: addresses for a UllGroup without a group email address while ignoring inactive users.');  


$t->diag('debug cc exclude list');
  
  // specified an exclution list for bcc mails and enable debug cc
  sfConfig::add(array(
    'app_mailing_debug_cc_exclude_list' => array('ull_user_test_module'),
    'app_mailing_send_debug_cc' => true,
    'app_mailing_debug_address' => 'me@example.com',
    'app_mailing_enable' => true,
    'app_mailing_reroute' => false
  ));

  $mail = new ullsfMail();
  $mail->prepareForSending();
  $t->is($mail->getBccs(), array('me@example.com' => ''), 'sets the correct to: debug bcc');
  
  $mail = new ullsfMail('ull_user_test_module');
  $mail->prepareForSending();
  $t->is($mail->getBccs(), array(), 'complies to debug cc exclude list');