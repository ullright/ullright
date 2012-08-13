<?php

/**
 * Cms actions.
 * 
 * This action extends ullTableTool to add some specific functionality
 * for ullCms
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllCmsActions extends BaseUllGeneratorActions
{  
  
  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    if ($this->getActionName() != 'show')
    {
      //Add ullCms stylsheet for all actions
      $path =  '/ullCmsTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
      $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
    }
  }    
  
  /**
   * Executes index action
   *
   * @param sfWebRequest $request
   */
  public function executeIndex(sfRequest $request) 
  {
    $this->checkPermission('ull_cms_index');
    
    $this->form = new ullFilterForm;
    
    $this->named_queries = new ullNamedQueriesUllCms;
    
    $this->breadcrumbForIndex();
  }    
  
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_cms_list');
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }   

  
  /**
   * Execute list action
   *
   * @param sfWebRequest $request
   */
  public function executeShow(sfRequest $request)
  {
    $this->checkPermission('ull_cms_show');
    
    $this->forward404Unless($this->getRoute() instanceof sfDoctrineRoute);
    
    $this->getUriMemory()->setUri();
    
    $this->doc = $this->getRoute()->getObject();
    
    $this->setVar('title', $this->doc->title);
    $this->setVar('body', $this->doc->body, true);
    $this->setVar('doc', $this->doc, true);
    
    $this->getResponse()->setTitle($this->doc->title);
    
    $this->loadMenus();
    
    $this->loadCustomAction($request);
    
    $this->loadCustomTemplate();
    
    $this->image_path = UllCmsPageTable::getImagePath();
  }
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullCmsGenerator('r', 'list', $this->columns);
  }  
  
  
  
  /**
   * Select content type for page upon creation
   */
  public function executeSelectContentType()
  {
    $this->types = UllCmsContentTypeTable::findForPages();
    
    $this->breadcrumbForEdit();
  }
  
  
  /**
   * Create action
   * 
   * @see BaseUllGeneratorActions::executeCreate()
   */
  public function executeCreate(sfRequest $request)
  {
    // If we have only on content type, use it 
    $single = UllCmsContentTypeTable::findOneAndOnlyPageType();
    if ($single)
    {
      $this->getRequest()->setParameter('content_type', $single->slug);
      
      return parent::executeCreate($request);
    }
    
    // Else: check if a content type was provided
    // If not provided redirect to type selection
    $content_type_slug = $request->getParameter('content_type');
    
    if (!$content_type_slug)
    {
      $this->redirect('ullCms/selectContentType');
    }
    
    return parent::executeCreate($request);
  }  
    
  
  /**
   * Executes edit action
   *
   * @param sfWebRequest $request
   */
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_cms_edit');
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonCmsSaveAndShow($this));
    
    if (ullCoreTools::isModuleEnabled('ullNews'))
    {
      $this->registerEditActionButton(new ullGeneratorEditActionButtonCmsSaveAndCreateNews($this, false));
    }      
        
    $row = $this->getRowFromRequestOrCreate();
    
    $this->generator = new ullCmsPageGenerator($row->UllCmsContentType, 'w');
    
    $this->generator->buildForm($row);
    
