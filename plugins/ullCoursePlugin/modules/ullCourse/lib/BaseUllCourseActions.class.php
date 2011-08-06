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
    
    //Add ullCourse stylesheet for all actions
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
    
    return parent::executeList($request);
  }
  
  /**
   * Show only active courses in offering 
   */
  protected function modifyQueryForFilter()
  {
    if ('offering' == $this->getActionName())
    {
      $this->q->addWhere('x.is_active = ?', true);
    }
  }
  
  
  public function executeEdit(sfRequest $request) 
  {
    $this->checkPermission('ull_course_edit');
    
    $this->registerEditActionButton(new ullGeneratorEditActionButtonNewsSaveAndShow($this));
    
    $this->setTableToolTemplate('edit');
    
    return parent::executeEdit($request);
  }

  public function executeShow(sfRequest $request) 
  {
    $this->checkPermission('ull_course_show');
    
    $doc = $this->getDocFromRequest();
    
    $this->setVar('doc', $doc, true);
  }  
  
  /**
   * Public offering of courses
   * 
   * @param sfRequest $request
   */
  public function executeOffering(sfRequest $request) 
  {
    $this->checkPermission('ull_course_offering');
    
    return  parent::executeList($request);    
  }
  
  public function executeSelectTariff(sfRequest $request)
  {
    $this->checkPermission('ull_course_select_tariff');   

    $doc = $this->getDocFromRequest();
    
    $this->setVar('doc', $doc, true);
  }
  
  /**
   * Course information sheet
   * @param sfRequest $request
   */
  public function executeInfo(sfRequest $request)
  {
    $this->checkPermission('ull_course_info');   

    $course = $this->getDocFromRequest();
    
    $columns = array(
        'UllUser->last_name_first',
        'UllUser->email',
        'UllUser->mobile_number',
        'created_at',
        'is_paid',
        'comment',
        'UllCourse->is_active',
      );
    
    $columnsConfig = $generator->getColumnsConfig();
    $columnsConfig
      ->disableAllExcept(array(
        'UllUser->last_name_first',
        'UllUser->email',
        'UllUser->mobile_number',
        'created_at',
        'is_paid',
        'comment',
      ))
    ;
    $columnsConfig['UllUser->last_name_first']
      ->setLabel('Name', null, 'common')
      ->setWidgetOption('nowrap', true)
    ;    
    $columnsConfig['UllUser->email']
      ->setLabel('Email', null, 'common')
    ;    
    $columnsConfig['UllUser->mobile_number']
      ->setLabel('Mobile number', null, 'common')
    ;
    $columnsConfig['is_paid']
      ->setAjaxUpdate(false)
    ;
    
    $bookings = UllCourseBookingTable::findByCourseOrderedByUserName($course->id);
    
    $generator->buildForm($bookings);

    $this->setVar('course', $course, true);
    $this->setVar('generator', $generator, true);
  }  
  
//  public function executeSelectTariffX(sfRequest $request)
//  {
//    $this->checkPermission('ull_course_select_tariff');    
//    
//    $course = $this->getDocFromRequest();
//    
//    $generator = new ullTableToolGenerator('UllCourseBooking', 'w', 'edit');
//    
//    $cc = $generator->getColumnsConfig();
//    
//    $cc->disableAllExcept(array(
//      'ull_course_tariff_id',
//      'comment',
//    ));
//    
//    $booking = new UllCourseBooking();
//    $booking->UllCourse = $course;
//    $booking->UllUser = UllUserTable::findLoggedInUser();
//    
//    $generator->buildForm($booking);
//    
//    $this->setVar('generator', $generator, true);
//  }  
  
