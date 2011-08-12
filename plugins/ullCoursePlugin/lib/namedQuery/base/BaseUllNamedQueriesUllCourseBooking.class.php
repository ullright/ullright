<?php

/**
 * Named queries
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */

class BaseUllNamedQueriesUllCourseBooking extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullCourseBooking/list')
      ->setI18nCatalogue('ullCourseMessages')
      ->add('ullNamedQueryUllCourseBookingAll')
    ;
  }
  
}