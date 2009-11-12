<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllVentoryNotAudited extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Not audited during current inventory taking';
    $this->identifier = 'not_audited';
  }
  
  public function modifyQuery($q)
  {
    // What are we doing here?
    //   We want all items which don't have an entry in UllVentoryItemTaking with 
    //   the id of the current UllVentoryTaking.
    //   So we join only the UllVentoryItemTakings for the current UllVentoryTaking
    //   And then negate  by saying we want only the ones with no entry in 
    //   UllVentoryItemTakings
    $q
      ->getDoctrineQuery()
        ->leftJoin('x.UllVentoryItemTaking it WITH it.ull_ventory_taking_id = ?', UllVentoryTakingTable::findLatest()->id)
        ->addWhere('it.id IS NULL')
    ;
  }
  
}