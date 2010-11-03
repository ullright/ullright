<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(15, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('queryAccess()');
  $q = new Doctrine_Query;
  $t->isa_ok(UllFlowDocTable::queryAccess($q, new UllFlowApp), 'Doctrine_Query', 'returns the correct object');
  
  try
  {
    UllFlowDocTable::queryAccess($q, new UllFlowDoc);
    $t->fail('doesn\'t throw an exception if any other object than UllFlowApp is given');
  }
  catch (Exception $e)
  {
    $t->pass('throws an exception if any other object than UllFlowApp is given');
  }
  
$t->diag('hasId()');
  $t->ok(UllFlowDocTable::hasId(1), 'returns true for an existing id');
  $t->ok(!UllFlowDocTable::hasId(666), 'returns false for a non existing id');
  
$t->diag('hasVirtualColumn()');
  $t->ok(UllFlowDocTable::hasVirtualColumn(1, 'my_email'), 'returns true for an existing slug');
  $t->ok(!UllFlowDocTable::hasVirtualColumn(1, 'foobar'), 'returns false for a non existing slug');  
  
//create a new doc in reminder period
$doc = new UllFlowDoc();
$doc['due_date'] = date('Y-m-d', strtotime('+2 day'));
$doc->UllFlowApp = Doctrine::getTable('UllFlowApp')->findOneBySlug('trouble_ticket');
$doc->save();

//and close doc with id 1
$doc = Doctrine::getTable('UllFlowDoc')->findOneById(1);
$doc->UllFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug('close');
$doc->save();

$t->diag('findOverdueDocs(true)');
  $overdueDocs = UllFlowDocTable::findOverdueDocs(true);
  $t->is(count($overdueDocs), 0, 'returns correct amount of overdue docs');

$t->diag('findDueDateDangerDocs(2, true)');
  $reminderDocs = UllFlowDocTable::findDueDateDangerDocs(2, true); 
  $t->is(count($reminderDocs), 0, 'returns correct amount of reminder-period docs');
    
$t->diag('findDueDateDangerDocs(3, true)');
  $reminderDocs = UllFlowDocTable::findDueDateDangerDocs(3, true); 
  $t->is(count($reminderDocs), 1, 'returns correct amount of reminder-period docs');
  $t->is($reminderDocs[0]['id'], 5, 'returns correct overdue document');

$t->diag('findOverdueDocs(false)');
  $overdueDocs = UllFlowDocTable::findOverdueDocs(false);
  $t->is(count($overdueDocs), 1, 'returns correct amount of overdue docs');
  $t->is($overdueDocs[0]['id'], 1, 'returns correct overdue document');
  
$t->diag('findDueDateDangerDocs(3, false)');
  $reminderDocs = UllFlowDocTable::findDueDateDangerDocs(3, false); 
  $primaryKeys = $reminderDocs->getPrimaryKeys();
  $t->is(count($reminderDocs), 2, 'returns correct amount of reminder-period docs');
  $t->ok(in_array(1, $primaryKeys), 'returns correct first reminder-period document');
  $t->ok(in_array(5, $primaryKeys), 'returns correct second reminder-period document');
