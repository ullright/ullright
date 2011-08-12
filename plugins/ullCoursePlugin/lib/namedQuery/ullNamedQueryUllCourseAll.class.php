<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllCourseAll extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'All courses';
    $this->identifier = 'all';
  }
  
  public function modifyQuery($q)
  {
  }
  
}