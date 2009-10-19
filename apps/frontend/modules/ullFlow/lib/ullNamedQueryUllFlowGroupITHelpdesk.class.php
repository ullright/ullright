<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllFlowGroupITHelpdesk extends ullNamedQuery
{
  protected $groupName;
  
  public function configure()
  {
    $this->name       = 'Tickets assigned to group IT-Helpdesk';
    $this->identifier = 'to_helpdesk';
    $this->groupName  = 'Helpdesk';
  }
  
  public function modifyQuery($q)
  {
    $helpdesk = Doctrine::getTable('UllGroup')->findOneByDisplayName($this->groupName, Doctrine::HYDRATE_ARRAY);
    if ($helpdesk !== false)
    {
       $q->addWhere('x.assigned_to_ull_entity_id = ?', $helpdesk['id']);
    }
    else
    {
      throw new UnexpectedValueException('Invalid group display name specified.');
    }
  }
}