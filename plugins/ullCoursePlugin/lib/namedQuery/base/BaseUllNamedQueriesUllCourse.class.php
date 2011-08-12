<?php

/**
 * Named queries
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */

class BaseUllNamedQueriesUllCourse extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullCourse/list')
      ->setI18nCatalogue('ullCourseMessages')
      ->add('ullNamedQueryUllCourseAll')
    ;
  }
  
}