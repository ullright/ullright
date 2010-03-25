<?php

/**
 * Cms actions.
 * 
 * This action extends ullTableTool to add some specific functionality
 * for ullCms
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllCmsActions extends BaseUllGeneratorActions
{  
  
  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    //Add ullCms stylsheet for all actions
    $path =  '/ullCmsTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }    
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }   

  
  /**
   * Execute list action
   *
   * @param sfWebRequest $request
   */
  public function executeShow(sfRequest $request)
  {
    $this->doc = $this->getRoute()->getObject();
    
    $menu = UllCmsItemTable::getMenuTree('main-menu', $this->doc->slug, 2);
    $this->setVar('main_menu', new ullTreeMenuRenderer($menu), true);    
    
    $this->loadMenus();
  }
  
  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullFilterForm';
  }  
  
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullCmsGenerator('r', 'list', $this->columns);
  }  
  
  
  /**
   * Executes edit action
   *
   * @param sfWebRequest $request
   */
  public function executeEdit(sfRequest $request) 
  {
    parent::executeEdit($request);
    
    $this->getResponse()->addJavascript('/ullCorePlugin/js/fckeditor/fckeditor.js');

    $this->setTableToolTemplate('edit');
  }   
  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullCmsGenerator('w');
  }

  /**
   * Load menues
   * 
   * @return unknown_type
   */
  protected function loadMenus()
  {
    $menu = UllCmsItemTable::getSubMenuFor('main-menu', $this->doc->slug);
    $this->setVar('sidebar_menu', new ullTreeMenuRenderer($menu), true);    
  }
  
}
