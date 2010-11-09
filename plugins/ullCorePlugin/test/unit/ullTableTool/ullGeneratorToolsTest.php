<?php

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
//$request = sfContext::getInstance()->getRequest();

$t = new lime_test(29, new lime_output_color);


$t->diag('relationArrayToString()');
  $t->is(ullGeneratorTools::relationArrayToString(array('UllUser', 'UllLocation')), 'UllUser->UllLocation', 'Returns the correct string');

  
$t->diag('relationArrayToString()');
  $t->is(ullGeneratorTools::relationStringToArray('UllUser->UllLocation'), array('UllUser', 'UllLocation'), 'Returns the correct array');

  
$t->diag('arrayizeRelations()');
  $t->is(ullGeneratorTools::arrayizeRelations('UllUser->UllLocation'), array('UllUser', 'UllLocation'), 'Returns the correct array');
  $t->is(ullGeneratorTools::arrayizeRelations(array('UllUser', 'UllLocation')), array('UllUser', 'UllLocation'), 'Returns the correct array');
  
  
$t->diag('hasRelations()');
  $t->is(ullGeneratorTools::hasRelations('name'), false, 'Returns false for a string without relations');
  $t->is(ullGeneratorTools::hasRelations('UllUser->name'), true, 'Returns true for a string with relations');

  
$t->diag('getFinalModelFromRelations()');
  $t->is(ullGeneratorTools::getFinalModelFromRelations('TestTable', 'Creator'), 'UllUser', 'Returns the correct model');
  $t->is(ullGeneratorTools::getFinalModelFromRelations('TestTable', array('Creator')), 'UllUser', 'Returns the correct model');
  $t->is(ullGeneratorTools::getFinalModelFromRelations('TestTable', 'Creator->UllLocation'), 'UllLocation', 'Returns the correct model');
  $t->is(ullGeneratorTools::getFinalModelFromRelations('TestTable', array('Creator', 'UllLocation')), 'UllLocation', 'Returns the correct model');
  try
  {
    ullGeneratorTools::getFinalModelFromRelations('TestTable', array('Creator', 'foobar'));
    $t->fail('Doesn\'t throw an exception when giving an invalid relation');
  }
  catch (Exception $e)
  {
    $t->pass('Throw an exception when giving an invalid relation');
  }
  
$t->diag('isValidColumn()');
  $t->is(ullGeneratorTools::isValidColumn('TestTable', 'my_email'), true, 
    'Returns true for an existing column');  
  $t->is(ullGeneratorTools::isValidColumn('TestTable', 'foobar'), false, 
    'Returns false for an invalid column name');
  $t->is(ullGeneratorTools::isValidColumn('TestTable', 'UllUser->username'), true, 
    'Returns true for an existing relation column');  
  $t->is(ullGeneratorTools::isValidColumn('TestTable', 'UllUser->foobar'), false, 
    'Returns false for an invalid relation column name');  
  $t->is(ullGeneratorTools::isValidColumn('FooTable', 'foobar'), false, 
    'Returns false for an invalid model name');
  $t->is(ullGeneratorTools::isValidColumn('TestTable', 'UllUser->UllEmploymentType->name'), true, 
    'Returns true for a translated column');
  
  
$t->diag('validateColumnNames()');
  $t->is(ullGeneratorTools::validateColumnNames('TestTable', array('my_email', 'UllUser->username')), true, 'Return true for valid columns');
  try
  {
    ullGeneratorTools::validateColumnNames('TestTable', array('my_email', 'foobar', 'UllUser->baz'));
    $t->fail('Doesn\'t throw an exception for invalid columns');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when giving invalid columns');
    $t->is($e->getMessage(), 'Unkown columns: foobar,UllUser->baz', 'Returns the correct exception message');
  }         
  try
  {
    ullGeneratorTools::validateColumnNames('TestTable', array('my_email', 'foobar', 'FooBar->baz'));
    $t->fail('Doesn\'t throw an exception for invalid columns');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when giving invalid columns');
    $t->is($e->getMessage(), 'Unknown relation alias FooBar', 'Returns the correct exception message');
  }  

  
$t->diag('buildRelationsLabel()');
  $t->is(ullGeneratorTools::buildRelationsLabel('TestTable', 'Creator->UllLocation'), 'Created by Location', 'Builds the correct label for non-customized relation names');  
  $t->is(ullGeneratorTools::buildRelationsLabel('TestTable', 'UllUser->UllLocation'), 'Owner special location', 'Builds the correct label for custom relation names');

  
$t->diag('convertOrderByFromUriToQuery()');
  $t->is(
    ullGeneratorTools::convertOrderByFromUriToQuery('subject|UllUser->username:desc'),
    'subject, UllUser->username desc',
    'Returns the correct string');

    
$t->diag('convertOrderByFromQueryToUri()');
  $t->is(
    ullGeneratorTools::convertOrderByFromQueryToUri('subject, UllUser->username desc'),
    'subject|UllUser->username:desc',
    'Returns the correct string');    
    
$t->diag('arrayizeOrderBy()');
  $t->is(
    ullGeneratorTools::arrayizeOrderBy('subject, UllUser->username desc'),
    array(
      array(
        'column'    => 'subject',
        'direction' => 'asc',
      ),
      array(
        'column'    => 'UllUser->username',
        'direction' => 'desc',
      ),
    ),
    'Returns the correct array');
  
$t->diag('stringizeOrderBy');
  $t->is(
    ullGeneratorTools::stringizeOrderBy(
      array(
        array(
          'column'    => 'subject',
          'direction' => 'asc',
        ),
        array(
          'column'    => 'UllUser->username',
          'direction' => 'desc',
        ),
      )
    ),
    'subject asc, UllUser->username desc',
    'Returns the correct string for a multi column orderBy statement'
  );
  $t->is(
    ullGeneratorTools::stringizeOrderBy(
      array(
        'column'    => 'subject',
        'direction' => 'asc',
      )
    ),
    'subject asc',
    'Returns the correct string for a single column orderBy statement'
  );  
  