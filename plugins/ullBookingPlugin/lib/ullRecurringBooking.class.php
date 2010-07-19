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
   * It wraps all reservations into a transaction, i.e.
   * either every booking is persisted or none at all.
   * 
   * Throws ullOverlappingBookingException if at least one of the
   * bookings was not successful due to overlapping date ranges.
   * This exception wraps the bookings which caused it to occur.
   * 
   * @param UllBooking $originalBooking - the 'starting' booking
   * @param string $recurrencePeriod - 'w' for weekly or 'd' for daily
   * @param int $repeats the number of times the reservation will repeat
   * @return nothing
   */
  public static function createRecurringBooking(UllBooking $originalBooking, $recurrencePeriod, $repeats)
  {
    $recurrenceString = self::parseRecurrencePeriod($recurrencePeriod);
    $overlappingBookings = new Doctrine_Collection('UllBooking');

    //put separate bookings into a transaction
    //with highest isolation level to prevent overbooking
    $conn = Doctrine_Manager::connection();
    $conn->beginTransaction();
    $transaction = $conn->transaction;
    $transaction->setIsolation('SERIALIZABLE');

    
    //note that each booking is in a separate try-block:
    //even if one fails, the other ones will still
    //try to persist - we do this because in case
    //of overlapping date ranges we want to return ALL
    //dates which fail, not just the first one
    
    try
    {
      $originalBooking->setNewBookingGroup();
      $originalBooking->save();
    }
    catch (ullOverlappingBookingException $e)
    {
      $overlappingBookings->merge($e->getOverlappingBookings());
    }

    $startDate = strtotime($originalBooking->start);
    $endDate = strtotime($originalBooking->end);

    for ($i = 1; $i <= $repeats; $i++)
    {
      //clone the original booking and adapt start and end dates
      try
      {
        $booking = $originalBooking->copy();

        //add one day or week
        $startDate = strtotime('+1 ' . $recurrenceString, $startDate);
        $endDate = strtotime('+1 ' . $recurrenceString, $endDate);

        $booking->start = date('c', $startDate);
        $booking->end = date('c', $endDate);

        $booking->save();
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