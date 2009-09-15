<?php

/**
 * Named queries for ullFlow
 */

class BaseUllNamedQueriesUllFlow extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullFlow/list')
      ->setI18nCatalogue('ullFlowMessages')
      ->add('ullNamedQueryUllFlowAllEntries')
      ->add('ullNamedQueryUllFlowByMe')
      ->add('ullNamedQueryUllFlowToMe')
      ->add('ullNamedQueryUllFlowToMeOrMyGroups')
    ;
  }
  
}