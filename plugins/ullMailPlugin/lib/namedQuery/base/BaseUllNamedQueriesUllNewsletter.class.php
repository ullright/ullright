<?php

/**
 * Named queries for ullNewsletter
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */

class BaseUllNamedQueriesUllNewsletter extends ullNamedQueries
{
  
  public function configure()
  {
    $this
      ->setBaseUri('ullNewsletter/list')
      ->setI18nCatalogue('ullMailMessages')
      ->add('ullNamedQueryUllNewsletterAll')
    ;
  }
  
}