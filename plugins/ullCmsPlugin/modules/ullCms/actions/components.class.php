<?php
class ullCmsComponents extends sfComponents
{
  
  public function executeMainNavigation()
  {
    $navigation = UllNavigationItemTable::getNavigationTree('main-navigation', '', 2);
    $this->setVar('main_navigation', new ullTreeNavigationRenderer($navigation), true);    
  }
  
  public function executeFooterNavigation()
  {
    $navigation = UllNavigationItemTable::getNavigationTree('footer', '', 2);
    $this->setVar('footer_navigation', new ullTreeNavigationRenderer($navigation), true);    
  }
  
}