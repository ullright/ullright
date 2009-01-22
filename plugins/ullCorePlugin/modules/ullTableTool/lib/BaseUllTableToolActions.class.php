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
    
    $this->generator = new ullTableToolGenerator($this->table_name);
    
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

    if (!$defaultOrder = $this->generator->getTableConfig()->sort_columns)
    {
      $defaultOrder = 'id';
    }
//    var_dump($defaultOrder);die;
    
    $this->order = $this->getRequestParameter('order', $defaultOrder);
    $this->order_dir = $this->getRequestParameter('order_dir', 'asc');
    
    $orderDir = ($this->order_dir == 'desc') ? 'DESC' : 'ASC';

    switch ($this->order)
    {
      case 'creator_user_id':
        $q->orderBy('x.Creator.display_name ' . $orderDir);
        break;
      case 'updator_user_id':
        $q->orderBy('x.Updator.display_name ' . $orderDir);
        break;
      default:
        if (strpos($this->order, '_translation_'))
        {
          $a = explode('_', $this->order);
          $q->orderBy('x.Translation.' . $a[0] . ' ' . $orderDir);
        } 
        else
        {
          $q->orderBy($this->order . ' ' . $orderDir);  
        }
    }    
    
    $this->pager = new Doctrine_Pager(
      $q, 
      $this->getRequestParameter('page', 1),
      sfConfig::get('app_pager_max_per_page')
    );
    $rows = $this->pager->execute();    
    
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
    $identifiers = $this->generator->getIdentifierAsArray();
    
    $q = new Doctrine_Query;
    $q->from($this->table_name . ' x');

    $hasIdentifier = false;
    
    foreach ($identifiers as $identifier)
    {
      $value = $this->getRequestParameter($identifier);
      if ($value)
      {
        $hasIdentifier = true;      
      }
      $q->addWhere('x.' . $identifier . ' = ?', $value); 
    }

    if ($hasIdentifier)
    {
      $row = $q->execute()->getFirst();
      $this->forward404Unless($row);
      
      return $row;
    }
    else 
    {
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
    $this->breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $this->breadcrumb_tree->add(__('Tabletool'));
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->generator->getTableConfig()->label);
    $this->breadcrumb_tree->add(__('Result list', null, 'common'), 'ullTableTool/list?table=' . $this->table_name);
  }
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $this->breadcrumb_tree = new breadcrumbTree();
    $this->breadcrumb_tree->setEditFlag(true);
    $this->breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
    $this->breadcrumb_tree->add(__('Tabletool'));
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->table_name);
    if ($this->id) 
    {
      $this->breadcrumb_tree->add(
        __('Result list', null, 'common')
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