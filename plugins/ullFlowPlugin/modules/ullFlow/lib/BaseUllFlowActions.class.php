<?php

/**
 * ullFlow actions.
 *
 * @package    ullright
 * @subpackage ullFlow
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllFlowActions extends ullsfActions
{

  /**
   * Execute prior to each ullFlow action
   *
   */
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
      $this->breadcrumbTree->add($this->app->label, 'ullFlow/index?app=' . $this->app->slug);
    } 
    else 
    {
      $this->apps = Doctrine::getTable('UllFlowApp')->findAll();
    }
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
  
  
  /**
   * Execute list action
   *
   * @param sfRequest $request
   */
  public function executeList($request) 
  {
    $this->checkAccess('LoggedIn');
    
    if ($request->isMethod('post'))
    {
//      var_dump($request->getParameterHolder()->getAll());die;
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

  /**
   * Executes show action
   *
   */
  public function executeShow() 
  {
    $this->forward('ullFlow', 'edit');
  }

  
  /**
   * Executes create action
   *
   */
  public function executeCreate() 
  {
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
  
  
  /**
   * Executes edit action
   *
   * @param sfRequest $request
   * @return unknown
   */
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
  }

  /**
   * Executes delete action
   *
   * @return unknown
   */  
  public function executeDelete()
  { 
    $this->checkAccess('LoggedIn');
    
    $this->refererHandler = new refererHandler();  
    
    $this->getDocFromRequest();
    
    $this->redirectUnless($this->doc->checkDeleteAccess(), 'ullUser/noaccess');
    
    // remove tags
    $this->doc->removeAllTags();
    $this->doc->save();
    
    $this->doc->delete();

    $refererHandler = new refererHandler();
    
    if (!$referer_edit = $refererHandler->getRefererAndDelete('edit')) {
//      $referer_edit = 'ullFlow/index';
      $referer_edit = $this->getUser()->getAttribute('referer');
    }   
    
    return $this->redirect($referer_edit);
  }  

  /**
   * Executes upload action
   *
   * @param sfRequest $request
   */  
  public function executeUpload($request) 
  {
   
//      ullCoreTools::printR(sfConfig::get('sf_upload_dir'));
//      ullCoreTools::printR(sfConfig::get('sf_web_dir')); 
//    exit();

    $this->getDocFromRequestOrCreate();
    
    $accessType = $this->doc->checkAccess();
    $this->redirectUnless($accessType, 'ullUser/noaccess');
    
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
  
  /**
   * Executes wiki link action
   *
   * @param sfRequest $request
   */
  public function executeWikiLink($request) 
  {
//    var_dump($this->getRequest()->getParameterHolder()->getAll());
    
    $this->getDocFromRequestOrCreate();
    
    $accessType = $this->doc->checkAccess();
    $this->redirectUnless($accessType, 'ullUser/noaccess');    
        
    $column = $request->getParameter('column');
    $this->column = $column;
    
    $this->value = $this->doc->$column;

    $wikiReturnUrl = 'ullFlow/wikiLink?doc=' . $this->doc->id . '&column=' . $column;
    
    // we'll save the wiki_return_url in the user session, because it's troublesome to pass it as a request param
    $this->getUser()->setAttribute('wiki_return_url', $wikiReturnUrl);
  
    // add new link
    if ($ullWikiDocId = $this->getRequestParameter('ull_wiki_doc_id')) 
    {
      $arr = array();
      if ($this->value) 
      {
        $arr = explode("\n", $this->value);
      }
      $arr[] = $ullWikiDocId;
      $this->value = implode("\n", $arr);
      
      $this->doc->$column = $this->value;
      $this->doc->save();
    }

    
    // handle delete
    if ($delete = $this->getRequestParameter('delete')) 
    {
      $arr = array();
      if ($this->value) 
      {
        $arr = explode("\n", $this->value);
        unset($arr[$delete - 1]);
        $this->value = implode("\n", $arr);
      }
      
      $this->doc->$column = $this->value;
      $this->doc->save();
    }

  }

  /**
   * Handle breadcrumbs
   *
   */
  protected function breadcrumbForIndex()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflows'), 'ullFlow/index');
  }

  /**
   * Handle breadcrumbs
   *
   */  
  protected function breadcrumbForList()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflows'), 'ullFlow/index');
    if ($this->app) 
    {
      $this->breadcrumbTree->add($this->app->label, 'ullFlow/index?app=' . $this->app->slug);
    }
    if ($this->app) 
    {
      $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list?app=' . $this->app->slug);
    } 
    else 
    {
      $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list');
    }
  }  
  
  /**
   * Handle breadcrumbs
   *
   */  
  protected function breadcrumbForEdit()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add(__('Workflows'), 'ullFlow/index');
    $this->breadcrumbTree->add($this->app->label, 'ullFlow/index?app=' . $this->app->slug);
    $this->breadcrumbTree->add(__('List', null, 'common'), 'ullFlow/list?app=' . $this->app->slug);
    if ($this->doc->exists()) 
    {
      $this->breadcrumbTree->addFinal(__('Edit', null, 'common'));
    } 
    else 
    {
      $this->breadcrumbTree->addFinal(__('Create', null, 'common'));
    }    
    
  }  

  /**
   * Find object according to request param
   *
   */
  protected function getAppfromRequest() 
  {
    if ($this->hasRequestParameter('app'))
    {    
      $this->app = UllFlowAppTable::findBySlug($this->getRequestParameter('app'));
    }
  }

  /**
   * Find objects according to request params
   *
   */  
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
    
    // ullFlow action
    if (!$ullFlowActionName = $this->filter_form->getValue('status')) 
    {
      $q->addWhere('x.UllFlowAction.is_in_resultlist = ?', true);
    } 
    elseif ($ullFlowActionName <> 'all') 
    {
      $q->addWhere('x.UllFlowAction.slug = ?', $ullFlowActionName);
    }    
    
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
      }
    }       

    
    // order
    $this->order = $this->getRequestParameter('order', 'created_at');
    $this->order_dir = $this->getRequestParameter('order_dir', 'desc');
    
    $orderDir = ($this->order_dir == 'desc') ? 'DESC' : 'ASC';

    // for native UllFlowDoc columns...
    if (Doctrine::getTable('UllFlowDoc')->hasColumn($this->order))
    {
      switch ($this->order)
      {
        case 'assigned_to_ull_entity_id':
          $q->orderBy('x.UllEntity.display_name ' . $orderDir);
          break;
        case 'creator_user_id':
          $q->orderBy('x.Creator.display_name ' . $orderDir);
          break;
        case 'updator_user_id':
          $q->orderBy('x.Updator.display_name ' . $orderDir);
          break;
        default:
          $q->orderBy($this->order . ' ' . $orderDir);
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
      $q->orderBy('v1.value'. ' ' . $orderDir);
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
   * Gets a UllFlowDocObject according to the given request param
   *
   */
  protected function getDocFromRequest()
  {
    if (!$docId = $this->getRequestParameter('doc')) 
    {
      throw new InvalidArgumentException('The "doc" parameter is empty');
    }

    $this->doc = Doctrine::getTable('UllFlowDoc')->find($docId);
    $this->forward404Unless($this->doc);
  }   
  
  
  /**
   * Gets or creates a UllFlowDocObject according to the given request param
   *
   */
  protected function getDocFromRequestOrCreate()
  {
    if (!$this->hasRequestParameter('app') and !$this->hasRequestParameter('doc')) 
    {
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