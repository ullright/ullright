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
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/list?table=' . $this->getRequestParameter('table');
    $this->getUriMemory()->setDefault($defaultUri);  
  }
  
  
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {    
    $this->redirect('ullAdmin/index');
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
    
    $this->getUriMemory()->setUri();
    
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

    $this->getTablefromRequest();

    $this->generator = new ullTableToolGenerator($this->table_name, 'w');
    $row = $this->getRowFromRequestOrCreate();
    $this->generator->buildForm($row);
    
    if ($this->generator->isVersionable())
    {
      $this->generator->buildHistoryGenerators();
    }
    
    $this->breadcrumbForEdit();

    if ($request->isMethod('post'))
    {
      if ($this->generator->getForm()->bindAndSave(array_merge($request->getParameter('fields'), array('id' => $row->id))))
      {
        $this->redirect($this->getUriMemory()->getAndDelete('list'));
      }
    }
  }


  /**
   * Execute delete action
   *
   */
  public function executeDelete()
  { 
    $this->checkAccess('MasterAdmins');
    
    $this->getTablefromRequest();
    
    $this->generator = new ullTableToolGenerator($this->table_name);
    
    $editConfig = TableToolEditConfig::loadClass($this->generator->getModelName());
    if ($editConfig != NULL)
    {
      $this->redirectToNoAccessUnless($editConfig->allowDelete());
    }
    
    $row = $this->getRowFromRequest();   
    $row->delete();
    
    $this->redirect($this->getUriMemory()->getAndDelete('list'));
  }  
  
  
  /**
   * Delete a scheduled future version
   * 
   * @param sfRequest $request
   * @return none
   */
  public function executeDeleteFutureVersion(sfRequest $request)
  {
    $this->checkAccess('MasterAdmins');

    $this->getTablefromRequest();
     
    $this->generator = new ullTableToolGenerator($this->table_name);

    $row = $this->getRowFromRequest();

    $this->forward404Unless($this->hasRequestParameter('version'), 'Please specify a future version to delete');
     
    $row->getAuditLog()->deleteFutureVersion($row, $request->getParameter('version'));
     
    $this->redirect('ullTableTool/edit?table=' . $this->table_name . '&id=' . $this->getRequestParameter('id'));
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
    
    $this->ull_filter = new ullFilter();
    
    $q = new Doctrine_Query;
    $q->from($this->table_name . ' x');
    
    if ($search = $this->filter_form->getValue('search'))
    {      
      $cols = $this->generator->getTableConfig()->getSearchColumnsAsArray();
      $columnsConfig = $this->generator->getColumnsConfig();
      
      foreach ($cols as $key => $col)
      {
        if ($columnsConfig[$col]->getTranslated() == true)
        {
          $cols[$key] = 'Translation.' . $col;
        }
      }
      ullCoreTools::doctrineSearch($q, $search, $cols);
    }

    if ($query = $this->getRequestParameter('query'))
    {
      switch($query)
      {
        case('custom'):
          //add ullSearch to query
          $ullSearch = $this->getUser()->getAttribute('user_ullSearch', null);
          if ($ullSearch != null)
          {
            $ullSearch->modifyQuery($q, 'x');
             
            $this->ull_filter->add(
              'query', __('Query', null, 'common') . ': ' . __('Custom', null, 'common')
            );
          }
          break;
      }
    }
    
    if (!$defaultOrder = $this->generator->getTableConfig()->getSortColumns())
    {
      $defaultOrder = 'id';
    }
    
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
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->generator->getTableConfig()->getName());
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
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->generator->getTableConfig()->getName());
    $this->breadcrumb_tree->add(__('Result list', null, 'common'), $this->getUriMemory()->get('list'));    
    if ($this->id) 
    {
      $this->breadcrumb_tree->add(__('Edit', null, 'common'));
    }
    else
    {
      $this->breadcrumb_tree->add(__('Create', null, 'common'));
    }
  }
  
}