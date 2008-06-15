<?php

/**
 * ullTableTool actions.
 *
 * @package    ull_at
 * @subpackage ullTabletool
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class BaseullTableToolActions extends ullsfActions
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

  
  public function executeList() {
    
    // check access
    $this->checkAccess(1);
    
    // check request paramater and get propel table info
    if (!$this->handleTable()) {
      return sfView::ERROR;
    }
    
    // referer handling
    $refererHandler = new refererHandler();
    $refererHandler->delete('edit');
    
    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add('ullAdmin', 'ullAdmin');
    $this->breadcrumbTree->add('ullTableTool');
    $this->breadcrumbTree->add(__('Table') . ' ' . $this->table_name);
    $this->breadcrumbTree->addFinal(__('List', null, 'common'));
    
    
//    redirect search to build get url
    if ($this->getRequest()->getMethod() == sfRequest::POST
      and $this->hasRequestParameter('search')
//      and $this->table_info_search_fields
    ) {
      
      $return = $this->getRequest()->getUri().'/table/'.$this->table_name;
      $return .= '/search/'.$this->getRequestParameter('search');
      
//      if ($this->hasRequestParameter('fulltext')) {
//        $return .= '/fulltext/true'; 
//      }
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

  }

  
  
  public function executeShow() {
    
//    $this->forward( $this->getModuleName(), 
//                    'edit?table=' 
//                    . $this->getRequestParameter('table')
//                    .'&id='
//                    . $this->getRequestParameter('id')
//                    );
    
    $this->forward('ullTableTool', 'edit');
                            
  }

  
  
  public function executeCreate() {
    
    $this->forward('ullTableTool', 'edit');
    
  }  

  
  
  public function executeEdit()
  {
    
    //ullCoreTools::printR($this->getRequest()->getParameterHolder()->getAll());
    //    exit(); 
        
    // check access
    $this->checkAccess(1);    
    
    // check request paramater and get propel table info
    if (!$this->handleTable()) {
      return sfView::ERROR;
    }
    
    $this->id = $this->getRequestParameter('id');
    
    // referer handling
    $this->refererHandler = new refererHandler();  
    $this->refererHandler->initialize('edit');
    
    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add('ullAdmin', 'ullAdmin');
    $this->breadcrumbTree->add('ullTableTool');
    $this->breadcrumbTree->add(__('Table') . ' ' . $this->table_name);
    if ($this->id) {
//      $this->breadcrumbTree->add(__('List', null, 'common'), 'ullTableTool/list?table=' . $this->table_name);
        $this->breadcrumbTree->add(
          __('List', null, 'common')
          , $this->refererHandler->getReferer()
        );
      $this->breadcrumbTree->addFinal(__('Edit', null, 'common'));
    } else {
      $this->breadcrumbTree->addFinal(__('Create', null, 'common'));
    }


    
    // get row (data)
    if ($this->id) {
      $row = call_user_func(
                array($this->table_class . 'Peer', 'retrieveByPk')
                , $this->id
              );
    } else {
      $row = new $this->table_class();
    }
    
    $this->forward404Unless($row);
                
 
    $this->ull_form = new ullFormTableTool();
    $this->ull_form->setAccessDefault('w');
    $this->ull_form->setContainerName($this->table_name);
    
    $this->ull_form->buildFieldsInfo();
    
    $this->ull_form->setValueObject($row);
    $this->ull_form->retrieveFieldsData();    
    
//    ullCoreTools::printR($this->ull_form);
//    exit();
    
  }

// 
//  public function validateUpdate() {
//    
//
//    
//  }
  

//  public function handleErrorUpdate() {
//    
//    $this->forward('ullTableTool', 'create');
//    
//  }
  
  
  

  public function executeUpdate()
  {
    
//    ullCoreTools::printR($this->getRequest()->getParameterHolder()->getAll());
//    exit();    
    
    $this->checkAccess(1);
    
    // check request paramater and get propel table info
    if (!$this->handleTable()) {
      return sfView::ERROR;
    }
    
    
//    ullCoreTools::printR($this->getRequest()->getParameterHolder()->getAll());
//    exit();
//    
    // create new object for create action
    if (!$id = $this->getRequestParameter('id')) {
      $row = new $this->table_class();
    
    } else {
      $row = call_user_func(array($this->table_class . 'Peer', 'retrieveByPk'), $id);   
        
    }
    
    $this->forward404Unless($row);
    
    // set culture to allow transparent access to i18n fields 
    if (method_exists($row, 'setCulture')) { 
      $row->setCulture(substr($this->getUser()->getCulture(), 0, 2));
    }    
    
    // defaults
    $user_id  = $this->getUser()->getAttribute('user_id');
    $now      = date("Y-m-d H:i:s");

    
    // get field info
    $this->ull_form = new ullFormTableTool();
    $this->ull_form->setContainerName($this->table_name);
    $this->ull_form->buildFieldsInfo();
    
    $fields_info = $this->ull_form->getFieldsInfo();

    
    // validation
    foreach ($fields_info as $field_name => $field) {
            
      $request_param = $this->getRequestParameter($field_name);
      
//      ullCoreTools::printR($field);
    
      // mandatory
      if (@$field['mandatory']) {
        if (!$request_param) {
          $this->getRequest()->setError($field_name, __('This field cannot be left blank', null, 'common'));
        }
      }
    
    }
    
    // redisplay to the form in case of errors    
    if ($this->getRequest()->hasErrors()) {
      $this->forward('ullTableTool', 'edit');
    }
    
//    ullCoreTools::printR($this->getRequest()->getErrors());
//    exit();
    
    foreach ($fields_info as $field_name => $field) {
//      $column_name = strtolower($column->getColumnName());
      
      $method_name = 'set' . sfInflector::camelize($field_name);
      
      // === defaults / automagical columns
      
      // for action create:
      if (!$id) {        
        if (strstr($field_name, 'creator_user_id')) {
          $row->$method_name($user_id);
        }
        
        if (strstr($field_name, 'created_at')) {
          $row->$method_name($now);
        }                
      }
      
      // for all actions:
      if (strstr($field_name, 'updator_user_id')) {
        $row->$method_name($user_id);
      }  

      if (strstr($field_name, 'updated_at')) {
        $row->$method_name($now);
      }
        
      // only save fields, that where submitted via request parameter.
      //  otherwise we would set all disabled fields to null
      if ($this->hasRequestParameter($field_name)) {
        
        $value = $this->getRequestParameter($field_name);
        
        // special handling per field_type
        if ($field['field_type'] == 'password') {
          if ($value) {
            $value = md5($value);
          }
        }         
        
        $row->$method_name($value);
//        echo "<br /> $column_name ($method_name): $value";
      }
      
      
    } 

    $row->save();
//    exit();

//    return $this->redirect('ullTableTool/list?table=' . $this->table_name);
    
    // referer handling - redirect to the page where the edit action was called
    $refererHandler = new refererHandler();
      
    return $this->redirect($refererHandler->getRefererAndDelete('edit'));
  }  

  
  
  public function executeDelete()
  { 
    // check access
    $this->checkAccess(1);
    
    // check request paramater and get propel table info
    if (!$this->handleTable()) {
      return sfView::ERROR;
    }
    
    $row = call_user_func(
              array($this->table_class . 'Peer', 'retrieveByPk')
              , $this->getRequestParameter('id')
            );     
    
    $this->forward404Unless($row);
    
    // set culture to allow transparent access to i18n fields 
    if (method_exists($row, 'setCulture')) { 
      $row->setCulture(substr($this->getUser()->getCulture(), 0, 2));
    }    
    
    $row->delete();

    $refererHandler = new refererHandler();
    
    if (!$referer_edit = $refererHandler->getRefererAndDelete('edit')) {
//      $referer_edit = 'ullTableTool/list?table=' . $this->table_name;
        $referer_edit = $this->getUser()->getAttribute('referer');
    }   
    
    return $this->redirect($referer_edit);
    
    
  }  
  
  
  
  
  public function handleTable() {
        
    // === get table request parameter
    if (!$this->hasRequestParameter('table')) {
      $this->error = __('Please specify a database table') . '!';
      return false;
    }
    
    $this->table_name = $this->getRequestParameter('table');

    $this->table_class = sfInflector::classify($this->table_name);
    
    if (!class_exists($this->table_class)) {
      $this->error = __('Database table not found') . '!';
      return false;
    }      

    
    // load table_info
    $c = new Criteria();

    $c->add(UllTableInfoPeer::DB_TABLE_NAME, $this->table_name);
    $this->table_info = UllTableInfoPeer::doSelectOne($c);

    if ($this->table_info) {
      $this->table_info_search_fields = $this->table_info->getSearchFields();
      $this->table_info_sort_fields   = $this->table_info->getSortFields();
    }
    
//    ullCoreTools::printR($this->table_info);
   

//    ullCoreTools::printR($this->map_builder);
//    exit();

    return true;
    
  }

  
  
  
  
  
}
