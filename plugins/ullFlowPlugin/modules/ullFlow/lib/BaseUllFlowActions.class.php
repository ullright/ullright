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
   * Execute before each action
   *
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/list';
    if ($app = $this->getRequestParameter('app'))
    {
      $defaultUri .= '?app=' . $app;
    }
    $this->getUriMemory()->setDefault($defaultUri);

    $path =  '/ullFlowTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }

  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
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

    $this->loadPopularTags();
    $this->loadNamedQueries();
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
    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }

    $this->getAppfromRequest();
    
    $this->loadNamedQueries();

    $this->generator = new ullFlowGenerator($this->app);

    $docs = $this->getFilterFromRequest($request);

    $this->generator->buildForm($docs);

    $this->getUriMemory()->setUri();

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
    $this->checkAccess('LoggedIn');

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
    $this->getDocFromRequestOrCreate();
    
    $accessType = $this->doc->checkAccess();
    $this->redirectToNoAccessUnless($accessType);

    $this->generator = new ullFlowGenerator($this->app, $accessType);
    $this->generator->buildForm($this->doc);
    
    $this->generator->buildListOfUllFlowActionHandlers();
    
    $this->user_widget = new ullWidgetForeignKey(array('model' => 'UllEntity', 'show_ull_entity_popup' => true));

    if ($request->isMethod('post'))
    {

      $this->generator->setUllFlowActionHandler($request->getParameter('action_slug'));

      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        // notify post_save event
        $this->dispatcher->notify(new sfEvent($this, 'ull_flow.post_save', array(
          'doc'        => $this->doc
        )));

        if (!$this->isStatusOnlyRequestAction())
        {
          $this->sendMails();
        }

        // manage full page widgets
        if ($fullPageWidgetName = $request->getParameter('full_page_widget'))
        {
          $fullPageWidgetClass = 'ullFlowFullPageWidget' . sfInflector::classify($fullPageWidgetName);

          if (class_exists($fullPageWidgetClass))
          {
            $fullPageWidget = new $fullPageWidgetClass($this->doc, $request->getParameter('full_page_widget_column'));
            $this->redirect($fullPageWidget->getInternalUri());
          }
        }

        // referer handling
        if ($request->getParameter('action_slug') == 'save_only')
        {
          $this->redirect('ullFlow/edit?doc=' . $this->doc->id);
        }

        $this->redirect($this->getUriMemory()->getAndDelete('list'));
      }
    }

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

    $this->getDocFromRequest();

    $this->redirectToNoAccessUnless($this->doc->checkDeleteAccess());

    // remove tags
    $this->doc->removeAllTags();
    $this->doc->save();

    $this->doc->delete();

    $this->redirect($this->getUriMemory()->getAndDelete('list'));
  }

  /**
   * Executes upload action
   *
   * @param sfRequest $request
   */
  public function executeUpload($request)
  {
    $this->getDocFromRequestOrCreate();

    $accessType = $this->doc->checkAccess();
    $this->redirectToNoAccessUnless($accessType);

    $column = $request->getParameter('column');
    $this->column = $column;

    $this->form = new ullFlowUploadForm;

    if ($request->isMethod('post'))
    {
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
    $this->getDocFromRequestOrCreate();

    $accessType = $this->doc->checkAccess();
    $this->redirectToNoAccessUnless($accessType);

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
      foreach ($arr as $value)
      {
        if ($value == $ullWikiDocId)
        {
          //this wiki doc id is already in the link list
          return ;
        }
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

  //				  _______  _______  _______  _______  _______
  //				(  ____ \(  ____ \(  ___  )(  ____ )(  ____ \|\     /|
  //				| (    \/| (    \/| (   ) || (    )|| (    \/| )   ( |
  //				| (_____ | (__    | (___) || (____)|| |      | (___) |
  //				(_____  )|  __)   |  ___  ||     __)| |      |  ___  |
  //				      ) || (      | (   ) || (\ (   | |      | (   ) |
  //				/\____) || (____/\| )   ( || ) \ \__| (____/\| )   ( |
  //				\_______)(_______/|/     \||/   \__/(_______/|/     \|
  //
  
  /**
   * This function builds a search form utilizing the ull search
   * framework, see the individual classes for reference.
   * If it handles a post request, it builds the actual search object
   * and forwards to the list action.
   * 
   * @param $request The current request
   */
 public function executeSearch(sfRequest $request)
  {
    $this->moduleName = $request->getParameter('module');
    $this->modelName = 'UllFlowDoc';
    $this->getUriMemory()->setUri('search');
    $this->getAppfromRequest();
    $this->breadcrumbForSearch();
    $searchConfig = ullSearchConfig::loadSearchConfig('ullFlowDoc', $this->app);

    $doRebind = ullSearchActionHelper::handleAddOrRemoveCriterionButtons($request, $this->getUser());

    $searchGenerator = new ullFlowSearchGenerator($searchConfig->getAllSearchableColumns(), $this->modelName, $this->app);
    $this->addCriteriaForm = new ullSearchAddCriteriaForm($searchConfig, $searchGenerator);
    $searchFormEntries = ullSearchActionHelper::retrieveSearchFormEntries($this->moduleName, $searchConfig, $this->getUser());
    $searchGenerator->reduce($searchFormEntries);
    $this->searchForm = new ullSearchForm($searchGenerator);

    $isSubmit = ($request->isMethod('post') && $this->getRequestParameter('searchSubmit'));
    if (isset($doRebind) || $isSubmit)
    {
      $this->searchForm->getGenerator()->getForm()->bind($request->getParameter('fields'));

      if ($isSubmit && $this->searchForm->getGenerator()->getForm()->isValid())
      {
        $search = new ullFlowSearch($this->app);
        ullSearchActionHelper::addTransformedCriteriaToSearch($search, $searchFormEntries,
          $this->searchForm->getGenerator()->getForm()->getValues());
         
        $this->getUser()->setAttribute('flow_ullSearch', $search);
        $redirectUrl = 'ullFlow/list?query=custom';
        if ($this->app != null)
        {
          $redirectUrl .= '&app=' . $this->app->slug;
        }
        $this->redirect($redirectUrl);
      }
    }
  }


  /**
   * Handle breadcrumbs
   *
   */
  protected function breadcrumbForIndex()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflow') . ' ' . __('Home', null, 'common'), 'ullFlow/index');
  }
  
  /**
   * Handles breadcrumb bar for search
   */
  protected function breadcrumbForSearch()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflow') . ' ' . __('Home', null, 'common'), 'ullFlow/index');
    if ($this->app)
    {
      $this->breadcrumbTree->add($this->app->label, 'ullFlow/index?app=' . $this->app->slug);
    }
    if ($this->app)
    {
      $this->breadcrumbTree->add(__('Advanced search', null, 'common'), 'ullFlow/search?app=' . $this->app->slug);
    }
    else
    {
      $this->breadcrumbTree->add(__('Advanced search', null, 'common'), 'ullFlow/search');
    }
  }

  /**
   * Handle breadcrumbs
   *
   */
  protected function breadcrumbForList()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->add(__('Workflow') . ' ' . __('Home', null, 'common'), 'ullFlow/index');
    if ($this->app)
    {
      $this->breadcrumbTree->add($this->app->label, 'ullFlow/index?app=' . $this->app->slug);
    }
    if ($this->app)
    {
      $this->breadcrumbTree->add(__('Result list', null, 'common'), 'ullFlow/list?app=' . $this->app->slug);
    }
    else
    {
      $this->breadcrumbTree->add(__('Result list', null, 'common'), 'ullFlow/list');
    }
  }

  protected function breadcrumbForEdit()
  {
    $this->breadcrumbTree = new breadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);
    $this->breadcrumbTree->add(__('Workflow')  . ' ' . __('Home', null, 'common'), 'ullFlow/index');
    $this->breadcrumbTree->add($this->app->label, 'ullFlow/index?app=' . $this->app->slug);
    $this->breadcrumbTree->add(__('Result list', null, 'common'), $this->getUriMemory()->get('list'));
    if ($this->doc->exists())
    {
      $this->breadcrumbTree->add(__('Edit', null, 'common'));
    }
    else
    {
      $this->breadcrumbTree->add(__('Create', null, 'common'));
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
  protected function getFilterFromRequest(sfRequest $request)
  {
    $this->filter_form = new ullFlowFilterForm;
    $this->filter_form->bind($this->getRequestParameter('filter'));

    $this->ull_filter = new ullFilter();

    $userId  = $this->getUser()->getAttribute('user_id');

     
    $q = new Doctrine_Query;
    $q->select('x.*, v.*, cc.slug, a.*');
    $q->from('UllFlowDoc x, x.UllFlowValues v, v.UllFlowColumnConfig cc, x.UllFlowApp a');

     
    // search has to be the first "where" part, because it uses "or"
    if ($search = $this->filter_form->getValue('search'))
    {
      $cols = array('id', 'subject', 'duplicate_tags_for_search');
      $q = ullGeneratorTools::doctrineSearch($q, $search, $cols);
    }

    // app
    if ($this->app)
    {
      $q->addWhere('x.ull_flow_app_id = ?', $this->app->id);

      $this->ull_filter->add(
        'app',
      __('Workflow', null, 'ullFlowMessages') . ': ' . $this->app->label
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

    // assigned to
    if ($displayName = $this->getRequestParameter('assigned_to'))
    {
      $entityId = UllEntityTable::findIdByDisplayName($displayName);
      $q->addWhere('x.assigned_to_ull_entity_id = ?', $entityId);
      $this->ull_filter->add(
        'assigned_to',
      __('Assigned to') . ': ' . $displayName
      );
    }

    // 'named' queries
    if ($query = $this->getRequestParameter('query'))
    {
      switch($query)
      {
        case('custom'):
          //add ullSearch to query
          $ullSearch = $this->getUser()->getAttribute('flow_ullSearch', null);
          if ($ullSearch != null)
          {
            $ullSearch->modifyQuery($q, 'x');
             
            $this->ull_filter->add(
	            'query',
            __('Query', null, 'common') . ': ' . __('Custom', null, 'common')
            );
          }
          break;
      }
    }

    //namedQueries may not have the correct filter, but
    //namedQueriesCustom should.
    try
    {
      $this->named_queries->handleFilter($q, $this->ull_filter, $this->getRequest());
    }
    catch (InvalidArgumentException $e)
    {
      if ($this->named_queries_custom)
      {
        $this->named_queries_custom->handleFilter($q, $this->ull_filter, $this->getRequest());
      }
    }
     
    // order
    $this->order = $this->getRequestParameter('order', 'created_at');
    $orderArray = array_flip(explode(',', $this->order));
    $this->order_dir = $this->getRequestParameter('order_dir', 'desc');

    $orderDir = ($this->order_dir == 'desc') ? 'DESC' : 'ASC';
    
    foreach($orderArray as $column => $value)
    {
      // for native UllFlowDoc columns...
      if (Doctrine::getTable('UllFlowDoc')->hasColumn($column))
      {
        switch ($column)
        {
          case 'assigned_to_ull_entity_id':
            $orderArray[$column] = 'x.UllEntity.display_name';
            break;
          case 'creator_user_id':
            $orderArray[$column] = 'x.Creator.display_name';
            break;
          case 'updator_user_id':
            $orderArray[$column] = 'x.Updator.display_name';
            break;
          default:
            $orderArray[$column] = 'x.' . $column;
        }
      }
      // for virtual columns...
      else
      {
        $order_cc_id = $this->app->findColumnConfigBySlug($column)->id;
        
        $q->leftJoin('x.UllFlowValues ' . $column . ' WITH ' . $column . '.ull_flow_column_config_id=?', $order_cc_id);
        
        $orderArray[$column] = $column . '.value';
      }
    }
    
    foreach ($orderArray as $order)
    {
      $q->addOrderBy($order . ' ' . $orderDir);
    }

    // add 'created_at (desc)' as final order criteria
    if (!in_array('x.created_at', $orderArray))
    {
      $q->addOrderBy('x.created_at DESC');
    }

//        printQuery($q->getQuery());
//        var_dump($q->getParams());
//        die;

    $this->pager = new Doctrine_Pager(
    $q,
    $this->getRequestParameter('page', 1),
    sfConfig::get('app_pager_max_per_page')
    );
    $docs = $this->pager->execute();

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
      $this->doc->setDefaults();
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

    $this->generator->getUllFlowActionHandler()->sendMail();

  }

  /**
   * Query popular tags for the index action
   */
  protected function loadPopularTags()
  {
    $q = new Doctrine_Query;
    $q->from('Tagging tg, tg.Tag t, tg.UllFlowDoc x');
    $q = UllFlowDocTable::queryAccess($q, $this->app);
    $this->tags_pop = TagTable::getPopulars($q, array('model' => 'UllFlowDoc', 'limit' => sfConfig::get('app_sfDoctrineActAsTaggablePlugin_limit', 100)));
  }
  
  /**
   * Returns true if the request action is a non-workflow action ("status only")
   * It's also a non-workflow action when a full page widget is called
   * @return unknown_type
   */
  protected function isStatusOnlyRequestAction()
  {
    if (in_array($this->getRequestParameter('action_slug'), array(
      'edit',
      'save_only',
      'save_close',
    )))
    {
      return true;
    }
    
    if ($this->getRequestParameter('full_page_widget'))
    {
      return true;
    }
  }
  
  
  /**
   * Load named queries for index and list action
   * 
   * @return none
   */
  protected function loadNamedQueries()
  {
    $this->named_queries = new ullNamedQueriesUllFlow();
    
    if (class_exists('ullNamedQueriesUllFlowCustom'))
    {
      $this->named_queries_custom = new ullNamedQueriesUllFlowCustom();
    }
    else
    {
      $this->named_queries_custom = null;
    }
    
    if ($this->app_slug)
    {
      $this->named_queries->setBaseUriForExisting($this->named_queries->getBaseUri() . '?app=' . $this->app->slug);
      if ($this->named_queries_custom)
      {
        $this->named_queries_custom->setBaseUriForExisting($this->named_queries_custom->getBaseUri() . '?app=' . $this->app->slug);
      }      
    }
  }
}