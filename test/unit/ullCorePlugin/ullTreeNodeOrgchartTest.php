<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

//sfContext::createInstance($configuration);
//$request = sfContext::getInstance()->getRequest();
//sfLoader::loadHelpers('ull');
//sfLoader::loadHelpers('I18N');

$t = new lime_test(11, new lime_output_color);


$t->diag('__construct()');
  $node = new ullTreeNodeOrgchart('testdata');
  $t->isa_ok($node, 'ullTreeNodeOrgchart', 'Returns the correct object');
  
  
$t->diag('addSubordinate/getSubordinates()');
  $node->addSubordinate(new ullTreeNodeOrgchart('subnode1 data'));

  $subordinates= $node->getSubordinates();
  $t->ok(is_array($subordinates), 'Returns an array of subordinates');
  $t->is(count($subordinates), 1, 'Returns one subordinates');
  $t->is(reset($subordinates)->getData(), 'subnode1 data', 'Subordinates contains the correct data');
  
  
$t->diag('hasSubordinates()');
  $t->ok($node->hasSubordinates(), 'Node has subordinates');
  $node = new ullTreeNodeOrgchart('testdata');
  $t->is($node->hasSubordinates(), false, 'Node has no subordinates');
  
$t->diag('addAssistant/getAssistants()');
  $node->addAssistant(new ullTreeNodeOrgchart('assistant1 data'));

  $assistants= $node->getAssistants();
  $t->ok(is_array($assistants), 'Returns an array of assistantss');
  $t->is(count($assistants), 1, 'Returns one assistants');
  $t->is(reset($assistants)->getData(), 'assistant1 data', 'Assistants contains the correct data');
  
  
$t->diag('hasAssistants()');
  $t->ok($node->hasAssistants(), 'Node has assistants');
  $node = new ullTreeNodeOrgchart('testdata');
  $t->is($node->hasAssistants(), false, 'Node has no assistants');