//  public function executeSelectPayment(sfRequest $request)
//  {
//    $this->checkPermission('ull_course_select_payment');    
//    
//    $doc = $this->getDocFromRequest();
//    
//    $this->setVar('doc', $doc, true);
//  }  
  
  
  public function executeConfirmation(sfRequest $request)
  {
    $this->checkPermission('ull_course_confirmation');

    $course = $this->getDocFromRequest();
    
    $tariff = $this->getTariffFromRequest($course);
    
    $generator = new ullTableToolGenerator('UllCourseBooking', 'w', 'edit');
    
    $cc = $generator->getColumnsConfig();
    
//    var_dump($cc);die;
    
    $cc['are_terms_of_use_accepted']
      ->setAccess('w')
      ->setIsRequired(true)
    ;
    
//    $cc->disableAllExcept(array(
//      'ull_course_tariff_id',
//      'comment',
//    ));
    
    $booking = new UllCourseBooking();
    $booking->UllCourse = $course;
    $booking->UllCourseTariff = $tariff;
    $booking->UllUser = UllUserTable::findLoggedInUser();
    
    $generator->buildForm($booking);
    
    if ($request->isMethod('post'))
    {
      // TODO: investigate why it is necessary to have required fields
      //   explicitly in the values. Why are given defaults ignored?
      if ($generator->getForm()->bindAndSave(
        array_merge(
          $generator->getForm()->getDefaults(),
          $request->getParameter('fields')
        )
      ))
      { 
//        $this->dispatcher->connect('ull_course.booked', array('ullCourseActions', 'listenToBookedEvent'));
        
        $booking->sendConfirmationMail();
        
        $this->notifyBookedEvent($booking);
        
        $this->redirect('ullCourse/booked');
      }
    }
    
    $this->setVar('form', $generator->getForm(), true);
    $this->setVar('generator', $generator, true);
    
    $this->setVar('course', $course, true);
    $this->setVar('tariff', $tariff, true);
  }


  
  /**
   * Notify a successful booking e.g. for mailing
   * 
   * @param UllCourseBooking $booking
   */
  protected function notifyBookedEvent(UllCourseBooking $booking)
  {
      sfContext::getInstance()->getEventDispatcher()->notify(
      new sfEvent($this, 'ull_course.booked', array(
        'booking'    => $booking, 
      ))
    ); 
  }
  
//  public static function listenToBookedEvent(sfEvent $event)
//  {
//    $params = $event->getParameters();
//    $booking = $params['booking'];
//    
//    $event->
//    
//  }
  
  public function executeBooked(sfRequest $request)
  {
    
  }
  
  /**
   * Show a list of trainers
   * 
   * @param sfRequest $request
   */
  public function executeTrainers(sfRequest $request)
  {
    $this->trainers = UllUserTable::findByGroup('Trainers');
  }
  
  
  /**
   * Show a trainer popup
   * 
   * @param sfRequest $request
   */
  public function executeTrainer(sfRequest $request)
  {
    $username = $request->getParameter('username');
    
    $trainer = Doctrine::getTable('UllUser')->findOneByUsername($username);
    $this->forward404Unless($trainer);
    
    if (!UllUserTable::hasGroup('Trainers', $trainer['id'], false))
    {
      $this->forward404('The given user is not a trainer: ' . $trainer['display_name']);
    }
    
    $this->setEmptyLayout();
    
    $this->trainer = $trainer;
  }  
  
  /**
   * Get tariff from request and check if the tarif is valid for the given course
   * 
   * @param UllCourse $course
   */
  protected function getTariffFromRequest(UllCourse $course)
  {
    $ullCourseTariffId = $this->getRequestParameter('ull_course_tariff_id');
    $tariff = Doctrine::getTable('UllCourseTariff')->findOneById($ullCourseTariffId);
    $this->forward404Unless($tariff);
    
    $validTariffIds = array();
    
    foreach ($course->UllCourseTariff as $record)
    {
      $validTariffIds[] = $record['id'];
    }
    
    if (!in_array($tariff['id'], $validTariffIds))
    {
      throw new InvalidArgumentException('Tarif ' . $tariff['display_name'] . 'is not a valid tariff for course ' . $course['name']);
    }
    
    return $tariff;
  }

  /**
   * modifyGeneratorBeforeBuildForm
   * 
   * @param Doctrine_Record $object
   */
  protected function modifyGeneratorBeforeBuildForm($object)
  {
    if ('list' == $this->getActionName())
    {
//      die('whoo');
      $columnsConfig = $this->generator->getColumnsConfig();
      $filter = $this->getRequestParameter('filter');
      if (
        // hide is_active column for default setting (show only active courses)
        !isset($filter['is_active']) || 
        // and also hide is_active column for all settings except any is_active status (= _all_)
        (isset($filter['is_active']) && '_all_' != $filter['is_active'])
      )
      {
        $columnsConfig['is_active']
          ->disable()
        ;
      }
    }
        
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