//    var_dump($this->generator->getColumnsConfig()->debug());die;
//    var_dump($this->generator->getForm()->debug());die;

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
        $this->processEditActionButtons($row, $request);
        
        return $this->executePostSave($row, $request);
      }
    }
    
    $form_uri = $this->getEditFormUri();
    
    if (!$row->exists())
    {
      $form_uri = ullCoreTools::appendParamsToUri($form_uri,
        'content_type=' . $row->UllCmsContentType->slug); 
    }
    
    $this->setVar('form_uri', $form_uri);

    $this->setVar('generator', $this->generator, true);
    
    $this->setTableToolTemplate('edit');
    
    $this->cultures = ullGenerator::getDefaultCultures();
    
    $this->breadcrumbForEdit();
  }  
  
  /**
   * Execute actions to be performed after successfully saving the object
   * 
   * Usually used for redirects
   * 
   * @param Doctrine_Record $row
   * @param sfRequest $request
   * 
   * @return boolean
   */
  protected function executePostSave(Doctrine_Record $row, sfRequest $request)
  {
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
      $createUrl = $this->create_base_uri;
      
      if ($contentType = $request->getParameter('content_type'));
      {
        $createUrl = ullCoreTools::appendParamsToUri(
          $createUrl, 
          'content_type=' . $contentType
        );
      }
      
      $this->redirect($createUrl);
    }
            
    $this->redirect($this->getUriMemory()->getAndDelete('list'));   

    return true;
  }  
  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullCmsGenerator('w');
  }

  
  /**
   * Handle content type in create mode
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions::getRowFromRequestOrCreate()
   */
  protected function getRowFromRequestOrCreate()
  {
    if ($id = $this->getRequest()->getParameter('id'))
    {
      $row = Doctrine::getTable('UllCmsPage')->findOneById($id);
    }
    else
    {
      $row = new UllCmsPage;
    }
    
    if (!$row->exists())
    {
      $content_type_slug = $this->getRequest()->getParameter('content_type');
      
      if (!$content_type_slug)
      {
        $ullCmsContentType = new ullCmsContentType;
        $ullCmsContentType->type = 'page';
      }
      else
      {
        $ullCmsContentType = Doctrine::getTable('UllCmsContentType')->findOneBySlug($content_type_slug);
      }
      
      if (!$ullCmsContentType)
      {
        throw new InvalidArgumentException('Invalid UllCmsContentType::slug given: '. $content_type_slug);
      }
      
      $row->UllCmsContentType = $ullCmsContentType;
    }    
    
    return $row;
  }  
  
  /**
   * Load menues
   * 
   * @return unknown_type
   */
  protected function loadMenus()
  {
    $tree = UllCmsItemTable::getSubMenuFor('main-menu', $this->doc->slug);
    
    $this->setVar('sidebar_menu', new ullTreeMenuRenderer($tree), true);    
  }
  
  /**
   * Execute delete action
   * 
   * @see BaseUllGeneratorActions#executeDelete($request)
   */
  public function executeDelete(sfRequest $request)
  {
    $this->checkPermission('ull_cms_delete');
    
    parent::executeDelete($request);
  }  
  
  /**
   * Define generator for delete action
   * 
   * @see BaseUllGeneratorActions#getDeleteGenerator()
   */
  protected function getDeleteGenerator()
  {
    return new ullCmsGenerator('r', 'list', $this->columns);
  } 
  
  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $breadcrumbTree = new ullCmsBreadcrumbTree();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
    
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumbTree = new ullCmsBreadcrumbTree();
    $breadcrumbTree->add(__('Result list', null, 'common'), 'ullCms/list');
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }  
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumbTree = new ullCmsBreadcrumbTree();
    $breadcrumbTree->setEditFlag(true);
    // display result list link only when there is a referer containing 
    //  the list action 
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumbTree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumbTree->addDefaultListEntry();
    }    
    
//    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullUser/list');  
    if ($this->id) 
    {
      $breadcrumbTree->add(__('Edit', null, 'common'));
    }
    else
    {
      $breadcrumbTree->add(__('Create', null, 'common'));
    }
    
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  } 
  
  
  /**
   * Look for a custom page-specifig action
   * 
   * Put an action method named executeShow$slug in 
   * apps/frontend/modules/ullCms/actions/actions.class.php
   *
   * @param sfRequest $request
   */
  protected function loadCustomAction(sfRequest $request)
  {
    $method = 'executeShow' . sfInflector::classify($this->doc->slug);
    
    if (method_exists($this, $method))
    {
      return call_user_func_array(array($this, $method), array('request' => $request));
    }
  }
  
  
  /**
   * Look for a custom page-specific template 
   * 
   * Put the template in apps/frontend/modules/ullCms/templates/
   * and name it $slugSuccess.php
   *
   */
  protected function loadCustomTemplate()
  {
    $filename = sfConfig::get('sf_app_dir') . DIRECTORY_SEPARATOR . 
      'modules'. DIRECTORY_SEPARATOR . 
      'ullCms' . DIRECTORY_SEPARATOR .
      'templates' . DIRECTORY_SEPARATOR .
      $this->doc->slug; 
    
//     var_dump($filename);

    if (file_exists($filename . 'Success.php'))
    {
      $this->setTemplate($filename);
    }
  }
  
}
