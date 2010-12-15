<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllNewsletterAll extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All newsletters';
    $this->identifier = 'all';
  }
  
  public function modifyQuery($q)
  {
  }
  
}