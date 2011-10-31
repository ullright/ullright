<?php

/**
 * This action extends ullTableTool to add some specific functionality
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllCmsContentBlockActions extends BaseUllGeneratorActions
{  
  
  /**
   * Execute  before each action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllsfActions#ullpreExecute()
   */
  public function preExecute()
  {
    parent::preExecute();
    
    //Add ullCourse stylsheet for all actions
    $path =  '/ullCmsTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }    
  
  /**
   * Executes index action
   *
   */
  public function executeIndex(sfRequest $request)
  {    
    $this->redirect('ullCms/index');
  }    
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_cms_content_block_list');
    
    $return = parent::executeList($request);
    
    $this->setTableToolTemplate('list');
    
    return $return;
  }
  
  public function executeCreate(sfRequest $request)
  {
    $content_type_slug = $request->getParameter('content_type');
    
    if (!$content_type_slug)
    {
      $this->redirect('ullCmsContentBlock/selectContentType');
    }
    
    parent::executeCreate($request);
  }
  
  public function executeSelectContentType()
  {
    $this->types = UllCmsContentTypeTable::findForContentBlocks();
    
    $this->breadcrumbForEdit();
  }

  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_cms_content_block_edit');
    
    $row = $this->getRowFromRequestOrCreate();
    
    $this->generator = new ullCmsContentBlockGenerator($row->UllCmsContentType, 'w');
    
    $this->generator->buildForm($row);
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonCmsContentBlockSaveAndShow($this));
    
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
    
    $this->breadcrumbForEdit();
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
      $row = Doctrine::getTable('UllCmsContentBlock')->findOneById($id);
    }
    else
    {
      $row = new UllCmsContentBlock;
    }
    
    if (!$row->exists())
    {
      $content_type_slug = $this->getRequest()->getParameter('content_type');
      $ullCmsContentType = Doctrine::getTable('UllCmsContentType')->findOneBySlug($content_type_slug);
      
      if (!$ullCmsContentType)
      {
        throw new InvalidArgumentException('Invalid UllCmsContentType::slug given: '. $content_type_slug);
      }
      
      $row->UllCmsContentType = $ullCmsContentType;
    }    
    
    return $row;
  }
  

  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullCmsContentBlockGenerator(null, 'r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for delete action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getDeleteGenerator()
  {
    return new ullCmsContentBlockGenerator(null, 'r', 'list', $this->columns);
  }     
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new ullCmsBreadcrumbTree();
    $breadcrumb_tree->add(__('Result list', null, 'common') . ' ' . __('Content blocks', null, 'ullCmsMessages', 'ullCourseBooking/list'));
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }    

  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new ullCmsBreadcrumbTree();
    $breadcrumb_tree->setEditFlag(true);
    // display result list link only when there is a referer containing 
    //  the list action 
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumb_tree->add(__('Result list', null, 'common') . ' ' . __('Content blocks', null, 'ullCmsMessages', 'ullCourseBooking/list'), $referer);
    }
    else
    {
      $breadcrumb_tree->add(__('Result list', null, 'common') . ' ' . __('Content blocks', null, 'ullCmsMessages', 'ullCourseBooking/list'), 'ullCourseBooking/list');
    }    
    
    if ($this->id) 
    {
      $breadcrumb_tree->add(__('Edit', null, 'common'));
    }
    else
    {
      $breadcrumb_tree->add(__('Create', null, 'common'));
    }
    
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  
  /**
   * Execute delete action
   * 
   * @see BaseUllGeneratorActions#executeDelete($request)
   */
  public function executeDelete(sfRequest $request)
  {
    $this->checkPermission('ull_cms_content_block_delete');
    
    parent::executeDelete($request);
  }
  

  
}
