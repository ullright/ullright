<?php

/**
 * Subclass for representing a row from the 'ull_select' table.
 *
 * 
 *
 * @package plugins.ullCorePlugin.lib.model
 */ 
class UllSelect extends BaseUllSelect
{
  
  //automatically set the current culture
  public function hydrate(ResultSet $rs, $startcol = 1) {
    
    parent::hydrate($rs, $startcol);
    $this->setCulture(substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
  }
  
  public function __toString() {
    return ullCoreTools::getI18nField($this, 'caption');
  }  
  
}
