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

class BaseUllNewsActions extends BaseUllGeneratorActions
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
    $this->checkPermission('ull_news_list');
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }
  
  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_news_edit');
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonNewsSaveAndShow($this));
    
    parent::executeEdit($request);

    $this->setTableToolTemplate('edit');
  }
  
 /* public function executeShow(sfRequest $request) 
  {
  if (!$this->hasRequestParameter('slug') and !$this->hasRequestParameter('id'))
    {
      throw new InvalidArgumentException('At least one of the "slug" or "id" parameters have to be given');
    }

    if ($docId = $this->getRequestParameter('slug'))
    {
      $this->doc = Doctrine::getTable('UllNews')->findOneBySlug($docId);
      $this->forward404Unless($this->doc);
    }
    elseif ($docId = $this->getRequestParameter('id'))
    {
      $this->doc = Doctrine::getTable('UllNews')->findOneById($docId);
      $this->forward404Unless($this->doc);
    }
  }*/

  public function executeNewsList(sfRequest $request)
  {
    $this->checkPermission('ull_news_newsList');
  }
  
  public function executeNewsListFeed(sfRequest $request)
  {
    $this->checkPermission('ull_news_newsList');
    
    $this->lang = $request->getParameter('lang', sfConfig::get('sf_default_culture'));
    if (!in_array($this->lang, sfConfig::get('app_i18n_supported_languages'))) {
      $this->redirect404('Invalid language: ' . $this->lang);
    }   
    $this->getUser()->setCulture($this->lang);
    $this->getResponse()->setContentType('text/xml');
    $this->setLayout(false);
    $this->newsEntries = Doctrine::getTable('UllNews')->findLatestActiveNews();
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
    return new ullNewsGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullNewsGenerator('w');
  } 

  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('News entries', null, 'ullNewsMessages'));
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullNews/list');
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
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('News entries', null, 'ullNewsMessages'));
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
  
  
  /**
   * Execute delete action
   * 
   * @see BaseUllGeneratorActions#executeDelete($request)
   */
  public function executeDelete(sfRequest $request)
  {
    $this->checkPermission('ull_news_delete');
    
    parent::executeDelete();
  }
  
  /**
   * Define generator for delete action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getDeleteGenerator()
  {
    return new ullNewsGenerator('r', 'list', $this->columns);
  } 
}
