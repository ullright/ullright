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
   * Executes index action
   *
   */
  public function executeIndex(sfRequest $request)
  {    
    $this->checkPermission('ull_course_index');
    
    $this->form = new ullFilterForm;

    $this->named_queries_course = new ullNamedQueriesUllCourse;
    $this->named_queries_booking = new ullNamedQueriesUllCourseBooking;

    $this->breadcrumbForIndex();
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
    
    // Redirect back when coming from the course offering
    if (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], 'courses/'))
    {
      $this->getUriMemory()->setReferer('list');
    }
    
    return parent::executeEdit($request);
  }

  
  public function executeShow(sfRequest $request) 
  {
    $this->checkPermission('ull_course_show');
    
    $doc = $this->getDocFromRequest();

    $this->getResponse()->setTitle(
      __('Course', null, 'ullCourseMessages') .
      ' ' . $doc->name
    );
    
    $this->allow_edit = UllUserTable::hasPermission('ull_course_edit');
    
    $this->setVar('doc', $doc, true);
    
  }  
  
  /**
   * Public offering of courses
   * 
   * @see: ullCourseGenerator::customizeTableConfig() for configuration
   * 
   * @param sfRequest $request
   */
  public function executeOffering(sfRequest $request) 
  {
    $this->checkPermission('ull_course_offering');
    
    $this->getResponse()->setTitle(__('Courses', null, 'ullCourseMessages'));
    
    $return = parent::executeList($request);

    $this->allow_edit = UllUserTable::hasPermission('ull_course_edit');
    
    return $return;
  }
  
  /**
   * Tariff selection
   * @param sfRequest $request
   */
  public function executeSelectTariff(sfRequest $request)
  {
    $doc = $this->getDocFromRequest();
    
    // Skip tariff selection for a single tariff 
    if (1 == count($doc->UllCourseTariff))
    {
      $id = $doc->UllCourseTariff->getFirst()->id;
      
      $this->redirect(
        'ullCourse/confirmation?slug=' . $request->getParameter('slug') .
        '&ull_course_tariff_id=' . $id
      );
    }
    
    $this->checkPermission('ull_course_select_tariff');
    
    $this->getResponse()->setTitle(
      __('Tariff selection', null, 'ullCourseMessages')
    );    
    
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
      
    $generator = new ullTableToolGenerator('UllCourseBooking', 'r', 'list', $columns);      
    
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
    
    $this->breadcrumbForInfo();
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
    
    $cc['are_terms_of_use_accepted']
      ->setAccess('w')
      ->setIsRequired(true)
    ;
    
    $cc->create('participant');
    
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

        $this->handleParticipantName($generator, $booking);
        
        $booking->sendConfirmationMail();
        
        $this->notifyBookedEvent($booking);
        
        $this->redirect('ullCourse/booked');
      }
    }
    
    $this->getResponse()->setTitle(
      __('Booking confirmation', null, 'ullCourseMessages')
    );   
    
    $this->setVar('form', $generator->getForm(), true);
    $this->setVar('generator', $generator, true);
    
    $this->setVar('course', $course, true);
    $this->setVar('tariff', $tariff, true);
  }

  
  /**
   * Add the participant name to the comment field, and notify the
   * course admin in case of any other comment
   * 
   * @param UllGenerator $generator
   * @param UllCourseBooking $booking
   */
  protected function handleParticipantName(UllGenerator $generator, UllCourseBooking $booking)
  {
    $participant = $generator->getForm()->getValue('participant');
    
    $originalComment = $booking->comment;
    
    if ($participant)
    {
      $booking->comment = __('Participant', null, 'ullCourseMessages') . 
        ': ' . 
        $participant .
        "\n" .
        $originalComment;
      ;
      
      $booking->save();
    }

    if ($originalComment)
    {
      $mail = new ullsfMail('ull_course_notify_comment');
    
      $mail->setFrom(
        $booking->UllUser->email,
        $booking->UllUser->display_name
      );
      $mail->addAddress(sfConfig::get('app_ull_course_from_address'));
  
      $mail->usePartial('ullCourse/notifyCommentMail', array(
        'booking' => $booking,
        'original_comment' => $originalComment
      ));
      
      $mail->send();      
    }
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
  
  /**
   * Booking landing page ("Thank you, we sent you an email with payment info")
   * 
   * @param sfRequest $request
   */
  public function executeBooked(sfRequest $request)
  {
    $this->checkPermission('ull_course_booked');
    
    $this->getResponse()->setTitle(
      __('Thank you for your booking', null, 'ullCourseMessages')
    );   
  }
  
  /**
   * Show a list of trainers
   * 
   * @param sfRequest $request
   */
  public function executeTrainers(sfRequest $request)
  {
    $this->checkPermission('ull_course_trainers');
    
    $q = new Doctrine_Query;
    $q
      ->from('UllUser u, u.UllGroup g')
      ->where('g.display_name = ?','Trainers' )
      ->orderBy('u.last_name, u.first_name')
    ;

    $this->trainers = $q->execute();
    
    $this->getResponse()->setTitle(__('Trainers', null, 'ullCourseMessages'));
  }
  
  
  /**
   * Cancel a course action
   *
   * @param sfRequest $request
   */
  public function executeCancel(sfRequest $request)
  {
    $this->checkPermission('ull_course_cancel');
    
    $course = $this->getDocFromRequest();
    
    $form = new UllCourseEmailForm();
    
    $mail = $course->composeMail('', '', 'cancelMail');
    
    if ($request->isMethod('get'))
    {
      $form->setDefaults(array(
        'recipients'  => $mail->getAddressesAsString(),
        'subject'     => $mail->getSubject(),
        'body'        => $mail->getBody(),
        'sms'         => get_partial('ullCourse/smsCancel')
      ));
    }
    
    if ($request->isMethod('post'))
    {
      if ('send' == $request->getParameter('action_slug'))
      {
        $form->bind($request->getParameter('fields'));
        
        if ($form->isValid())
        {
          $mail->setSubject($form->getValue('subject'));
          $mail->setBody($form->getValue('body'));
          
          $this->getMailer()->batchSend($mail);
          
          $this->sendSms($course, $form->getValue('sms'));
          
          $this->dispatcher->notify(new sfEvent($this, 'ull_course.cancel_course', array(
            'object'        => $course
          )));
          
          $this->redirect('ullCourse/list');
        }        
      } 
      
      if ('cancel' == $request->getParameter('action_slug'))
      {
        $this->redirect('ullCourse/list');
      }
      
    }
    
    $this->setVar('form', $form, true);
    $this->setVar('course', $course, true);
  }
  
  /**
   * Send a generic mail (and sms) to all course recipients
   * 
   * @param sfRequest $request
   */
  public function executeMail(sfRequest $request)
  {
    $this->checkPermission('ull_course_mail');
    
    $course = $this->getDocFromRequest();
    
    $form = new UllCourseEmailForm();
    
    $mail = $course->composeMail('', '', 'genericMail');
    
    if ($request->isMethod('get'))
    {
      $form->setDefaults(array(
        'recipients'  => $mail->getAddressesAsString(),
        'subject'     => $mail->getSubject(),
        'body'        => $mail->getBody(),
      ));
    }
    
    if ($request->isMethod('post'))
    {
      if ('send' == $request->getParameter('action_slug'))
      {
        $form->bind($request->getParameter('fields'));
        
        if ($form->isValid())
        {
          $mail->setSubject($form->getValue('subject'));
          $mail->setBody($form->getValue('body'));
          
          $this->getMailer()->batchSend($mail);
          
          $this->sendSms($course, $form->getValue('sms'));
          
          $this->dispatcher->notify(new sfEvent($this, 'ull_course.info_mail', array(
            'object'        => $course,
          )));          
          
          $this->redirect('ullCourse/list');
        }        
      } 
      
      if ('cancel' == $request->getParameter('action_slug'))
      {
        $this->redirect('ullCourse/list');
      }
      
    }
    
    $this->setVar('form', $form, true);
    $this->setVar('course', $course, true);
  }  
  
  /**
   * Show a trainer popup
   * 
   * @param sfRequest $request
   */
  public function executeTrainer(sfRequest $request)
  {
    $id = $request->getParameter('id');
    
    $trainer = Doctrine::getTable('UllUser')->findOneById($id);
    $this->forward404Unless($trainer);
    
    if (!UllUserTable::hasGroup('Trainers', $trainer['id'], false))
    {
      $this->forward404('The given user is not a trainer: ' . $trainer['display_name']);
    }
    
    $this->setEmptyLayout();
    
    $this->getResponse()->setTitle(
      ' ' . $trainer['first_name'] .
      ' ' . $trainer['last_name']
    );   
    
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
      
      $columnsConfig['UllCourseStatus->name']
        ->setMetaWidgetClassName('ullMetaWidgetCourseStatus')
      ;
      
    }
    
    if (in_array($this->getActionName(), array('create', 'edit')))
    {
      $this->registerEditActionButton(new ullGeneratorEditActionButtonCourseSaveAndShow($this));
      
      if ($object->exists())
      {
        $this->registerEditActionButton(new ullGeneratorEditActionButtonCourseShowBookings($this));        
        
        $this->registerEditActionButton(new ullGeneratorEditActionButtonCourseMail($this, false));
        $this->registerEditActionButton(new ullGeneratorEditActionButtonCourseCancel($this, false));
      }
    }
        
  } 
  
  /**
   * Gets the doc according to request param
   *
   * @return UllCourse
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

  /**
   * Create breadcrumbs for index action
   * 
   */
  protected function breadcrumbForIndex() 
  {
    $breadcrumbTree = new ullCourseBreadcrumbTree();
    $this->setVar('breadcrumb_tree', $breadcrumbTree, true);
  }
  
  /**
   * Handles breadcrumb for list action
   */
  protected function breadcrumbForList()
  {
    $breadcrumb_tree = new ullCourseBreadcrumbTree();
    $breadcrumb_tree->add(__('Result list', null, 'common'), 'ullCourse/list');
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  /**
   * Handles breadcrumb for show action
   */
  protected function breadcrumbForShow()
  {
    $breadcrumb_tree = new ullCourseBreadcrumbTree();
    $breadcrumb_tree->add(__('Show', null, 'common'));
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }  
  
  /**
   * Handles breadcrumb for info action
   */
  protected function breadcrumbForInfo()
  {
    $breadcrumb_tree = new ullCourseBreadcrumbTree();
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumb_tree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumb_tree->addDefaultListEntry();
    }  
    $breadcrumb_tree->add(__('Info', null, 'ullCourseMessages'));
    $this->setVar('breadcrumb_tree', $breadcrumb_tree, true);
  }    
  
  /**
   * Handles breadcrumb for edit action
   *
   */
  protected function breadcrumbForEdit()
  {
    $breadcrumb_tree = new ullCourseBreadcrumbTree();
    $breadcrumb_tree->setEditFlag(true);
    if ($referer = $this->getUriMemory()->get('list'))
    {
      $breadcrumb_tree->add(__('Result list', null, 'common'), $referer);
    }
    else
    {
      $breadcrumb_tree->addDefaultListEntry();
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
  

  
  /**
   * Send sms. Used for info and course cancellation 
   * 
   * If no text is supplied, no action is performed
   * 
   * @param ullCourse $course
   */
  public function sendSms(ullCourse $course, $text)
  {
    if (!$text)
    {
      return false;
    }
    
    $smsCounter = 0;
    
    foreach ($course->UllCourseBooking as $booking)
    {
      $user = $booking->UllUser;
      
      if ($mobileNumber = $user->phone_number)
      {
        $sms = new ullSms();
        $sms->setFrom(sfConfig::get('app_ull_course_from_mobile_number'));
        $sms->setTo($mobileNumber);
        $sms->setText($text);
        
        $sms->send();
        
        $smsCounter++;
      } 
    }
    
    $flash = sfContext::getInstance()->getUser()->getFlash('message');
    sfContext::getInstance()->getUser()->setFlash('message', $flash . ' ' . $smsCounter . ' Sms versendet.');
  }  
  
}
