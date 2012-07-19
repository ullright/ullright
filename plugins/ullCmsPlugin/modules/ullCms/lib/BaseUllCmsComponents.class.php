<?php
class BaseUllCmsComponents extends sfComponents
{
  
  /**
   * Render a menu
   * 
   * Expects the "slug" of the cms item
   * 
   * @param sfRequest $request
   * @param integer $depth                Optional. Depth of sub hierachies. Default = 2
   * @param string $renderClass           Optional. Give a custom render class
   * @param string $topLevelHtmlTag       Optional. Configure the top html tag type. Default = 'li'
   */
  public function executeRenderMenu(sfRequest $request, $depth = 2, $renderClass = 'ullTreeMenuRenderer', $topLevelHtmlTag = 'li')
  {
    $navigation = UllCmsItemTable::getMenuTree(
      $this->slug, 
      $request->getParameter('slug'),
      ($this->depth) ? $this->depth : $depth
    );
    
    $this->setVar(
      'menu', 
      new $renderClass($navigation, $this->getVar('renderUlTag'), $topLevelHtmlTag),
      true
    );     
  }
  
  /**
   * Render sidebar menu
   * 
   * @param sfRequest $request
   * @param unknown_type $renderClass
   * @param unknown_type $topLevelHtmlTag
   */
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

    $this->html_class = str_replace('-', '_', $this->slug);
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
  
  
  /**
   * Shortcut for main-menu
   * 
   * @deprecated
   * @param sfRequest $request
   * @param unknown_type $renderClass
   * @param unknown_type $topLevelHtmlTag
   */
  public function executeMainMenu(sfRequest $request, $renderClass = 'ullTreeMenuRenderer', $topLevelHtmlTag = 'li')
  {
    $this->slug = 'main-menu';
    
    $this->executeRenderMenu(
      $request, 
      sfConfig::get('app_ull_cms_main_menu_depth', 2),
      $renderClass,
      $topLevelHtmlTag
    );
    
    $this->setVar('main_menu', $this->menu, true);
  }
  
  
  /**
   * Shortcut for footer-menu
   * 
   * @deprecated
   * @param sfRequest $request
   * @param unknown_type $renderClass
   * @param unknown_type $topLevelHtmlTag
   */
  public function executeFooterMenu(sfRequest $request, $renderClass = 'ullTreeMenuRenderer', $topLevelHtmlTag = 'li')
  {
    $this->slug = 'footer-menu';
    
    $this->executeRenderMenu(
      $request, 
      2,
      $renderClass,
      $topLevelHtmlTag
    );
    
    $this->setVar('footer_menu', $this->menu, true);
  }    
  
  /**
   * Edit link component
   */
  public function executeEditLink()
  {
    $this->allow_edit = UllUserTable::hasPermission('ull_cms_edit');

    if (!$this->doc)
    {
      throw new InvalidArgumentException('Please give a UllCmsPage or a UllCmsContentBlock as $doc');
    }
    
    if ($this->doc instanceof UllCmsPage)
    {
      $this->module = 'ullCms';
    }
    elseif ($this->doc instanceof UllCmsContentBlock)
    {
      $this->module = 'ullCmsContentBlock';
    }
  }
}