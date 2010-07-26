<?php

/**
 * PluginUllBooking
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginUllBooking extends BaseUllBooking
{
  /**
   * Overrides the save() function for booking records to provide
   * secure booking, meaning transactional checking for overlapping
   * dates.
   * 
   * The ids specified in $idWhitelist are exempt from this collision
   * check. This functionality is needed for editing multiple bookings
   * at once and should be used with care!
   * 
   * Throws:
   * DomainException - if the end date is before the start date
   * ullOverlappingBookingException - if the desired date range
   *  is not avilable, i.e. already booked. This exception wraps
   *  the bookings which caused the exception to be thrown
   *  
   *  @param Doctrine_Connection $conn
   *  @param array $idWhitelist list of bookings ids to exempt from overlap check
   *  
   *  @return ullBooking $this
   */
  public function save(Doctrine_Connection $conn = null, $idWhitelist = array())
  {
    $conn = ($conn) ? $conn : Doctrine_Manager::connection();
    
    if (!is_array($idWhitelist))
    {
      throw new InvalidArgumentException('id whitelist has to be an array');
    }
    
    //if this booking is not a new one, add its own id to the whitelist
    if ($this->exists())
    {
      $idWhitelist[] = $this->id;
    }
    
    //put booking range overlap check and record save into
    //a transaction with highest isolation level to
    //prevent overbooking
    $conn->beginTransaction();
    $transaction = $conn->transaction;
    $transaction->setIsolation('SERIALIZABLE');

    try
    {
      //check if end date is after start date
      if (strtotime($this->end) <= strtotime($this->start))
      {
        throw new DomainException('End date has to be after start date');
      }
      
      $q = new Doctrine_Query();
      $q
        ->from('UllBooking b')
        //overlapping date range?
        ->where('? < b.end AND b.start < ?', array($this->start, $this->end))
        //only check for desired booking resource
        ->andWhere('b.ull_booking_resource_id = ?', $this->ull_booking_resource_id)
      ;
      
      //exempt whitelisted ids from overlap check
      if (count($idWhitelist) > 0)
      {
        $q->andWhereNotIn('b.id', $idWhitelist);
      }
      
      $results = $q->execute();
      if ($results->count())
      {
        throw new ullOverlappingBookingException($results);
      }
      
      //if this is a recurring booking or an
      //existing booking, the group name is
      //already set, but not for single bookings
      if (empty($this->booking_group_name))
      {
        $this->setNewBookingGroup();
      }
      
      parent::save($conn);

      $conn->commit();
      
      return $this;
    }
    catch (Exception $e)
    {
      $conn->rollback();
      throw $e;
    }
  }
  
  /**
   * Sets the booking group name of this booking to
   * a random string.
   * 
   * @return ullBooking $this
   */
  public function setNewBookingGroup()
  {
    $this->booking_group_name = str_replace('.', '', uniqid(null, true));
    
    return $this;
  }
  
  /**
   * Formats the range of time this booking occupies
   * into a string.
   * 
   * @return string the formatted range
   */
  public function formatDateRange($zeroPadding = false)
  {
    //format the date different if it does not occupy multiple days
    if (date('Y-m-d', strtotime($this->start)) == date('Y-m-d', strtotime($this->end)))
    {
      return ull_format_datetime($this->start, $zeroPadding, false) . ' - ' . date('H:i', strtotime($this->end));
    } 
    else
    {
      return ull_format_datetime($this->start, $zeroPadding, false) .
        ' - ' . ull_format_datetime($this->end, $zeroPadding, false);
    }
  }
}