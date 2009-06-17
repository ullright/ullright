<?php

/**
 *
 * Load the layout according to the configured theme
 * 
 * @author klemens.ullmann@ull.at
 *
 */

class themeFilter extends sfFilter 
{
 
  public function execute($filterChain) 
  {
    $theme = sfConfig::get('sf_root_dir') . '/plugins/ullCoreTheme' .
      sfConfig::get('app_theme_package', 'NG') .
      'Plugin/templates/layout';

    $this->getContext()->getController()->getActionStack()->getLastEntry()->getActionInstance()->setLayout($theme);
    $filterChain->execute();
  }
}
