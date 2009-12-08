<?php

/**
 * ullOrgchart actions.
 *
 * @package    ullright
 * @subpackage ullOrgchart
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllOrgchartActions extends ullsfActions
{

  /**
   * Execute  before each action
   *
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/list';
    $this->getUriMemory()->setDefault($defaultUri);

    //Add ullOrgchart stylsheet for all actions
    $path = '/ullPhoneTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }


  /**
   * Execute index action
   *
   */
  public function executeIndex(sfRequest $request)
  {
    $this->redirect('ullOrgchart/list');
  }


  /**
   * Execute list action
   *
   * @param $request
   * @return unknown_type
   */
  public function executeList(sfRequest $request)
  {
    $this->checkPermission('ull_orgchart_list');

    $userId = $request->getParameter('user_id', sfConfig::get('app_ull_orgchart_ceo_user_id', 1));
    $depth = $request->getParameter('depth', 2);
    
    $entity = UllEntityTable::findById($userId);
    
    $this->forward404Unless($entity);
    
    $treeData = UllEntityTable::getSubordinateTree($entity, $depth);
    
//    var_dump($treeData->toArray());die;
    
    $this->setVar('tree', new ullTreeRenderer($treeData), true);
    
//    $this->filter_form = new ullFilterForm;
    
    $this->breadcrumbForList();

  }
  
  
  /**
   * Execute show action
   *
   */
  public function executeShow(sfRequest $request)
  {
    $this->redirect('ullOrgchart/list');
  }

  
  /**
   * Execute create action
   *
   */
  public function executeCreate(sfRequest $request)
  {
    $this->redirect('ullOrgchart/list');
  }    
  
  
  /**
   * Execute edit action
   *
   */
  public function executeEdit(sfRequest $request)
  {
    $this->redirect('ullOrgchart/list');
  }    
  
  
  /**
   * Execute delete action
   *
   */
  public function executeDelete(sfRequest $request)
  {
    $this->redirect('ullOrgchart/list');
  }    

  /**
   * Create breadcrumbs for index action
   *
   */
  protected function breadcrumbForIndex()
  {
    $breadcrumbTree = new ullOrgchartBreadcrumbTree();
    
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }

  /**
   * Create breadcrumbs for list action
   *
   */
  protected function breadcrumbForList()
  {
    $breadcrumbTree = new ullOrgchartBreadcrumbTree();
    $breadcrumbTree->addDefaultListEntry();
    
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }




}