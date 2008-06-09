<?php

/**
 * Subclass for representing a row from the 'ull_flow_action' table.
 *
 * 
 *
 * @package plugins.ullFlowPlugin.lib.model
 */ 
class UllFlowAction extends BaseUllFlowAction
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
