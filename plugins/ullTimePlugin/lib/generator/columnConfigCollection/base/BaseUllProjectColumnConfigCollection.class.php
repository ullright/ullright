<?php 

class BaseUllProjectColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    if ($this->isListAction())
    {
      $this['created_at']->enable();
    }
    
    $this['is_routine']->setLabel(__('Routine', null, 'ullTimeMessages'));
    
    //$this['is_default']->setLabel(__('Default', null, 'ullTimeMessages'));      
  }
}