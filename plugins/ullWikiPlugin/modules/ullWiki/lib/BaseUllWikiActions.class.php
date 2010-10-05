<?php
/**
 * ullWiki actions
 *
 * @package    ullright
 * @subpackage ullWiki
 * @author     Klemens Ullmann <klemens.ullmann@ull.at>
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */

class BaseUllWikiActions extends BaseUllGeneratorActions
{
 
  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    $this->addModuleStylesheet();  
  }  
  
	
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->form = new ullWikiFilterForm;

    // allow ullwiki to be used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');
    
    $this->loadPopularTags();

    $this->breadcrumbForIndex();
  }

  /**
   * Execute list action
   * 
   */
  public function executeList(sfRequest $request) 
  {
    $this->getUriMemory()->setUri();

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');

    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }

    $this->breadcrumbForList();
    
    $this->generator = new ullWikiGenerator();

    $this->docs = $this->getFilterFromRequest();
    
    $this->generator->buildForm($this->docs);
  }


  /**
   * Execute Show action
   * 
   */
  public function executeShow(sfRequest $request) 
  {
    $this->getDocFromRequest();
    $this->appendToTitle($this->doc);
    
    
    $accessType = $this->doc->checkAccess();
    $this->redirectToNoAccessUnless($accessType);    

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    if ($this->return_var = $this->getRequestParameter('return_var')) 
    {
       $this->return_url = $this->getUser()->getAttribute('wiki_return_url') 
        . '&' . $this->return_var . '=' . $this->doc->id;
    }
    
    $this->has_no_write_access = $this->getRequestParameter('no_write_access'); 

    $this->breadcrumbForShow();
    
    $this->setVar('user_widget', new ullWidgetForeignKey(array('model' => 'UllEntity', 'show_ull_entity_popup' => true)), true);
  }

  /**
   * Execute create action
   * 
   */
  public function executeCreate(sfRequest $request) 
  {
    $this->forward('ullWiki', 'edit');
  }

  /**
   * Execute edit action
   * 
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkAccess('LoggedIn');
    
    $this->getDocFromRequestOrCreate();
    
    if ($this->doc->exists())
    {
      $this->appendToTitle($this->doc);
      $accessType = $this->doc->checkAccess();
      $this->redirectToNoAccessUnless($accessType);
    
      if ($accessType == 'r')
      {
        $this->redirect('ullWiki/show?docid=' . $this->doc->id . '&no_write_access=true');
      }
    }
    
    $this->generator = new ullWikiGenerator('w');
    $this->generator->buildForm($this->doc);
    
    $this->breadcrumbForEdit();
    
    // allows ullWiki to be used as a plugin (e.g. ullFlow to ullWiki interface)
    $this->return_var = $this->getRequestParameter('return_var');    

    if ($request->isMethod('post'))
    {
//      var_dump($this->getRequest()->getParameterHolder()->getAll());die;
      
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        // == forward junction
        
        // save only
        if ($request->getParameter('action_slug') == 'save_only') 
        {
          $this->redirect('ullWiki/edit?docid=' . $this->doc->id);
        }
        
        // plugin mode
        //   allows ullWiki to be used as a plugin (e.g. ullFlow to ullForms interface)         
        elseif ($this->return_var) 
        {
          
          $this->redirect($this->getUser()->getAttribute('wiki_return_url') 
            . '&' . $this->return_var . '=' . $this->doc->id);
        }

        // save and show
        elseif ($request->getParameter('action_slug') == 'save_show') 
        {
          $this->redirect('ullWiki/show?docid=' . $this->doc->id);
        } 
        
        // save and new
        elseif ($request->getParameter('action_slug') == 'save_new') 
        {
          $this->redirect('ullWiki/create');
        }            
        
        // use the default referer
        else
        {
          $this->redirect($this->getUriMemory()->getAndDelete('list'));
        }
      }
    }
  }

  /**
   * Execute delete action
   */
  public function executeDelete(sfRequest $request) 
  {
    $this->checkPermission('ull_wiki_delete');
    
    $this->getDocFromRequest();
    $this->doc->delete();
    
    $this->redirect($this->getUriMemory()->getAndDelete('list'));
  }
  

  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullWikiFilterForm';
  } 
  

  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $breadcrumbTree = new breadcrumbTree();
    $breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }

  /**
   * Create breadcrumbs for list action
   * 
   */  
  protected function breadcrumbForList() 
  {
    $breadcrumbTree = new breadcrumbTree();
    $breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');
    $breadcrumbTree->add(__('Result list', null, 'common'), 'ullWiki/list');
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }  

  /**
   * Create breadcrumbs for show action
   * 
   */  
  protected function breadcrumbForShow() 
  {
    $breadcrumbTree = new breadcrumbTree();
    $breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');
    
    // display result list link only when there is a referer containing 
    //  the list action 
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumbTree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumbTree->add(__('Result list', null, 'common'), 'ullWiki/list');
    }

    $breadcrumbTree->add(__('Show', null, 'common'));    
    $breadcrumbTree->add($this->doc->subject);
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }  

  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() 
  {
    $breadcrumbTree = new breadcrumbTree();
    $breadcrumbTree->setEditFlag(true);
    $breadcrumbTree->add(__('Wiki') . ' ' . __('Home', null, 'common'), 'ullWiki/index');

    // display result list link only when there is a referer containing 
    //  the list action 
    $breadcrumbTree->add(__('Result list', null, 'common'), $this->getUriMemory()->get('list'));

    if ($this->doc->exists()) 
    {
      $breadcrumbTree->add(__('Edit', null, 'common'));
    } 
    else 
    {
      $breadcrumbTree->add(__('Create', null, 'common'));
    } 
    
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }

