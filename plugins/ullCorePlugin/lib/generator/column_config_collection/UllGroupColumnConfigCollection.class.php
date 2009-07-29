<?php

class UllGroupColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['email']->setMetaWidgetClassName('ullMetaWidgetEmail');
    $this['is_virtual_group']->setLabel(__('Is a virtual group', null, 'ullCoreMessages'));
    
    $this->disableAllExcept(array('id', 'display_name', 'email', 'is_virtual_group',
      'creator_user_id', 'created_at', 'updator_user_id', 'updated_at'));
    
    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'display_name', 'email'));
    } 
  }
}