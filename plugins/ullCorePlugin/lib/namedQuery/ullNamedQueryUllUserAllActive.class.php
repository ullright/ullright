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
  
  public function modifyQuery(Doctrine_Query $q)
  {
    $q->addWhere('UllEntity->UllUserStatus->slug = ?', 'active');
  }
  
}