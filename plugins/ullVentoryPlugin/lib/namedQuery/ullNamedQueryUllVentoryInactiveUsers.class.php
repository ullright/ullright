<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllVentoryInactiveUsers extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Items belonging to inactive users';
    $this->identifier = 'inactive_users';
  }
  
  public function modifyQuery($q)
  {
    $q->addWhere('UllEntity->UllUserStatus->is_active = ?', false);
  }
  
}