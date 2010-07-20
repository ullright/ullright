<?php
/**
 * This exception wraps overlapping bookings, and
 * is thrown e.g. by ullRecurringBooking and
 * PluginUllBooking in case of a failed reservation.
 */
class ullOverlappingBookingException extends DomainException
{
  protected $overlappingBookings;
  
  public function __construct($overlappingBookings)
  {
    parent::__construct('Date range already booked for this resource.', 0);
    $this->setOverlappingBookings($overlappingBookings);
  }
  
  public function getOverlappingBookings()
  {
    return $this->overlappingBookings;
  }
  
  protected function setOverlappingBookings($overlappingBookings)
  {
    $this->overlappingBookings = $overlappingBookings;
  }
}