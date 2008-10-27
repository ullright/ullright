<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');
  $doc = new UllFlowDoc;
  $t->isa_ok($doc, 'UllFlowDoc', 'returns the correct object');

$t->begin('setValueByColumn() and getValueByColumn()');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $doc1->setValueByColumn('my_email', 'luke.skywalker@ull.at');
  $doc1->save();  
  
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is($doc1->getValueByColumn('my_email'), 'luke.skywalker@ull.at', 'getValueByColumn() returns the correct value');

$t->begin('set() and get() for virtual columns');
  $doc->ull_flow_app_id = 1;
  $doc->assigned_to_ull_entity_id = 1;
  $doc->title = 'My title';
  $doc->my_title = $doc->title;
  $doc->my_datetime = '2008-08-08 08:08:08';
  $doc->save();
    
  $doc = Doctrine::getTable('UllFlowDoc')->find(3);
  $t->is($doc->my_title, 'My title', 'get() returns the correct value');
  $t->is($doc->my_datetime, '2008-08-08 08:08:08', 'get() returns the correct value');
  