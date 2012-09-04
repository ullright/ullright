<?php

/**
 * Configures the ullCms plugin. 
 */
class ullCmsPluginConfiguration extends sfPluginConfiguration
{
  
  public function initialize()
  {
    $this->dispatcher->connect(
      'ull_user.log_in_success', 
      array($this, 'resetInlineEditingToggle')
    );
  }
  
  /** 
   * Always turn on inline editing after login
   *  
   * @param sfEvent $event
   */
  public static function resetInlineEditingToggle(sfEvent $event)
  {
    sfContext::getInstance()->getUser()->setAttribute('inline_editing', true);
  }
  
  
}
