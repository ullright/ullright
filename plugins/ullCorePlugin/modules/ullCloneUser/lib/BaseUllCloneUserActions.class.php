<?php

/**
 * clone user actions.
 * 
 *
 * @package    ullright
 * @subpackage ullCore
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllCloneUserActions extends BaseUllGeneratorActions
{
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkAccess('Masteradmins');
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }  
  
  /**
   * Setup ullUserGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullCloneUserGenerator('r', 'list', $this->columns);
  }
  
  
  /**
   * Executes edit action
   *
   * @param sfWebRequest $request
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkAccess('Masteradmins');
    
    parent::executeEdit($request);

    $this->setTableToolTemplate('edit');
  }  
  
  
  /**
   * Setup ullUserGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullCloneUserGenerator('w');
  }  
  
  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDelete(sfRequest $request)
  { 
    $this->checkAccess('MasterAdmins');
    
    parent::executeDelete($request);
  }  
  

  /**
   * Setup ullUserGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getDeleteGenerator() 
  { 
    return new ullCloneUserGenerator();
  }
  
  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDeleteFutureVersion(sfRequest $request)
  { 
    $this->checkAccess('MasterAdmins');
    
    parent::executeDeleteFutureVersion($request);
  }

  
  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullTableToolFilterForm';
  }  
  
  
  /**
   * Apply custom modifications to the query
   *  
   * @return none
   */
  protected function modifyQueryForFilter()
  {
    // hack because of a possible doctrine bug with UllEntity
    //  see ullQueryTest for details
    $this->q->getDoctrineQuery()->addSelect('x.*'); 
  }   
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('Clone users', null, 'ullCoreMessages'));
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullCloneUser/list');
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
    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('Clone users', null, 'ullCoreMessages'));
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullCloneUser/list');  
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
