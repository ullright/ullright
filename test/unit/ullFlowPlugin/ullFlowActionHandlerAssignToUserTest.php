<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct()');

  $form = new ullFlowForm(Doctrine::getTable('UllFlowDoc')->find(1));
  $handler = new ullFlowActionHandlerAssignToUser($form);
  
  $t->isa_ok($handler, 'ullFlowActionHandlerAssignToUser', 'returns the correct object');

$t->diag('configure()');  

  $t->is(count($form->getWidgetSchema()->getFields()), 1, 'Sets the correct number of widgets');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('ull_flow_action_assign_to_user_ull_entity'), 'sfWidgetFormDoctrineSelect', 'Sets the correct widget');
  $t->is($handler->getFormFields(), array('ull_flow_action_assign_to_user_ull_entity'), 'Sets the correct list of form fields');
  
$t->diag('render()');
    
  $reference = '<input type="submit" name="submit|action_slug=assign_to_user" value="Assign" /> to user 
<select name="fields[ull_flow_action_assign_to_user_ull_entity]" id="fields_ull_flow_action_assign_to_user_ull_entity">
<option value="" selected="selected"></option>
<option value="1">Master Admin</option>
<option value="3">Helpdesk Admin User</option>
<option value="4">Helpdesk User</option>
<option value="2">Test User</option>
</select>';
  $t->is($handler->render(), $reference, 'returns the correct html code');
  
$t->diag('setting options');

  $form = new ullFlowForm(Doctrine::getTable('UllFlowDoc')->find(1));
  $handler = new ullFlowActionHandlerAssignToUser($form, array('group' => 'Helpdesk'));
  
  $reference = '<input type="submit" name="submit|action_slug=assign_to_user" value="Assign" /> to user 
<select name="fields[ull_flow_action_assign_to_user_ull_entity]" id="fields_ull_flow_action_assign_to_user_ull_entity">
<option value="" selected="selected"></option>
<option value="4">Helpdesk User</option>
</select>';
  $t->is($handler->render(), $reference, 'returns the correct html code');  
  
  

