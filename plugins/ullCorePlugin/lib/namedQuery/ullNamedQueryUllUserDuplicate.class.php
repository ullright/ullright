<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllUserDuplicate extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Duplicate users';
    $this->identifier = 'duplicate';
  }
  
  public function getUri()
  {
    //this is overridden because we don't actually want to
    //modify the query, but use a custom url
    return 'ullUser/findDuplicateUsers';
  }
  
}