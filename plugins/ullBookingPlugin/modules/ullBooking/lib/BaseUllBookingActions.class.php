<?php

/**
 * ullBooking actions.
 *
 * @package    ullright
 * @subpackage ullBooking
 */

class BaseUllBookingActions extends BaseUllGeneratorActions
{
  public function preExecute()
  {
    parent::preExecute();

    $this->addModuleStylesheet();

    $this->getResponse()->setTitle(__('Booking schedule', null, 'ullBookingMessages'));
  }

  /**
   * Executes index action -> links to the schedule action
   */
  public function executeIndex(sfRequest $request)
  {
    $this->checkPermission('ull_booking_index');

    //instead, redirect to schedule
    $this->redirect('booking_schedule');
  }

  /**
   * Main page for the booking system, displays a graphical schedule
   * of all booking resources. Assumes 15 minutes intervals.
   * 
   * Also provides links to create and delete new bookings.
   */
  public function executeSchedule(sfRequest $request)
  {
    $this->checkPermission('ull_booking_schedule');
    $this->ull_reqpass_redirect();
    
    $this->date_select_form = new UllScheduleSelectForm();
    
    //no check for post action here, could be previous/next links
    if ($request->hasParameter('fields'))
    {
      $this->date_select_form->bind($request->getParameter('fields'));

      if ($this->date_select_form->isValid())
      {
        $date = $this->date_select_form->getValue('date');
      }
    }

    //no date selected? assume current date
    $this->date = (isset($date)) ? strtotime($date) : time();
    $this->date_select_form->setDefault('date', date('Y-m-d', $this->date));
    $this->previous_day = date('Y-m-d', strtotime('yesterday', $this->date));
    $this->next_day = date('Y-m-d', strtotime('tomorrow', $this->date));

    //calculate schedule cells - structure looks like this:
    //array with booking resource ids as keys (will be reindexed later on)
    //  subarray with
    //    24 hours/4*15 minutes = 96 cells with 0-95 as keys
    //    name key with translated booking resource name
    $this->cell_status = array();

    //retrieve all booking resources and store their (translated) names for the schedule legend
    $bookingResources = Doctrine_Core::getTable('UllBookingResource')->findBookableResources();
    foreach ($bookingResources as $bookingResource)
    {
      $this->cell_status[$bookingResource['id']]['name'] = $bookingResource['name'];
    }

    //retrieve all bookings for the chosen day, only for bookable resources
    $bookings = UllBookingTable::findBookingsByDay($this->date, array_keys($this->cell_status));
    $this->booking_info_list = array();
    
    foreach ($bookings as $booking)
    {
      foreach(array('start', 'end') as $index)
      {
        //is the start/end date of this booking equal to the current date?
        //most likely, but not in case of a booking spanning multiple days
        if (date('Y-m-d', strtotime($booking[$index])) == date('Y-m-d', $this->date))
        {
          $hour = date('G', strtotime($booking[$index]));   //parse hours (0-23)
          $minute = date('i', strtotime($booking[$index])); //parse minutes (0-59)
  
          //as soon as the beginning of a quarter hour is transcended
          //the cell gets marked (e.g. 16:01 means occupation until 16:15)
          ${$index . 'Index'} = $hour * 4 + ceil($minute / 15);
        }
        else //booking actually starts/ends before/after the day we are currently displaying
        {
          ${$index . 'Index'} = ($index == 'start') ? 0 : 96;
        }
      }
      
      //store cell status and in case of occupied cells the booking name
      for ($i = $startIndex; $i < $endIndex; $i++)
      {
        $cellStatus = array(
          'bookingName' => $booking['name'],
          'cellType' => ($i == $startIndex) ? 'open' : (($i == $endIndex - 1) ? 'close' : 'normal'),
        );
        $this->cell_status[$booking['ull_booking_resource_id']][$i] = $cellStatus;
      }
      
      //fill an array with information about the displayed bookings
      $this->booking_info_list[$booking['id']] = array(
          'name' => $booking['name'],
          'bookingGroupName' => $booking['booking_group_name'],
          'bookingGroupCount' => $booking['booking_group_count'],
          'resourceName' => $this->cell_status[$booking['ull_booking_resource_id']]['name'],
          'range' => $booking->formatDateRangeTimeOnly(),
        );
    }
    
    //reindex array, makes it easier to iterate in the view
    $this->cell_status = array_values($this->cell_status);

    //read start/end hours from config
    $this->start_hour = sfConfig::get('app_ull_booking_schedule_start_hour', 9);
    $this->end_hour = sfConfig::get('app_ull_booking_schedule_end_hour', 22);
  }

