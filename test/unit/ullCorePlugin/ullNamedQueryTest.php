<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

sfContext::createInstance($configuration);
sfLoader::loadHelpers('I18N');

$t = new lime_test(6, new lime_output_color);

$t->diag('__construct()');
  $namedQuery = new ullNamedQueryTest;
  $t->isa_ok($namedQuery, 'ullNamedQueryTest', 'returns the correct object');
  
  
$t->diag('getName()');
  $t->is($namedQuery->getName(), 'My test query', 'returns the correct name');
  
  
$t->diag('getIdentifier()');
  $t->is($namedQuery->getIdentifier(), 'my_test_query', 'returns the correct name');

  
$t->diag('getUri()');
  try
  {
    $namedQuery->getUri();
    $t->fail('Doesn\'t throw an exception if no baseUri was defined');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception if no baseUri was defined');
  }
  $namedQuery->setBaseUri('ullUser/list');
  $t->is($namedQuery->getUri(), 'ullUser/list?query=my_test_query', 'returns the correct URI');  

  
$t->diag('modifyQuery()');
  $q = new Doctrine_Query;
  $q
    ->select('u.last_name')
    ->from('UllUser u')
  ;
  $namedQuery->modifyQuery($q);
  $expectedSql = 'SELECT u.id AS u__id, u.last_name AS u__last_name FROM ull_entity u WHERE u.username = ? AND (u.type = \'user\')';
  $t->is($q->getSql(), $expectedSql, 'correctly modifies the query');