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
    $this->forward('ullVentory', 'edit');
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
    
//    $this->breadcrumbForEdit();
    
    if ($request->isMethod('post'))
    {
      
//      var_dump($_REQUEST);
//      var_dump($this->getRequest()->getParameterHolder()->getAll());
//      die;

      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        $this->redirect($this->getUriMemory()->getAndDelete('list'));
      }
      else
      {
//        var_dump($this->generator->getForm()->getErrorSchema());
      }
    }
//    echo $this->generator->getForm()->debug();
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
    }
    
    if (!$username)
    {
      $username = UllUserTable::getLoggedInUsername();
    }
    
    $userId = UllUserTable::findIdByUsername($username);
    
    $this->docs = UllProjectReportingTable::findByDateAndUserId($date, $userId);
    
    
    $id = $this->getRequestParameter('id');
    if ($id)
    {
      $this->doc = Doctrine::getTable('UllProjectReporting')->findOneById($id);
    }
    else
    {
      $this->doc = new UllProjectReporting;
    }
  }
  

}