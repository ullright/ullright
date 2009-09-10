<?php

/**
 * Test class for ullNamedQuery
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryTest extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'My test query';
    $this->identifier = 'my_test_query';
  }
  
  public function modifyQuery(Doctrine_Query $q)
  {
    $q->addWhere('u.username = ?', 'admin');
  }
  
}