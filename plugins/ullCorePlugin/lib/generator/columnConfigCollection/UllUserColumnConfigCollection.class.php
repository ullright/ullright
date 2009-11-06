<?php 

class UllUserColumnConfigCollection extends UllEntityColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable('type');
    
    $this['email']->setShowSpacerAfter(true);
    
    $this['username']->setValidatorOption('required', true);
  }  
 
}