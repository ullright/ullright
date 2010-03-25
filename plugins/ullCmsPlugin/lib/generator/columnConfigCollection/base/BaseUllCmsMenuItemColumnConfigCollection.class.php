<?php 

class BaseUllCmsMenuItemColumnConfigCollection extends UllCmsItemColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->disable(array(
      'title', 'body'
    ));
  }
}