<?php

class ullFlowAssignmentGenerator extends ullFlowGenerator
{

  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {   
    parent::buildColumnsConfig();
    
    $this->columnsConfig['assigned_to_ull_entity_id']
      ->enable()
      ->setAutoRender(false)
    ;
    
    $this->columnsConfig->order(array(
      'ull_flow_app_id',
      'subject',
      'priority',
      'assigned_to_ull_entity_id',
    ));
  }
  

}