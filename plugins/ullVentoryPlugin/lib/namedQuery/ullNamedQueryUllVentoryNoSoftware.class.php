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
  
  public function modifyQuery($q)
  {
    $q
      ->addWhere('UllVentoryItemModel->UllVentoryItemType->has_software = ?', true)
      ->addWhere('UllVentoryItemSoftware->id IS NULL')
    ;
  }
  
}