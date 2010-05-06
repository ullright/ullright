<?php 

class BaseUllUserColumnConfigCollection extends UllEntityColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable('type');
    
    $this['username']->setValidatorOption('required', true);
  }  
 
}