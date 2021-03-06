<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfContext::getInstance()->getConfiguration()->loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(6, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('__construct()');

  $generator = new ullFlowGenerator();
  $generator->buildForm(new UllFlowDoc());
  $form = $generator->getForm();
  $handler = new ullFlowActionHandlerAssignToUser($generator);
  
  $t->isa_ok($handler, 'ullFlowActionHandlerAssignToUser', 'returns the correct object');

$t->diag('configure()');  

  $t->is(count($form->getWidgetSchema()->getFields()), 14, 'Sets the correct number of widgets');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('ull_flow_action_assign_to_user_ull_entity'),
  	'ullWidgetFormChoiceUllEntity', 'Sets the correct widget');
  $t->is($handler->getFormFields(), array('ull_flow_action_assign_to_user_ull_entity'), 'Sets the correct list of form fields');
  
$t->diag('render()');
    
  $reference = '<input type="submit" name="submit|action_slug=assign_to_user" value="Assign" /> to user 
<script type="text/javascript">
//<![CDATA[

$(document).ready(function()
{
  $("#fields_ull_flow_action_assign_to_user_ull_entity").addSelectFilter();
});
      
//]]>
</script><div style="white-space: nowrap; display: inline;"><select name="fields[ull_flow_action_assign_to_user_ull_entity]" id="fields_ull_flow_action_assign_to_user_ull_entity">
<option value="" selected="selected"></option>
<option value="1">Admin Master</option>
<option value="3">Admin User Helpdesk</option>
<option value="4">User Helpdesk</option>
<option value="2">User Test</option>
</select></div>';
  $t->is($handler->render(), $reference, 'returns the correct html code');
  
$t->diag('setting options');

  $generator = new ullFlowGenerator();
  $generator->buildForm(new UllFlowDoc());
  $form = $generator->getForm();
  $handler = new ullFlowActionHandlerAssignToUser($generator, array('group' => 'TestGroup'));
  
  $reference = '<input type="submit" name="submit|action_slug=assign_to_user" value="Assign" /> to user 
<script type="text/javascript">
//<![CDATA[

$(document).ready(function()
{
  $("#fields_ull_flow_action_assign_to_user_ull_entity").addSelectFilter();
});
      
//]]>
</script><div style="white-space: nowrap; display: inline;"><select name="fields[ull_flow_action_assign_to_user_ull_entity]" id="fields_ull_flow_action_assign_to_user_ull_entity">
<option value="" selected="selected"></option>
<option value="2">User Test</option>
</select></div>';
  $t->is($handler->render(), $reference, 'returns the correct html code');  
  
  

