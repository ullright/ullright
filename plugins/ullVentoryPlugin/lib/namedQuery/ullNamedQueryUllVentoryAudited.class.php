<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllVentoryAudited extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Audited during current inventory taking';
    $this->identifier = 'audited';
  }
  
  public function modifyQuery($q)
  {
    $q
      ->addWhere('UllVentoryItemTaking->ull_ventory_taking_id = ?', UllVentoryTakingTable::findLatest()->id);
    ;
  }
  
}