//  /**
//   * Parses filter request params and gets a collection of UllWiki docs
//   * 
//   */
//  protected function getFilterFromRequest()
//  {
//
//    $this->filter_form = new ullWikiFilterForm;
//    $this->filter_form->bind($this->getRequestParameter('filter'));
//
//    // build query
//    // Querying for records excludes deleted records.
//    $q = new Doctrine_Query();
//    $q->from('UllWiki x');
//
//    // search has to be the first "where" part, because it uses "or" 
//    if ($search = $this->filter_form->getValue('search'))
//    {
//      $cols = array('id', 'subject', 'duplicate_tags_for_search');
//      if ($this->filter_form->getValue('fulltext'))
//      {
//        $cols[] = 'body';
//      }
//      $q = ullGeneratorTools::doctrineSearch($q, $search, $cols);
//    }
//    
//    // TODO: (Klemens 2008-12-16) this should not be necessary,
//    //   because the soft delete behaviour handles this. But the
//    //   softdelete functionality needs the doctrine attribute
//    //   "use_dql_callbacks" enabled, which is not at the moment
//    //   because it has side effects and needs further testing
//    $q->addWhere('x.deleted = ?', false);
//    
//    $q = self::queryReadAccess($q);
//    
//    $this->order = $this->getRequestParameter('order', 'updated_at');
//    $this->order_dir = $this->getRequestParameter('order_dir', 'desc');
//    
//    $orderDir = ($this->order_dir == 'desc') ? 'DESC' : 'ASC';
//
//    switch ($this->order)
//    {
//      case 'creator_user_id':
//        $q->orderBy('x.Creator.display_name ' . $orderDir);
//        break;
//      case 'updator_user_id':
//        $q->orderBy('x.Updator.display_name ' . $orderDir);
//        break;
//      default:
//        $q->orderBy($this->order . ' ' . $orderDir);
//    }
//    
////    printQuery($q->getQuery());
////    var_dump($q->getParams());
////    die;    
//    
//    $this->pager = new Doctrine_Pager(
//      $q, 
//      $this->getRequestParameter('page', 1),
//      sfConfig::get('app_pager_max_per_page')
//    );
//    $docs = $this->pager->execute();
//
//    return ($docs->count()) ? $docs : new UllWiki;
//  }

  /**
   * Gets UllWiki doc according to request param
   * 
   */
  protected function getDocFromRequest()
  {
    $this->forward404Unless($this->getRequestParameter('docid'), 'docid is mandatory!');

    $this->getDocFromRequestOrCreate();
  }
  
  /**
   * Gets UllWiki doc or creates it according to request param
   * 
   */
  protected function getDocFromRequestOrCreate()
  {
    if ($id = $this->getRequestParameter('docid')) 
    {
      $this->doc = Doctrine::getTable('UllWiki')->find($id);
      $this->forward404Unless($this->doc);
    }
    else
    {
      $this->doc = new UllWiki;
    }
  }
  
  
  /** 
   * Handle fulltext search
   * 
   * @return array
   */
  protected function getSearchColumnsForFilter()
  {
    $columns = $this->generator->getTableConfig()->getSearchColumnsAsArray();
    
    if ($this->filter_form->getValue('fulltext'))
    {
      $columns[] = 'body';
    }  
    
    return $columns;
  }  
  
  
  public function modifyQueryForFilter()
  {
    $this->q->addWhere('deleted_at IS NULL');
    
    if(!$this->filter_form->getValue('outdated'))
    {
      $this->q->addWhere('is_outdated = false');
    }
    
    self::queryReadAccess($this->q->getDoctrineQuery());    
  }
  
  
  
  
  public static function queryReadAccess(Doctrine_Query $q)
  {
    if (UllUserTable::hasGroup('MasterAdmins'))
    {
      return $q;
    }
    
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    
    $q->leftJoin('x.UllWikiAccessLevel.UllWikiAccessLevelAccess a');
    $q->leftJoin('a.UllGroup ag');
    
    // check public access
    $where = '
      a.UllPrivilege.slug = ?
        AND ag.display_name = ?  
    ';
    $values = array('read', 'Everyone');
    
    if ($userId)
    {
      // check access for any "logged in user"
      $where .= '
        OR a.UllPrivilege.slug = ?
          AND ag.display_name = ?  
      ';
      $values = array_merge($values, array('read', 'Logged in users'));      
      
      // check group membership
      $where .= '
        OR a.UllPrivilege.slug = ? 
          AND ag.UllUser.id = ?
      ';     
      $values = array_merge($values, array('read', $userId));
    }
    
//    var_dump($where);
//    var_dump($values);
    
    $q->addWhere($where, $values);

    return $q;
  }
  
  /**
   * Query popular tags for the index action
   */
  protected function loadPopularTags()
  {
    $q = new Doctrine_Query;
    $q->from('Tagging tg, tg.Tag t, tg.UllWiki x');
    $q->addWhere('x.is_outdated = ?', false);
    $q->limit(sfConfig::get('app_sfDoctrineActAsTaggablePlugin_limit', 100));
    $q = $this->queryReadAccess($q);
    $this->tags_pop = TagTable::getPopulars($q, array('model' => 'UllWiki'));
    $this->tagurl = str_replace('%25', '%', ull_url_for(array('action' => 'list', 'filter[search]' => '%s')));
  }
}