  /**
   * Persists a new booking, supporting the creation of multiple
   * bookings at once (= recurring bookings).
   * 
   * Also see UllBookingCreateForm and UllBookingAdvancedCreateForm.
   * The first one includes common options like date, time and duration
   * The second one includes advanced option, e.g. recurrence period
   */
  public function executeCreate(sfRequest $request)
  {
    $this->checkPermission('ull_booking_create');

    //did the user submit a simple or recurring booking?
    $this->is_simple = ($request->getParameter('booking_type') == 'advanced') ? false : true;
    //the displayed form is always the advanced form (including recurring options)
    //but for JS-enabled clients those advanced options are hidden
    $this->form = new UllBookingAdvancedCreateForm();

    if ($request->isMethod('post'))
    {
      //we do not want to validate advanced options if the user submitted
      //from a simple form -> chose validation form accordingly
      $this->validationForm = ($this->is_simple) ? new UllBookingCreateForm() : new UllBookingAdvancedCreateForm();
      $this->validationForm->bind($request->getParameter('fields'));

      if ($this->validationForm->isValid())
      {
        //transform input data into a booking object
        //add duration field to start time
        $booking = new UllBooking();
        $booking->name = $this->validationForm->getValue('name');
        $booking->ull_booking_resource_id = $this->validationForm->getValue('booking_resource');
        $startTimestamp = strtotime($this->validationForm->getValue('date')) + $this->validationForm->getValue('time');
        $booking->start = date('Y-m-d H:i:s', $startTimestamp);
        //old version for duration
        //$booking->end = date('Y-m-d H:i:s', $startTimestamp + $this->validationForm->getValue('duration'));
        //new version for end
        $endTimestamp = strtotime($this->validationForm->getValue('date')) + $this->validationForm->getValue('end');
        $booking->end = date('Y-m-d H:i:s', $endTimestamp);
        
        try
        {
          //do we have to persist a single booking or multiple ones?
          if ($this->is_simple || $this->validationForm->getValue('recurring') == 'n')
          {
            $booking->save();
          }
          else
          {
            //subtract one from the repeat count, since
            //the original booking also counts
            //TODO: make it absolutely clear to the user that
            //the number he puts into the 'repeats' field
            //indicates -total- count of bookings
            //OR: assume otherwise, and remove the -1.
            ullRecurringBooking::createRecurringBooking($booking,
              $this->validationForm->getValue('recurring'),
              ($this->validationForm->getValue('repeats') - 1));
          }
          
          //if reservation was successful, redirect to the schedule view
          //of the date of the (first) booking
          $this->redirect(url_for('booking_schedule',
            array('fields[date]' => $this->validationForm->getValue('date'))));
        }
        catch (ullOverlappingBookingException $e)
        {
          //one or more bookings were unsuccessful, retrieve
          //the list of bookings which caused them to fail
          //and put them into an array for the view
          $this->overlappingBookings = array();
          foreach($e->getOverlappingBookings() as $overlappingBooking)
          {
            $this->overlappingBookings[] = $overlappingBooking['name'] . ' - ' . $overlappingBooking->formatDateRange();
          }
          
          //form data was valid, but booking was not successful
          //rebind the form so we do not lose the original input
          $this->form->bind($request->getParameter('fields'));
        }
      }
      else
      {
        //we could put this line and the same one above at the end
        //of the function since all other branches seem to
        //redirect anyway
        $this->form->bind($request->getParameter('fields'));
      }
    }
  }
  
  /**
   * Handles deletion of a booking (optionally for an entire
   * booking group) and redirects back to the schedule.
   */
  public function executeDelete(sfRequest $request)
  {
    $this->checkPermission('ull_booking_delete');
    
    //if groupName is set, delete the whole group of bookings
    //otherwise delete a single booking specified by id
    
    $groupName = $request->getParameter('groupName');
    $bookingId = $request->getParameter('id');
    $viewDate = $request->getParameter('viewDate');
    if ((!$groupName && !$bookingId) || !$viewDate)
    {
      throw new InvalidArgumentException("The 'viewDate' and either 'groupName' or 'id' parameter have to be set");
    }
    
    $fieldName = ($groupName) ? 'booking_group_name' : 'id';
    $fieldValue = ($groupName) ? $groupName : $bookingId;
   
    $q = new Doctrine_Query();
    $q
      ->delete('UllBooking b')
      ->where('b.' . $fieldName . ' = ?', $fieldValue);
    ;
    $q->execute();
    
    $this->redirect(url_for('booking_schedule', array('fields[date]' => date('Y-m-d', $viewDate))));
  }
  
