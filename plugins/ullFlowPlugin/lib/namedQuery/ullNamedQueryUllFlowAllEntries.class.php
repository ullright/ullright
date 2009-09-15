<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllFlowAllEntries extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All entries';
    $this->identifier = 'all_entries';
  }
  
  public function modifyQuery(Doctrine_Query $q)
  {
  }
  
}