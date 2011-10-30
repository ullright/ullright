<?php

/**
 * Named queries for ullCms
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */

class BaseUllNamedQueriesUllCms extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullCms/list')
      ->setI18nCatalogue('ullCmsMessages')
      ->add('ullNamedQueryUllCmsAll')
    ;
  }
  
}