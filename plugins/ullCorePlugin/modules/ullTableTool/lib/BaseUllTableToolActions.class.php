<?php

/**
 * ullTableTool actions.
 *
 * @package    ull_at
 * @subpackage ullTabletool
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseUllTableToolActions extends ullsfActions
{
  
  private
    $class_name   = '',
    $field_types  = array(),
    $columns      = null;
  
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {    
    $this->forward($this->getModuleName(), 'list');
  }

  
  public function executeList($request) 
  {
    $this->checkAccess('Masteradmins');
    
    $this->getTablefromRequest();
    
//    var_dump($request->getParameterHolder()->getAll());
//    die;

    if ($request->isMethod('post'))
    {
      //TODO: req_pass redirect
    }
    
    $rows = $this->getFilterFromRequest();    
    
    $this->table_tool = ($rows) ? new ullTableTool($rows) : null;
    
    $refererHandler = new refererHandler();
    $refererHandler->delete('edit');
    
    $this->breadcrumbForList();
    
    /*
    //i18n doctrine tests:
    $info = Doctrine::getTable('TableInfo')->findByDbTableName('UllUser')->getFirst();
    
//    $info->setLanguage('en');
    
    var_dump($info->Translation['en']->toArray());
    die;
    
    
    
    
    // check request paramater and get propel table info
    if (!$this->handleTable()) {
      return sfView::ERROR;
    }
    
    $refererHandler = new refererHandler();
    $refererHandler->delete('edit');
    
    $this->breadcrumb_tree = new breadcrumbTree();
    $this->breadcrumb_tree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumb_tree->add('ullTableTool');
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->table_name);
    $this->breadcrumb_tree->addFinal(__('List', null, 'common'));
    
    
//    redirect search to build get url
    if (  $this->getRequest()->getMethod() == sfRequest::POST
          and $this->hasRequestParameter('search')) 
    {
      
      $return = $this->getRequest()->getUri().'/table/'.$this->table_name;
      $return .= '/search/'.$this->getRequestParameter('search');
      return $this->redirect($return);
    }
    
    
    $this->ull_form = new ullFormTableTool();
    
    // since this is the list action, we only want 'read' formating
    $this->ull_form->setAccessDefault('r');
    
    $this->ull_form->setContainerName($this->table_name);
    
    $this->ull_form->buildFieldsInfo();

    
    $c = new Criteria();
    
    // add search parameter
    if ($this->search = $this->getRequestParameter('search')) {
      
      $c->add(
        constant($this->table_class . 'Peer::' . strtoupper($this->table_info_search_fields))
        , $this->search
      );
      
//      $search_words_arr = explode(' ', $search);
//      foreach($search_words_arr as $key => $search) {
//        $search_words_arr[$key] = '%'.$search.'%';
//      }
//      
//      $search_word_first = array_shift($search_words_arr);
//      
//      // use propel criterions to build a vaild "OR" query
//      // the first word uses getNewCriterion
//      $cton_subject = $c->getNewCriterion(UllWikiPeer::SUBJECT, $search_word_first, Criteria::LIKE);
//      if ($fulltext) {
//        $cton_body = $c->getNewCriterion(UllWikiPeer::BODY, $search_word_first, Criteria::LIKE);
//      }
//      
//      //all subsequent words have to use addAnd
//      foreach($search_words_arr as $key => $search) {
//        $cton_subject->addAnd($c->getNewCriterion(UllWikiPeer::SUBJECT, $search, Criteria::LIKE));
//        if ($fulltext) {
//          $cton_body->addAnd($c->getNewCriterion(UllWikiPeer::BODY, $search, Criteria::LIKE));
//        }
//      }
//
//      if ($fulltext) {
//        $cton_subject->addOr($cton_body);
//      }
//      $c->add($cton_subject);

    } 

    
    // order ...
    if ($this->table_info_sort_fields) {
      $sort_fields = explode(',', $this->table_info_sort_fields);
      foreach ($sort_fields as $sort_field) {
        $c->addAscendingOrderByColumn(constant(
          $this->table_class . 'Peer::' . strtoupper($sort_field))
        ); 
      }
    }
    
    
    $this->ull_table_pager = new sfPropelPager($this->table_class, 25);
    $this->ull_table_pager->setCriteria($c);
    $this->ull_table_pager->setPage($this->getRequestParameter('page', 1));
    $this->ull_table_pager->init();    
    
    
//    $rows = call_user_func($this->table_class . 'Peer::doSelect', $c);
    

    // check for column info definitions for the current table
    $c = new Criteria();
    $c->add(UllColumnInfoPeer::DB_TABLE_NAME, $this->table_name);
    if (UllColumnInfoPeer::doCount($c)) {
      $this->column_info_available = true;
    }
    
    
    
    // loop through rows
       
//    foreach ($rows as $row) {
    foreach ($this->ull_table_pager->getResults() as $row) {
      
      $this->ull_form->setValueObject($row);
      $this->ull_form->retrieveFieldsData();        
  
      
    } // end of rows loop
    
//    ullCoreTools::printR($this->ull_form);
//    exit();

    // handle request params
    
//    ullCoreTools::printR($this->request_params);

    */

  }

  
  
  public function executeShow() 
  {
    $this->forward('ullTableTool', 'edit');
  }

  
  
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
  
  public function executeEdit($request)
  {
    // TODO: put access check in a protected function to allow custom override 
    $this->checkAccess('Masteradmins');
    
    $this->refererHandler = new refererHandler();

    $this->getTablefromRequest();
    
    $row = $this->getRowFromRequestOrCreate();
    
    $this->table_tool = new ullTableTool($row, 'w');
    
    if ($request->isMethod('post'))
    {
      if ($this->table_tool->getForm()->bindAndSave($request->getParameter('fields')))
      {
        $this->redirect($this->refererHandler->getRefererAndDelete('edit'));
      }
    }
    else
    {
      $this->refererHandler->initialize('edit');
      
    }
    $this->breadcrumbForEdit();
    
  }    

  
  public function executeDelete()
  { 
    // check access
    $this->checkAccess('MasterAdmins');
    
    $this->getTablefromRequest();
    
    $row = $this->getRowFromRequest();   
    $row->delete();
    
    $refererHandler = new refererHandler();
    
    if (!$referer = $refererHandler->getRefererAndDelete('edit')) {
        $referer = $this->getUser()->getAttribute('referer');
    }   
    
    $this->redirect($referer);
  }  
  
  
  
  
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
    
    // TODO: handle....
    /*
    // load table_info
    $c = new Criteria();

    $c->add(UllTableInfoPeer::DB_TABLE_NAME, $this->table_name);
    $this->table_info = UllTableInfoPeer::doSelectOne($c);

    if ($this->table_info) {
      $this->table_info_search_fields = $this->table_info->getSearchFields();
      $this->table_info_sort_fields   = $this->table_info->getSortFields();
    }
    */
    
