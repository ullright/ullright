<?php

class UllNewsColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['image_upload']
      ->setMetaWidgetClassName('ullMetaWidgetSimpleUpload');
      //->setValidatorOption('required', false);
      
    $this['activation_date']->setDefaultValue(date('Y-m-d'));
    
    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'title', 'link_name', 'link_url', 'activation_date', 'deactivation_date'));
    }
  }
}