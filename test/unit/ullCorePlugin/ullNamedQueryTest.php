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
  $q = new ullQuery('TestTable');
  $q
    ->addSelect('my_email')
  ;
  $namedQuery->modifyQuery($q);
  $expectedSql = 'SELECT t.id AS t__id, t.my_email AS t__my_email FROM test_table t WHERE (t.my_useless_column = ?)';
  $t->is($q->getSqlQuery(), $expectedSql, 'correctly modifies the query');