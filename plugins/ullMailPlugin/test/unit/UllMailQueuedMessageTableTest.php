<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(4, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('querySpooledMessages()');
  $q = UllMailQueuedMessageTable::querySpooledMessages();

  $t->is(
     $q->count(), 
     0, 
    'Returns the correct number of unsent messages when having no messages'
  );
  
  $message = new UllMailQueuedMessage;
  $message->message = 'foo';
  $message->save();
  
  $t->is(
     $q->count(), 
     1, 
    'Returns the correct number of unsent messages for one unsent message'
  );  
  
  $message = new UllMailQueuedMessage;
  $message->message = 'bar';
  $message->save();  
  
  $t->is(
     $q->count(), 
     2, 
    'Returns the correct number of unsent messages for two unsent messages'
  );    
  
  $message->is_sent = true;
  $message->save();
  
  $t->is(
     $q->count(), 
     1, 
    'Returns the correct number of unsent messages for one sent and one unsent message'
  );  
  
