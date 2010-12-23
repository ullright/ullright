<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('findEditionsToBeSpooled()');

  $editions = UllNewsletterEditionTable::findEditionsToBeSpooled();
  
  $t->is(count($editions), 1, 'Got one composed edition that is not spooled from the fixtures');
  
  $edition = new UllNewsletterEdition;
  $edition->fromArray(array(
    'subject' => 'foo',
    'body'  => 'bar',
    'sent_at' => '2010-10-10 10:10:10',
  ));
  $edition->save();
  
  $editions = UllNewsletterEditionTable::findEditionsToBeSpooled();
  
  $t->is(count($editions), 2, 'Now we have one composed editions that is marked as to be spooled');
  
