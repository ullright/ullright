<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllFlowToMe extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Entries assigned to me';
    $this->identifier = 'to_me';
  }
  
  public function modifyQuery(Doctrine_Query $q)
  {
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id'); 
    $q->addWhere('x.assigned_to_ull_entity_id = ?', $userId);
  }
  
}