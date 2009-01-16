<?php

class ullFlowActionHandlerAssignToUser extends ullFlowActionHandler
{
  
  public function configure()
  {
    $this->addMetaWidget(
      'ullMetaWidgetUllUser', 
      'ull_flow_action_assign_to_user_ull_entity', 
      array('add_empty' => true),
      array(),
      array('required' => false)
    );
  } 
  
  public function render()
  {
    $return = ull_submit_tag(__('Assign'), array('name' => 'submit|action_slug=assign_to_user'));
    
    $return .= ' ' . __('to user') . " \n";
    
    $return .= $this->getForm()->offsetGet('ull_flow_action_assign_to_user_ull_entity')->render();
    $return .= $this->getForm()->offsetGet('ull_flow_action_assign_to_user_ull_entity')->renderError();
    
    return $return;
  }
  
  public function getNext()
  {
    $ullEntityId = $this->getForm()->getValue('ull_flow_action_assign_to_user_ull_entity');
    $ullEntity = Doctrine::getTable('UllEntity')->find($ullEntityId);
    return array('entity' => $ullEntity);    
  }
  
  
}
