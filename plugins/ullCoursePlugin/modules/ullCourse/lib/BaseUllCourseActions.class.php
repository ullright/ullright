<?php

/**
 * ullCourse actions.
 * 
 * This action extends ullTableTool to add some specific functionality
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllCourseActions extends BaseUllGeneratorActions
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
    $path =  '/ullCourseTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
    $this->getResponse()->addStylesheet($path, 'last', array('media' => 'all'));
  }    
  
  /**
   * Executes list action
   *
   * @param sfWebRequest $request
   */
  public function executeList(sfRequest $request) 
  {
    $this->checkPermission('ull_course_list');
    
    parent::executeList($request);

    $this->setTableToolTemplate('list');
  }
  
  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_course_edit');
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonNewsSaveAndShow($this));
    
    parent::executeEdit($request);

    $this->setTableToolTemplate('edit');
  }
  
  public function executeShow(sfRequest $request) 
  {
    $this->checkPermission('ull_course_show');
    
    $doc = $this->getDocFromRequest();
    
    
    $this->setVar('doc', $doc, true);
  }
  
  public function executeSelectPayment(sfRequest $request)
  {
    $this->checkPermission('ull_course_select_payment');    
    
    $doc = $this->getDocFromRequest();
    
    $this->setVar('doc', $doc, true);
  }  
  
  
  public function executeConfirmation(sfRequest $request)
  {
    $this->checkPermission('ull_course_confirmation');    
    
    $doc = $this->getDocFromRequest();
    
    $this->setVar('doc', $doc, true);
  }

  
  
  
  /**
   * Gets the doc according to request param
   * 
   */
  protected function getDocFromRequest()
  {
    $slug = $this->getRequestParameter('slug');
    $doc = Doctrine::getTable('UllCourse')->findOneBySlug($slug);
    $this->forward404Unless($doc);
    
    return $doc;
  }
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullCourseGenerator('r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullCourseGenerator('w');
  } 

//  /**
//   * Handles breadcrumb for list action
//   */
//  protected function breadcrumbForList()
//  {
//    $breadcrumb_tree = new breadcrumbTree();
//    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
//    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('News entries', null, 'ullNewsMessages'));
//    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullNews/list');
//    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
//  }  
//  
//  /**
//   * Handles breadcrumb for edit action
//   *
//   */
//  protected function breadcrumbForEdit()
//  {
//    $breadcrumb_tree = new breadcrumbTree();
//    $breadcrumb_tree->setEditFlag(true);
//    $breadcrumb_tree->add('Admin' . ' ' . __('Home', null, 'common'), 'ullAdmin/index');
//    $breadcrumb_tree->add(__('Manage', null, 'common') . ' ' . __('News entries', null, 'ullNewsMessages'));
//    // display result list link only when there is a referer containing 
//    //  the list action 
//    if ($referer = $this->getUriMemory()->get('list'))
//    {
//      $breadcrumb_tree->add(__('Result list', null, 'common'), $referer);
//    }
//    else
//    {
//      $breadcrumb_tree->addDefaultListEntry();
//    }    
//    
////    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullUser/list');  
//    if ($this->id) 
//    {
//      $breadcrumb_tree->add(__('Edit', null, 'common'));
//    }
//    else
//    {
//      $breadcrumb_tree->add(__('Create', null, 'common'));
//    }
//    
//    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
//  }  
  
  
  /**
   * Execute delete action
   * 
   * @see BaseUllGeneratorActions#executeDelete($request)
   */
  public function executeDelete(sfRequest $request)
  {
    $this->checkPermission('ull_course_delete');
    
    parent::executeDelete($request);
  }
  
  /**
   * Define generator for delete action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getDeleteGenerator()
  {
    return new ullCourseGenerator('r', 'list', $this->columns);
  } 
  
}
