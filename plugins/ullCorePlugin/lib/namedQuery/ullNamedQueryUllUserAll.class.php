<?php

/**
 * Named Query
 *
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllUserAll extends ullNamedQuery
{

  public function configure()
  {
    $this->name       = 'All users';
    $this->identifier = 'all';
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
