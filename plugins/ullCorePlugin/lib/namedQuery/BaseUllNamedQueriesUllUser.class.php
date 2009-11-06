<?php

/**
 * Named queries for ullUser
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */

class BaseUllNamedQueriesUllUser extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullUser/list')
      ->setI18nCatalogue('ullCoreMessages')
      ->add('ullNamedQueryUllUserAllActive')
      ->add('ullNamedQueryUllUserAllInactive')
      ->add('ullNamedQueryUllUserModifiedToday')

    ;
  }
  
}