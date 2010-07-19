<?php
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
  
  public function setOverlappingBookings($overlappingBookings)
  {
    $this->overlappingBookings = $overlappingBookings;
  }
  
}