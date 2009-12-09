<?php

/**
 * ullVentory actions.
 *
 * @package    ullright
 * @subpackage ullVentory
 * @author     Klemens Ullmann-Marx <klemens.ullmann-marx@ull.at>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllVentoryActions extends BaseUllGeneratorActions
{
 
  /**
   * Everything here is executed before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#preExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    //Add ullVentory stylsheet for all actions
    $path =  '/ullVentoryTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));    
  }

  
  /**
   * Execute index action
   * 
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->checkPermission('ull_ventory_index');
    
    $this->form = new ullVentoryFilterForm;
    
    $this->named_queries = new ullNamedQueriesUllVentory;

    $this->breadcrumbForIndex();
  }

  
  /**
   * Execute list action
   * 
   */
  public function executeList(sfRequest $request) 
  {
//    var_dump($_REQUEST);
//    var_dump($this->getRequest()->getParameterHolder()->getAll());
//    die;
    
    $this->checkPermission('ull_ventory_list');
    
    $this->getUriMemory()->setUri();

    if ($request->isMethod('post'))
    {
      $this->ull_reqpass_redirect();
    }
    
    $this->breadcrumbForList();
    
    $this->generator = new ullVentoryGenerator();

    $this->named_queries = new ullNamedQueriesUllVentory;

    $this->docs = $this->getFilterFromRequest();
    
    if($this->entity)
    {
      $this->appendToTitle($this->entity);
    }
    
    $this->redirectToEditIfSingleResult();

    $this->generator->buildForm($this->docs);
    
    $filterParam = $request->getParameter('filter');
    
    $this->display_mass_change_owner_button =
      (is_array($filterParam) && isset($filterParam['ull_entity_id'])) ? true : false;
     
  }
  
  
  /**
   * Shortcut: redirect directly to edit action if we have a single result
   * 
   * @return none
   */
  protected function redirectToEditIfSingleResult()
  {
    if (count($this->docs) == 1 && $this->getRequestParameter('single_redirect', 'true') == 'true')
    {
      $this->getUser()->setFlash('message', __(
        'Redirected from the result list because there was only a single result. Click on "result list" in the breadcrumb navigation above to return to the list view.', 
        null, 'common'));
      $this->getUriMemory()->append('single_redirect=false');
      $this->redirect('ullVentory/edit?inventory_number=' . $this->docs[0]->inventory_number);
    }
  }
  
  
  public function executeToggleInventoryTaking(sfRequest $request)
  {
    $this->checkPermission('ull_ventory_edit');
    
    $this->doc = $this->getRoute()->getObject();
    $this->doc->toggleInventoryTaking();
    
    $this->redirect($this->getUriMemory()->getAndDelete('list'));
  }


  /**
   * Execute create action
   * 
   */
  public function executeCreate(sfRequest $request) 
  {
    $this->checkPermission('ull_ventory_create');
    
    $this->entity = $this->retrieveEntityFromRequest();
    
    $this->form = new UllVentoryCreateForm;
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'));
      if ($this->form->isValid())
      {
        $this->redirect(url_for('ullVentory/createWithType') . '/' . $this->form->getValue('type') . '/entity/' . $this->entity->username);    
      }
    }
    
    $this->breadcrumbForEdit();
  }

  
  /**
   * Execute create action
   * 
   */
  public function executeCreateWithType(sfRequest $request) 
  {
    $this->checkPermission('ull_ventory_create');
    $this->forward404Unless(Doctrine::getTable('UllVentoryItemType')->findOneBySlug($request->getParameter('type')));
    
    $this->forward('ullVentory', 'edit');
  } 
   
  
  /**
   * Execute edit action
   * 
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_ventory_edit');
    
    if ($request->hasParameter('inventory_number'))
    {
      $this->getItemFromRequest();
      $this->entity = $this->doc->UllEntity;
      $this->appendToTitle($this->entity);
      $this->appendToTitle($this->doc);
    }
    else
    {
      $this->doc = new UllVentoryItem;
      $this->entity = $this->retrieveEntityFromRequest();
      $this->getResponse()->setTitle(
        $this->getModuleName() . 
        ' - ' .
        __('Create', null, 'common')
      );
      $this->appendToTitle($this->entity);
    }
    
    $this->generator = new ullVentoryGenerator($request->getParameter('type'));
    $this->generator->buildForm($this->doc);
    $this->handleEntityforCreate();    
    
    $this->breadcrumbForEdit();
    
    if ($request->isMethod('post'))
    {
      
//      var_dump($_REQUEST);
//      var_dump($this->getRequest()->getParameterHolder()->getAll());
//      die;

      if ($this->handlePresetLoading($request)) { return; } 
      
      if ($this->generator->getForm()->bindAndSave($request->getParameter('fields')))
      {
        // == forward junction
        
        // save only
        if ($request->getParameter('action_slug') == 'save_only') 
        {
          
          $this->redirect($this->generateUrl('ull_ventory_edit', $this->doc));
        }
        
        // save and show
        elseif ($request->getParameter('action_slug') == 'save_show') 
        {
          $this->redirect('ullVentory/show?id=' . $this->doc->id);
        } 
        
        // save and new
        elseif ($request->getParameter('action_slug') == 'save_new') 
        {
          $this->redirect('ullVentory/create' . ($request->hasParameter('entity') ? '?entity=' . $request->getParameter('entity') : ''));
        }         
        
        // use the default referer
        else
        {
          $this->redirect($this->getUriMemory()->getAndDelete('list'));
        }
      }
      else
      {
//        var_dump($this->generator->getForm()->getErrorSchema());
      }
    }
//    echo $this->generator->getForm()->debug();
  }
  
  
  /**
   * Loads attribute presets for the current model and redisplays the form
   * 
   * @param $request
   * @return boolean
   */
  protected function handlePresetLoading(sfRequest $request)
  {
    if ($request->getParameter('action_slug') == 'load_presets')
    {
      $fields = $request->getParameter('fields');
      $model = Doctrine::getTable('UllVentoryItemModel')->findOneById($fields['ull_ventory_item_model_id']);

      if (isset($fields['attributes']))
      {
        foreach ($fields['attributes'] as $key => $attribute)
        {
          if (!$attribute['value'])
          {
            $fields['attributes'][$key]['value'] =
                UllVentoryItemAttributePresetTable::findValueByModelIdAndTypeAttributeId($model->id, $attribute['ull_ventory_item_type_attribute_id']);  
          }
        }
      }
      
      $this->generator->getForm()->setDefaults($fields);
      return true;                
    }    
  }

  
  /**
   * Setup ullGenerator
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getDeleteGenerator() 
  { 
    return new ullVentoryGenerator();
  }  
  
  
  /**
   * Ajax action for item models select box
   * 
   * @param sfRequest $request
   * @return unknown_type
   */
  public function executeItemModels(sfRequest $request)
  {
//    var_dump($request->getParameterHolder()->getAll());
//    die;
  
//    $this->getResponse()->setContentType('application/json');
    
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
      $models[] = array('id' => $values['id'], 'name' => $values['full_name']);
    }
    $x['results'] = $models;
     
    return $this->renderText(json_encode($x));
  }
  
  
  /**
   * Ajax action for item models select box
   * 
   * @param sfRequest $request
   * @return unknown_type
   */
  public function executeItemModelsByManufacturer(sfRequest $request)
  {
//    var_dump($request->getParameterHolder()->getAll());
//    die;
  
    $q = new Doctrine_Query;
    $q
      ->select('mo.id, mo.name')
      ->from('UllVentoryItemModel mo')
    ;
    if ($id = $request->getParameter('ull_ventory_item_manufacturer_id'))
    {
      $q->where('mo.ull_ventory_item_manufacturer_id = ?',$id);
    }
    if ($id = $request->getParameter('ull_ventory_item_type_id'))
    {
      $q->addWhere('mo.ull_ventory_item_type_id = ?',$id);
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
  
  
  //          _______  _______  _______  _______  _______
  //        (  ____ \(  ____ \(  ___  )(  ____ )(  ____ \|\     /|
  //        | (    \/| (    \/| (   ) || (    )|| (    \/| )   ( |
  //        | (_____ | (__    | (___) || (____)|| |      | (___) |
  //        (_____  )|  __)   |  ___  ||     __)| |      |  ___  |
  //              ) || (      | (   ) || (\ (   | |      | (   ) |
  //        /\____) || (____/\| )   ( || ) \ \__| (____/\| )   ( |
  //        \_______)(_______/|/     \||/   \__/(_______/|/     \|
  //
  
  /**
   * This function builds a search form utilizing the ullSearch
   * framework, see the individual classes for reference.
   * If it handles a post request, it builds the actual search object
   * and forwards to the list action.
   * 
   * @param $request The current request
   */
  public function executeSearch(sfRequest $request)
  {
    $this->checkPermission('ull_ventory_list');
    
    $this->moduleName = $request->getParameter('module');
    $this->modelName = 'UllVentoryItem';
    $this->getUriMemory()->setUri('search');
    $this->breadcrumbForSearch();
    $searchConfig = ullSearchConfig::loadSearchConfig('ullVentoryItem');

    $doRebind = ullSearchActionHelper::handleAddOrRemoveCriterionButtons($request, $this->getUser());

    $searchGenerator = new ullVentorySearchGenerator($searchConfig->getAllSearchableColumns(), $this->modelName);
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
        $search = new ullVentorySearch();
        ullSearchActionHelper::addTransformedCriteriaToSearch($search, $searchFormEntries,
          $this->searchForm->getGenerator()->getForm()->getValues());
         
        $this->getUser()->setAttribute('ullVentoryGenerator_ullSearch', $search);
        $this->redirect('ullVentory/list?query=custom');
      }
    }
  }
  
  
  /**
   * Mass change owner action
   * 
   *  Transfer all items from one owner to a new owner 
   *  
   * @param $request
   * @return none
   */
  public function executeMassChangeOwner(sfRequest $request)
  {
    $this->checkPermission('ull_ventory_edit');
    
    $this->oldEntityId = $request->getParameter('oldEntityId');
    $this->redirectUnless(($this->oldEntityId !== null), 'ullVentory/list');
    
    $oldEntity = Doctrine::getTable('UllEntity')->findOneById($this->oldEntityId);
    $this->redirectUnless(($oldEntity !== false), 'ullVentory/list');
    
    $this->form = new ullVentoryMassChangeOwnerForm();
    $this->oldEntityDisplayName = (string)$oldEntity;
    $this->breadcrumbForMassChangeOwner();
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'));
      if ($this->form->isValid())
      {
        $newEntityId = $this->form->getValue('ull_new_owner_entity_id');
        //safety check new id
        $this->redirectUnless(
          (Doctrine::getTable('UllEntity')->findOneById($newEntityId) !== false), 'ullVentory/list');
        
        $connection = Doctrine_Manager::connection();
        try
        {
          $connection->beginTransaction();
            
          $items = Doctrine::getTable('UllVentoryItem')->findByUllEntityId($this->oldEntityId);
          foreach ($items as $item)
          {
            $item->ull_entity_id = $newEntityId;
            
            $memory = new UllVentoryItemMemory();
            $memory->source_ull_entity_id = $this->oldEntityId;
            $memory->target_ull_entity_id = $newEntityId;
            $memory->transfer_at = date('Y-m-d');
            $memory->comment = $this->form->getValue('ull_change_comment');
            
            $item->UllVentoryItemMemory[] = $memory;
            $item->save();
          }
          
          $connection->commit();
        }
        catch (Doctrine_Exception $e)
        {
          $connection->rollback();
          throw $e;
        }
      
        $this->redirect('ullVentory/list');    
      }
    }
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
   * Create breadcrumbs for massChangeOwner action
   * 
   */ 
  protected function breadcrumbForMassChangeOwner() 
  {
    $this->breadcrumbTree = new ullVentoryBreadcrumbTree();
    $this->breadcrumbTree->addDefaultListEntry();
    $this->breadcrumbTree->add(__('Change owner', null, 'ullVentoryMessages'));
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

//    var_dump($this->getUriMemory()->get('list'));die;
    
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
   * Handles breadcrumb for search
   */
  protected function breadcrumbForSearch()
  {
    $this->breadcrumbTree = new ullVentoryBreadcrumbTree();
    $this->breadcrumbTree->add(__('Advanced search', null, 'common'), 'ullVentory/search');
  }
  
  
  /**
   * Apply custom modifications to the query
   *  
   * @return none
   */
  protected function modifyQueryForFilter()
  {
    //filter per entity
    if ($ullEntityId = $this->filter_form->getValue('ull_entity_id'))
    {
      $this->q->addWhere('x.ull_entity_id = ?', $ullEntityId);
      $this->entity = Doctrine::getTable('UllEntity')->findOneById($ullEntityId);
      
      $this->ull_filter->add('filter[ull_entity_id]', __('Owner', null, 'common') . ': ' . $this->entity);
    }
    else
    {
      $this->entity = null;
    }   
  } 

  


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
  
  
  /**
   * Retrieve ullVentoryItem by given inventory_number
   * 
   * @return none
   */
  protected function getItemFromRequest()
  {
    if (!$itemId = $this->getRequestParameter('inventory_number'))
    {
      throw new InvalidArgumentException('The "inventory_number" parameter is empty');
    }
 
    $q = new Doctrine_Query();
    $q
      //->select('x.*, a1.id, ta1.id, t.id, mo.id, ct1.class, v.value')
      ->from('
        UllVentoryItem x, 
        x.UllVentoryItemModel mo, 
        mo.UllVentoryItemType t, t.Translation tt,
        t.UllVentoryItemTypeAttribute ta1,
        ta1.UllVentoryItemAttribute a1,
        a1.UllColumnType ct1,
        mo.UllVentoryItemManufacturer ma,   
        x.UllEntity e,
        x.UllVentoryItemAttributeValue v,
        v.UllVentoryItemTypeAttribute ta2,
        ta2.UllVentoryItemAttribute a2')
      ->where('x.inventory_number = ?', $itemId);
    
    $this->doc = $q->fetchOne();
    
    $this->forward404Unless($this->doc);
  }
  
  
  /**
   * Configure the ullFilter class name
   * 
   * @return string
   */
  public function getUllFilterClassName()
  {
    return 'ullVentoryFilterForm';
  }    
  
}