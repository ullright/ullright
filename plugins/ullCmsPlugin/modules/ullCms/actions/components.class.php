<?php
class ullCmsComponents extends sfComponents
{
  
  public function executeMainMenu()
  {
    $navigation = UllCmsItemTable::getMenuTree('main-menu', '', 2);
    $this->setVar('main_menu', new ullTreeMenuRenderer($navigation, $this->getVar('renderUlTag')), true);    
  }
  
  public function executeFooterMenu()
  {
    $navigation = UllCmsItemTable::getMenuTree('footer-menu', '', 2);
    $this->setVar('footer_menu', new ullTreeMenuRenderer($navigation, $this->getVar('renderUlTag')), true);    
  }
  
}