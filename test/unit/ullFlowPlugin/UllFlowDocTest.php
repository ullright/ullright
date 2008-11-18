<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getUser()->setCulture('en'); // because it's set to 'xx' per default !?!

$t = new myTestCase(12, new lime_output_color, $configuration);
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

$t->begin('set() and get() for virtual columns and automatically set columns');
  $doc->ull_flow_app_id = 1;
  $doc->title = 'My title';
  $doc->my_title = $doc->title;
  $doc->my_datetime = '2008-08-08 08:08:08';
  $doc->save();

  $doc = Doctrine::getTable('UllFlowDoc')->find(5);
  $t->is($doc->my_title, 'My title', 'get() returns the correct value');
  $t->is($doc->my_datetime, '2008-08-08 08:08:08', 'get() returns the correct value');
  $t->is($doc->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('save_only')->id, 'The default action is automatically set');
  $t->is($doc->assigned_to_ull_entity_id, 1, 'sets the default assigned_to_ull_entity_id correctly');
  $t->is($doc->assigned_to_ull_flow_step_id, $doc->UllFlowApp->getStartStep()->id, 'sets the correct start step');

$t->begin('getVirtualValuesAsArray()');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $columns = $doc1->getVirtualValuesAsArray();
  
  $reference = array(
    'my_title'    => 'My first trouble ticket',
    'my_datetime' => '1321006271', // TODO: should be 2011-11-11 11:11:11
    'my_email'    => 'quasimodo@ull.at',
  );
  
  $t->is($columns, $reference, 'returns the correct values');  
  
$t->begin('getVirtualColumnsAsArray()');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $columns = $doc1->getVirtualColumnsAsArray();
  
  $reference = array(
    'my_title',
    'my_datetime',
    'my_email',
  );
  
$t->is($columns, $reference, 'returns the correct values');    

$t->begin('update works correctly for the automatically set columns');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $doc1->ull_flow_action_id = Doctrine::getTable('UllFlowAction')->findOneBySlug('reject');
  $doc1->assigned_to_ull_entity_id = Doctrine::getTable('UllUser')->findOneByUsername('helpdesk_user');
  $doc1->assigned_to_ull_flow_step_id = 2;
  $doc1->save();

  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is($doc1->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('reject')->id, 'sets the action correctly');
  $t->is($doc1->assigned_to_ull_entity_id, Doctrine::getTable('UllUser')->findOneByUsername('helpdesk_user')->id, 'sets assigned_to_ull_entity_id correctly');
  $t->is($doc1->assigned_to_ull_flow_step_id, 2, 'sets the correct step');
