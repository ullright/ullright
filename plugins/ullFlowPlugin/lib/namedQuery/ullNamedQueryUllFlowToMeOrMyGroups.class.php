<?php

/**
 * Named Query
 *
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllFlowToMeOrMyGroups extends ullNamedQuery
{

  public function configure()
  {
    $this->name       = 'Entries assigned to me or my groups';
    $this->identifier = 'to_me_or_my_groups';
  }

  public function modifyQuery(Doctrine_Query $q)
  {
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    
    $q->leftJoin('x.UllEntity e_me');
    $q->leftJoin('e_me.UllEntityGroupsAsGroup aeg_me');

    $q->addWhere('
            e_me.id = ? 
            OR aeg_me.ull_entity_id = ?', 
    array($userId, $userId)
    );
  }

}