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
  
  public function executeRenderSidebarMenu(sfRequest $request, $renderClass = 'ullTreeMenuRenderer', $topLevelHtmlTag = 'li')
  {
    if (!isset($this->renderUlTag))
    {
      $this->renderUlTag = true;
    }
    
    $item = Doctrine::getTable('UllCmsItem')->findOneBySlug($this->slug);
    
    // In some cases the translation need is not fetched... 
    $item->refreshRelated('Translation');
    
    $this->setVar(
      'item',
      $item
    );
    
    $navigation = UllCmsItemTable::getMenuTree(
      $this->slug, 
      $request->getParameter('slug')  //TODO: get the top level slug for subpages
    );
    $this->setVar(
      'menu',
      new $renderClass($navigation, $this->getVar('renderUlTag'), $topLevelHtmlTag),
      true
    );    
  }  
  
  /*
   * Directly render a cms page by slug
   * 
   * This can be used for user-editable content blocks
   */
  public function executeRenderCmsPage(sfRequest $request)
  {
    $this->doc = Doctrine::getTable('UllCmsPage')->findOneBySlug($this->slug);

    // In some cases the translation need is not fetched... 
    $this->doc->refreshRelated('Translation');
    $this->setVar('title', $this->doc->title);
    $this->setVar('body', $this->doc->body, true);
    $this->setVar('is_active', $this->doc->is_active);
    
    $this->allow_edit = UllUserTable::hasPermission('ull_cms_edit');
  }
  
  
  /**
   * Render all child pages of the given slug as sidebar blocks
   * 
   * @param sfRequest $request
   * 
   */
  public function executeRenderSidebarBlocks(sfRequest $request)
  {
    $this->setVar('tree', UllCmsItemTable::getMenuTree($this->slug, null, 2), true);
  }
  
}