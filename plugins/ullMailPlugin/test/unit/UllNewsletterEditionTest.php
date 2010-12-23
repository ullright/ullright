<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(14, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('findRecipients()');
  $edition = Doctrine::getTable('UllNewsletterEdition')->findOneById(1); // 1 = ullright presents "ullMail"
  
  $recipients = $edition->findRecipients();
  
  $t->is(count($recipients), 2, 'Returns two recipients:');
  $t->is($recipients[0]->username, 'admin', 'admin');
  $t->is($recipients[1]->username, 'test_user', 'and testuser.');

  
$t->diag('countRecipients()');
  $edition = Doctrine::getTable('UllNewsletterEdition')->findOneById(1); // 1 = ullright presents "ullMail"
  
  $recipients = $edition->countRecipients();
  
  $t->is($edition->countRecipients(), 2, 'Returns the correct number of recipients');
  
  
$t->diag('findMailingListsForUser()');

  $lists = $edition->findMailingListsForUser(1); // 1 = Master Admin
  
  $t->is(count($lists), 2, 'Returns 2 lists for Master Admin');
  $t->is($lists[0]['name'], 'Best practices', 'Returns the correct list');
  $t->is($lists[1]['name'], 'Product news', 'Returns the correct list');
  
  $lists = $edition->findMailingListsForUser(2); // 1 = Test user
  
  $t->is(count($lists), 1, 'Returns 1 list for Test User');
  $t->is($lists[0]['name'], 'Product news', 'Returns the correct list');
  
  
$t->diag('createMailMessage()');

  $user = UllUserTable::findByDisplayName('Test User');
  $edition = Doctrine::getTable('UllNewsletterEdition')->findOneById(1); // 1 = ullright presents "ullMail"
  
  $mail = $edition->createMailMessage($user);
  
  $t->isa_ok($mail, 'ullsfMail', 'Created the correct object');
  $t->is($mail->getSubject(), 
    'ullright presents "ullMail" - our easy-cheesy newsletter system', 
    'Returns the correct subject'
  );
  $t->is($mail->getFrom(), 
    array('test.user@example.com' => 'Test User'), 
    'Returns the correct sender'
  );
  $t->is($mail->getHtmlBody(), 
    '<img src="http://www.ullright.org/ullCoreThemeNGPlugin/images/logo_120.png" /><br /><h1>ullright News</h1><p>Hello [FIRST_NAME] [LAST_NAME],</p><p>we are proud to present our newsletter system. Here are the main features:</p><ul><li>Newsletter categories</li><li>Web-archive</li><li>Tracking</li></ul><img src="http://www.ullright.org/ullMailThemeNGPlugin/images/ull_mail_32x32.png" /><p>Have a nice day.</p><p>[UNSUBSCRIBE]</p><p>(C) 2011 by ull.at</p>', 
    'Returns the correct body'
  );
  $t->is($mail->getNewsletterEditionId(), 1, 'Returns the newsletter edition id');
  