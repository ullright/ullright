<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllFlowByMe extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Entries created by me';
    $this->identifier = 'by_me';
  }
  
  public function modifyQuery(Doctrine_Query $q)
  {
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    $q->addWhere('x.creator_user_id = ?', $userId);
  }
  
}