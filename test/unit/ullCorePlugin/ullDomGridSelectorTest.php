<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

//sfContext::createInstance($configuration);
//$request = sfContext::getInstance()->getRequest();
//sfLoader::loadHelpers('ull');
//sfLoader::loadHelpers('I18N');

$t = new lime_test(35, new lime_output_color);


$t->diag('convertArray()');
  $testNew = array('id', 'subject');
  $testOld = array('id' => 1, 'subject' => 2);
  $reference = array('id' => 1, 'subject' => 2);
  $t->is(ullDomGridSelector::convertArray($testNew), $reference, 'correctly converts the new simplified array format');  
  $t->is(ullDomGridSelector::convertArray($testOld), $reference, 'correctly leaves the the old array as it was');

  
$t->diag('__construct()');

  $s1 = new ullDomGridSelector('table > tbody', 'tr', 'td',
    // new, simplified way
    array(
      'subject',
      'body',
      'tags',
    ),
    // old, deprecated wasy
    array(
      'label'     => 1,
      'value'     => 2,
      'error'     => 3,
    ),
    'table > thead > tr', 'th'
  );
  $t->isa_ok($s1, 'ullDomGridSelector', 'returns the correct object');
  
  $s2 = new ullDomGridSelector('ul#memory', 'li', null, 
    array(
      'created',
      'status',
      'next',
    )
  );
  $t->isa_ok($s2, 'ullDomGridSelector', 'returns the correct object');
  
$t->diag('getBaseSelector()');
  $t->is($s1->getBaseSelector(), 'table > tbody', 'returns the correct result');
  $t->is($s2->getBaseSelector(), 'ul#memory', 'returns the correct result');
  
$t->diag('getRowSelector()');
  $t->is($s1->getRowSelector(), 'tr', 'returns the correct result');
  $t->is($s2->getRowSelector(), 'li', 'returns the correct result');

$t->diag('getColumnSelector()');
  $t->is($s1->getColumnSelector(), 'td', 'returns the correct result');
  $t->is($s2->getColumnSelector(), null, 'returns the correct result');
  
$t->diag('getHeaderBaseSelector()');
  $t->is($s1->getHeaderBaseSelector(), 'table > thead > tr', 'returns the correct result');
  $t->is($s2->getHeaderBaseSelector(), null, 'returns the correct result');  
  
$t->diag('getHeaderColumnSelector()');
  $t->is($s1->getHeaderColumnSelector(), 'th', 'returns the correct result');
  $t->is($s2->getHeaderColumnSelector(), null, 'returns the correct result');
  
$t->diag('getFullRowSelector()');
  $t->is($s1->getFullRowSelector(), 'table > tbody > tr', 'returns the correct result');
  $t->is($s2->getFullRowSelector(), 'ul#memory > li', 'returns the correct result');    

$t->diag('getFullHeaderColumnSelector()');
  $t->is($s1->getFullHeaderColumnSelector(), 'table > thead > tr > th', 'returns the correct result');
  $t->is($s2->getFullHeaderColumnSelector(), null, 'returns the correct result');    
  
  
$t->diag('get()');

  $t->is($s1->get(1, 1), 'table > tbody > tr:nth-child(1) > td:nth-child(1)', 'returns the correct result for 1,1');
  $t->is($s1->get(2, 1), 'table > tbody > tr:nth-child(2) > td:nth-child(1)', 'returns the correct result for 2,1');
  $t->is($s1->get(1, 2), 'table > tbody > tr:nth-child(1) > td:nth-child(2)', 'returns the correct result for 1,2');
  try
  {
    $s1->get('foobar', 1);
    $t->fail('doesn\'t throw an exception for an undefined row alias');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for an undefined row alias');
  }
  try
  {
    $s1->get(1, 'foobar');
    $t->fail('doesn\'t throw an exception for an undefined column alias');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for an undefined column alias');
  }  
  $t->is($s1->get('subject', 'label'), 'table > tbody > tr:nth-child(1) > td:nth-child(1)', 'returns the correct result');  
  $t->is($s1->get('body', 'label'), 'table > tbody > tr:nth-child(2) > td:nth-child(1)', 'returns the correct result');
  $t->is($s1->get('subject', 'value'), 'table > tbody > tr:nth-child(1) > td:nth-child(2)', 'returns the correct result');  

  $t->is($s2->get(1), 'ul#memory > li:nth-child(1)', 'returns the correct result for a given row (int)');
  try
  {
    $s2->get(1, 1);
    $t->fail('doesn\'t throw an exception for a given column, although no column selector is set');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for a given column, when no column selector is set');
  }
  $t->is($s2->get(2), 'ul#memory > li:nth-child(2)', 'returns the correct result for row 2');
  $t->is($s2->get('created'), 'ul#memory > li:nth-child(1)', 'returns the correct result for a given row (alias)');
  $t->is($s2->get('status'), 'ul#memory > li:nth-child(2)', 'returns the correct result for a given row (alias)');

  
$t->diag('getHeader()');
  $t->is($s1->getHeader(1),'table > thead > tr > th:nth-child(1)', 'returns the correct result for 1');   
  $t->is($s1->getHeader(2),'table > thead > tr > th:nth-child(2)', 'returns the correct result for 2');
  $t->is($s1->getHeader('label'),'table > thead > tr > th:nth-child(1)', 'returns the correct result for "label"');
  $t->is($s1->getHeader('value'),'table > thead > tr > th:nth-child(2)', 'returns the correct result for "value"');

