<?php

/**
 * ullTableTool actions.
 *
 * @package    ullright
 * @subpackage ullTableTool
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllTableToolActions extends ullsfActions
{
  
  private
    $class_name   = '',
    $field_types  = array(),
    $columns      = null
  ;
  
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {    
    $this->forward($this->getModuleName(), 'list');
  }


  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList($request) 
  {
    $this->checkAccess('Masteradmins');
    
    $this->getTablefromRequest();
    
    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }
    
    $this->generator = new ullTableToolGenerator($this->table_name);
    
    $rows = $this->getFilterFromRequest();
    
    $this->generator->buildForm($rows);
    
    $refererHandler = new refererHandler();
    $refererHandler->delete('edit');
    
    $this->breadcrumbForList();

  }

  
  /**
   * Executes show action
   *
   */
  public function executeShow() 
  {
    $this->forward('ullTableTool', 'edit');
  }

  
  /**
   * Executes create action
   *
   */
  public function executeCreate() 
  {
    $this->forward('ullTableTool', 'edit');
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
  public function executeEdit($request)
  {
    $this->checkAccess('Masteradmins');

    $this->refererHandler = new refererHandler();

    $this->getTablefromRequest();
    
    $this->generator = new ullTableToolGenerator($this->table_name, 'w');

    $row = $this->getRowFromRequestOrCreate();

    $this->generator->buildForm($row);

    if ($request->isMethod('post'))
    {
//      var_dump($request->getParameterHolder()->getAll());
      
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        $referer = $this->refererHandler->getRefererAndDelete();
        $referer = ($referer) ? $referer : $this->getRefererFallbackURI();
        $this->redirect($referer);
      }
    }
    else
    {
      $this->refererHandler->initialize();
    }
    $this->breadcrumbForEdit();
  }


  /**
   * Execute delete action
   *
   */
  public function executeDelete()
  { 
    // check access
    $this->checkAccess('MasterAdmins');
    
    $this->getTablefromRequest();
    
    $row = $this->getRowFromRequest();   
    $row->delete();
    
    $refererHandler = new refererHandler();
    $referer = $refererHandler->getRefererAndDelete('edit');    
    $referer = ($referer) ? $referer : $this->getUser()->getAttribute('referer');
    $referer = ($referer && !strstr($referer, 'edit')) ? $referer : $this->getRefererFallbackURI();
    $this->redirect($referer);
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
   * Parses filter request params
   * and returns records accordingly
   *
   * @return Doctrine_Collection
   */
  protected function getFilterFromRequest()
  {
    $this->filter_form = new ullTableToolFilterForm;
    $this->filter_form->bind($this->getRequestParameter('filter'));
    
    $q = new Doctrine_Query;
    $q->from($this->table_name . ' x');
    
    if ($search = $this->filter_form->getValue('search'))
    {      
      $cols = $this->generator->getTableConfig()->getSearchColumnsAsArray();
      $columnsConfig = $this->generator->getColumnsConfig();
      
      foreach ($cols as $key => $col)
      {
        if (isset($columnsConfig[$col]['translation']))
        {
          $cols[$key] = 'Translation.' . $col;
        }
      }
      ullCoreTools::doctrineSearch($q, $search, $cols);
    }

    $rows = $q->execute();
    
    return ($rows->count()) ? $rows : new $this->table_name;
  }

  /**
   * Gets record according to request param
   *
   * @return unknown
   */
  protected function getRowFromRequest()
  {
    $this->forward404Unless($this->getRequestParameter('id') > 0, 'ID is mandatory');
    
    return $this->getRowFromRequestOrCreate();
  }
  
  /**
   * Gets record according to request params or creates a new
   *
   * @return Doctrine_Record
   */
  protected function getRowFromRequestOrCreate()
  {
    if (($id = $this->getRequestParameter('id')) > 0) 
    {
      $this->id = $id;
      $row = Doctrine::getTable($this->table_name)->find($this->id);
      $this->forward404Unless($row);
      
      return $row;
    }
      else 
    {
      $this->id = null;
      return new $this->table_name;
    }
  }  
       
  /**
   * Handles breadcrumb for list action
   *
   */
  protected function breadcrumbForList()
  {
    $this->breadcrumb_tree = new breadcrumbTree();
    $this->breadcrumb_tree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumb_tree->add('ullTableTool');
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->generator->getTableConfig()->label);
    $this->breadcrumb_tree->add(__('List', null, 'common'), 'ullTableTool/list?table=' . $this->table_name);
  }
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $this->breadcrumb_tree = new breadcrumbTree();
    $this->breadcrumb_tree->setEditFlag(true);
    $this->breadcrumb_tree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumb_tree->add('ullTableTool');
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->table_name);
    if ($this->id) 
    {
      $this->breadcrumb_tree->add(
        __('List', null, 'common')
        , $this->refererHandler->getReferer()
      );
      $this->breadcrumb_tree->addFinal(__('Edit', null, 'common'));
    }
    else
    {
      $this->breadcrumb_tree->addFinal(__('Create', null, 'common'));
    }
  }
  
  /**
   * Returns fallback URL if there is no valid referer
   *
   * @return string fallback URL
   */
  protected function getRefererFallbackURI()
  {
    return $this->getModuleName() . '/list?table=' . $this->table_name;
  }
  
}