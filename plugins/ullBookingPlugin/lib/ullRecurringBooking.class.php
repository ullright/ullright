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
    $bookings = self::buildRecurringBookings($originalBooking, $recurrencePeriod, $repeats);
    UllBookingTable::saveMultipleBookings($bookings);
  }
   
  /**
   * Builds an array of bookings starting from a given
   * booking, a recurrence period and a repeat count.
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
      $booking = $originalBooking->copy();

      //add one unit of the recurrence period
      $startDate = strtotime('+1 ' . $recurrenceString, $startDate);
      $endDate = strtotime('+1 ' . $recurrenceString, $endDate);

      $booking->start = date('c', $startDate);
      $booking->end = date('c', $endDate);

      $bookings[] = $booking;
    }

    return $bookings;
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