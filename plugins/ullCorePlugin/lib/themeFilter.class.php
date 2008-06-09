<?php

class themeFilter extends sfFilter {
 
  public function execute($filterChain) {
    
    $sf_root_dir = sfConfig::get('sf_root_dir');
    
    // load theme
    $this->theme = sfConfig::get('app_theme', 'ullThemeDefault');
    $theme = $sf_root_dir.'/plugins/'.$this->theme.'/templates/layout';

    $this->getContext()->getController()->getActionStack()->getFirstEntry()->getActionInstance()->setLayout($theme);
              
 
//        switch (SF_ENVIRONMENT) {
//            case 'frontend':
//                $this->getContext()->getController()->getActionStack()->getFirstEntry()->getActionInstance()->setLayout('layout_frontend');
//                break;
//            case 'mobile':
//                $this->getContext()->getController()->getActionStack()->getFirstEntry()->getActionInstance()->setLayout('layout_mobile');
//                break;
//        }
 
    $filterChain->execute();
 
//        switch (SF_ENVIRONMENT) {
//            case 'frontend':
//                $this->getContext()->getResponse()->addStyleSheet('frontend');
//                break;
//            case 'mobile':
//                $this->getContext()->getResponse()->addStyleSheet('mobile');
//                break;
//        }
 
  }
}

?>