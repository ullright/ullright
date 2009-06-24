<?php

/**
 * ullVentory actions.
 *
 * @package    ullright
 * @subpackage ullVentory
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllVentoryActions extends ullsfActions
{
 
  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function ullpreExecute()
  {
    $defaultUri = $this->getModuleName() . '/list';
    $this->getUriMemory()->setDefault($defaultUri);  
    
    //Add ullVentory stylsheet for all actions
    $path =  '/ullVentoryTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }  
  
  /**
   * Execute index action
   * 
   */
  public function executeIndex() 
  {
    $this->form = new ullWikiFilterForm;

    // allow ullwiki to be used as a plugin (e.g. ullFlow to ullForms interface)
    $this->return_var = $this->getRequestParameter('return_var');

    $this->breadcrumbForIndex();
  }

  /**
   * Execute list action
   * 
   */
  public function executeList(sfRequest $request) 
  {
    $this->getUriMemory()->setUri();

    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }

    $this->breadcrumbForList();
    
    $this->generator = new ullVentoryGenerator();

    $this->docs = $this->getFilterFromRequest();
    
    $this->generator->buildForm($this->docs);
  }


  /**
   * Execute Show action
   * 
   */
  public function executeShow() 
  {
    $this->getDocFromRequest();

    // allow ullwiki used as a plugin (e.g. ullFlow to ullForms interface)
    if ($this->return_var = $this->getRequestParameter('return_var')) 
    {
       $this->return_url = $this->getUser()->getAttribute('wiki_return_url') 
        . '&' . $this->return_var . '=' . $this->doc->id;
    }
    
    $this->has_no_write_access = $this->getRequestParameter('no_write_access'); 

    $this->breadcrumbForShow();
  }

  /**
   * Execute create action
   * 
   */
  public function executeCreate($request) 
  {
    $this->checkAccess('LoggedIn');
    
    $this->entity = $this->retrieveEntityFromRequest();
    
    $this->form = new UllVentoryCreateForm;
//    $this->form->setDefault('entity', $entity->username);
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'));
      if ($this->form->isValid())
      {
        // TODO: why action "create" and not "createWithType ???
        $this->redirect(url_for('ullVentory/createWithType') . '/' . $this->form->getValue('type') . '/entity/' . $this->entity->username);    
      }
    }
    
    $this->breadcrumbForEdit();
  }

  /**
   * Execute create action
   * 
   */
  public function executeCreateWithType($request) 
  {
    $this->checkAccess('LoggedIn');
    $this->forward404Unless(Doctrine::getTable('UllVentoryItemType')->findOneBySlug($request->getParameter('type')));
    
    $this->forward('ullVentory', 'edit');
  } 
   
  /**
   * Execute edit action
   * 
   */
  public function executeEdit($request) 
  {
    if ($request->hasParameter('inventory_number'))
    {
      $this->doc = $this->getRoute()->getObject();
    }
    else
    {
      $this->doc = new UllVentoryItem;
      $this->entity = $this->retrieveEntityFromRequest();
    }
    
       

//    var_dump($this->item->toArray());die;
//    
//    $this->getDocFromRequestOrCreate();
    
//    $accessType = $this->doc->checkAccess();
//    $this->redirectToNoAccessUnless($accessType);
    
//    if ($accessType == 'r')
//    {
//      $this->redirect('ullWiki/show?docid=' . $this->doc->id . '&no_write_access=true');
//    }

//    var_dump($this->getRequest()->getParameterHolder()->getAll());die;
    
    $this->generator = new ullVentoryGenerator('w', $request->getParameter('type'));
    $this->handleEntityforEdit();
    $this->generator->buildForm($this->doc);
    $this->handleEntityforCreate();    
    
    
    $this->breadcrumbForEdit();
    
    if ($request->isMethod('post'))
    {
//      var_dump($this->getRequest()->getParameterHolder()->getAll());die;
      
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        // == forward junction
        
        // save only
        if ($request->getParameter('action_slug') == 'save_only') 
        {
//          $this->redirect('ullVentory/edit?id=' . $this->doc->id);
          
          $this->redirect($this->generateUrl('ull_ventory_edit', $this->doc));
        }
        
        // save and show
        elseif ($request->getParameter('action_slug') == 'save_show') 
        {
          $this->redirect('ullVentory/show?id=' . $this->doc->id);
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
  public function executeDelete() 
  {
    $this->checkPermission('ull_wiki_delete');
    
    $this->getDocFromRequest();
    $this->doc->delete();
    
    $this->redirect($this->getUriMemory()->getAndDelete('list'));
  }

  public function executeItemModels($request)
  {
//    var_dump($request->getParameterHolder()->getAll());
  
//    $this->getResponse()->setContentType('application/json');
//    $authors = DemoAuthorPeer::retrieveForSelect($request->getParameter('q'), $request->getParameter('limit'));
    
    $q = new Doctrine_Query;
    $q
      
      ->select('mo.id, CONCAT(mo.name, \' \', ma.name, \' \', ti.name) as full_name')
      ->from('UllVentoryItemModel mo, mo.UllVentoryItemManufacturer ma, mo.UllVentoryItemType t, t.Translation ti INDEXBY mo.id')
      ->where('CONCAT(mo.name, \' \', ma.name, \' \', ti.name) LIKE ?', '%' . $request->getParameter('q') . '%')
      // KU 2009-05-07: How about refactoring the following into a resuable component?
      ->addWhere('ti.lang = ?', substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
    ;
//    printQuery($q->getQuery());
//    var_dump($q->getParams());
    $result = $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    
    $models = array();
    foreach ($result as $values)
    {
//      $models[$values['id']] = $values['full_name'];
      $models[] = array('id' => $values['id'], 'name' => $values['full_name']);
    }
    $x['results'] = $models;
     
    return $this->renderText(json_encode($x));
  }
  
  public function executeItemModelsByManufacturer($request)
  {
//    var_dump($request->getParameterHolder()->getAll());
  
//    $this->getResponse()->setContentType('application/json');
//    $authors = DemoAuthorPeer::retrieveForSelect($request->getParameter('q'), $request->getParameter('limit'));
    
    $q = new Doctrine_Query;
    $q
      ->select('mo.id, mo.name')
      ->from('UllVentoryItemModel mo')
    ;
    if ($id = $request->getParameter('ull_ventory_item_manufacturer_id'))
    {
      $q->where('mo.ull_ventory_item_manufacturer_id = ?',$request->getParameter('ull_ventory_item_manufacturer_id'));
    }    
    
//    printQuery($q->getQuery());
//    var_dump($q->getParams());
    $result = $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    
    $models = array();
    foreach ($result as $values)
    {
//      $models[$values['id']] = $values['name'];
      $models[] = array('id' => $values['id'], 'name' => $values['name']);
    }
//    var_dump($models);die;

    return $this->renderText(json_encode($models));
  }  
  
  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $this->breadcrumbTree = new ullVentoryBreadcrumbTree();
  }

  /**
   * Create breadcrumbs for list action
   * 
   */  
  protected function breadcrumbForList() 
  {
    $this->breadcrumbTree = new ullVentoryBreadcrumbTree();
    $this->breadcrumbTree->addDefaultListEntry();
  }  

  /**
   * Create breadcrumbs for show action
   * 
   */  
  protected function breadcrumbForShow() 
  {
    $this->breadcrumbTree = new ullVentoryBreadcrumbTree();    
    // display result list link only when there is a referer containing 
    //  the list action 
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $this->breadcrumbTree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $this->breadcrumbTree->addDefaultListEntry();
    }

    $this->breadcrumbTree->add(__('Show', null, 'common'));    
    $this->breadcrumbTree->add($this->doc->subject);
  }  

  /**
   * Create breadcrumbs for edit action
   * 
   */
  protected function breadcrumbForEdit() 
  {
    $this->breadcrumbTree = new ullVentoryBreadcrumbTree();
    $this->breadcrumbTree->setEditFlag(true);

    // display result list link only when there is a referer containing 
    //  the list action 
    $this->breadcrumbTree->add(__('Result list', null, 'common'), $this->getUriMemory()->get('list'));

    if (isset($this->doc) && $this->doc->exists()) 
    {
      $this->breadcrumbTree->add(__('Edit', null, 'common'));
    } 
    else 
    {
      $this->breadcrumbTree->add(__('Create', null, 'common'));
    } 
  }

  /**
   * Parses filter request params and gets a collection of UllWiki docs
   * 
   */
  protected function getFilterFromRequest()
  {

    $this->filter_form = new ullWikiFilterForm;
//    $this->filter_form->bind($this->getRequestParameter('filter'));

    $q = new Doctrine_Query();
    $q->from('UllVentoryItem x');

    // search has to be the first "where" part, because it uses "or" 
//    if ($search = $this->filter_form->getValue('search'))
//    {
//      $cols = array('id', 'subject', 'duplicate_tags_for_search');
//      if ($this->filter_form->getValue('fulltext'))
//      {
//        $cols[] = 'body';
//      }
//      $q = ullCoreTools::doctrineSearch($q, $search, $cols);
//    }
    
    $this->order = $this->getRequestParameter('order', 'updated_at');
    $this->order_dir = $this->getRequestParameter('order_dir', 'desc');
    
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
        $q->orderBy($this->order . ' ' . $orderDir);
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

    return ($docs->count()) ? $docs : new UllVentoryItem;
  }

//  /**
//   * Gets UllWiki doc according to request param
//   * 
//   */
//  protected function getDocFromRequest()
//  {
//    $this->forward404Unless($this->getRequestParameter('id'), 'id is mandatory!');
//
//    $this->getDocFromRequestOrCreate();
//  }
//  
//  /**
//   * Gets a UllVentoryItem or creates it according to request param
//   * 
//   */
//  protected function getDocFromRequestOrCreate()
//  {
//    if ($id = $this->getRequestParameter('id')) 
//    {
//      $this->doc = Doctrine::getTable('UllVentoryItem')->find($id);
//      $this->forward404Unless($this->doc);
//    }
//    else
//    {
//      $this->doc = new UllVentoryItem;
//    }
//  }

  /**
   * Parse given UllEntity::username and return appropriate UllEntity object
   * @return UllEntity
   */
  protected function retrieveEntityFromRequest()
  {
    if ($ull_entity_username = $this->getRequestParameter('entity'))
    {
      $ull_entity = Doctrine::getTable('UllEntity')->findOneByUsername($ull_entity_username);
      
      if ($ull_entity)
      {
        return $ull_entity;  
      }
    }
    
    // default to status user "stored"
    return Doctrine::getTable('UllVentoryStatusDummyUser')->findOneByUsername('stored');
  }

  /**
   * Set the given user in the form
   * @return none
   */
  protected function handleEntityforCreate()
  {
    if (!$this->doc->exists())
    {
      $this->generator->getForm()->setDefault('ull_entity_id', $this->entity->id);
    }
  }  
  
  protected function handleEntityforEdit()
  {
    if ($this->doc->exists())
    {
      $cc = $this->generator->getColumnsConfig();
      $cc['ull_entity_id']->removeWidgetOption('is_hidden');
      $cc['ull_entity_id']->setAccess('r');
      $this->generator->setColumnsConfig($cc);
    }
  }   
}