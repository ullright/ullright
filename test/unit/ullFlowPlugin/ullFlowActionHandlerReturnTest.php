<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(4, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct()');

  $form = new ullFlowForm(Doctrine::getTable('UllFlowDoc')->find(1));
  $handler = new ullFlowActionHandlerReturn($form);
  
  $t->isa_ok($handler, 'ullFlowActionHandlerReturn', 'returns the correct object');
  
$t->diag('render()');
    
  $reference = '<input type="submit" name="submit|action_slug=return" value="Return" />';
  $t->is($handler->render(), $reference, 'returns the correct html code');
  
$t->diag('getNextFromPreviousStep()');

  // create and send
  $t->loginAs('test_user');
  $doc = new UllFlowDoc;
  $doc->ull_flow_app_id = 1;
  $doc->my_subject = 'My fancy subject';
  $doc->ull_flow_action_id = Doctrine::getTable('UllFlowAction')->findOneBySlug('send');
  $doc->assigned_to_ull_entity_id = Doctrine::getTable('UllGroup')->findOneByDisplayName('Helpdesk')->id;
  $doc->assigned_to_ull_flow_step_id = Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_dispatcher')->id;
  $doc->save();
  
  // assign to helpdesk admin user
  $t->loginAs('helpdesk_user');
  $doc->ull_flow_action_id = Doctrine::getTable('UllFlowAction')->findOneBySlug('assign_to_user');
  $doc->assigned_to_ull_entity_id = Doctrine::getTable('UllUser')->findOneByDisplayName('Helpdesk Admin User')->id;
  $doc->assigned_to_ull_flow_step_id = Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_troubleshooter')->id;
  $doc->save();
  
  $form = new ullFlowForm($doc);
  $handler = new ullFlowActionHandlerReturn($form);
  $next = $handler->getNextFromPreviousStep();
  
  $t->is($next['entity']->id, Doctrine::getTable('UllGroup')->findOneByDisplayName('Helpdesk')->id, 'return the correct UllEntity');
  $t->is($next['step']->id, Doctrine::getTable('UllFlowStep')->findOneBySlug('trouble_ticket_dispatcher')->id, 'return the correct step');
  
  