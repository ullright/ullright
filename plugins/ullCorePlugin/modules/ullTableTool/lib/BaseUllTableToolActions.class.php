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

  
  public function executeList() 
  {
    $this->checkAccess('Masteradmins');
    
    $refererHandler = new refererHandler();
    $refererHandler->delete('edit');
    
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumbTree->add('ullTableTool');
    $this->breadcrumbTree->add(__('Table') . ' ' . $this->tableName);
    $this->breadcrumbTree->addFinal(__('List', null, 'common'));
    
    if (!$this->getTablefromRequest()) {
      return sfView::ERROR;
    }
//    $rows = Doctrine::getTable($this->tableName)->findAll();
    $q = new Doctrine_Query;
    // TODO: try to minimize queries -> doesn't work this way:
//    $q->from($this->tableName . ' x, x.Creator, x.Updator');
    $q->from($this->tableName . ' x');
    $rows = $q->execute();
    
//    var_dump($rows->);
//    die;
//    
    $this->tableTool = new ullTableTool($rows);
    
    
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
    
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumbTree->add('ullTableTool');
    $this->breadcrumbTree->add(__('Table') . ' ' . $this->table_name);
    $this->breadcrumbTree->addFinal(__('List', null, 'common'));
    
    
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

  
  
//                           __
//                   _.--""  |
//    .----.     _.-'   |/\| |.--.
//    |jrei|__.-'   _________|  |_)  _______________  
//    |  .-""-.""""" ___,    `----'"))   __   .-""-.""""--._  
//    '-' ,--. `    |tic|   .---.       |:.| ' ,--. `      _`.
//     ( (    ) ) __|tac|__ \\|// _..--  \/ ( (    ) )--._".-.
//      . `--' ;\__________________..--------. `--' ;--------'
//       `-..-'                               `-..-'
  
  public function executeEdit()
  {
    
//    var_dump($_REQUEST);
//    die;
  
    // TODO: put access check in a protected function to allow custom override 
    $this->checkAccess('Masteradmins');
    
    $this->refererHandler = new refererHandler();

    $this->getTablefromRequest();
    
    $row = $this->getRowFromRequestOrCreate();
    
    $this->tableTool = new ullTableTool($row, 'w');
    
    if ($this->getRequest()->isMethod('post'))
    {
      if ($this->tableTool->getForm()->bindAndSave($this->getRequestParameter('fields')))
      {
        $this->refererHandler->getRefererAndDelete('edit');
      }
    }
  
    $this->refererHandler->initialize('edit');

    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add('ullAdmin', 'ullAdmin/index');

      

      
//    var_dump($this->tableTool);
//    die;
    
    
//    
//    $this->requestForm = new TableToolRequestForm();
//    $this->requestForm->setDefault('id', $this->id);
//    $this->requestForm->setDefault('table_name', $this->tableName);
//    
//    $this->form = new UllForm($row->getTable()->getColumns(), $row);
       
      
//    var_dump($this->form->getWidgetSchema());
//    die;
    
//    var_dump($this->form);
//    die;
    
//    $this->ull_form = new ullFormTableTool();
//    $this->ull_form->setAccessDefault('w');
//    $this->ull_form->setContainerName($this->table_name);
//    
//    $this->ull_form->buildFieldsInfo();
//    
//    $this->ull_form->setValueObject($row);
//    $this->ull_form->retrieveFieldsData();    
    
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
//    var_dump($this->getRequest()->getParameterHolder()->getAll());
//    die;
    
    $this->checkAccess('Masteradmins');

    $this->refererHandler = new refererHandler();  

    $this->requestForm = new TableToolRequestForm();
    $this->requestForm->bind(array(
        'id' => $this->getRequestParameter('id'), 
        'table' => $this->getRequestParameter('table'),
    ));
    
    if (!$this->requestForm->isValid())
    {
      throw new InvalidArgumentException('request form invalid');
    }    
    $this->tableName = $this->requestForm->getValue('table');
    $this->id        = $this->requestForm->getValue('id');
    $row             = $this->getRowById();      
    
    $this->tableTool = new ullTableTool($row, 'w');
    
    $this->tableTool->getForm()->bind($this->getRequestParameter('fields'));
    
    if ($this->tableTool->getForm()->isValid())
    {
      die('good');
    }
    else
    {
//      $this->redirect($this->refererHandler->getRefererAndDelete('edit'));
        $this->forward('ullTableTool', 'edit');
    }   
    
    
//    ullCoreTools::printR($this->getRequest()->getParameterHolder()->getAll());
//    exit();    
    
//    $this->checkAccess('Masteradmins');
//      
//    $this->requestForm = new TableToolRequestForm();
//    $this->requestForm->bind($this->getRequestParameter('table_tool'));
//    
//    if (!$this->requestForm->isValid())
//    {
//      die('request form invalid');
//    }    
//    $this->tableName = $this->requestForm->getValue('table_name');
//    $this->id        = $this->requestForm->getValue('id');
//    $row             = $this->getRowById();
//    
//    $this->form = new UllForm($row->getTable()->getColumns(), $row);
//    $this->form->bind($this->getRequestParameter('fields'));
//    
//    if ($this->form->isValid())
//    {
//      $columnInfo = $row->getTable()->getColumns();
//      
//      foreach ($columnInfo as $columnName => $column)
//      {
//        $row->$columnName = $this->form->getValue($columnName);
//      }
//      
//      $row->save();
//
//      // referer handling - redirect to the page where the edit action was called
//      $refererHandler = new refererHandler;
////      var_dump($refererHandler->getRefererAndDelete('edit'));
////      die;
//      return $this->redirect($refererHandler->getRefererAndDelete('edit'));        
//    }
//    else
//    {
//      $this->forward('ullTableTool','edit');
//    }
    
//    
//    // set culture to allow transparent access to i18n fields 
//    if (method_exists($row, 'setCulture')) { 
//      $row->setCulture(substr($this->getUser()->getCulture(), 0, 2));
//    }    
//    
//    // defaults
//    $user_id  = $this->getUser()->getAttribute('user_id');
//    $now      = date("Y-m-d H:i:s");
//
//    
//    // get field info
//    $this->ull_form = new ullFormTableTool();
//    $this->ull_form->setContainerName($this->table_name);
//    $this->ull_form->buildFieldsInfo();
//    
//    $fields_info = $this->ull_form->getFieldsInfo();
//
//    
//    // validation
//    foreach ($fields_info as $field_name => $field) {
//            
//      $request_param = $this->getRequestParameter($field_name);
//      
////      ullCoreTools::printR($field);
//    
//      // mandatory
//      if (@$field['mandatory']) {
//        if (!$request_param) {
//          $this->getRequest()->setError($field_name, __('This field cannot be left blank', null, 'common'));
//        }
//      }
//    
//    }
//    
//    // redisplay to the form in case of errors    
//    if ($this->getRequest()->hasErrors()) {
//      $this->forward('ullTableTool', 'edit');
//    }
//    
////    ullCoreTools::printR($this->getRequest()->getErrors());
////    exit();
//    
//    foreach ($fields_info as $field_name => $field) {
////      $column_name = strtolower($column->getColumnName());
//      
//      $method_name = 'set' . sfInflector::camelize($field_name);
//      
//      // === defaults / automagical columns
//      
//      // for action create:
//      if (!$id) {        
//        if (strstr($field_name, 'creator_user_id')) {
//          $row->$method_name($user_id);
//        }
//        
//        if (strstr($field_name, 'created_at')) {
//          $row->$method_name($now);
//        }                
//      }
//      
//      // for all actions:
//      if (strstr($field_name, 'updator_user_id')) {
//        $row->$method_name($user_id);
//      }  
//
//      if (strstr($field_name, 'updated_at')) {
//        $row->$method_name($now);
//      }
//        
//      // only save fields, that where submitted via request parameter.
//      //  otherwise we would set all disabled fields to null
//      if ($this->hasRequestParameter($field_name)) {
//        
//        $value = $this->getRequestParameter($field_name);
//        
//        // special handling per field_type
//        if ($field['field_type'] == 'password') {
//          if ($value) {
//            $value = md5($value);
//          }
//        }         
//        
//        $row->$method_name($value);
////        echo "<br /> $column_name ($method_name): $value";
//      }
//      
//      
//    } 
//
//    $row->save();
////    exit();
//
////    return $this->redirect('ullTableTool/list?table=' . $this->table_name);
//    
//    */
//    // referer handling - redirect to the page where the edit action was called
//    $refererHandler = new refererHandler();
//      
//    return $this->redirect($refererHandler->getRefererAndDelete('edit'));
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
  
  
  
  
  protected function getTablefromRequest() 
  {
    if (!$this->hasRequestParameter('table')) {
      throw new UnexpectedValueException('Please specify a database table');
    }
    
    $this->tableName = $this->getRequestParameter('table');
    
    if (!class_exists($this->tableName)) {
      throw new UnexpectedValueException('Database table not found: ' . $this->tableName);
    }      

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

  protected function getRowFromRequestOrCreate()
  {
    if ($this->hasRequestParameter('id')) 
    {
      $this->id = $this->getRequestParameter('id');
      $row = Doctrine::getTable($this->tableName)->find($this->id);
      $this->forward404Unless($row);
      return $row;
    }
      else 
    {
      return new $this->tableName;
    }
  }
       
  protected function breadcrumbEdit()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add('ullAdmin', 'ullAdmin/index');
    $this->breadcrumbTree->add('ullTableTool');
    $this->breadcrumbTree->add(__('Table') . ' ' . $this->table_name);
    if ($this->id) {
        $this->breadcrumbTree->add(
          __('List', null, 'common')
          , $this->refererHandler->getReferer()
        );
      $this->breadcrumbTree->addFinal(__('Edit', null, 'common'));
    } else {
      $this->breadcrumbTree->addFinal(__('Create', null, 'common'));
    }
  }
  
  
  
}
