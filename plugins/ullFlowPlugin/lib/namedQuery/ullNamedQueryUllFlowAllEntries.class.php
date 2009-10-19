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
  
  public function modifyQuery($q)
  {
  }
  
  public function getUri()
  {
    //this is overridden because we don't actually want to
    //modify the query
    return $this->getBaseUri();
  }
}