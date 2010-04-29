<?php

class UllNewsColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['abstract']->setLabel(__('Abstract', null, 'ullNewsMessages'));
    
    $this['link_name']->setLabel(__('Link name', null, 'ullNewsMessages'));
    $this['link_url']
      ->setMetaWidgetClassName('ullMetaWidgetNewsLink')
      ->setLabel(__('Link URL', null, 'ullNewsMessages'));
    
    $this['image_upload']
      ->setMetaWidgetClassName('ullMetaWidgetSimpleUpload')
      ->setLabel(__('Image upload', null, 'ullNewsMessages'))
      ->setWidgetAttribute('alt', __('News image', null, 'ullNewsMessages'));
      
    $this['activation_date']
      ->setDefaultValue(date('Y-m-d'))
      ->setLabel(__('Activation date', null, 'ullNewsMessages'));
      
    $this['deactivation_date']->setLabel(__('Deactivation date', null, 'ullNewsMessages'));
    
  if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'title', 'link_name', 'link_url', 'activation_date', 'deactivation_date'));
    }
  }
}