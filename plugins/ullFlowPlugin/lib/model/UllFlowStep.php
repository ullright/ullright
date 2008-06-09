<?php

/**
 * Subclass for representing a row from the 'ull_flow_step' table.
 *
 * 
 *
 * @package plugins.ullFlowPlugin.lib.model
 */ 
class UllFlowStep extends BaseUllFlowStep
{
  
    //automatically set the current culture
  public function hydrate(ResultSet $rs, $startcol = 1) {
    
    parent::hydrate($rs, $startcol);
    $this->setCulture(substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2));
  }
  
  public function __toString() {
    $return = $this->getUllFlowApp()->__toString();
    $return .= ' - ';
    $return .= ullCoreTools::getI18nField($this, 'caption');
    return $return;
  }  
  
}
