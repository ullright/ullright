<?php

/**
 * ullCourseBooking actions.
 * 
 * This action extends ullTableTool to add some specific functionality
 *
 * @package    ullright
 * @subpackage ullCms
 * @author     Klemens Ullmann-Marx
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

class BaseUllCourseBookingActions extends BaseUllGeneratorActions
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
    $this->checkPermission('ull_course_booking_list');
    
    $this->course = null;
    $filter = $request->getParameter('filter');
    if (isset($filter['ull_course_id']))
    {
      $courseId = $filter['ull_course_id'];
      $this->course = Doctrine::getTable('UllCourse')->findOneById($courseId); 
    }
    
    return parent::executeList($request);
  }
  
  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_course_booking_edit');
    
    return parent::executeEdit($request);
  }
  
  protected function modifyGeneratorBeforeBuildForm($row)
  {
    if ('list' == $this->getActionName())
    {
      $columnsConfig = $this->generator->getColumnsConfig();
      $columnsConfig['UllCourse->name']
        ->setMetaWidgetClassName('ullMetaWidgetLink')
        ->removeOption('show_search_box', true)
        ->removeWidgetOption('add_empty', true)
      ;      
      $columnsConfig['UllCourse->is_active']
        ->disable()
      ;
      if ($this->course)
      {
        $columnsConfig['UllCourse->name']
          ->disable()
        ;
      }
      
    }
  }
  
  protected function modifyGeneratorAfterBuildForm($row)
  {
    // check if the selected tarif is valid for the selected course
    $form = $this->generator->getForm();
    $form->mergePostValidator(
      new ullValidatorSchemaUllCourseTariff('ull_course_tariff_id', 'ull_course_id')
    );
    
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
    $return = parent::executePostSave($row, $request);
    
    $booking->sendConfirmationMail();
    
    return $return;
  }  

  public function executeShow(sfRequest $request) 
  {
    $this->checkPermission('ull_course_booking_show');
    
    $doc = $this->getDocFromRequest();
    
    $this->setVar('doc', $doc, true);
  }  
  
  public function executeFindTariffsForCourse(sfRequest $request)
  {
    $this->checkPermission('ull_course_booking_edit');
    
    $courseId = $request->getParameter('course_id');
    
    $tariffIds = UllCourseTariffTable::findIdsByCourseId($courseId);
    
    return $this->renderText(implode(',', $tariffIds));
  }
  
//  /**
//   * Gets the doc according to request param
//   * 
//   */
//  protected function getDocFromRequest()
//  {
//    $slug = $this->getRequestParameter('slug');
//    $doc = Doctrine::getTable('UllCourse')->findOneBySlug($slug);
//    $this->forward404Unless($doc);
//    
//    return $doc;
//  }
  
  /**
   * Define generator for list action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getListGenerator()
  {
    return new ullTableToolGenerator('UllCourseBooking', 'r', 'list', $this->columns);
  }  
  
  /**
   * Define generator for edit action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getEditGenerator()
  {
    return new ullTableToolGenerator('UllCourseBooking', 'w');
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
    $this->checkPermission('ull_course_booking_delete');
    
    parent::executeDelete($request);
  }
  
  /**
   * Define generator for delete action
   * 
   * @see plugins/ullCorePlugin/lib/BaseUllGeneratorActions#getListGenerator()
   */
  protected function getDeleteGenerator()
  {
    return new ullTableToolGenerator('UllCourseBooking', 'r', 'list', $this->columns);
  }   
  
}
