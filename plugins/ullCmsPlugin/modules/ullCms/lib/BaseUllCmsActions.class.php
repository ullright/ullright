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
    $this->checkPermission('ull_cms_list');
    
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
    $this->checkPermission('ull_cms_show');
    
    $this->forward404Unless($this->getRoute() instanceof sfDoctrineRoute);
    
    $this->doc = $this->getRoute()->getObject();
    
    $this->setVar('title', $this->doc->title);
    $this->setVar('body', $this->doc->body, true);
    
    $this->getResponse()->setTitle($this->doc->title);
    
    $this->loadMenus();
    
    $this->allow_edit = UllUserTable::hasPermission('ull_cms_edit');
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
    $this->checkPermission('ull_cms_edit');
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonCmsSaveAndShow($this));
    $this->registerEditActionButton(new ullGeneratorEditActionButtonCmsSaveAndCreateNews($this, false));
    
    parent::executeEdit($request);
    
    $this->cultures = ullGenerator::getDefaultCultures();
    
    //$this->setTableToolTemplate('edit');
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
  
  /**
   * Execute delete action
   * 
   * @see BaseUllGeneratorActions#executeDelete($request)
   */
  public function executeDelete(sfRequest $request)
  {
    $this->checkPermission('ull_cms_delete');
    
    parent::executeDelete($request);
  }  
  
  /**
   * Define generator for delete action
   * 
   * @see BaseUllGeneratorActions#getDeleteGenerator()
   */
  protected function getDeleteGenerator()
  {
    return new ullCmsGenerator('r', 'list', $this->columns);
  } 
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('CMS pages', null, 'ullCmsMessages'));
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullCms/list');
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->setEditFlag(true);
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('CMS pages', null, 'ullCmsMessages'));
    // display result list link only when there is a referer containing 
    //  the list action 
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumb_tree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumb_tree->addDefaultListEntry();
    }    
    
//    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullUser/list');  
    if ($this->id) 
    {
      $breadcrumb_tree->add(__('Edit', null, 'common'));
    }
    else
    {
      $breadcrumb_tree->add(__('Create', null, 'common'));
    }
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  } 
  
}
