<?php
class BaseUllCmsComponents extends sfComponents
{
  
  public function executeMainMenu(sfRequest $request, $renderClass = 'ullTreeMenuRenderer', $topLevelHtmlTag = 'li')
  {
    $navigation = UllCmsItemTable::getMenuTree(
      'main-menu', 
      $request->getParameter('slug'),  //TODO: get the top level slug for subpages
      sfConfig::get('app_ull_cms_main_menu_depth', 2)
    );
    $this->setVar(
      'main_menu', 
      new $renderClass($navigation, $this->getVar('renderUlTag'), $topLevelHtmlTag),
      true
    );    
  }
  
  public function executeFooterMenu(sfRequest $request, $renderClass = 'ullTreeMenuRenderer', $topLevelHtmlTag = 'li')
  {
    $navigation = UllCmsItemTable::getMenuTree('footer-menu', '', 2);
    $this->setVar(
      'footer_menu', 
      new $renderClass($navigation, $this->getVar('renderUlTag'), $topLevelHtmlTag), 
      true
    );    
  }
  
  public function executeRenderCmsPage(sfRequest $request)
  {
    $this->doc = Doctrine::getTable('UllCmsPage')->findOneBySlug($this->slug);
    $this->setVar('title', $this->doc->title);
    $this->setVar('body', $this->doc->body, true);
    $this->setVar('is_active', $this->doc->is_active);
    
    $this->allow_edit = UllUserTable::hasPermission('ull_cms_edit');
  }
  
}