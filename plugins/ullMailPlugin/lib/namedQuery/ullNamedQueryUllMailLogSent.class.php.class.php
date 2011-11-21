<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllMailLogSent extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Sent newsletter';
    $this->identifier = 'sent';
  }
  
  public function modifyQuery($q)
  {
    $q->addWhere('failed_at IS NULL');
  }
  
}