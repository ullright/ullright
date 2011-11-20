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
    $this->name       = 'Sent newsletters';
    $this->identifier = 'sent';
  }
  
  public function modifyQuery($q)
  {
    
  }
  
}