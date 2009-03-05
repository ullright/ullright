<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

class myTestCase extends lime_test
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);
sfLoader::loadHelpers(array('ull', 'I18N'));

$t = new myTestCase(6, new lime_output_color, $configuration);

$t->diag('__construct()');

  $form = new ullFlowForm(new UllFlowDoc());
  $handler = new ullFlowActionHandlerAssignToUser($form);
  
  $t->isa_ok($handler, 'ullFlowActionHandlerAssignToUser', 'returns the correct object');

$t->diag('configure()');  

  $t->is(count($form->getWidgetSchema()->getFields()), 1, 'Sets the correct number of widgets');
  $t->isa_ok($form->getWidgetSchema()->offsetGet('ull_flow_action_assign_to_user_ull_entity'), 'ullWidgetUllUser', 'Sets the correct widget');
  $t->is($handler->getFormFields(), array('ull_flow_action_assign_to_user_ull_entity'), 'Sets the correct list of form fields');
  
$t->diag('render()');
    
  $reference = '<input type="submit" name="submit|action_slug=assign_to_user" value="Assign" /> to user 
<script type="text/javascript">
//<![CDATA[

function filtery_fields_ull_flow_action_assign_to_user_ull_entity(pattern, list){
    pattern = new RegExp(\'^\'+pattern,"i");
    i = 0;
    sel = 0;
    while(i < list.options.length) {
      if (pattern.test(list.options[i].text)) {
            sel = i;
            break
        }
        i++;
    }
    list.options.selectedIndex = sel;
}

//]]>
</script><input type="text" name="fields_ull_flow_action_assign_to_user_ull_entity_filter" id="fields_ull_flow_action_assign_to_user_ull_entity_filter" value="" size="1" onkeyup="filtery_fields_ull_flow_action_assign_to_user_ull_entity(this.value, document.getElementById(&quot;fields_ull_flow_action_assign_to_user_ull_entity&quot;))" /> <select name="fields[ull_flow_action_assign_to_user_ull_entity]" id="fields_ull_flow_action_assign_to_user_ull_entity">
<option value="" selected="selected"></option>
<option value="1">Admin Master</option>
<option value="3">Admin User Helpdesk</option>
<option value="4">User Helpdesk</option>
<option value="2">User Test</option>
</select>';
  $t->is($handler->render(), $reference, 'returns the correct html code');
  
$t->diag('setting options');

  $form = new ullFlowForm(new UllFlowDoc());
  $handler = new ullFlowActionHandlerAssignToUser($form, array('group' => 'TestGroup'));
  
  $reference = '<input type="submit" name="submit|action_slug=assign_to_user" value="Assign" /> to user 
<script type="text/javascript">
//<![CDATA[

function filtery_fields_ull_flow_action_assign_to_user_ull_entity(pattern, list){
    pattern = new RegExp(\'^\'+pattern,"i");
    i = 0;
    sel = 0;
    while(i < list.options.length) {
      if (pattern.test(list.options[i].text)) {
            sel = i;
            break
        }
        i++;
    }
    list.options.selectedIndex = sel;
}

//]]>
</script><input type="text" name="fields_ull_flow_action_assign_to_user_ull_entity_filter" id="fields_ull_flow_action_assign_to_user_ull_entity_filter" value="" size="1" onkeyup="filtery_fields_ull_flow_action_assign_to_user_ull_entity(this.value, document.getElementById(&quot;fields_ull_flow_action_assign_to_user_ull_entity&quot;))" /> <select name="fields[ull_flow_action_assign_to_user_ull_entity]" id="fields_ull_flow_action_assign_to_user_ull_entity">
<option value="" selected="selected"></option>
<option value="2">User Test</option>
</select>';
  $t->is($handler->render(), $reference, 'returns the correct html code');  
  
  