//    ullCoreTools::printR($this->table_info);
   

//    ullCoreTools::printR($this->map_builder);
//    exit();

  }
  
  protected function getFilterFromRequest()
  {
    $this->filter_form = new ullTableToolFilterForm;
    $this->filter_form->bind($this->getRequestParameter('filter'));
    
    $q = new Doctrine_Query;
    $q->from($this->table_name . ' x');
    
    if ($search = $this->filter_form->getValue('search'))
    {
      $q->where('x.id = ?', $search);
    }
    
    $rows = $q->execute();
    return ($rows->count()) ? $rows : null;
  }

  protected function getRowFromRequest()
  {

    $this->forward404Unless($this->getRequestParameter('id') > 0, 'ID is mandatory');
    
    return $this->getRowFromRequestOrCreate();
    
  }
  
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
//      $this->id = 0;
      return new $this->table_name;
    }
  }  
       
  protected function breadcrumbForList()
  {
    $this->breadcrumb_tree = new breadcrumbTree();
    $this->breadcrumb_tree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumb_tree->add('ullTableTool');
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->tableName);
    $this->breadcrumb_tree->addFinal(__('List', null, 'common'));
  }
  
  protected function breadcrumbForEdit()
  {
    $this->breadcrumb_tree = new breadcrumbTree();
    $this->breadcrumb_tree->setEditFlag(true);
    $this->breadcrumb_tree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumb_tree->add('ullTableTool');
    $this->breadcrumb_tree->add(__('Table') . ' ' . $this->table_name);
    if ($this->id) {
        $this->breadcrumb_tree->add(
          __('List', null, 'common')
          , $this->refererHandler->getReferer()
        );
      $this->breadcrumb_tree->addFinal(__('Edit', null, 'common'));
    } else {
      $this->breadcrumb_tree->addFinal(__('Create', null, 'common'));
    }
  }
  
  
  
}
