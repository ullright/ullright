<?php

/**
 * ullFlow actions.
 *
 * @package    ull_at
 * @subpackage ullFlow
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseullFlowActions extends ullsfActions
{

  public function ullpreExecute()
  { 
    $path =  '/ullFlowTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }
  
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->checkAccess('LoggedIn');
    
    $this->form = new ullFlowFilterForm;
    
    $this->breadcrumbForIndex();
    
    if ($this->app_slug = $this->getRequestParameter('app'))  
    {
      $this->app = UllFlowAppTable::findBySlug($this->app_slug);
      $this->breadcrumbTree->add($this->app->label);
    } 
    else 
    {
      $this->apps = Doctrine::getTable('UllFlowApp')->findAll();
    }
  }

  
  public function executeList($request) {
    
    $this->checkAccess('LoggedIn');
    
    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }

    $this->getAppfromRequest();
    $this->generator = new ullFlowGenerator($this->app);
    
    $docs = $this->getFilterFromRequest();
    $this->generator->buildForm($docs);
    
    $refererHandler = new refererHandler();
    $refererHandler->delete('edit');
    
    $this->breadcrumbForList();    
    
  }

  
  
//        .------------------------------.
//        |            Basket            |
//        +----+-----------------+-------+
//        | Id | Name            | Price |
//        +----+-----------------+-------+
//        |  1 | Dummy product 1 |  24.4 |
//        |  2 | Dummy product 2 |  21.2 |
//        |  3 | Dummy product 3 |  12.3 |
//        +----+-----------------+-------+
//        |    | Total           |  57.9 |
//        '----+-----------------+-------'
  
  
  
  public function executeTabular() {
    
    // check access
    $this->checkLoggedIn();

    $this->ull_reqpass_redirect();

    // referer handling
    $refererHandler = new refererHandler();
    $refererHandler->delete('edit');
    
    $this->app_slug = $this->getRequestParameter('app');
    if ($this->app_slug) {
      $this->app      = UllFlowAppPeer::retrieveBySlug($this->app_slug);
      $this->forward404Unless($this->app);
    }    
    
    
    // breadcrumb
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflows'), 'ullFlow/index');
    if ($this->app_slug) {
      $this->breadcrumbTree->add(ullCoreTools::getI18nField($this->app, 'caption'));
    }
//    $this->breadcrumbTree->addFinal(__('List', null, 'common'));
    if ($this->app_slug) {
      $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list?app=' . $this->app_slug);
    } else {
      $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list');
    }


    
    $user_id  = $this->getUser()->getAttribute('user_id');
    
    
    // == initialize ull_filter
    $this->ull_filter = new ullFilter();
    
    // === load entries
    $c = new Criteria();
    
    
    // == tags
    // the tag param has to be before the first criteria because it creates the criteria    
    if ($tags = $this->getRequestParameter('tags')) {
      $c = TagPeer::getTaggedWithCriteria('UllFlowDoc', $tags, $c);
      $this->ull_filter->add(
        'tags'
        , __('Tags') . ': ' . $tags
      );      
    }
    
    
    // === access check
    $c->addAlias('access', UllUserGroupPeer::TABLE_NAME);
    $c->addJoin(
      UllFlowDocPeer::READ_ULL_GROUP_ID
      , UllUserGroupPeer::alias('access', UllUserGroupPeer::ULL_GROUP_ID)
      , Criteria::LEFT_JOIN
    );
    $c->add(UllUserGroupPeer::alias('access', UllUserGroupPeer::ULL_USER_ID), $user_id);
//    $c->add(UllUserGroupPeer::ULL_USER_ID, false, Criteria::ISNULL);
//    $cton1 = $c->getNewCriterion(UllUserGroupPeer::ULL_USER_ID, $user_id);
//    $cton2 = $c->getNewCriterion(UllUserGroupPeer::ULL_USER_ID, false, Criteria::ISNULL); // this produces an sql error! (a trailing '0' after ... IS NULL) !!!
//    $cton1->addOr($cton2);
//    $c->add($cton1);

    
    // select single app
    if ($this->app_slug) {
      $c->add(UllFlowDocPeer::ULL_FLOW_APP_ID, $this->app->getId());
      $this->ull_filter->add(
        'app'
        , __('Application') . ': ' . ullCoreTools::getI18nField($this->app, 'caption')
      );
    }
    
    // == filter by ull_flow_action
    $ull_flow_action_slug = $this->getRequestParameter('flow_action');
    
    // no requested action -> list default actions
    if (!$ull_flow_action_slug) {
      
      // get default actions for list
      $ca = new Criteria();
      $ca->add(UllFlowActionPeer::IN_RESULTLIST_BY_DEFAULT, true);
      $default_actions = UllFlowActionPeer::doSelect($ca);
      
      $default_action_ids = array();
      foreach ($default_actions as $default_action) {
        $default_action_ids[] = $default_action->getId();
      } 
      $c->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $default_action_ids, Criteria::IN);

    // list items with requested action if action <> 'all'
    } elseif ($ull_flow_action_slug <> 'all') {
      
      $ull_flow_action_id = UllFlowActionPeer::getActionIdBySlug($ull_flow_action_slug);
      if ($ull_flow_action_id) {
        $c->add(UllFlowDocPeer::ULL_FLOW_ACTION_ID, $ull_flow_action_id);
      }
      
    }
//    $this->ull_filter->add(__('Status') . ': ' . $ull_flow_action_slug);
    
    
    // == search:
    $this->ull_flow_search = $this->getRequestParameter('flow_search');
    
    if ($this->ull_flow_search) {
      
      $cton_id = $c->getNewCriterion(UllFlowDocPeer::ID, $this->ull_flow_search);
      
      $search_words_arr = explode(' ', $this->ull_flow_search);
      foreach($search_words_arr as $key => $search_word) {
        $search_words_arr[$key] = '%'.$search_word.'%';
      }
      
      $search_word_first = array_shift($search_words_arr);
      
      // use propel criterions to build a vaild "OR" query
      // the first word uses 'getNewCriterion'
      $cton_title = $c->getNewCriterion(UllFlowDocPeer::TITLE, $search_word_first, Criteria::LIKE);
      $cton_tags = $c->getNewCriterion(UllFlowDocPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH, $search_word_first, Criteria::LIKE);
//      if ($fulltext) {
//        $cton_body = $c->getNewCriterion(UllWikiPeer::BODY, $search_word_first, Criteria::LIKE);
//      }
      
      //all subsequent words use 'addAnd'
      foreach($search_words_arr as $search_word) {
        $cton_title->addAnd($c->getNewCriterion(UllFlowDocPeer::TITLE, $search_word, Criteria::LIKE));
        $cton_tags->addAnd($c->getNewCriterion(UllFlowDocPeer::DUPLICATE_TAGS_FOR_PROPEL_SEARCH, $search_word, Criteria::LIKE));
//        if ($fulltext) {
//          $cton_body->addAnd($c->getNewCriterion(UllWikiPeer::BODY, $search, Criteria::LIKE));
//        }
      }

//      if ($fulltext) {
//        $cton_subject->addOr($cton_body);
//      }
      $cton_title->addOr($cton_tags);
      $cton_title->addOr($cton_id);
      $c->add($cton_title);
      
      $this->ull_filter->add(
        'search'
        , __('Search', null, 'common') . ': ' . $this->ull_flow_search
      );

    }
    
    // 'named' queries
    if ($query = $this->getRequestParameter('query')) {
      switch($query) {

        case('by_me'):
          $c->add(UllFlowDocPeer::CREATOR_USER_ID, $user_id);
          $this->ull_filter->add(
            'query'
            , __('Query', null, 'common') . ': ' . __('Entries created by me')
          );
          break;
          
        case('to_me'):
//          $c->setDistinct(true) ;
//          $c->addJoin(UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID, UllUserGroupPeer::ULL_GROUP_ID, Criteria::LEFT_JOIN);
//          $cton_user = $c->getNewCriterion
//          $cton_group = $c->getNewCriterion(UllUserGroupPeer::ULL_USER_ID, $user_id);
//          $cton_user->addOr($cton_group);
//          $c->add($cton_user);
          $c->add(UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID, $user_id);
          $this->ull_filter->add(
            'query'
            , __('Query', null, 'common') . ': ' . __('Entries assigned to me')
          );
          break;
      }
    }
    
    // assigned to group
    if ($group_id = $this->getRequestParameter('group')) {
      $c->add(UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID, $group_id);
      $this->ull_filter->add(
        'group'
        , __('Assigned to group') . ': ' . UllGroupPeer::retrieveByPK($group_id)->__toString()
      );   
    }
   
    
    
    // == order
    $this->order = $this->getRequestParameter('order', 'created_at');
    
    $this->order_dir = $this->getRequestParameter('order_dir', 'desc');
    $order_func = ($this->order_dir == 'desc') ? 'addDescendingOrderByColumn' : 'addAscendingOrderByColumn';

    // order by creator: join ull_user to order by name
    if ($this->order == 'creator_user_id') {
      $c->addJoin(UllFlowDocPeer::CREATOR_USER_ID, UllUserPeer::ID);
      $c->$order_func(UllUserPeer::LAST_NAME);
      $c->$order_func(UllUserPeer::FIRST_NAME);
    } else {
      $c->$order_func(constant('UllFlowDocPeer::' . strtoupper($this->order)));
    }
    
    // add 'created_at' (desc) as default 2nd order criteria
    if ($this->order <> 'created_at') {
      $c->addDescendingOrderByColumn(UllFlowDocPeer::CREATED_AT);
    }
    

    // == pager
    $this->ull_flow_doc_pager = new sfPropelPager('UllFlowDoc', 25);
    $this->ull_flow_doc_pager->setCriteria($c);
    $this->ull_flow_doc_pager->setPage($this->getRequestParameter('page', 1));
    $this->ull_flow_doc_pager->init();
    
    // create form
    $this->ull_form = new ullFormFlowDoc();
    $this->ull_form->setAccessDefault('r');  
    $this->ull_form->setContainerName('ull_flow_doc');
    if (isset($this->app)) {      
      $this->ull_form->setApp($this->app);
    }
    
    $this->ull_form->buildFieldsInfo();
//
//    ullCoreTools::printR($this->ull_form);
//    exit();
    
    
    // loop through rows
       
    foreach ($this->ull_flow_doc_pager->getResults() as $row) {
      
//      ullCoreTools::printR($row->getAssignedTo());
      
      $this->ull_form->setValueObject($row);
      $this->ull_form->retrieveFieldsData();        
  
      
    } // end of rows loop
    
//    ullCoreTools::printR($this->ull_form);
//    exit();
    
    
    // get list of flow_actions for the action select box
    $c = new Criteria();
    if ($this->app_slug) {
      $c->addJoin(UllFlowActionPeer::ID, UllFlowStepActionPeer::ULL_FLOW_ACTION_ID, Criteria::LEFT_JOIN);
      $c->addJoin(UllFlowStepActionPeer::ULL_FLOW_STEP_ID, UllFlowStepPeer::ID, Criteria::LEFT_JOIN);
      $cton1 = $c->getNewCriterion(UllFlowStepPeer::ULL_FLOW_APP_ID, $this->app->getId());
      $cton2 = $c->getNewCriterion(UllFlowActionPeer::SLUG, 'create');
      $cton2->addOr($cton1);
      $c->add($cton2);
    } else {
      $c->add(UllFlowActionPeer::STATUS_ONLY, false); 
    }
    $c->addAscendingOrderByColumn(UllFlowActionI18nPeer::CAPTION_I18N);
    $this->flow_actions = UllFlowActionPeer::doSelectWithI18n($c, substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
    $this->flow_action = $this->getRequestParameter('flow_action');
    
  }  
  
  
  public function executeShow() {
    
    $this->forward('ullFlow', 'edit');
    
  }

  
  
  public function executeCreate() {

    $this->forward('ullFlow', 'edit');
   
  }  
  

  
//                                 .       .
//                                / `.   .' \
//                        .---.  <    > <    >  .---.
//                        |    \  \ - ~ ~ - /  /    |
//                         ~-..-~             ~-..-~
//                     \~~~\.'                    `./~~~/
//           .-~~^-.    \__/                        \__/
//         .'  O    \     /               /       \  \
//        (_____,    `._.'               |         }  \/~~~/
//         `----.          /       }     |        /    \__/
//               `-.      |       /      |       /      `. ,~~|
//                   ~-.__|      /_ - ~ ^|      /- _      `..-'   f: f:
//                        |     /        |     /     ~-.     `-. _||_||_
//                        |_____|        |_____|         ~ - . _ _ _ _ _>
  
  
  public function executeEdit($request)
  {
    $this->checkAccess('LoggedIn');

    $this->refererHandler = new refererHandler();  
    
    $this->getDocFromRequestOrCreate();
    
    $accessType = $this->doc->checkAccess();
    $this->redirectUnless($accessType, 'ullUser/noaccess');
    $this->workflowActionAccessCheck();
    
    $this->generator = new ullFlowGenerator($this->app, $accessType);
    $this->generator->buildForm($this->doc);
    
//    var_dump($this->doc->UllFlowStep->UllFlowStepActions->UllFlowAction->toArray());
//    die;
    
    if ($request->isMethod('post'))
    {
//      var_dump($request->getParameterHolder()->getAll());die;
      
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
//        var_dump($request->getParameterHolder()->getAll());
//        die;

        $this->sendMails();
        
          // manage full page widgets
          if ($fullPageWidgetName = $request->getParameter('full_page_widget')) 
          {
            $fullPageWidgetClass = 'ullFlowFullPageWidget' . sfInflector::classify($fullPageWidgetName);
            
            if (class_exists($fullPageWidgetClass)) 
            {
              $fullPageWidget = new $fullPageWidgetClass($this->doc, $request->getParameter('full_page_widget_column'));
              return $this->redirect($fullPageWidget->getInternalUri());
            }
          }        
        
        if ($request->getParameter('action_slug') == 'save_only') 
        {
          return $this->redirect('ullFlow/edit?doc=' . $this->doc->id);
        }
        
        $referer = $this->refererHandler->getRefererAndDelete();
        $referer = ($referer) ? $referer : $this->getRefererFallbackURI();
        $this->redirect($referer);
      }
    }
    
    $this->refererHandler->initialize();
    $this->breadcrumbForEdit();    

//    // get step
//    if ($this->new) {
//      $c = new Criteria();
//      $c->add(UllFlowStepPeer::ULL_FLOW_APP_ID, $this->app_id);
//      $c->add(UllFlowStepPeer::IS_START, true);
//      $step_id = UllFlowStepPeer::doSelectOne($c)->getId();       
//    } else {
//      $step_id = $this->doc->getAssignedToUllFlowStepId();
//    }
//
////    ullCoreTools::printR($step_id);
//    
//    // get all available actions
////    $this->actions = UllFlowActionPeer::doSelect(new Criteria());
//        
//    // get actions for current step
//    $c = new Criteria();
//    $c->add(UllFlowStepActionPeer::ULL_FLOW_STEP_ID, $step_id);
//    $c->addAscendingOrderByColumn(UllFlowStepActionPeer::SEQUENCE);
//    $this->step_actions = UllFlowStepActionPeer::doSelect($c);
//    
////    $this->step_actions = array();
////    
//////    foreach ($step_actions as $i => $step_action) {
////////      $step_action_id = $step_action->getId();
//////      $this->step_actions[$i]['step_action'] = $step_action;
//////      $this->step_actions[$i]['ull_flow_action'] = $step_action->getUllFlowAction(); 
//////    }
////    
////      foreach ($step_actions as $i => $step_action) {
//////      $step_action_id = $step_action->getId();
////      $this->step_actions[$i]['step_action'] = $step_action;
////      $this->step_actions[$i]['ull_flow_action'] = $step_action->getUllFlowAction(); 
////    }
//    
////    ullCoreTools::printR($this->step_actions);
////    exit();
//
//    
//    // == get memories
//    $c = new Criteria();
//    $c->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $this->doc_id);
//    $c->addAscendingOrderByColumn(UllFlowMemoryPeer::CREATED_AT);
//    $c->addAscendingOrderByColumn(UllFlowMemoryPeer::ID); // date is sometimes not exact enough
//    $this->memories = UllFlowMemoryPeer::doSelect($c);
//    
//    
//    // == workflow action access check
//
//    if ($this->new) {
//      $this->workflow_action_access = true;
//          
//    } else {
//      $this->workflow_action_access = false;
//      
//      // workflow action access is only allowed for the nextuser and nextgroup
//      if ($this->doc->getAssignedToUllUserId() == $user_id) {
//        $this->workflow_action_access = true;   
//      }
//      
//      $group_id     = $this->doc->getAssignedToUllGroupId();
//      $c_user_ids   = UllUserGroupPeer::retrieveUserIdsByGroupId($group_id);
//      foreach($c_user_ids as $c_user_id) {
//        if ($c_user_id == $user_id) {
//          $this->workflow_action_access = true;
//          break;  
//        }
//      }
//      
//    }
    
//    ullCoreTools::printR($this->step_action_ids);
    
       
//    ullCoreTools::printR($this->ull_form);
//    exit();

    
    
  }

  
//          ____  ____  ____  ____
//         /\   \/\   \/\   \/\   \
//        /  \___\ \___\ \___\ \___\
//        \  / __/_/   / /   / /   /
//         \/_/\   \__/\/___/\/___/
//           /  \___\    /  \___\
//           \  / __/_  _\  /   /
//            \/_/\   \/\ \/___/
//              /  \__/  \___\
//              \  / _\  /   /
//               \/_/\ \/___/
//                 /  \___\
//                 \  /   /
//                  \/___/  
  
  public function executeUpdate()
  {
    
    // check access
    $this->checkLoggedIn();

//    ullCoreTools::printR($this->getRequest()->getParameterHolder()->getAll());
//    exit();

    
    // == get doc data
    if ($doc_id = $this->getRequestParameter('doc')) {
      $doc = UllFlowDocPeer::retrieveByPK($doc_id);
      $new = false;
    
    } else {
      // create new doc object for create action
      $doc = new UllFlowDoc();
      $new = true;
        
    }
    $this->forward404Unless($doc); 
    
    // access check
    if ($this->checkDocAccess($doc) <> 'w' and !$new) {
        $this->error = __('Access denied.') . '!';
        return sfView::ERROR; 
    }
    
    // == referer handling
    $this->refererHandler = new refererHandler();  
    $this->refererHandler->initialize('update');
    
    // == get app data
    if ($app_slug = $this->getRequestParameter('app')) {
        $app = UllFlowAppPeer::retrieveBySlug($app_slug);
        
    } else {
        $app = UllFlowAppPeer::retrieveByPK($doc->getUllFlowAppId());
        $app_slug = $app->getSlug();
      
    }
    $this->forward404Unless($app);
    $app_id       = $app->getId();
    $app_caption  = ullCoreTools::getI18nField($app, 'caption'); 
    
//    ullCoreTools::printR($app);
//    ullCoreTools::printR($doc);
//    exit();
    
    
    // == defaults
    $user_id  = $this->getUser()->getAttribute('user_id');
    $user     = UllUserPeer::retrieveByPK($user_id);
    $now      = date('Y-m-d H:i:s');
    
    
    // == get field info    
    $this->ull_form   = new ullFormFlow();
    $this->ull_form->setContainerName($app_id);
    $this->ull_form->buildFieldsInfo();
    $fields_info      = $this->ull_form->getFieldsInfo();
    
    
    // == get action info
    $ull_flow_action_slug   = $this->getRequest()->getParameter('ull_flow_action');
    $ull_flow_action        = UllFlowActionPeer::retrieveBySlug($ull_flow_action_slug);
    $ull_flow_action_is_status_only  = $ull_flow_action->getStatusOnly();
    
    
    $ull_flow_action_comment = $this->getRequestParameter('ull_flow_action_comment');
    
    // == validation
    if (!$ull_flow_action->getDisableValidation()) {
      
      foreach ($fields_info as $field_name => $field) {
              
        $request_param = $this->getRequestParameter($field_name);
        
  //      ullCoreTools::printR($field);
  
        if (isset($field['access'])) {
      
          // mandatory
          if (@$field['mandatory']) {
            if (!$request_param) {
              $this->getRequest()->setError($field_name, __('This field cannot be left blank', null, 'common'));
            }
          }
          
        }
      
      } // end of foreach $fields_info
      
      // validate ull_flow_action_comment 
      if ($ull_flow_action->getCommentIsMandatory()) {
        if (!$this->getRequestParameter('ull_flow_action_comment')) {
          $this->getRequest()->setError('ull_flow_action_comment', __('Please enter a reason for this action'));    
        }
      }
      
      // redisplay to the form in case of errors    
      if ($this->getRequest()->hasErrors()) {
        $this->forward('ullFlow', 'create');
      }    
      
    } // end of no validation list check    

    
      
    // == set basic ullFlowDoc data
    $doc->setUllFlowAppId($app_id);
    
    if ($new) {
      $doc->setCreatorUserId($user_id);
      $doc->setCreatedAt($now);
    }
    
    $doc->setUpdatorUserId($user_id);
    $doc->setUpdatedAt($now);
//    $doc->setTags(strtolower($this->getRequestParameter('tags')));
    $doc->save();
    
    // get newley created doc_id
    if ($new) {
      $doc_id = $doc->getId();
    }    
    
//    ullCoreTools::printR($fields_info);
//    exit();    
    
    
    // TODO: refactor as an API function. 
    // reason: for other scripts which save ullFlow data (mail2doc, etc) 
    
    // == save fields data
    foreach ($fields_info as $field_name => $field) {    
      
      // only save fields, that where submitted via request parameter.
      //  otherwise we would set all disabled fields to null
      if ($this->hasRequestParameter($field_name)) {
        
        $value = $this->getRequestParameter($field_name);
        
//        ullCoreTools::printR($field_name . ' ' . $value);
//        exit();
        
        $field_id = str_replace('field','',$field_name);
        
        $c = new Criteria();
        $c->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $doc_id);
        $c->add(UllFlowValuePeer::ULL_FLOW_FIELD_ID, $field_id);
        $c->add(UllFlowValuePeer::CURRENT, true);
        $value_object = UllFlowValuePeer::doSelectOne($c);
        
        if (!$value_object) {
          $value_object = new UllFlowValue();
        }
        
        // == field_type specific handling
        $field_type_class_name = 'ullFieldHandler' . sfInflector::camelize($field['field_type']);
        if (class_exists($field_type_class_name)) {
          $field_handler = new $field_type_class_name();
          if (method_exists($field_handler, 'updateHandler')) {
            $field_handler->setPropelObject($value_object);
            $value = $field_handler->updateHandler('value', $value, $doc);
          }
        }
        
        $value_object->setUllFlowDocId($doc_id);
        $value_object->setUllFlowFieldId($field_id);
        $value_object->setCurrent(true);
        $value_object->setValue($value);
    
        $value_object->setUpdatorUserId($user_id);
        $value_object->setUpdatedAt($now);        
        
        $value_object->save();
        
        if (isset($field['is_subject'])) {
          if (!$value) {
            $value = ' ';
          }
          $doc->setTitle($value);
        }
        
        if (isset($field['is_priority'])) {
          $doc->setPriority($value);
        }
        
        if (isset($field['is_deadline'])) {
          $doc->setDeadline($value);
        }
        
        if (isset($field['is_custom_field1'])) {
          $doc->setCustomField1($value);
        }
        
      } // end of if in request params
      
    } // end of foreach field

    
    
    
    // == action handling
    
    
    // handle special procedures for action 'create' 
    if ($new) {
      $doc->setUllFlowActionId(UllFlowActionPeer::getActionIdBySlug('create')); 
      $doc->setAssignedToUllUserId($user_id);
      
      // get and save start step
      $c = new Criteria();
      $c->add(UllFlowStepPeer::ULL_FLOW_APP_ID, $app_id);
      $c->add(UllFlowStepPeer::IS_START, true);
      $start_step = UllFlowStepPeer::doSelectOne($c);
//      if ($start_step) {
        $start_step_id = $start_step->getId();
        $doc->setAssignedToUllFlowStepId($start_step_id);
//      } 
      
      // save creation memory
      $memory = new UllFlowMemory();
      $memory->setUllFlowDocId($doc_id);
      $memory->setUllFlowStepId($start_step_id);
      $memory->setUllFlowActionId(UllFlowActionPeer::getActionIdBySlug('create'));
      $memory->setComment($ull_flow_action_comment);
      $memory->setCreatorUserId($user_id);
      $memory->setCreatedAt($now);
      $memory->save();
    } // end of 'create' procedures
    
    
    // set ull_flow_action only for non-'status only' ullflow actions:
    if (!$ull_flow_action_is_status_only) {
      $doc->setUllFlowActionId($ull_flow_action->getId());
    }
    
    // save old/current group_id before it is overwritten by the ullflow action handling
    $doc_before_new_assignment = clone $doc;
    
    


    // TODO: action access check!    
    
    // call rule only for 'real' actions 
    if (!$ull_flow_action_is_status_only) {
      
      // load ull_flow action handler
      $action_handler_class_name = 'ullFlowActionHandler' . sfInflector::camelize($ull_flow_action_slug);
      $action_handler = new $action_handler_class_name();
      $action_handler->setDoc($doc);
      
      // check for an updateHandler() method of actions that have standardized behaviour
      // (e.g. 'reject' or 'return' always return the last step and user/group
      if (method_exists($action_handler, 'updateHandler')) {
        $assign_to_params = $action_handler->updateHandler();
//        ullCoreTools::printR($assign_to_params);

      } else {
        // parse rule for current step 
        $rule_class_name = 'ullFlowRule' . sfInflector::camelize($app_slug);
        $rule = new $rule_class_name();
        $rule->setDoc($doc);
        $rule->setRequest($this->getRequest());
        $assign_to_params= $rule->getParams();
      }
      
//      ullCoreTools::printR($assign_to_params);
//      exit();

      // only overwrite if values exist. default is to retain the current values
      if ($assign_to_params['next_group']) {
        $doc->setAssignedToUllGroupId($assign_to_params['next_group']);
        $doc->setAssignedToUllUserId(0);
      } 
        
      if ($assign_to_params['next_user']) {
          $doc->setAssignedToUllUserId($assign_to_params['next_user']);
          $doc->setAssignedToUllGroupId(0);
      }
        
      if ($assign_to_params['next_step']) {
        $doc->setAssignedToUllFlowStepId($assign_to_params['next_step']);
      }
    }
    
    // save memory data
    $memory = new UllFlowMemory();
    $memory->setUllFlowDocId($doc_id);
    $memory->setUllFlowStepId($doc_before_new_assignment->getAssignedToUllFlowStepId());
    $memory->setUllFlowActionId(UllFlowActionPeer::getActionIdBySlug($ull_flow_action_slug));
    // set assigned to
    if ($doc->getAssignedToUllGroupId()) {
      $memory->setAssignedToUllGroupId($doc->getAssignedToUllGroupId());
    } elseif ($doc->getAssignedToUllUserId()) {
      $memory->setAssignedToUllUserId($doc->getAssignedToUllUserId());
    }
    if (!$new) {
      $memory->setComment($this->getRequestParameter('ull_flow_action_comment'));
    }
    
    // set group, if the doc was currently assigned to a group
    if ($old_group_id = $doc_before_new_assignment->getAssignedToUllGroupId()) {
      $memory->setCreatorGroupId($old_group_id);
    }  
    $memory->setCreatorUserId($user_id);
    $memory->setCreatedAt($now);
    $memory->save();
    
    // == save ullFlowDoc again 
    $doc->save();

    
    // == build access groups
    ullFlowFunctions::build_ull_flow_doc_access($doc);
    
    
    // === E-Mail notifications
    
    // == Notify creator
    if ($ull_flow_action->getNotifyCreator()) {
      
      $mail = new ullFlowMailNotifyCreator();
      $mail->setApp($app);
      $mail->setDoc($doc);
      $mail->setUser($user);
      $mail->setUllFlowAction($ull_flow_action);
      $mail->setComment($ull_flow_action_comment);
      $mail->send();
      
    }
    
    // == Notify next
    if ($ull_flow_action->getNotifyNext()) {
      
      $mail = new ullFlowMailNotifyNext();
      $mail->setApp($app);
      $mail->setDoc($doc);
      $mail->setUser($user);
      $mail->setUllFlowAction($ull_flow_action);
      $mail->setComment($ull_flow_action_comment);
      $mail->send();
      
    }
    
    // == Check for custom E-Mail function for current ull_flow_action
    if (!$ull_flow_action_is_status_only) {
      if (method_exists($action_handler, 'getCustomMailer')) {
          $mail = $action_handler->getCustomMailer();
          $mail->setApp($app);
          $mail->setDoc($doc);
          $mail->setUser($user);
          $mail->setUllFlowAction($ull_flow_action);
          $mail->setComment($ull_flow_action_comment);
          $mail->send();
      }
    }
     
    // == manage externals
    if ($external_name = $this->getRequestParameter('external')) {
//      ullCoreTools::printR($external_name);
//      exit();
      $external_class_name = 'ullFlowExternal' . sfInflector::classify($external_name);
      if (class_exists($external_class_name)) {
        
        $external = new $external_class_name();
        $external->setDoc($doc);
        $external->setRequest($this->getRequest());
        $external->initialize();
        $module = $external->getModule();
        $action = $external->getAction();
        
        return $this->forward($module, $action);
      }
    }
    
    
    // == referer handling
    
    if ($ull_flow_action_slug == 'save_only') {
      return $this->redirect('ullFlow/edit?doc=' . $doc_id);
    }
    
    $refererHandler = new refererHandler();
    return $this->redirect($refererHandler->getRefererAndDelete('edit'));
    
  }  

  
  
  public function executeDelete()
  { 
    // check access
    $this->checkAccess(1);
    
    if (!$this->hasRequestParameter('doc')) {
      $this->error = __('Please specify a ullFlow document') . '!';
      return sfView::ERROR;
    }
    
    $doc_id = $this->getRequestParameter('doc');
    
    $doc = UllFlowDocPeer::retrieveByPK($doc_id);
     
    $this->forward404Unless($doc);
    
    // get access groups
    $access_groups[] = $doc->getReadUllGroupId();
    $access_groups[] = $doc->getWriteUllGroupId();
    
    // remove tags
    $doc->removeAllTags();
    $doc->save();
    
    $doc->delete();
    
    // delete values;
    $c = new Criteria();
    $c->add(UllFlowValuePeer::ULL_FLOW_DOC_ID, $doc_id);
    $values = UllFlowValuePeer::doSelect($c);
    
    foreach ($values as $value) {
      $value->delete(); 
    }

    // delete memories;
    $c = new Criteria();
    $c->add(UllFlowMemoryPeer::ULL_FLOW_DOC_ID, $doc_id);
    $memories = UllFlowMemoryPeer::doSelect($c);
    
    foreach ($memories as $memory) {
      $memory->delete(); 
    }    
    
    // delete access groups
    foreach ($access_groups as $access_group) {
      $c = new Criteria();
      $c->add(UllUserGroupPeer::ULL_GROUP_ID, $access_group);
      UllUserGroupPeer::doDelete($c);

      $c = new Criteria();
      $c->add(UllGroupPeer::ID, $access_group);
      UllGroupPeer::doDelete($c);
    }

//    return $this->redirect('ullFlow/index');

    $refererHandler = new refererHandler();
    
    if (!$referer_edit = $refererHandler->getRefererAndDelete('edit')) {
//      $referer_edit = 'ullFlow/index';
      $referer_edit = $this->getUser()->getAttribute('referer');
    }   
    
    return $this->redirect($referer_edit);
  }  

  
  
  /*
   * Rebuilds the access rights per doc
   * 
   * This is e.g. necessary after changing group-memberships
   */
  public function executeRebuildDocAccess() {

    $docs = UllFlowDocPeer::doSelect(new Criteria());
    
    foreach ($docs as $doc) {
       ullFlowFunctions::build_ull_flow_doc_access($doc);
    }
    
    return $this->redirect('ullFlow');
    
  }
  
  
  

  
  public function executeUpload($request) 
  {
   
//      ullCoreTools::printR(sfConfig::get('sf_upload_dir'));
//      ullCoreTools::printR(sfConfig::get('sf_web_dir')); 
//    exit();

    $this->getDocFromRequestOrCreate();
    
    $column = $request->getParameter('column');
    $this->column = $column;

    $this->form = new ullFlowUploadForm;
    
    if ($request->isMethod('post'))
    {
//      var_dump($this->getRequest()->getParameterHolder()->getAll());
      
      $this->form->bind($request->getParameter('fields'), $this->getRequest()->getFiles('fields'));
      
      if ($this->form->isValid())
      {
        $file = $this->form->getValue('file');
        $value = $this->form->getValue('value');
        
        $path = 
            sfConfig::get('sf_upload_dir') . 
            '/ullFlow/' .
            $this->doc->UllFlowApp->slug .
            '/' .
            $this->doc->id .
            '/' .
            date('Y-m-d-H-i-s_') .
            $file->getOriginalName()
        ;
        
        $file->save($path);
        
        $relativePath = str_replace(sfConfig::get('sf_web_dir'), '', $path);
        
        // create upload csv row
        $row = 
            $file->getOriginalName(). ';' .
            $relativePath . ';' .
            $file->getType() . ';' .
            $this->getUser()->getAttribute('user_id') . ';' .
            date('Y-m-d H:i:s')
        ;
        
        // add row to csv
        $array = array();
        
        if ($value) 
        {
          $arr = explode("\n", $value);
        }
        $arr[] = $row;
        
        $value = implode("\n", $arr);
        
        $this->doc->$column = $value;
        $this->doc->save();
        
        // reset form to allow re-setting of defaults
        $this->form = new ullFlowUploadForm;        
        $this->form->setDefault('value', $value);
      }
    }
    else
    {
      $this->form->setDefault('value', $this->doc->$column);
    }
      
  }  
  
  
  public function executeWikiLink() {
    
//    ullCoreTools::printR($this->getRequest()->getParameterHolder()->getAll());
//    exit();
  
    $this->external_field = $this->getRequestParameter('external_field');
    $this->value          = rawurldecode($this->getRequestParameter($this->external_field));
    $this->app            = $this->getRequestParameter('app');
    $this->doc            = $this->getRequestParameter('doc');
    $this->ull_flow_action= $this->getRequestParameter('ull_flow_action');
    $this->delete         = $this->getRequestParameter('delete');
    
    // remove \r from value
//    $this->value = str_replace("\r", '' , $this->value);
    
    // handle delete
    if (!is_null($this->delete)) {
      $arr = array();
      if ($this->value) {
        $arr = explode("\n", $this->value);
        unset($arr[$this->delete]);
        $this->value = implode("\n", $arr);
      }
    }

        
    // handle new link
    if ($ull_wiki_doc_id = $this->getRequestParameter('ull_wiki_doc_id')) {
      
      // create upload csv row
      $row = 
        $ull_wiki_doc_id
      ;
      
      // add row to csv
      $arr = array();
      if ($this->value) {
        $arr = explode("\n", $this->value);
      }
      $arr[] = $row;
      $this->value = implode("\n", $arr);
      
    }
 
    $wiki_return_url = 'ullFlow/wikiLink?external_field=' . $this->external_field;
    if ($this->value) {
      $wiki_return_url .= '&' . $this->external_field . '=' . rawurlencode($this->value);
    }
    $wiki_return_url .=
      '&app=' . $this->app
      . '&doc=' . $this->doc
      . '&ull_flow_action=' . $this->ull_flow_action
    ;
    
    // we'll save the wiki_return_url in the user session, because it's troublesome to pass it as a request param
    $this->getUser()->setAttribute('wiki_return_url', $wiki_return_url);
//    $this->wiki_return_url = rawurlencode($wiki_return_url);
      
      
     
  }

  
  protected function checkDocAccess(ullFlowDoc $doc) {

    $return = false;
  
    $read_group = $doc->getReadUllGroupId();
    if (UllUserPeer::userHasGroup($read_group)) {
      $return = 'r';
    }
    
    $write_group = $doc->getWriteUllGroupId();
    if (UllUserPeer::userHasGroup($write_group)) {
      $return = 'w';
    }      
    
    return $return;
    
  }
  
  protected function breadcrumbForIndex()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflows'), 'ullFlow/index');
  }
  
  protected function breadcrumbForList()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflows'), 'ullFlow/index');
    if ($this->app) {
      $this->breadcrumbTree->add($this->app->label);
    }
    if ($this->app) {
      $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list?app=' . $this->app->slug);
    } else {
      $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list');
    }
  }  
  
  protected function breadcrumbForEdit()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add(__('Workflows'), 'ullFlow/index');
    $this->breadcrumbTree->add($this->app->label);
