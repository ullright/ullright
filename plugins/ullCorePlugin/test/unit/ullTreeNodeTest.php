<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

//sfContext::createInstance($configuration);
//$request = sfContext::getInstance()->getRequest();
//sfContext::getInstance()->getConfiguration()->loadHelpers('ull');
//sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');

$t = new lime_test(21, new lime_output_color);


$t->diag('__construct()');
  $node = new ullTreeNode('testdata');
  $t->isa_ok($node, 'ullTreeNode', 'Returns the correct object');

  
$t->diag('getData()');
  $t->is($node->getData(), 'testdata', 'Returns the correct data');
  
  
$t->diag('addSubnode/getSubnodes()');
  $node->addSubnode(new ullTreeNode('subnode1 data'));

  $subnodes = $node->getSubnodes();
  $t->ok(is_array($subnodes), 'Returns an array of subnodes');
  $t->is(count($subnodes), 1, 'Returns one subnode');
  $t->is(reset($subnodes)->getData(), 'subnode1 data', 'Subnode contains the correct data');
  
  
$t->diag('hasSubnodes()');
  $t->ok($node->hasSubnodes(), 'Node has subnodes');
  $node = new ullTreeNode('testdata');
  $t->is($node->hasSubnodes(), false, 'Node has no subnodes');
  
  
$t->diag('set/getMeta()');
  $node->setMeta('leftmost', true);
  $node->setMeta('something', 'nice');
  $t->is($node->getMeta('leftmost'), true, 'Returns the correct meta data');
  $t->is($node->getMeta('something'), 'nice', 'Returns the correct meta data');
  try
  {
    $node->getMeta('invalid');
    $t->fail('Doesn\'t throw an exception when trying to get an invalid meta data key/value pair');
  }
  catch (Exception $e)
  {
    $t->pass('Throws an exception when trying to get an invalid meta data key/value pair');
  }
  
  
$t->diag('hasMeta()');
  $t->is($node->hasMeta('leftmost'), true, 'Returns true if meta data exist for a given key');
  $t->is($node->hasMeta('invalid'), false, 'Returns true if meta data exist for a given key');
  
  
$t->diag('isFirst()');
  $node = new ullTreeNode('testdata');
  $t->is($node->isFirst(), false, 'Isn\'t first node');
  $node->setIsFirst(true);
  $t->is($node->isFirst(), true, 'Is first node');

  
$t->diag('isLast()');
  $node = new ullTreeNode('testdata');
  $t->is($node->isLast(), false, 'Isn\'t last node');
  $node->setIsLast(true);
  $t->is($node->isLast(), true, 'Is last node');
  
  

  
$t->diag('toArray()');
  $node = new ullTreeNode('topnode');
  
  $reference = array(
    'data'     => 'topnode',
    'meta'     => array(
      'level'   => 1,
    ),
    'subnodes' => array(),
  );
  
  $t->is($node->toArray(), $reference, 'Returns the correct array format for a single node');  
  
  $node->addSubnode(new ullTreeNode('subnode1'));
  $node->addSubnode(new ullTreeNode('subnode2'));
  $subnode3 = new ullTreeNode('subnode3');
  $subnode3->addSubnode(new ullTreeNode('subsubnode1'));
  $node->addSubnode($subnode3);
  
  $reference = array(
    'data'     => 'topnode',
    'meta'     => array(
      'level'  => 1,
    ),
    'subnodes' => array(
      0 => array(
        'data'      => 'subnode1',
        'meta'      => array(
          'level'     => 2,
          'is_first'  => true
        ),
        'subnodes' => array(),
      ),
      1 => array(
        'data'      => 'subnode2',
        'meta'      => array(
          'level'   => 2,
        ),
        'subnodes' => array(),
      ),      
      2 => array(
        'data'      => 'subnode3',
        'meta'      => array(
          'level'   => 2,
          'is_last' => true
        ),
        'subnodes' => array(
        0 => array(
          'data'      => 'subsubnode1',
          'meta'      => array(
            'level'   => 3,
            'is_first' => true, 
            'is_last' => true
          ),
          'subnodes' => array(),
          ),         
        ),
      ),      
    ),
  );  
  
  $t->is($node->toArray(), $reference, 'Returns the correct array format for a nested node');
  
  
$t->diag('getLevel()');
  $t->is($node->getLevel(), 1, 'Returns the correct level');  
  $node->setLevel(99);
  $t->is($node->getLevel(), 99, 'Returns the correct level');
  $subnodes = $node->getSubnodes();
  $t->is(reset($subnodes)->getLevel(), 2, 'Returns the correct level');  