  /**
   * Handles editing of single and group bookings,
   * redirects to schedule
   */
  public function executeEdit(sfRequest $request)
  {
    $this->checkPermission('ull_booking_edit');
    
    $groupName = $request->getParameter('groupName');
    $singleId = $request->getParameter('singleId');
    if (!$groupName && !$singleId)
    {
      throw new InvalidArgumentException("The 'groupName' or 'singleId' parameter has to be set");
    }
    
    $isGroupEdit = ($groupName) ? true : false;
    
    if ($isGroupEdit)
    {
      $this->redirectUnless(count($bookings = Doctrine::getTable('UllBooking')
        ->findByBookingGroupName($groupName)), 'booking_schedule');
      $booking = $bookings[0];
      $this->group_name = $groupName;
    }
    else
    {
      $this->redirectUnless($booking = Doctrine::getTable('UllBooking')->find($singleId), 'booking_schedule');
      $this->single_id = $singleId; 
    }
    
    $this->form = new UllBookingCreateForm();
    
    //parse existing booking data
    $defaults = array(
      'date' => date('Y-m-d', strtotime($booking['start'])),
      'name' => $booking['name'],
      'time' => date('H:i', strtotime($booking['start'])),
      'end'  => date('H:i', strtotime($booking['end'])),
      //'duration' => ullCoreTools::timeToString(strtotime($booking['end']) - strtotime($booking['start'])),
      'booking_resource' => $booking['ull_booking_resource_id']);
    
    $this->form->setDefaults($defaults);
    
    if ($isGroupEdit)
    {
      //if we are editing multiple bookings, disable the date field
      unset ($this->form['date']);
    }
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('fields'));

      if ($this->form->isValid())
      {
        try
        {
          if ($isGroupEdit)
          {
            foreach ($bookings as $booking)
            {
              $booking['name'] = $this->form->getValue('name');
              $booking['ull_booking_resource_id'] = $this->form->getValue('booking_resource');
              $booking['start'] = date('Y-m-d', strtotime($booking['start'])) . ' ' .
                ullCoreTools::timeToString($this->form->getValue('time'));
              $booking['end'] = date('Y-m-d', strtotime($booking['end'])) . ' ' .
                ullCoreTools::timeToString($this->form->getValue('end'));
              //$booking['end'] = date('Y-m-d H:i:s', strtotime($booking['start']) + $this->form->getValue('duration'));
            }
            UllBookingTable::saveMultipleBookings($bookings);
          }
          else
          {
            $booking['name'] = $this->form->getValue('name');
            $booking['ull_booking_resource_id'] = $this->form->getValue('booking_resource');
            $startTimestamp = strtotime($this->form->getValue('date')) + $this->form->getValue('time');
            $booking['start'] = date('Y-m-d H:i:s', $startTimestamp);
            //$booking['end'] = date('Y-m-d H:i:s', $startTimestamp + $this->form->getValue('duration'));
            $endTimestamp = strtotime($this->form->getValue('date')) + $this->form->getValue('end');
            $booking['end'] = date('Y-m-d H:i:s', $endTimestamp);
            $booking->save();
          }
          
          //redirect to the schedule view of the date of the booking
          $this->redirect(url_for('booking_schedule',
            array('fields[date]' => $this->form->getValue('date'))));
        }
        catch (ullOverlappingBookingException $e)
        {
          //one or more bookings were unsuccessful, retrieve
          //the list of bookings which caused them to fail
          //and put them into an array for the view
          $this->overlappingBookings = array();
          foreach($e->getOverlappingBookings() as $overlappingBooking)
          {
            $this->overlappingBookings[] = $overlappingBooking['name'] . ' - ' . $overlappingBooking->formatDateRange();
          }
        }
      }
    }
  }
  
  /**
   * Renders all bookings belonging to a given group name
   * using the partial 'ullBooking/listGroupBookings',
   * if the request is an AJAX call. Renders sfView:NONE
   * otherwise.
   */
  public function executeListGroupBookings(sfRequest $request)
  {
    $this->checkPermission('ull_booking_schedule');
    
    $this->redirectUnless($groupName = $request->getParameter('groupName'), 'booking_schedule');
    $this->redirectUnless($id = $request->getParameter('id'), 'booking_schedule');
    
    if ($request->isXmlHttpRequest())
    {
      $bookings = UllBookingTable::findGroupBookings($groupName);
      $params = array('bookings' => $bookings, 'id' => $id);
      return $this->renderPartial('ullBooking/listGroupBookings', $params);
    }
    else
    {
      return sfView::NONE;
    }
  }
}
