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
  
  public function getUri()
  {
    //this is overridden because we don't actually want to
    //modify the query, we use uri params instead
    return ullCoreTools::appendParamsToUri(
      $this->getBaseUri(),
      'filter[ull_user_status_id]=' . Doctrine::getTable('UllUserStatus')->findOneBySlug('active')->id
    );
  }  
}