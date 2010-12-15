<?php

/**
 * ullNewsletter actions.
 *
 * @package    ullright
 * @subpackage ullMail
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllNewsletterActions extends BaseUllGeneratorActions
{
  
  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    $this->categories = array('Product News', 'Pest Practicies');
    
    //Add module stylsheet for all actions
    $path =  '/ullMailTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));    
  }  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->checkPermission('ull_mail_index');
    
    $this->form = new ullFilterForm;
//    
    $this->named_queries = new ullNamedQueriesUllNewsletter;

    $this->breadcrumbForIndex();
  }
  
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_newsletter_list');
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }
  
  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_newsletter_edit');
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonNewsSaveAndShow($this));
    
    parent::executeEdit($request);

    $this->setTableToolTemplate('edit');
  }

  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullNewsletterGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullNewsletterGenerator('w');
  } 
  
  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $breadcrumbTree = new ullNewsletterBreadcrumbTree();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new ullNewsletterBreadcrumbTree();
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullNews/list');
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new ullNewsletterBreadcrumbTree();
    $breadcrumb_tree->setEditFlag(true);
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumb_tree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumb_tree->addDefaultListEntry();
    }    
    
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