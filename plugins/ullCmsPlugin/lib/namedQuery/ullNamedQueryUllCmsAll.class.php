<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllCmsAll extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All pages';
    $this->identifier = 'all';
  }
  
  public function modifyQuery($q)
  {
  }
  
}