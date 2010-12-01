<?php
class ullCmsComponents extends sfComponents
{
  
  public function executeMainMenu(sfRequest $request)
  {
    $navigation = UllCmsItemTable::getMenuTree(
      'main-menu', 
      $request->getParameter('slug'),  //TODO: get the top level slug for subpages
      sfConfig::get('app_ull_cms_main_menu_depth', 2)
    );
    $this->setVar('main_menu', new ullTreeMenuRenderer($navigation, $this->getVar('renderUlTag')), true);    
  }
  
  public function executeFooterMenu()
  {
    $navigation = UllCmsItemTable::getMenuTree('footer-menu', '', 2);
    $this->setVar('footer_menu', new ullTreeMenuRenderer($navigation, $this->getVar('renderUlTag')), true);    
  }
  
}