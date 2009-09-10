<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllVentoryModifiedToday extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All items modified today';
    $this->identifier = 'modified_today';
  }
  
  public function modifyQuery(Doctrine_Query $q)
  {
    $q->addWhere('x.updated_at LIKE ?', date('Y-m-d%'));
  }
  
}