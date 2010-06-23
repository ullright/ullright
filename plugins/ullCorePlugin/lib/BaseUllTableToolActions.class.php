<?php

/**
 * ullTableTool actions.
 *
 * @package    ullright
 * @subpackage ullTableTool
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllTableToolActions extends BaseUllGeneratorActions
{

  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();

    $tableParam = '?table=' . $this->getRequestParameter('table');
    
    $this->list_base_uri    = $this->getModuleName() . '/list' . $tableParam;
    $this->create_base_uri  = $this->getModuleName() . '/create' . $tableParam;
    $this->edit_base_uri    = $this->getModuleName() . '/edit' . $tableParam;
    $this->show_base_uri    = $this->getModuleName() . '/show' . $tableParam;
    $this->delete_base_uri  = $this->getModuleName() . '/delete' . $tableParam;
    $this->delete_future_version_base_uri = $this->getModuleName() . '/deleteFutureVersion' . $tableParam;
    
    $this->getUriMemory()->setDefault($this->list_base_uri);
    
    $this->getTablefromRequest();
  }
  
  
  /**
   * Executes index action
   *
   */
  public function executeIndex(sfRequest $request)
  {    
    $this->redirect('ullAdmin/index');
  }

  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {    
    $this->checkPermission($this->getPermissionName());
    
    parent::executeList($request);
    
    $this->setCommonTitle();
  }  

  
  /**
   * Setup ullGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullTableToolGenerator($this->table_name, 'r', 'list', $this->columns);
  }  

  
  /**
   * Executes show action
   *
   */
  public function executeShow(sfRequest $request) 
  {
    $this->forward($this->getModuleName(), 'edit');
  }

  /**
   * Handles a state-change request for a flag for
   * the currently logged-in user. This requires a
   * model to adopt the ullFlaggable behavior.
   * 
   * Needs four parameters:
   * table - the model
   * id - the record id
   * flag - the flag name
   * value - 'true' => true, otherwise false; null is valid
   * 
   * @param sfRequest $request
   * @return string view rendered by sfView:NONE
   */
  public function executeSetUserFlag(sfRequest $request)
  {
    //parse model
    //(done by preExecute() into $this->table_name)
    
    //parse record id
    $recordId = $request->getParameter('id');
    $record = ($recordId) ? Doctrine::getTable($this->table_name)->findOneById($recordId) : false;
    $this->forward404If(!$record, 'Record id not specified or invalid');
    
    //TODO: include check here if model adopts UllFlaggable behavior
    //should not be a problem if it does not (hasFlag call simply fails),
    //but should be done anyway
    
    //parse flag name
    $flagName = $request->getParameter('flag');
    $this->forward404If(!$record->hasFlag($flagName), 'Flag not specified or invalid');
    
    //parse flag value (null is ok, will remove the flag)
    $flagValue = $request->getParameter('value');
    if ($flagValue !== null)
    {
      $flagValue = ($flagValue == 'true') ? true : false;
    }
        
    //set flag for currently logged in user
    $record->setFlag($flagName, $flagValue);
    
    if ($request->isXmlHttpRequest())
    {
      return sfView::NONE;
    }
    else
    {
      $referer = $request->getReferer();
      $this->redirect($referer ? $referer : 'homepage');
    }
  }
  
  
  /**
   * Executes create action
   *
   */
  public function executeCreate(sfRequest $request) 
  {
    $this->forward($this->getModuleName(), 'edit');
  }  

  
  
//                           __
//                   _.--""  |
//    .----.     _.-'   |/\| |.--.
//    |jrei|__.-'   _________|  |_)  _______________  
//    |  .-""-.""""" ___,    `----'"))   __   .-""-.""""--._  
//    '-' ,--. `    |tic|   .---.       |:.| ' ,--. `      _`.
//     ( (    ) ) __|tac|__ \\|// _..--  \/ ( (    ) )--._".-.
//      . `--' ;\__________________..--------. `--' ;--------'
//       `-..-'                               `-..-'
  
  /**
   * Executes edit action
   *
   * @param sfWebRequest $request
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission($this->getPermissionName());
    
    parent::executeEdit($request);
    
    $this->setCommonTitle();
    $this->appendToTitle($this->generator->getRow());
  }  
  
  
  /**
   * Setup ullGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullTableToolGenerator($this->table_name, 'w');
  }  

  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDelete(sfRequest $request)
  { 
    $this->checkPermission($this->getPermissionName());
    
    parent::executeDelete($request);
  }
  
  
  /**
   * Setup ullGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */  
  protected function getDeleteGenerator()
  {
    return new ullTableToolGenerator($this->table_name);
  }
  
  
  /**
   * Executes delete action
   *
   * @param sfWebRequest $request
   */
  public function executeDeleteFutureVersion(sfRequest $request)
  { 
    $this->checkPermission($this->getPermissionName());
    
    parent::executeDeleteFutureVersion($request);
  }    
  
  /**
   * Gets a table object according to request param
   *
   * @return Doctrine_Record
   */
  protected function getTablefromRequest() 
  {
    $this->forward404Unless(
        $this->hasRequestParameter('table'), 
        'Please specify a database table'
    );
    
    $this->table_name = $this->getRequestParameter('table');

    $this->forward404Unless(
        class_exists($this->table_name),
        'Database table not found: ' . $this->table_name
    );

    return true;
  }
  
  
  /**
   * Dynamically create the UllPermission name
   * 
   * @return string
   */
  protected function getPermissionName()
  {
    $permission = 'ull_tabletool_' . sfInflector::underscore($this->getRequest()->getParameter('table'));
    
    return $permission;
  }
  
  
  /**
   * Handles breadcrumb for list action
   *
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Tabletool'));
    $breadcrumb_tree->add(__('Table') . ' ' . $this->generator->getTableConfig()->getName());
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullTableTool/list?table=' . $this->table_name);
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }
  
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    //is this even called anymore?
    //it looks exactly like the one in BaseUllGeneratorActions...
    
    $breadcrumb_tree = new breadcrumbTree();
    $breadcrumb_tree->setEditFlag(true);
    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $breadcrumb_tree->add(__('Tabletool'));
    $breadcrumb_tree->add(__('Table') . ' ' . $this->generator->getTableConfig()->getName());
    $breadcrumb_tree->add(__('Result list', null, 'common'), $this->getUriMemory()->get('list'));    
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
  
  protected function setCommonTitle()
  {
    $this->getResponse()->setTitle(
      $this->getModuleName() . 
      ' - ' . 
      $this->generator->getTableConfig()->getName() .
      ' - ' .
      __(ucfirst($this->getRequestParameter('action')), null, 'common')
    );
  }
  
}