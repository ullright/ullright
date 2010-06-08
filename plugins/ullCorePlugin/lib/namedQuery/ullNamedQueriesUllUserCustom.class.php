<?php

/**
 * Named queries for ullUser
 */

class ullNamedQueriesUllUserCustom extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullUser/list')
      ->setI18nCatalogue('messages')
      ->add('ullNamedQueryUllUserOpeningCompetition')
    ;
  }
  
}