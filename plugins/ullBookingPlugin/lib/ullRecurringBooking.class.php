<?php
/**
 * This class can automatically create multiple bookings depending
 * on recurrence period and repeat count.
 */
class ullRecurringBooking
{
  /**
   * Creates single bookings for a recurring reservation.
   *
   * Uses UllBookingTable::saveMultipleBookings, which wraps
   * all reservations into a transaction, i.e. either every
   * booking is persisted or none at all.
   *
   * Throws ullOverlappingBookingException if at least one of the
   * bookings was not successful due to overlapping date ranges
   * defined by existing bookings.
   * This exception wraps the bookings which caused it to occur.
   *
   * Throws InvalidArgumentException if the combination of booking
   * duration and recurrence period would cause the creation of new
   * overlapping bookings.
   * 
   * @param UllBooking $originalBooking - the 'starting' booking
   * @param string $recurrencePeriod - 'w' for weekly or 'd' for daily
   * @param int $repeats the number of times the reservation will repeat
   * @return nothing
   */
  public static function createRecurringBooking(UllBooking $originalBooking, $recurrencePeriod, $repeats)
  {
    $bookings = self::buildRecurringBookings($originalBooking, $recurrencePeriod, $repeats);
    UllBookingTable::saveMultipleBookings($bookings);
  }
   
  /**
   * Builds an array of bookings starting from a given
   * booking, a recurrence period and a repeat count.
   * 
   * If the combination of booking duration and recurrence period would
   * cause overlapping bookings, an InvalidArgumentException is thrown.
   *
   * @param UllBooking $originalBooking - the 'starting' booking
   * @param string $recurrencePeriod - 'w' for weekly or 'd' for daily
   * @param int $repeats the number of times the reservation will repeat
   * @return array containing the new bookings, booking[0] is $originalBooking
   */
  protected static function buildRecurringBookings(UllBooking $originalBooking, $recurrencePeriod, $repeats)
  { 
    $recurrenceString = self::parseRecurrencePeriod($recurrencePeriod);

    $bookings = array();
    $originalBooking->setNewBookingGroup();
    $bookings[] = $originalBooking;

    $startDate = strtotime($originalBooking->start);
    $endDate = strtotime($originalBooking->end);

    for ($i = 1; $i <= $repeats; $i++)
    {
      //clone the original booking and adapt start and end dates
      //bug #1246: there is a problem in combination with behaviors:
      //newly copy()ied records have their Timestampeable/Personable
      //columns set as modified to Doctrine_Null, which fails for
      //Timestampable (and is wrong anyway)
      //we fix this by setting the fields manually, which is at best
      //an ugly workaround
      $booking = $originalBooking->copy();
      self::fixBehaviorFields($booking);
      
      //add one unit of the recurrence period
      $startDate = strtotime('+1 ' . $recurrenceString, $startDate);
      $endDate = strtotime('+1 ' . $recurrenceString, $endDate);

      $booking->start = date('c', $startDate);
      $booking->end = date('c', $endDate);
      
      //Check the booking we just built and the one before for collisions
      //why? e.g. a daily repeating booking spanning multiple days
      //would cause this function to return overlapping bookings
      if (strtotime($bookings[$i - 1]->end) > $startDate)
      {
        throw new InvalidArgumentException(
          'Combination of booking duration and recurrence period would result in overlapping bookings');
      }
      
      $bookings[] = $booking;
    }

    return $bookings;
  }

  /**
   * Fixes bug #1246, see buildRecurringBookings() above
   * @param $booking the ullBooking to fix
   */
  protected static function fixBehaviorFields(UllBooking $booking)
  {
    $booking->created_at = date('c');
    $booking->updated_at = date('c');

    //userId is allowed to be null
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    $booking->creator_user_id = $booking->updator_user_id = $userId;
  }
  
  /**
   * Converts single characters into matching recurrence
   * periods compatible with strtotime.
   *
   * @param string $recurrencePeriod - 'w' or 'd'
   * @return 'weeks' or 'days'
   */
  protected static function parseRecurrencePeriod($recurrencePeriod)
  {
    switch ($recurrencePeriod)
    {
      case 'w':
        return 'weeks';
      case 'd':
        return 'days';
      default:
        throw new InvalidArgumentException("recurrencePeriod must be 'w' or 'd'");
    }
  }
}