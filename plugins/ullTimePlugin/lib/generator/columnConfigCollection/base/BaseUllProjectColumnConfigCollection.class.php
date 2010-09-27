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
    
    if ($this->isCreateOrEditAction())
    {
      $this->create('Managers')
        ->setMetaWidgetClassName('ullMetaWidgetManyToMany')
        ->setWidgetOption('model', 'UllUser')
        ->setValidatorOption('model', 'UllUser')
      ;
    }    
  }
}