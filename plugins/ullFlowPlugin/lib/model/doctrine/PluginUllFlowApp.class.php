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
  public function findStartStep()
  {
    $q = new Doctrine_Query;
    $q->from('UllFlowStep s')
      ->where('s.ull_flow_app_id = ?', $this->id)
      ->addWhere('s.is_start = ?', true)
    ;
    return $q->execute()->getFirst();
  }
  
  /**
   * returns UlLFlowStep for a given slug and for the current UllFlowApp
   *
   * @param string $slug  a UllFlowStep slug
   * @return UllFlowStep
   */
  public function findStepBySlug($slug)
  {
    $q = new Doctrine_Query;
    $q->from('UllFlowStep s')
      ->where('s.ull_flow_app_id = ?', $this->id)
      ->addWhere('s.slug = ?', $slug)
    ;
    return $q->execute()->getFirst();
  }

}