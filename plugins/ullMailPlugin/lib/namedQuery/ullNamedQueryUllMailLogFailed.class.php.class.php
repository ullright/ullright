<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllMailLogFailed extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Failed newsletter';
    $this->identifier = 'failed';
  }
  
  public function modifyQuery($q)
  {
    $q->addWhere('failed_at IS NOT NULL');
  }
  
}