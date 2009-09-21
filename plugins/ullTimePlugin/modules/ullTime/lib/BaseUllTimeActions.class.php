<?php

/**
 * ullTime actions.
 *
 * @package    ullright
 * @subpackage ullTime
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllTimeActions extends ullsfActions
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
    
    //Add ullTime stylsheet for all actions
    $path =  '/ullTimeTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }  
  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex() 
  {
//    $this->checkPermission('ull_ventory_index');
    
//    $this->form = new ullVentoryFilterForm;
    
//    $this->named_queries = new ullNamedQueriesUllVentory;

//    $this->breadcrumbForIndex();
  }
  
  /**
   * Execute create action
   * 
   */
  public function executeCreate($request) 
  {
    $this->forward('ullTime', 'edit');
  } 
  
  /**
   * Execute edit action
   * 
   */
  public function executeEdit($request) 
  {
    $this->checkAccess('LoggedIn');
    
    $this->getDocsFromRequestOrCreate();
    
    $this->list_generator = new ullTableToolGenerator('UllProjectReporting', 'r', 'list');
    $this->list_generator->buildForm($this->docs);
    
    $this->edit_generator = new ullTableToolGenerator('UllProjectReporting', 'w');
    $this->edit_generator->buildForm($this->doc);
    
    $this->breadcrumbForEdit();
    
    if ($request->isMethod('post'))
    {
      
//      var_dump($_REQUEST);
//      var_dump($this->getRequest()->getParameterHolder()->getAll());
//      die;
      if ($this->edit_generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        if ($request->getParameter('action_slug') == 'save_new') 
        {
          $this->redirect('ullTime/create?date=' . $request->getParameter('date') . '&username=' . $request->getParameter('username'));
        } 
        // use the default referer
        else
        {
          $this->redirect($this->getUriMemory()->getAndDelete('list'));
        }
      }
      else
      {
//        var_dump($this->generator->getForm()->getErrorSchema());
      }
    }
//    echo $this->generator->getForm()->debug();
  }


  /**
   * Delete a project effort
   * 
   * @return none
   */
  public function executeDelete($request)
  {
    $this->checkAccess('LoggedIn');
    $this->getDocsFromRequestOrCreate();
    $this->doc->delete();
    $this->redirect('ullTime/create?date=' . $request->getParameter('date') . '&username=' . $request->getParameter('username'));
  }
  
  /**
   * Gets  doc according to request params
   * 
   */
  protected function getDocsFromRequestOrCreate()
  {
    $date = $this->getRequestParameter('date');
    $username = $this->getRequestParameter('username');
    
    if (!$date)
    {
      $date = date('Y-m-d');
      $this->getRequest()->setParameter('date', $date);
    }
    
    if (!$username)
    {
      $username = UllUserTable::getLoggedInUsername();
      $this->getRequest()->setParameter('username', $username);
    }
    
    $userId = UllUserTable::findIdByUsername($username);
    
    $this->docs = UllProjectReportingTable::findByDateAndUserId($date, $userId);
    
    if ($this->getRequestParameter('action') == 'create')
    {
      $this->doc = new UllProjectReporting;
      $this->doc->ull_user_id = $userId;
      $this->doc->date = $date;      
    }
    else
    {
      $this->forward404Unless($this->doc = Doctrine::getTable('UllProjectReporting')->findOneById($this->getRequestParameter('id')));
      
    }
  }
  
  
  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() 
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add(__('Time Reporting') . ' ' . __('Home', null, 'common'), 'ullTime/index');

    if ($this->doc->exists()) 
    {
      $this->breadcrumbTree->add(__('Edit', null, 'common'));
    } 
    else 
    {
      $this->breadcrumbTree->add(__('Create', null, 'common'));
    } 
  }  
  

}