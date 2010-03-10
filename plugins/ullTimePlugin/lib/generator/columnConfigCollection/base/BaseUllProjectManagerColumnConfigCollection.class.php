<?php 

class BaseUllProjectManagerColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_user_id']
      ->setWidgetOption('add_empty', true)
      ->setWidgetOption('show_search_box', true)
    ;
    
    $this['ull_project_id']
      ->setLabel(__('Project', null, 'ullTimeMessages'))
      ->setWidgetOption('add_empty', true)
      ->setWidgetOption('show_search_box', true)
    ;
    
    $this->order(array(
      'ull_project_id',
      'ull_user_id'
    ));
    
    //$this['is_default']->setLabel(__('Default', null, 'ullTimeMessages'));      
  }
}