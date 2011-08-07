<?php

/**
 * PluginUllCourse
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginUllCourse extends BaseUllCourse
{
  /**
   * Pre save hook
   * @param unknown_type $event
   */
  public function preSave($event)
  {
    // Set tags in taggable behaviour
    $this->setTags($this->duplicate_tags_for_search);
    
    $this->updateStatus();
  }
  
  /**
   * Pre update hook
   * @param unknown_type $event
   */
  public function postUpdate($event)
  {
    $this->updateSupernumeraryBookings();
  }  
  
  
  public function updateProxies()
  {
    if (!$this->exists())
    {
      throw new RuntimeException('Object must be saved first!');
    }

    $this->updateParticipantsApplied();
    $this->updateParticipantsPaid();
    $this->updateTurnover();
    
    $this->save();
    
  }

  /*
   * Update the proxy for participants who applied for this course
   */
  public function updateParticipantsApplied()
  {
    if (!$this->exists())
    {
      throw new RuntimeException('Object must be saved first!');
    }
    
    $q = new Doctrine_Query;
    $q
      ->from('UllCourseBooking b')
      ->where('b.ull_course_id = ?', $this->id)
    ;
    
    $this->proxy_number_of_participants_applied = $q->count();
  }
  
  /*
   * Update the proxy for participants who paid for this course
   */
  public function updateParticipantsPaid()
  {
    if (!$this->exists())
    {
      throw new RuntimeException('Object must be saved first!');
    }
    
    $q = new Doctrine_Query;
    $q
      ->from('UllCourseBooking b')
      ->where('b.ull_course_id = ?', $this->id)
      ->addWhere('b.is_paid = ?', true)
    ;
    
    $this->proxy_number_of_participants_paid = $q->count();
  }

  /*
   * Update the turnover proxy for this course
   */
  public function updateTurnover()
  {
    if (!$this->exists())
    {
      throw new RuntimeException('Object must be saved first!');
    }
    
    $q = new Doctrine_Query;
    $q
      ->select('SUM(t.price) as sum')
      ->from('UllCourseBooking b, b.UllCourseTariff t')
      ->where('b.ull_course_id = ?', $this->id)
      ->addWhere('b.is_paid = ?', true)
    ;
    
    $result = $q->fetchOne(null, Doctrine::HYDRATE_ARRAY);
    
    $this->proxy_turnover = $result['sum']; 
  }    
  
  /**
   * Check if we have a multi day course
   */
  public function isMultiDay()
  {
    return (boolean) ($this['begin_date'] < $this['end_date']);
  }
  
  /**
   * Check if the course is full
   */
  public function isFullyBooked() 
  { 
    return(boolean) ($this['proxy_number_of_participants_paid'] >= $this['max_number_of_participants']);
  }
  
  /**
   * Check of this course has insufficient participants
   */
  public function isInsufficientParticipants() 
  {
    return (boolean) ($this['proxy_number_of_participants_paid'] < $this['min_number_of_participants']);
  }

  /**
   * Get the number of available course spots 
   */
  public function getSpotsAvailable() 
  {
    return(integer) $this['max_number_of_participants'] - $this['proxy_number_of_participants_paid'];
  }
  
  /**
   * Update the human readable UllCourseStatus depending on the course data
   */
  public function updateStatus()
  {
    if ($this->is_canceled)
    {
      $this->UllCourseStatus = $this->findStatus('canceled');
      
      return;
    }   
    
    if (date('Y-m-d') > $this->end_date)
    {
      $this->UllCourseStatus = $this->findStatus('finished');
      
      return;
    }      

    if ($this->is_active && $this->proxy_number_of_participants_paid < $this->min_number_of_participants)
    {
      $this->UllCourseStatus = $this->findStatus('insufficient-participants');
      
      return;
    }       
    
    if ($this->is_active && $this->proxy_number_of_participants_paid > $this->max_number_of_participants)
    {
      $this->UllCourseStatus = $this->findStatus('overbooked');
      
      return;
    }      
    
    if (!$this->is_active && date('Y-m-d') < $this->begin_date)
    {
      $this->UllCourseStatus = $this->findStatus('planned');
    }
    elseif ($this->is_active && date('Y-m-d') < $this->begin_date)
    {
      $this->UllCourseStatus = $this->findStatus('announced');
    }
    elseif ($this->is_active && date('Y-m-d') >= $this->begin_date && date('Y-m-d') <= $this->end_date)
    {
      $this->UllCourseStatus = $this->findStatus('active');
    } 
  
  }
  
  /**
   * If max_number_of_participants is modified we have to re-itererate the 
   * bookings to update the supernumerary flags
   */
  public function updateSupernumeraryBookings()
  {
    $modified = $this->getLastModified();
    
    if (isset($modified['max_number_of_participants']))
    {
      UllCourseBookingTable::updateSupernumerary($this['id']);   
    }
  }
  
  /**
   * Helper shortcut method to get an UllCourseStatus by slug
   * 
   * @param string $slug
   */
  protected function findStatus($slug)
  {
    return Doctrine::getTable('UllCourseStatus')->findOneBySlug($slug);
  }
  
  
}