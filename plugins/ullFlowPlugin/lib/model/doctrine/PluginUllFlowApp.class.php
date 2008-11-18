<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllFlowApp extends BaseUllFlowApp
{
  
  /**
   * return the app's label
   *
   * @return unknown
   */
  public function __toString()
  {
    return $this->label;
  }
  
  /**
   * Get the first UllFlowStep
   *
   * @return UllFlowStep
   */
  public function getStartStep()
  {
    $q = new Doctrine_Query;
    $q->from('UllFlowStep s')
      ->where('s.ull_flow_app_id = ?', $this->id)
      ->addWhere('s.is_start = ?', true)
    ;
    return $q->execute()->getFirst();
  }

}