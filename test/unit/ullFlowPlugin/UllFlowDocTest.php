<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(52, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct');

  $doc = new UllFlowDoc;
  $t->isa_ok($doc, 'UllFlowDoc', 'returns the correct object');
  
$t->diag('create');
  $doc->ull_flow_app_id = 1;
  $doc->my_subject = 'My fancy subject';
  $doc->my_date = '2008-08-08 08:08:08';
  $doc->memory_comment = 'My fancy memory comment';
  $doc->save();

  $doc = Doctrine::getTable('UllFlowDoc')->find(5);

  $t->is($doc->subject, 'My fancy subject', 'sets the subject correctly');
  $t->is($doc->ull_flow_action_id, Doctrine::getTable('UllFlowAction')->findOneBySlug('save_close')->id, 'sets the action correctly (default)');
  $t->is($doc->assigned_to_ull_entity_id, 1, 'sets the default assigned_to_ull_entity_id correctly');
  $t->is($doc->assigned_to_ull_flow_step_id, $doc->UllFlowApp->findStartStep()->id, 'sets the correct start step');  
  $t->is($doc->my_subject, 'My fancy subject', 'sets the correct virtual columns value');
  $t->is($doc->my_date, '2008-08-08 08:08:08', 'sets the correct virtual columns value');
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
    'my_subject'    => 'My first trouble ticket',
    'my_information_update' =>  'blub macht da fisch :)',
    'my_date' => '2011-11-11',
    'my_email'    => 'quasimodo@ull.at',
    'upload'      => 'Icons.zip;/uploads/ullFlow/bug_tracking/215/2008-11-13-09-37-41_Icons.zip;application/zip;1;2008-11-13 09:37:41',
    'wiki_link'   => '1',
    'column_tags' => 'ull_flow_tag1',
  );
  
  $t->is($columns, $reference, 'returns the correct values');  
  
$t->diag('getVirtualColumnsAsArray()');
  $doc1 = Doctrine::getTable('UllFlowDoc')->find(1);
  $columns = $doc1->getVirtualColumnsAsArray();
  $reference = array(
    'my_information_update',
    'my_subject',    
    'my_date',
    'my_email',
    'column_priority',
    'upload',
    'wiki_link',
    'column_tags',
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