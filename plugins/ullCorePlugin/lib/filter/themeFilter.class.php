<?php

/**
 *
 * Set the correct layout
 * 
 * @author klemens.ullmann@ull.at
 *
 */

class themeFilter extends sfFilter 
{
 
  public function execute($filterChain) 
  {
    // try to load custom override layout
    if ($overrideLayout = sfConfig::get('app_override_layout'))
    {
      $this->getContext()->getController()->getActionStack()->getLastEntry()->getActionInstance()->setLayout($overrideLayout);
    }
    
    // load layout of the configured theme
    else
    {
      $theme = sfConfig::get('sf_root_dir') . '/plugins/ullCoreTheme' .
        sfConfig::get('app_theme_package', 'NG') .
        'Plugin/templates/layout';
      $this->getContext()->getController()->getActionStack()->getLastEntry()->getActionInstance()->setLayout($theme);
    }
    
    $filterChain->execute();
  }
}
