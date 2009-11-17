<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllUserAllActive extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All active users';
    $this->identifier = 'all_active';
  }
  
  public function modifyQuery($q)
  {
    $q->addWhere('UllUserStatus->is_active = ?', true);
  }
  
}