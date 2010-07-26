<?php
/**
 */
class PluginUllBookingTable extends UllRecordTable
{
  /**
   * Takes a numerical timestamp and retrieves all
   * bookings for the day ('midnight' to 'tomorrow')
   * it represents.
   * 
   * The second parameter is used to retrieve bookings
   * for certain booking resources only.
   * 
   * Also adds an additional 'booking_group_count'
   * field which contains the total number of bookings
   * in case the booking belongs to a group.
   * 
   * @param $timestamp - the numerical timestamp representing a day
   * @param @bookingResourceIds - if not null, filter by these booking resource ids
   * @return Doctrine_Collection - all bookings which overlap with the day $timestamp falls into
   */
  public static function findBookingsByDay($timestamp, $bookingResourceIds = null)
  {
    $startOfDay = strtotime('midnight', $timestamp);
    $endOfDay = strtotime('tomorrow', $timestamp);
    
    $startOfDay = date("Y-m-d H:i:s", $startOfDay);
    $endOfDay = date("Y-m-d H:i:s", $endOfDay);
    
    $q = new Doctrine_Query();
    $q
      ->select('b.id, b.start, b.end, b.ull_booking_resource_id, b.booking_group_name')
      //this subquery retrieves as the amount of other queries in this booking group
      ->addSelect('(SELECT count(*) FROM UllBooking bsub ' .
        'where bsub.booking_group_name = b.booking_group_name) as booking_group_count')
      ->from('UllBooking b')
      //note the '<' instead of the '<='
      //if the date range is e.g. 1.1.2010 00:00:00 - 2.1.2010 00:00:00
      //an event beginning 2.1.2010 00:00:00 should not be retrieved
      ->where('? <= b.end AND b.start < ?', array($startOfDay, $endOfDay))
    ;
    
    if (is_array($bookingResourceIds) && count($bookingResourceIds) > 0)
    {
      //retrieve only events for certain booking resources
      $q->andWhereIn('b.ull_booking_resource_id', $bookingResourceIds);
    }
    
    return $q->execute();
  }
  
  /**
   * Retrieves all bookings belonging to a specific group name.
   * 
   * @param string $groupName the group name
   * @return Doctrine_Collection list of bookings
   */
  public static function findGroupBookings($groupName)
  {
    $q = new Doctrine_Query();
    $q
      ->select('b.id, b.start, b.end, b.ull_booking_resource_id, b.booking_group_name')
      ->from('UllBooking b')
      ->where('b.booking_group_name = ?', $groupName)
    ;
    return $q->execute();
  }
  
  /**
   * Saves an array of bookings, wrapping all reservations
   * into a transaction, i.e. either every booking is persisted
   * or none at all.
   *
   * Throws ullOverlappingBookingException if at least one of the
   * bookings was not successful due to overlapping date ranges.
   * This exception wraps the bookings which caused it to occur.
   *
   * @param $bookings array or Doctrine_Collection containing the bookings to persist
   * @return nothing
   */
  public static function saveMultipleBookings($bookings)
  {
    $overlappingBookings = new Doctrine_Collection('UllBooking');
    $idWhitelist = array();
    
    //put separate bookings into a transaction
    //with highest isolation level to prevent overbooking
    $conn = Doctrine_Manager::connection();
    $conn->beginTransaction();
    $transaction = $conn->transaction;
    $transaction->setIsolation('SERIALIZABLE');

    //build a complete whitelist of booking ids, we do this
    //because we need to exclude these ids from the overlap
    //check - which happens in UllBooking->save() - for a
    //special case like this one:
    //assume a daily event, beginning 9:00
    //change that to 23:45, with a duration of 10 hours
    //-> new end date would be 9:45 next day, which overlaps
    //with next day's old booking -> overlapping exceptions caused
    //by existing bookings from the same group
           
    foreach ($bookings as $booking)
    {
      if ($booking->exists())
      {
        $idWhitelist[] = $booking->id;
      }
    }
    
    //note that each booking is in a separate try-block:
    //even if one fails, the other ones will still
    //try to persist - we do this because in case
    //of overlapping date ranges we want to return ALL
    //dates which fail, not just the first one
    for ($i = 0; $i < count($bookings); $i++)
    {
      try
      {
        $bookings[$i]->save($conn, $idWhitelist);
      }
      catch (ullOverlappingBookingException $e)
      {
        //in case of multiple failing bookings the resulting exception
        //should wrap all 'offending' bookings
        $overlappingBookings->merge($e->getOverlappingBookings());
      }
    }

    if ($overlappingBookings->count())
    {
      $conn->rollback();
      throw new ullOverlappingBookingException($overlappingBookings);
    }
    else
    {
      $conn->commit();
    }
  }
}