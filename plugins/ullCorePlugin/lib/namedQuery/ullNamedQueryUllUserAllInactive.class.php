<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllUserAllInactive extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All inactive users';
    $this->identifier = 'all_inactive';
  }
  
  public function modifyQuery($q)
  {
    $q->addWhere('UllUserStatus->is_active = ?', false);
  }
  
}