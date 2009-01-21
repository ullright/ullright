<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

//sfContext::createInstance($configuration);
//$request = sfContext::getInstance()->getRequest();
//sfLoader::loadHelpers('ull');
//sfLoader::loadHelpers('I18N');

$t = new lime_test(23, new lime_output_color);

$t->diag('__construct()');

  $s = new ullDomGridSelector();
  $t->isa_ok($s, 'ullDomGridSelector', 'returns the correct object');
  
  $s1 = new ullDomGridSelector('table > tbody', 'tr', 'td',
    array(
      'subject'   => 1,
      'body'      => 2,
      'tags'      => 3,
    ),
    array(
      'label'     => 1,
      'value'     => 2,
      'error'     => 3,
    )
  );
  $t->isa_ok($s, 'ullDomGridSelector', 'returns the correct object');
  
  $s2 = new ullDomGridSelector('ul#memory', 'li', null, 
    array(
      'created'   => 1,
      'status'    => 2,
      'next'      => 3,
    )
  );
  $t->isa_ok($s, 'ullDomGridSelector', 'returns the correct object');
  
$t->diag('getBaseSelector()');
  $t->is($s->getBaseSelector(), 'table > tbody', 'returns the correct result');
  $t->is($s1->getBaseSelector(), 'table > tbody', 'returns the correct result');
  $t->is($s2->getBaseSelector(), 'ul#memory', 'returns the correct result');
  
$t->diag('getRowSelector()');
  $t->is($s->getBaseSelector(), 'tr', 'returns the correct result');
  $t->is($s1->getBaseSelector(), 'tr', 'returns the correct result');
  $t->is($s2->getBaseSelector(), 'li', 'returns the correct result');

$t->diag('getColumnSelector()');
  $t->is($s->getBaseSelector(), 'td', 'returns the correct result');
  $t->is($s1->getBaseSelector(), 'td', 'returns the correct result');
  $t->is($s2->getBaseSelector(), null, 'returns the correct result');

$t->diag('getNode()');
  $t->is($s->getNode(), 'table > tbody > tr > td', 'returns the correct result for default values');    
  $t->is($s->getNode(1, 1), 'table > tbody > tr > td', 'returns the correct result for 1,1');
  $t->is($s->getNode(2), 'table > tbody > tr > td', 'returns the correct result for default column');
  $t->is($s->getNode(2, 1), 'table > tbody > tr + tr > td', 'returns the correct result for 2,1');
  $t->is($s->getNode(1, 2), 'table > tbody > tr > td + td', 'returns the correct result for 1,2');
  try
  {
    $s->getNode('email', 1);
    $t->fail('doesn\'t throw an exception for an undefined row alias');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for an undefined row alias');
  }
  try
  {
    $s->getNode(1, 'email');
    $t->fail('doesn\'t throw an exception for an undefined column alias');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for an undefined column alias');
  }
  
  $t->is($s1->getNode(1, 1), 'table > tbody > tr > td', 'returns the correct result for 1,1');  
  $t->is($s1->getNode(2, 1), 'table > tbody > tr + tr > td', 'returns the correct result for 2,1');
  $t->is($s1->getNode(1, 2), 'table > tbody > tr > td + td', 'returns the correct result for 1,2');  
  $t->is($s1->getNode('subject', 'label'), 'table > tbody > tr > td', 'returns the correct result');  
  $t->is($s1->getNode('body', 'label'), 'table > tbody > tr + tr > td', 'returns the correct result');
  $t->is($s1->getNode('subject', 'value'), 'table > tbody > tr > td + td', 'returns the correct result');  

  $t->is($s2->getNode(), 'ul#memory > li', 'returns the correct result for default values');    
  $t->is($s2->getNode(1), 'ul#memory > li', 'returns the correct result for a given row (int)');
  try
  {
    $s2->getNode(1, 1);
    $t->fail('doesn\'t throw an exception for a given column, although no column selector is set');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception for a given column, when no column selector is set');
  }
  $t->is($s2->getNode(2), 'ul#memory > li + li', 'returns the correct result for row 2');
  $t->is($s2->getNode('created'), 'ul#memory > li', 'returns the correct result for a given row (alias)');
  $t->is($s2->getNode('status'), 'ul#memory > li + li', 'returns the correct result for a given row (alias)');
