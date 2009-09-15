<?php

/**
 * Named queries for ullFlow
 */

class ullNamedQueriesUllFlowCustom extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullFlow/list')
      ->setI18nCatalogue('messages')
      ->add('ullNamedQueryUllFlowGroupITHelpdesk')
    ;
  }
  
}