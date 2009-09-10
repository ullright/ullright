<?php

/**
 * Named queries for ullVentory
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */

class BaseUllNamedQueriesUllVentory extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullVentory/list')
      ->setI18nCatalogue('ullVentoryMessages')
      ->add('ullNamedQueryUllVentoryModifiedToday')
    ;
  }
  
}