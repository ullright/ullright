<?php

/**
 * Base class for all ullgenerator based actions.
 *
 * @package    ullright
 * @subpackage ullCore
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
abstract class BaseUllGeneratorActions extends ullsfActions
{
  
  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    $this->list_base_uri    = $this->getModuleName() . '/list';
    $this->create_base_uri  = $this->getModuleName() . '/create';
    $this->edit_base_uri    = $this->getModuleName() . '/edit';
    $this->delete_base_uri  = $this->getModuleName() . '/delete';
    $this->delete_future_version_base_uri = $this->getModuleName() . '/deleteFutureVersion';
    
    $this->getUriMemory()->setDefault($this->list_base_uri);
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
    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }
    
    //temp test for relation project
    if ($this->columns = $request->getParameter('columns'))
    {
      $this->columns = explode('|', $this->columns);
    }
    else
    {
      $this->columns = array();
    }
    
    $this->generator = $this->getListGenerator();
    
    $rows = $this->getFilterFromRequest();
    
    $this->generator->buildForm($rows);
    
    $this->setVar('generator', $this->generator, true);
    
    $this->getUriMemory()->setUri();
    
    $this->breadcrumbForList();
  }

  
  /**
   * Template method to construct and configure an ullGenerator
   * 
   * @return ullGenerator
   */
  protected function getListGenerator()
  {
    
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
   * Executes create action
   *
   */
  public function executeCreate(sfRequest $request) 
  {
    $this->forward($this->getModuleName(), 'edit');
  }  
  
  
  /**
   * Executes edit action
   *
   * @param sfWebRequest $request
   */
  public function executeEdit(sfRequest $request)
  {
    $this->generator = $this->getEditGenerator();
    
    $row = $this->getRowFromRequestOrCreate();
    
    //should this be in BaseUllUserActions instead?
    if (get_class($row) == 'UllUser')
    {
      //let's override some accessors since we are editing
      $row->mapValue('overridePhotoAccessor', true);
      $row->mapValue('overridePhoneExtensionAccessor', true);
    }
    
    $this->generator->buildForm($row);
    
    $this->setVar('generator', $this->generator, true);    
    
    $this->breadcrumbForEdit();

    if ($request->isMethod('post'))
    {
      if ($this->generator->getForm()->bindAndSave(array_merge($request->getParameter('fields'), array('id' => $row->id))))
      {
        // save only
        if ($request->getParameter('action_slug') == 'save_only') 
        {
          $this->redirect(ullCoreTools::appendParamsToUri(
            $this->edit_base_uri, 
            'id=' . $this->generator->getForm()->getObject()->id
          ));
        }
        elseif ($request->getParameter('action_slug') == 'save_new') 
        {
          $this->redirect($this->create_base_uri);
        }
                
        $this->redirect($this->getUriMemory()->getAndDelete('list'));
      }
    }
    
    $this->setVar('form_uri', $this->getEditFormUri(), true);
  }  
  
  
  /**
   * Template method to construct and configure an ullGenerator
   * 
   * @return ullGenerator
   */
   protected function getEditGenerator()
   {
     
   }

  
  /**
   * Get the edit form uri
   * 
   * @return string
   */
  protected function getEditFormUri()
  {
    $uri = $this->edit_base_uri;
    
    if ($this->generator->getRow()->exists())
    {
     $uri = ullCoreTools::appendParamsToUri($uri, $this->generator->getIdentifierUrlParams(0));
    }
    
    return $uri;
  }

  
  /**
   * Execute delete action
   *
   */
  public function executeDelete(sfRequest $request)
  { 
    $this->generator = $this->getDeleteGenerator();
    
    $this->redirectToNoAccessUnless($this->generator->getAllowDelete());
    
    $row = $this->getRowFromRequest();   
    $row->delete();
    
    $this->redirect($this->getUriMemory()->getAndDelete('list'));
  }  

  
  /**
   * Template method to construct and configure an ullGenerator
   * 
   * @return ullGenerator
   */
  protected function getDeleteGenerator()
  {
    
  }    

  
  /**
   * Delete a scheduled future version
   * 
   * @param sfRequest $request
   * @return none
   */
  public function executeDeleteFutureVersion(sfRequest $request)
  {
    $this->generator = $this->getDeleteGenerator();

    $row = $this->getRowFromRequest();

    $this->forward404Unless($this->hasRequestParameter('version'), 'Please specify a future version to delete');
     
    $row->getAuditLog()->deleteFutureVersion($row, $request->getParameter('version'));
     
    $this->redirect(ullCoreTools::appendParamsToUri($this->edit_base_uri, 'id=' . $this->getRequestParameter('id')));
  }
  
  
  /**
   * Parses filter request params
   * and returns records accordingly
   *
   * @return Doctrine_Collection
   */
  protected function getFilterFromRequest()
  { 
    $this->q = $this->generator->createQuery();
    
    $filterClassName = $this->getUllFilterClassName();

    $this->filter_form = new $filterClassName;
    $this->filter_form->bind($this->getRequestParameter('filter'));
    
    $ull_filter = new ullFilter();
    
    if ($search = $this->filter_form->getValue('search'))
    {      
      $this->q->addSearch($search, $this->getSearchColumnsForFilter());
      // TODO: add filter for search. see #640
//      $ull_filter->add('filter[search]', __('Search', null, 'common') . ': ' . $search);
    }
    
    if ($query = $this->getRequestParameter('query'))
    {
      switch($query)
      {
        case('custom'):
          //add ullSearch to query
          $ullSearch = $this->getUser()->getAttribute(get_class($this->generator) . '_ullSearch');
          if ($ullSearch != null)
          {
            $ullSearch->modifyQuery($this->q->getDoctrineQuery(), 'x');
             
            $ull_filter->add(
              'query', __('Query', null, 'common') . ': ' . __('Custom', null, 'common')
            );
          }
          break;
      }
    }
    
    if (isset($this->named_queries))
    {
      $this->named_queries->handleFilter($this->q, $ull_filter, $this->getRequest());
    }

    $this->setVar('ull_filter', $ull_filter, true);

    // ORDER
    if ($this->hasRequestParameter('order'))
    {
      $order = ullGeneratorTools::convertOrderByFromUriToQuery($this->getRequestParameter('order'));
    }
    else
    {
      if (!$order = $this->generator->getTableConfig()->getOrderBy())
      {
        $order = 'id';
      }
      
    }
    
    $this->q->addOrderBy($order);
    $this->setVar('order', $order, true);
    
    $this->modifyQueryForFilter();
    
    //printQuery($this->q->getDoctrineQuery()->getSql());
    //var_dump($this->q->getDoctrineQuery()->getParams());

    $this->pager = new Doctrine_Pager(
      $this->q->getDoctrineQuery(), 
      $this->getRequestParameter('page', 1),
      sfConfig::get('app_pager_max_per_page')
    );
    $rows = $this->pager->execute();
    
    $modelName = $this->generator->getModelName();
    
    return ($rows->count()) ? $rows : new $modelName;
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
   * Configure the ullFilter class name
   * 
   * @return string
   */
  abstract public function getUllFilterClassName();

  
  /** 
   * Get array of columns for the quicksearch
   * 
   * @return array
   */
  protected function getSearchColumnsForFilter()
  {
    return $this->generator->getTableConfig()->getSearchColumnsAsArray();
  }
  
  
  /**
   * Apply custom modifications to the query
   *  
   * @return none
   */
  protected function modifyQueryForFilter()
  {
    
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
    $q->from($this->generator->getModelName() . ' x');

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
      $modelName = $this->generator->getModelName();
      return new $modelName;
    }
    
    
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
  
  
  /**
   * Shortcut method to set a template of ullTableTool
   * @param string $name      name of the template. Examples: "list", "edit", ...
   */
  protected function setTableToolTemplate($name)
  {
    $this->setTemplate(sfConfig::get('sf_plugins_dir') . '/ullCorePlugin/modules/ullTableTool/templates/' . $name);    
  }  
  
}