//    if (!$this->referer_edit = $this->refererHandler->getReferer()) {
//      $this->referer_edit = 'ullFlow/list?app=' . $this->app->getSlug();
//    }    
//    $this->breadcrumbTree->add(__('List', null, 'common'), $this->referer_edit);
    $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list?app=' . $this->app->slug);
    if ($this->doc->exists()) {
      $this->breadcrumbTree->addFinal(__('Edit', null, 'common'));
    } else {
      $this->breadcrumbTree->addFinal(__('Create', null, 'common'));
    }    
    
  }  

  protected function getAppfromRequest() 
  {
    if ($this->hasRequestParameter('app'))
    {    
      $this->app = UllFlowAppTable::findBySlug($this->getRequestParameter('app'));
    }
  }

  protected function getFilterFromRequest()
  {
    $this->filter_form = new ullFlowFilterForm;
    $this->filter_form->bind($this->getRequestParameter('filter'));
    
    $this->ull_filter = new ullFilter();
    
    $userId  = $this->getUser()->getAttribute('user_id');
    
    $q = new Doctrine_Query;
    $q->select('x.*, v.*');
    $q->from('UllFlowDoc x, x.UllFlowValues v');

    // search has to be the first "where" part, because it uses "or" 
    if ($search = $this->filter_form->getValue('search'))
    {
      $cols = array('id', 'subject', 'duplicate_tags_for_search');     
      $q = ullCoreTools::doctrineSearch($q, $search, $cols);
    }

    // app
    if ($this->app)
    {
      $q->addWhere('x.ull_flow_app_id = ?', $this->app->id);
      
      $this->ull_filter->add(
        'app',
        __('Application') . ': ' . $this->app->label
      );      
    }
    
    // access
    $q = UllFlowDocTable::queryAccess($q, $this->app);
    
    // 'named' queries
    if ($query = $this->getRequestParameter('query')) 
    {
      switch($query) 
      {
        case('by_me'):
          $q->addWhere('x.creator_user_id = ?', $userId);
          $this->ull_filter->add(
            'query',
            __('Query', null, 'common') . ': ' . __('Entries created by me')
          );
          break;
        case('to_me'):
          $q->addWhere('x.assigned_to_ull_entity_id = ?', $userId);
          $this->ull_filter->add(
            'query',
            __('Query', null, 'common') . ': ' . __('Entries assigned to me')
          );
          break;          
////          $c->setDistinct(true) ;
////          $c->addJoin(UllFlowDocPeer::ASSIGNED_TO_ULL_GROUP_ID, UllUserGroupPeer::ULL_GROUP_ID, Criteria::LEFT_JOIN);
////          $cton_user = $c->getNewCriterion
////          $cton_group = $c->getNewCriterion(UllUserGroupPeer::ULL_USER_ID, $user_id);
////          $cton_user->addOr($cton_group);
////          $c->add($cton_user);
//          $c->add(UllFlowDocPeer::ASSIGNED_TO_ULL_USER_ID, $user_id);
//          $this->ull_filter->add(
//            'query'
//            , __('Query', null, 'common') . ': ' . __('Entries assigned to me')
//          );
//          break;
      }
    }       
    

    
    // order
    $this->order = $this->getRequestParameter('order', 'created_at');
    $this->order_dir = $this->getRequestParameter('order_dir', 'desc');
    
    $order_func = ($this->order_dir == 'desc') ? 'DESC' : 'ASC';

    // for native UllFlowDoc columns...
    if (Doctrine::getTable('UllFlowDoc')->hasColumn($this->order))
    {
      switch ($this->order)
      {
        case 'assigned_to_ull_entity_id':
          $q->orderBy('x.UllEntity.display_name ' . $order_func);
          break;
        case 'creator_user_id':
          $q->orderBy('x.Creator.display_name ' . $order_func);
          break;
        case 'updator_user_id':
          $q->orderBy('x.Updator.display_name ' . $order_func);
          break;
        default:
          $q->orderBy($this->order . ' ' . $order_func);
      }
    }
    // for virtual columns...
    else
    {
      //doesn't work...
      //      $q->leftJoin('x.UllFlowValues v1 WITH v1.UllFlowColumnConfig.slug=?', $this->order);

      // resolve virtual column slug to UllColumnConfig.id
      $q1 = new Doctrine_Query();
      $q1
        ->from('UllFlowColumnConfig')
        ->where('ull_flow_app_id = ?', $this->app->id)
        ->addWhere('slug = ?', $this->order)
      ;
      $order_cc_id = $q1->execute()->getFirst()->id;
        
      $q->leftJoin('x.UllFlowValues v1 WITH v1.ull_flow_column_config_id=?', $order_cc_id);
      $q->orderBy('v1.value'. ' ' . $order_func);
    }
    
    // add 'created_at (desc)' as default 2nd order criteria
    if ($this->order != 'created_at') {
      $q->addOrderBy('x.created_at DESC');
    }

//    printQuery($q->getQuery());
//    var_dump($q->getParams());
//    die;

    
    $this->pager = new Doctrine_Pager(
      $q, 
      $this->getRequestParameter('page', 1),
      sfConfig::get('app_pager_max_per_page')
    );
    $docs = $this->pager->execute();    
    
//    var_dump($rows->toArray());
//    die;
    
    return ($docs->count()) ? $docs : new UllFlowDoc;
  }  
  
  /**
   * Gets or creates a UllFlowDocObject according to the given request param
   *
   */
  protected function getDocFromRequestOrCreate()
  {
    if (!$this->hasRequestParameter('app') and !$this->hasRequestParameter('doc')) {
      throw new InvalidArgumentException('At least one of the "app" or "doc" parameters have to be given');
    }

    if ($docId = $this->getRequestParameter('doc'))
    {
      $this->doc = Doctrine::getTable('UllFlowDoc')->find($docId);
      $this->forward404Unless($this->doc);
      $this->app = $this->doc->UllFlowApp;
    }
    else
    {
      $this->doc = new UllFlowDoc;
      $this->app = UllFlowAppTable::findBySlug($this->getRequestParameter('app'));
      $this->forward404Unless($this->app);
      $this->doc->UllFlowApp = $this->app;
    }    
  }  

  /**
   * send notify emails
   *
   */
  protected function sendMails()
  {
    if ($this->doc->UllFlowAction->is_notify_next) 
    {
      $mail = new ullFlowMailNotifyNext($this->doc);
      $mail->send();
    }

    if ($this->doc->UllFlowAction->is_notify_creator) 
    {
      $mail = new ullFlowMailNotifyCreator($this->doc);
      $mail->send();
    }        
  }
  
  /**
   * Check access for the workflow actions
   * 
   * for existing docs, only the entities whom the doc is assigned to 
   * are allowed to do make workflow actions (like send, assign, reject, ...)
   * 
   */
  protected function workflowActionAccessCheck()
  {
    if ($this->doc->exists()) 
    {
      $this->workflow_action_access = false;
      
      if (UllEntityTable::has($this->doc->UllEntity)) 
      {
        $this->workflow_action_access = true;
      }
    }
    else 
    {
      $this->workflow_action_access = true;
    }
  }    

}