<?php

class ullFlowActionHandlerAssignToUser extends ullFlowActionHandler
{
  
  public function configure()
  {
    $columnConfigOptions = array('entity_classes' => array('UllUser'), 'show_search_box' => true);
    
    if (isset($this->options['group']))
    {
      $columnConfigOptions['filter_users_by_group'] = $this->options['group'];
      unset($this->options['group']);
    }
    
    $this->addMetaWidget(
      'ullMetaWidgetUllEntity', 
      'ull_flow_action_assign_to_user_ull_entity', 
      array_merge($this->options, array('add_empty' => true)), //widget options
      array(), //widget attributes
      array('required' => false), //validator options
      $columnConfigOptions
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

  public static function getFormFieldNames()
  {
    return array('ull_flow_action_assign_to_user_ull_entity');
  }
}
