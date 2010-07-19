<?php 

class UllBookingResourceColumnConfigCollection extends ullColumnConfigCollection
{
  protected function applyCustomSettings()
  {
    $this['is_bookable']->setLabel(__('Bookable', null, 'ullBookingMessages'));
  }
}