<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(5, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('getSubscribedByDefaultIds()');
  $t->is(UllNewsletterMailingListTable::getSubscribedByDefaultIds(), array(101), 'Returns the correct ids');


$t->diag('create two ullNewsletterMailingList objects, check default MailingList');
  $listOne = new UllNewsletterMailingList();
  $listOne->name = 'testListOne';
  $listOne->save();
  
  $listTwo = new UllNewsletterMailingList();
  $listTwo->name = 'testListTwo';
  $listTwo->save();
  
  $t->is(PluginUllNewsletterMailingListTable::getDefaultIds(), array(), 'There isn\'t a default list');
  
$t->diag('set lists as default');
  $listOne->is_default = true;
  $listOne->save();
  
  $t->is(PluginUllNewsletterMailingListTable::getDefaultIds(), array($listOne->id), 'List one is default');
  
  
  $listTwo->is_default = true;
  $listTwo->save();
  
  $t->is(PluginUllNewsletterMailingListTable::getDefaultIds(), array($listOne->id, $listTwo->id), 'Both are default');
  
  
  $listOne->is_default = false;
  $listOne->save();
  
  $t->is(PluginUllNewsletterMailingListTable::getDefaultIds(), array($listTwo->id), 'List two is default');