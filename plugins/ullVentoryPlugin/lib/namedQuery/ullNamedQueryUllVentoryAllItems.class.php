<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllVentoryAllItems extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All items';
    $this->identifier = 'all_items';
  }
  
  public function modifyQuery(Doctrine_Query $q)
  {
  }
  
}