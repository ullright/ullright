<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(63, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $doc = new UllFlowDoc;
  $t->isa_ok($doc, 'UllFlowDoc', 'returns the correct object');
  
$t->diag('create');
  $doc->ull_flow_app_id = 1;
  $doc->my_subject = 'My fancy subject';
  $doc->my_due_date = '2008-08-08 08:08:08';
  $doc->my_priority = 5;
  $doc->my_tags = 'footag';
  $doc->my_project = 2;
  $doc->memory_comment = 'My fancy memory comment';
  
  try
  {
    $doc->invalid_column = 666;
    $t->fail('Doesn\'t throw an exception for setting an invalid virtual column');
  }
  catch (Doctrine_Record_UnknownPropertyException $e)
  {
    $t->pass('Throw an exception for setting an invalid virtual column');
  }  
  
  $doc->save();

  $doc = Doctrine::getTable('UllFlowDoc')->find(5);

  $t->is($doc->subject, 'My fancy subject', 'sets the native subject duplicate correctly');
  $t->is($doc->priority, 5, 'sets native priority duplicate correctly');
  $t->is($doc->ull_project_id, 2, 'sets native ull_project_id duplicate correctly');
  $t->is($doc->duplicate_tags_for_search, 'footag', 'sets the native tagging duplicate correctly');
  $t->is($doc->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('save_close')->id, 'sets the action correctly (default)');
  $t->is($doc->assigned_to_ull_entity_id, 1, 'sets the default assigned_to_ull_entity_id correctly');
  $t->is($doc->assigned_to_ull_flow_step_id, $doc->UllFlowApp->findStartStep()->id, 'sets the correct start step');  
  $t->is($doc->my_subject, 'My fancy subject', 'sets the correct virtual columns value');
  $t->is($doc->my_due_date, '2008-08-08 08:08:08', 'sets the correct virtual columns value');
  $t->is($doc->my_priority, 5, 'sets the correct virtual columns value');
  $t->is($doc->my_project, 2, 'sets the correct virtual columns value');
  $t->is($doc->my_tags, 'footag', 'sets the correct virtual columns value');
  try
  {
    $foo = $doc->invalid_column;
    $t->fail('Doesn\'t throw an exception for getting an invalid virtual column');
  }
  catch (Doctrine_Record_UnknownPropertyException $e)
  {
    $t->pass('Throw an exception for getting an invalid virtual column');
  }    
  $t->is($doc->memory_comment, 'My fancy memory comment', 'the current memory comment is accessable via $doc->memory_comment');  
  $t->is($doc->UllFlowMemories[0]->ull_flow_step_id, $doc->UllFlowApp->findStartStep()->id, 'sets the correct memory step');
  $t->is($doc->UllFlowMemories[0]->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('create')->id, 'sets the first memories action correctly (create)');
  $t->is($doc->UllFlowMemories[0]->assigned_to_ull_entity_id, 1, 'sets the correct memory assigned_to_ull_entity_id');  
  $t->is($doc->UllFlowMemories[0]->comment, '', 'sets the first memories comment correctly');
  $t->is($doc->UllFlowMemories[0]->creator_ull_entity_id, 1, 'sets the correct memory creator_ull_entity_id');
  $t->is($doc->UllFlowMemories[1]->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('save_close')->id, 'sets the second memories action correctly (save_close)');
  $t->is($doc->UllFlowMemories[1]->comment, 'My fancy memory comment', 'sets the second memories comment correctly');

$t->diag('findLatestNonStatusOnlyMemory()');

  $t->is($doc->findLatestNonStatusOnlyMemory()->id, $doc->UllFlowMemories[0]->id, 'finds the correct latest non status-only memory');

$t->diag('edit');

  $doc->my_subject = 'My fancy edited subject';
  $doc->memory_comment = 'My fancy edited memory comment';

  $t->is($doc->subject, 'My fancy edited subject', 'sets the subject in UllFlowDoc correctly');
  $t->is($doc->my_subject, 'My fancy edited subject', 'sets the virtual column subject correctly');
  $t->is($doc->UllFlowValues[0]->value, 'My fancy edited subject', 'virtual column: ok in direct record relation');
  
  $doc->save();  

  $doc = Doctrine::getTable('UllFlowDoc')->find(5);

  $t->is($doc->my_subject, 'My fancy edited subject', 'sets the virtual column subject correctly');
  $t->is($doc->subject, 'My fancy edited subject', 'sets the subject correctly');
  $t->is($doc->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('save_close')->id, 'sets the action correctly (default)');
  $t->is($doc->UllFlowMemories[2]->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('save_close')->id, 'sets the third memories action correctly (save_close)');  
  $t->is($doc->UllFlowMemories[2]->comment, 'My fancy edited memory comment', 'sets the second memories comment correctly');
  
$t->diag('do workflow action (send)');

  $doc->ull_flow_action_id = Doctrine::getTable('UllFlowAction')->findOneBySlug('send');
  $doc->assigned_to_ull_entity_id = Doctrine::getTable('UllGroup')->findOneByDisplayName('Helpdesk')->id;
  $doc->assigned_to_ull_flow_step_id = Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_dispatcher')->id;
  $doc->save();  
  
  $t->is($doc->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('send')->id, 'sets correct action');
  $t->is($doc->assigned_to_ull_entity_id, Doctrine::getTable('UllGroup')->findOneByDisplayName('Helpdesk')->id, 'assigns to the correct UllEntity');
  $t->is($doc->assigned_to_ull_flow_step_id, Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_dispatcher')->id, 'assigns to the correct UllFlowStep');

  $t->is($doc->UllFlowMemories[3]->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('send')->id, 'memory: sets the correct action');
  $t->is($doc->UllFlowMemories[3]->assigned_to_ull_entity_id, Doctrine::getTable('UllGroup')->findOneByDisplayName('Helpdesk')->id, 'memory: assigns to the correct UllEntity');
  $t->is($doc->UllFlowMemories[3]->ull_flow_step_id, Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_dispatcher')->id, 'memory: assigns to the correct UllFlowStep');    
  $t->is($doc->UllFlowMemories[3]->creator_ull_entity_id, 1, 'memory: sets the correct Creator UllEntity');
  
$t->diag('do workflow action (assign)');

  $doc->ull_flow_action_id = Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user');
  $doc->assigned_to_ull_entity_id = Doctrine::getTable('UllUser')->findOneByDisplayName('Helpdesk Admin User')->id;
  $doc->assigned_to_ull_flow_step_id = Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_troubleshooter')->id;
  $doc->save();  
  
  $t->is($doc->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user')->id, 'sets correct action');
  $t->is($doc->assigned_to_ull_entity_id, Doctrine::getTable('UllUser')->findOneByDisplayName('Helpdesk Admin User')->id, 'assigns to the correct UllEntity');
  $t->is($doc->assigned_to_ull_flow_step_id, Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_troubleshooter')->id, 'assigns to the correct UllFlowStep');

  $t->is($doc->UllFlowMemories[4]->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user')->id, 'memory: sets the correct action');
  $t->is($doc->UllFlowMemories[4]->assigned_to_ull_entity_id, Doctrine::getTable('UllUser')->findOneByDisplayName('Helpdesk Admin User')->id, 'memory: assigns to the correct UllEntity');
  $t->is($doc->UllFlowMemories[4]->ull_flow_step_id, Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_troubleshooter')->id, 'memory: assigns to the correct UllFlowStep');    
  $t->is($doc->UllFlowMemories[4]->creator_ull_entity_id, Doctrine::getTable('UllGroup')->findOneByDisplayName('Helpdesk')->id, 'memory: sets the correct Creator UllEntity');
    
$t->diag('findLatestNonStatusOnlyMemory()');

  $t->is($doc->findLatestNonStatusOnlyMemory()->id, $doc->UllFlowMemories[4]->id, 'finds the correct latest non status-only memory');  
  
$t->diag('findLatestMemory()');

  $t->is($doc->findLatestMemory()->id, $doc->UllFlowMemories[4]->id, 'finds the correct latest memory');  
 
$t->diag('findPreviousNonStatusOnlyMemory()');

  $t->is($doc->findPreviousNonStatusOnlyMemory()->id, $doc->UllFlowMemories[3]->id, 'finds the correct latest non status-only memory');  
  
$t->begin('setValueByColumn() and getValueByColumn()');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $doc1->setValueByColumn('my_email', 'luke.skywalker@ull.at');
  $doc1->save();  
  
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is($doc1->getValueByColumn('my_email'), 'luke.skywalker@ull.at', 'getValueByColumn() returns the correct value');

$t->begin('getVirtualValuesAsArray()');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $columns = $doc1->getVirtualValuesAsArray();
  
  $reference = array(
    'my_subject'  => 'My first trouble ticket',
    'my_information_update' =>  'blub macht da fisch :)',
    'my_due_date'     => '2010-09-20',
    'my_email'    => 'quasimodo@ull.at',
    'my_upload'   => 'Icons.zip;/uploads/ullFlow/bug_tracking/215/2008-11-13-09-37-41_Icons.zip;application/zip;1;2008-11-13 09:37:41',
    'my_wiki_link' => '1',
    'my_project'  => 1,
    'my_tags'     => 'ull_flow_tag1, ull_flow_tag2',
  );
  
  $t->is($columns, $reference, 'returns the correct values');  
  
$t->diag('getVirtualColumnsAsArray()');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $columns = $doc1->getVirtualColumnsAsArray();
  $reference = array(
    'my_information_update',
    'my_subject',    
    'my_email',
    'my_priority',
    'my_upload',
    'my_wiki_link',
    'my_project',
    'my_due_date',
    'my_tags',
  );
  $t->is($columns, $reference, 'returns the correct values');    
  
$t->diag('checkAccess() - write');

  $doc = Doctrine::getTable('UllFlowDoc')->find(2);
  $t->loginAs('admin');
  $t->is($doc->checkAccess(), 'w', 'returns write access for MasterAdmins');
  
  $t->loginAs('helpdesk_admin_user');
  $t->is($doc->checkAccess(), 'w', 'returns write access for master read group');

  $doc = Doctrine::getTable('UllFlowDoc')->find(3);
  $t->loginAs('test_user');
  $t->is($doc->checkAccess(), 'w', 'returns write because the doc is assigned to the user');
  
$t->diag('checkAccess() - read');

  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->loginAs('helpdesk_user');
  $t->is($doc->checkAccess(), 'r', 'returns read access for master read group');
  
  $t->loginAs('test_user');
  $t->is($doc->checkAccess(), 'r', 'returns read access because the user created the doc');

$t->diag('checkAccess() - none');  
  
  $doc = Doctrine::getTable('UllFlowDoc')->find(2);
  $t->loginAs('test_user');
  $t->is($doc->checkAccess(), null, 'returns no access for test_user');
  
$t->diag('checkDeleteAccess()');

  $t->loginAs('test_user');
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->ok($doc->checkDeleteAccess(), 'allows access for the creator');
  
  $t->loginAs('admin');
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->ok($doc->checkDeleteAccess(), 'allows access for masteradmin');  
  
  $t->loginAs('helpdesk_user');
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->ok(!$doc->checkDeleteAccess(), 'disallows access for someone else');

  
$t->diag('__toString()');
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is((string) $doc, 'Trouble ticket "My first trouble ticket"', 'Returns the correct value when casted to string');

  
$t->diag('getEditUri()');
  $doc = Doctrine::getTable('UllFlowDoc')->find(1);
  $t->is($doc->getEditUri(), 'ullFlow/edit?doc=1', 'Returns the correct uri for edit');
  
// Deactivated because of many sideeffects
//$t->diag('Native column setters');
//  $doc = new UllFlowDoc;
//  $doc->ull_flow_app_id = 1;
//  $doc->subject = 'Jonathan Livingstone';
//  $doc->priority = 1;
//  $doc->save();
//  
//  $t->is($doc->subject, 'Jonathan Livingstone', 'Returns the correct native subject');
//  $t->is($doc->my_subject, 'Jonathan Livingstone', 'Returns the correct column subject');
//  $t->is($doc->priority, 1, 'Returns the correct native priority');
//  $t->is($doc->my_priority, 1, 'Returns the correct column priority');
