<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllMailLogRead extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Read newsletter';
    $this->identifier = 'read';
  }
  
  public function modifyQuery($q)
  {
    $q->addWhere('first_read_at IS NOT NULL');
    $q->addWhere('subject NOT LIKE ?', '%#Test#');
  }
  
}