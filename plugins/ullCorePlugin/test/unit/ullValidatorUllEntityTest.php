<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';


class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(16, new lime_output_color, $configuration);
$path = sfConfig::get('sf_root_dir') . '/plugins/ullCorePlugin/data/fixtures/';
$t->setFixturesPath($path);


$t->begin('__construct()');

  try
  {
    new ullValidatorUllEntity();
    $t->fail('__construct() throws an RuntimeException if you don\'t pass an expected option');
  }
  catch (Exception $e)
  {
    $t->pass('__construct() throws an RuntimeException if you don\'t pass an expected option');
  }

$adminId = UllUserTable::findIdByDisplayName('Master Admin');
$testUserId = UllUserTable::findIdByDisplayName('Test User');
$testGroupId = UllGroupTable::findIdByDisplayName('TestGroup');


$t->diag('clean() for UllUsers');

  $v = new ullValidatorUllEntity(array('entity_classes' => array('UllUser')));

  $t->is($v->clean($adminId), $adminId, 'Ok for user Master Admin');
  $t->is($v->clean($testUserId), $testUserId, 'Ok for user Test User');
  
  try
  {
    $v->clean('666');
    $t->fail('Does not throw an exception for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Does not accept an invalid UllUser::id');
  }  
  
  try
  {
    $v->clean($testGroupId);
    $t->fail('Does not throw an exception for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Does not accept an UllGroup::id');
  }  
  
  
$t->diag('clean() for UllUsers and UllGroups');  
  
  $v = new ullValidatorUllEntity(array('entity_classes' => array('UllUser', 'UllGroup')));

  $t->is($v->clean($adminId), $adminId, 'Ok for user Master Admin');
  $t->is($v->clean($testUserId), $testUserId, 'Ok for user Test User');
  $t->is($v->clean($testGroupId), $testGroupId, 'Ok for user Test Group');
  
  try
  {
    $v->clean(666);
    $t->fail('Does not throw an exception for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Does not accept an invalid id');
  }  
  
  
  
$t->diag('clean() for UllUsers with option hide_choices');  
  
  $v = new ullValidatorUllEntity(array(
    'entity_classes' => array('UllUser'), 
    'hide_choices' => array($testUserId),
  ));

  $t->is($v->clean($adminId), $adminId, 'Ok for user Master Admin');
  
  try
  {
    $v->clean($testUserId);
    $t->fail('Does not throw an exception for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Does not accept Test User, because he is in hidden choices');
  }  
  
  
$t->diag('clean() for UllUsers with option filter_users_by_group');  
  
  $v = new ullValidatorUllEntity(array(
    'entity_classes' => array('UllUser'), 
    'filter_users_by_group' => 'TestGroup',
  ));

  $t->is($v->clean($testUserId), $testUserId, 'Ok for user Master Admin');
  
  try
  {
    $v->clean($adminId);
    $t->fail('Does not throw an exception for an invalid value');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('Does not accept Admin because he is not in the test group');
  }    
  

$t->diag('clean() for invalid option combination');  
  
  $v = new ullValidatorUllEntity(array(
    'entity_classes' => array('UllGroup'), 
    'filter_users_by_group' => 'TestGroup',
  ));

  try
  {
    $v->clean('whatever');
    $t->fail('Does not throw an exception for an invalid option combination');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception for a invalid option combination');
  }      
  
$t->diag('clean() for _all_');

  $v = new ullValidatorUllEntity(array('entity_classes' => array('UllUser'), 'required' => false));

  $t->is($v->clean('_all_'), '', 'Allows filter form "empty" value for required = false');
  
  $v = new ullValidatorUllEntity(array('entity_classes' => array('UllUser'), 'required' => true));
  
  try
  {
    $v->clean('_all_');
    $t->fail('Does not throw an exception for an empty value');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception for an empty value');
  }    
  
