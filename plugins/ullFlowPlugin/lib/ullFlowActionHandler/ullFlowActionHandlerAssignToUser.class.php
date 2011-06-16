<?php

class ullFlowActionHandlerAssignToUser extends ullFlowActionHandler
{

  protected 
    $actionSlug = '',
    $actionLabel = '',
    $fieldName = ''
  ;  
  
  public function configure()
  {
    $this->setNames();
    
    $this->fieldName = 'ull_flow_action_' . $this->actionSlug . '_ull_entity';
    
    $columnConfigOptions = array('entity_classes' => array('UllUser'), 'show_search_box' => true);
    
    if (isset($this->options['group']))
    {
      $columnConfigOptions['filter_users_by_group'] = $this->options['group'];
      unset($this->options['group']);
    }
    
    $this->addMetaWidget(
      'ullMetaWidgetUllEntity', 
      $this->fieldName, 
      array_merge($this->options, array('add_empty' => true)), //widget options
      array(), //widget attributes
      array('required' => false), //validator options
      $columnConfigOptions
    );
  } 
  
  /**
   * Handlers which are based on this action handler can configure the names here
   */
  protected function setNames()
  {
    $this->actionSlug = 'assign_to_user';
    $this->actionLabel = __('Assign');
  }
  
  public function render()
  {
    $return = ull_submit_tag($this->actionLabel, array('name' => 'submit|action_slug=' . $this->actionSlug));
    
    $return .= ' ' . __('to user') . " \n";
    
    $return .= $this->getForm()->offsetGet($this->fieldName)->render();
    $return .= $this->getForm()->offsetGet($this->fieldName)->renderError();
    
    return $return;
  }
  
  public function getNext()
  {
    $ullEntityId = $this->getForm()->getValue($this->fieldName);
    $ullEntity = Doctrine::getTable('UllEntity')->find($ullEntityId);
    
    if ($ullEntity)
    {
      return array('entity' => $ullEntity);
    }
    else
    {
      throw new InvalidArgumentException('Invalid ' . $this->fieldName . ' given');
    }    
  }

}
