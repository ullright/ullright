<?php
class ullCmsComponents extends sfComponents
{
  
  public function executeMainNavigation()
  {
    $navigation = UllNavigationItemTable::getNavigationTree('main-navigation', '', 2);
    $this->setVar('main_navigation', new ullTreeNavigationRenderer($navigation), true);    
  }
  
}