<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'ull'));

$t = new lime_test(6, new lime_output_color);

class ullNamedQueriesTest extends ullNamedQueries
{
  public function configure()
  {
    $this->setBaseUri('ullUser/list');
  }
}


$t->diag('__construct()');
  $namedQueries = new ullNamedQueriesTest;
  $t->isa_ok($namedQueries, 'ullNamedQueriesTest', 'returns the correct object');
  
  
$t->diag('add()');
  try
  {
    $namedQueries->add('foobar');
    $t->fail('adding an invalid namedQuery doesn\'t throw an exception');
  }
  catch (Exception $e)
  {
    $t->pass('adding an invalid namedQuery throws an exception');
  }
  $namedQueries->add('ullNamedQueryTest');
  
  
$t->diag('get()');
  try
  {
    $namedQueries->get('foobar');
    $t->fail('getting an invalid namedQuery doesn\'t throw an exception');
  }
  catch (Exception $e)
  {
    $t->pass('getting an invalid namedQuery throws an exception');
  }
  $t->is($namedQueries->get('my_test_query')->getIdentifier(), 'my_test_query', 'returns the correct namedQuery');  
  
  
$t->diag('getAll()');
  $t->is(count($namedQueries->getAll()), 1, 'returns an array with one namedQuery');  
  
  
$t->diag('renderList()');
  $list = '<ul>
<li><a href="/ullUser/list/query/my_test_query">My test query</a></li>
</ul>

';
  $t->is($namedQueries->renderList(), $list, 'renders the list correctly');