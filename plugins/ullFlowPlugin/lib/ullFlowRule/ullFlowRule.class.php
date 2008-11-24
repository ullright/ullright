<?php

/**
 * Base class for all ullFlow rules
 * 
 * offers some convenience shortcut methods to simplify the custome rule classes 
 *
 */
abstract class ullFlowRule 
{
  
  protected
    $doc
  ;

  /**
   * Constructor
   *
   * @param UllFlowDoc $doc
   */
  public function __construct(UllFlowDoc $doc)
  {
    $this->doc = $doc;
  }
  
  /**
   * get next step and next entity (user/group)
   * 
   * @return array  array('step' => UllFlowStep, 'entity' => UllEntity)
   *
   */
  abstract function getNext();
  
  /**
   * returns a UllGroup for a given group display_name
   * 
   * @param string $displayName   Display name of a UllGroup
   * @return integer              UllEntity->id of the given group
   */
  public function findGroup($displayName)
  {
    return Doctrine::getTable('UllGroup')->findOneByDisplayName($displayName);
  }  
  
  /**
   * returns UllFlowStep->id for a given slug
   *
   * @param unknown_type $slug
   * @return unknown
   */
  public function findStep($slug) 
  {
    return $this->doc->UllFlowApp->findStepBySlug($slug);
  }
  
  
  /**
   * returns true for the current step
   *
   * @param string $slug    UllFlowStep slug
   * @return boolean
   */
  public function isStep($slug)
  {
    if ($this->doc->UllFlowStep->id == $this->findStep($slug))
    {
      return true;
    }
  }  
  
}
