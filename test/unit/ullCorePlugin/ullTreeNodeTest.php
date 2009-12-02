<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

//sfContext::createInstance($configuration);
//$request = sfContext::getInstance()->getRequest();
//sfLoader::loadHelpers('ull');
//sfLoader::loadHelpers('I18N');

$t = new lime_test(18, new lime_output_color);


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
  
  
$t->diag('isLeftMost()');
  $node = new ullTreeNode('testdata');
  $t->is($node->isLeftMost(), false, 'Isn\'t a leftmost node');
  $node->setIsLeftMost(true);
  $t->is($node->isLeftMost(), true, 'Is a leftmost node');

  
$t->diag('isRightMost()');
  $node = new ullTreeNode('testdata');
  $t->is($node->isRightMost(), false, 'Isn\'t a rightmost node');
  $node->setIsRightMost(true);
  $t->is($node->isRightMost(), true, 'Is a rightmost node');
  
  
$t->diag('toArray()');
  $node = new ullTreeNode('topnode');
  
  $reference = array(
    'data'     => 'topnode',
    'meta'     => array(),
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
    'meta'     => array(),
    'subnodes' => array(
      0 => array(
        'data'      => 'subnode1',
        'meta'      => array('leftmost' => true),
        'subnodes' => array(),
      ),
      1 => array(
        'data'      => 'subnode2',
        'meta'      => array(),
        'subnodes' => array(),
      ),      
      2 => array(
        'data'      => 'subnode3',
        'meta'      => array('rightmost' => true),
        'subnodes' => array(
        0 => array(
          'data'      => 'subsubnode1',
          'meta'      => array(),
          'subnodes' => array(),
          ),         
        ),
      ),      
    ),
  );  
  
  $t->is($node->toArray(), $reference, 'Returns the correct array format for a nested node');
  
