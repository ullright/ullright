<?php

class UllBookingResourceTableConfiguration extends ullTableConfiguration
{

  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Booking resources', null, 'ullClimbingRouteDBMessages'))
      ->setSearchColumns(array('id', 'name'))
      ->setOrderBy('name')
    ;
  }
}