<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new sfDoctrineTestCase(9, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('__construct()');

  try 
  {
    $spool = new Swift_UllDoctrineSpool(
      'UllEmailMessage', 
      'message', 
      'querySpooledMessages', 
      30
    );
    $t->pass('Constructor accepts mailsPerMin param');
  }
  catch (Exception $e)
  {
    $t->fail('Constructor does not accept mailsPerMin param');
  }
  
  $t->isa_ok($spool, 'Swift_UllDoctrineSpool', 'Creates the correct object');

  
$t->diag('Swift_UllDoctrineSpool::calculateSleepTime()');
  try
  {
    Swift_UllDoctrineSpool::calculateSleepTime('foo');
    $t->fail('Accepts a non integer value');
  }
  catch (InvalidArgumentException $e)
  {
    $t->pass('Throws an Exception for non integer value');
  }
  
  try
  {
    Swift_UllDoctrineSpool::calculateSleepTime(null);
    $t->fail('Accepts a non integer value');
  }
  catch (InvalidArgumentException $e)
  {
    $t->pass('Throws an Exception for null value');
  }  
  
  try
  {
    Swift_UllDoctrineSpool::calculateSleepTime(0);
    $t->fail('Accepts value 0');
  }
  catch (OutOfRangeException $e)
  {
    $t->pass('Throws an Exception for value 0');
  }  
  
  $t->is(
    Swift_UllDoctrineSpool::calculateSleepTime(1),
    60000000,
    'Returns the correct sleep time'
  );
  
  $t->is(
    Swift_UllDoctrineSpool::calculateSleepTime(60),
    1000000,
    'Returns the correct sleep time'
  );  
  
  $t->is(
    Swift_UllDoctrineSpool::calculateSleepTime(100),
    600000,
    'Returns the correct sleep time'
  );   

  $t->is(
    Swift_UllDoctrineSpool::calculateSleepTime(59),
    1016949,
    'Returns the correct sleep time'
  );     

  
  
