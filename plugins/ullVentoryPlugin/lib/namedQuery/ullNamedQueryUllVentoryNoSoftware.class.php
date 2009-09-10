<?php

/**
 * Named Query
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullNamedQueryUllVentoryNoSoftware extends ullNamedQuery
{
  
  public function configure()
  {
    $this->name       = 'Computer without software';
    $this->identifier = 'no_software';
  }
  
  public function modifyQuery(Doctrine_Query $q)
  {
    $q
      ->addWhere('t.has_software = ?', true)
      ->addWhere('x.UllVentoryItemSoftware.id IS NULL')
    ;
    
    
  }
  
}