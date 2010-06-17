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
  protected 
    $edit_action_buttons_store = array()
  ;
  
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
    $this->show_base_uri    = $this->getModuleName() . '/show';
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
    
    $this->docs = $this->getFilterFromRequest();
    
    $this->generator->buildForm($this->docs);
    
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
    $this->id = $row->id;
    
    //should this be in BaseUllUserActions instead?
    if (get_class($row) == 'UllUser')
    {
      //let's override some accessors since we are editing
      $row->mapValue('overridePhotoAccessor', true);
      $row->mapValue('overrideContactDataAccessor', true);
      
      if ($row->exists())
      {
        $this->generator->getColumnsConfig()->offsetGet('superior_ull_user_id')
          ->setOption('hide_choices', array($row->id));
      }
    }
    
    $this->generator->buildForm($row);
    
    $this->setVar('generator', $this->generator, true);    
    
    $this->breadcrumbForEdit();

    if ($request->isMethod('post'))
    {
//      var_dump($request->getParameterHolder()->getAll());
//      var_dump($this->getRequest()->getFiles());
//      die;
      
      if ($this->generator->getForm()->bindAndSave(
        array_merge($request->getParameter('fields'), array('id' => $row->id)), 
        $this->getRequest()->getFiles('fields')
      ))
      {
        $this->processEditActionButtons();
        
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
    
    $this->createFilterForm();
    
    $filterParams = $this->getRequest()->getParameter('filter');
    
    // What if another variable name than "generator" is used?
    if (isset($this->generator))
    {
      $filterParams = $this->generator->setFilterFormDefaults($filterParams);
    }
    
//    $this->filter_form->debug();
    
    $this->filter_form->bind($filterParams);
    
    if (!$this->filter_form->isValid())
    {
      throw new RuntimeException('Filter form validation error');
    }
    
    $this->ull_filter = new ullFilter();
  
    if (isset($filterParams['search']) && ($search = $filterParams['search']))
    {      
      $this->q->addSearch($search, $this->getSearchColumnsForFilter());
      $this->ull_filter->add('filter[search]', __('Search', null, 'common') . ': ' . $search);
    }
    
    if (isset($this->generator))
    {
      $this->generator->addFilter($this->q, $this->ull_filter);
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
             
            $this->ull_filter->add(
              'query', __('Query', null, 'common') . ': ' . __('Custom', null, 'common')
            );
          }
          break;
      }
    }
    
      //namedQueries may not have the correct filter, but
    //namedQueriesCustom should.
    if (isset($this->named_queries)) 
    {
      try
      {
        $this->named_queries->handleFilter($this->q, $this->ull_filter, $this->getRequest());
      }
      catch (InvalidArgumentException $e)
      {
        if ($this->named_queries_custom)
        {
          $this->named_queries_custom->handleFilter($this->q, $this->ull_filter, $this->getRequest());
        }
      }
    }

    $this->setVar('ull_filter', $this->ull_filter, true);

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
    
    //natural ordering
    //disabled for now, because of incompatabilities with the pager
    /*
    $orderArray = ullGeneratorTools::arrayizeOrderBy($order);
    $databaseColumns = $this->generator->getDatabaseColumns();
    foreach ($orderArray as $key => $orderBy)
    {
      //this should always be true, shouldn't it?
      if (isset($databaseColumns[$orderBy['column']]))
      {
        if ($databaseColumns[$orderBy['column']]->getNaturalOrdering())
        {
          //adding '+ 0' to the column name causes MySQL to use natural sort
          //SQL's convert() function would be database agnostic, but Doctrine
          //does not support using it (see: BaseUllFlowActions)
          $orderArray[$key]['column'] = '(x.' . $orderBy['column'] . ' + 0)';
        }
      }
    }
    */
    
    //$this->q->addOrderBy($orderArray);
    $this->q->addOrderBy($order);
    $this->setVar('order', $order, true);
    
    $this->modifyQueryForFilter();
    
    //printQuery($this->q->getSqlQuery());
    //var_dump($this->q->getParams());

    $this->paging = $this->getRequestParameter('paging', 'true');
    
    $this->pager = new Doctrine_Pager(
      clone $this->q->getDoctrineQuery(), 
      $this->getRequestParameter('page', 1),
      // request params come as strings
      ($this->paging == 'false') ? 5000 : sfConfig::get('app_pager_max_per_page', 30)
    );
    
    $rows = $this->pager->execute();
    
    //printQuery($this->pager->getQuery());
    
    // Completely discard the pager's query modifications because (as of sf1.2) 
    //   it messes up queries using SUM() and group_by because it adds WHERE 
    //   clauses for specific ids which produces wrong sums 
    if ($this->paging == 'false' || $this->paging == false)
    {
      $rows = $this->q->execute();
    }
    
    $modelName = $this->generator->getModelName();
    
    return ($rows->count()) ? $rows : new $modelName;
  }   
  
  
  /**
   * Create filter form
   * 
   * @return none
   */
  protected function createFilterForm()
  {
    // legacy (manual hard coded filter form)
    if (method_exists($this, 'getUllFilterClassName') && $filterClassName = $this->getUllFilterClassName())
    {
      $this->filter_form = new $filterClassName;    
    }
    // Use generic filter mechanism
    else
    {
      $this->filter_form = $this->generator->getFilterForm();
    }
  
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

  
  /**
   * Registers an ullGeneratorEditActionButton
   * 
   * @param ullGeneratorEditActionButton $button
   * @return none
   */
  protected function registerEditActionButton(ullGeneratorEditActionButton $button)
  {
    $this->edit_action_buttons_store[] = $button;
    
    $this->setVar('edit_action_buttons', $this->edit_action_buttons_store, true);
  }
  
  
  /**
   * Executes the logic of the edit actions buttons after binding the form
   * @return none
   */
  protected function processEditActionButtons()
  {
    if (count($this->edit_action_buttons_store))
    {
      foreach($this->edit_action_buttons_store as $button)
      {
        $button->executePostFormBindAndSave();
      }
    }  
  }
  
}