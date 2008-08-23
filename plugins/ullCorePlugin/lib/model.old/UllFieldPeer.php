<?php

/**
 * Subclass for performing query and update operations on the 'ull_field' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllFieldPeer extends BaseUllFieldPeer
{
  
  public static function getFieldTypeByID($ull_field_id) {
     
    $obj = UllFieldPeer::retrieveByPK($ull_field_id);
    if ($obj) {
      
      return $obj->getFieldType();
      
    }
  }
  
}
