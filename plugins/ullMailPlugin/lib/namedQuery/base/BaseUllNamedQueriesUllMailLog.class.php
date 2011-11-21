<?php

/**
 * Named queries for ullMailLog
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */

class BaseUllNamedQueriesUllMailLog extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullMailLog/list')
      ->setI18nCatalogue('ullMailMessages')
      ->add('ullNamedQueryUllMailLogSent')
      ->add('ullNamedQueryUllMailLogRead')
      ->add('ullNamedQueryUllMailLogFailed')
    ;
  }
  
}