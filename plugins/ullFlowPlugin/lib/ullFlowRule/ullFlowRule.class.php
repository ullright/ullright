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
  abstract public function getNext();
  
  /**
   * gets the superior of the UllEntity to which the document is assigned
   *   to if it is a UllUser
   * 
   * @return UllUser
   *
   */
  public function findSuperior()
  {
    if ($this->doc->UllEntity instanceof UllUser)
    {
      if ($superior = $this->doc->UllEntity->Superior)
      {
        return $superior;
      }
    }
  }  
  
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
    $step = $this->doc->UllFlowApp->findStepBySlug($slug);
    return $step;
  }
  
  /**
   * returns UllFlowStep->id for a given label
   *
   * @param string $label
   * @return UllFlowStep
   */
  public function findStepByLabel($label)
  {
    $step = $this->doc->UllFlowApp->findStepByLabel($label);
    
    return $step;
  }  
  
  
  /**
   * returns true if the document is assigned to the given step
   *
   * @param string $slug    UllFlowStep slug
   * @return boolean
   */
  public function isStep($slug)
  {
    $ullFlowStepId = $this->doc->UllFlowApp->findStepIdBySlug($slug);
    
    if ($this->doc->assigned_to_ull_flow_step_id == $ullFlowStepId)
    {
      return true;
    }
  }
  
  /**
   * returns true if the document is assigned to the given step
   *
   * @param string $slug    UllFlowStep label
   * @return boolean
   */
  public function isStepByLabel($label)
  {
    $ullFlowStepId = $this->doc->UllFlowApp->findStepByLabel($label)->id;
    
    if ($this->doc->assigned_to_ull_flow_step_id == $ullFlowStepId)
    {
      return true;
    }
  }  

  /**
   * returns true if performed ullFlowAction is the the given action
   *
   * @param string $slug    UllFlowStep slug
   * @return boolean
   */
  public function isAction($slug)
  {
    $ullFlowActionId = UllFlowActionTable::findIdBySlug($slug);
    
    if ($this->doc->ull_flow_action_id == $ullFlowActionId)
    {
      return true;
    }
  }   
  
  /**
   * Shortcut to get the current step slug
   */
  public function getStep()
  {
    return (string) $this->doc->UllFlowStep->slug;
  }

  /**
   * Shortcut to get the current action slug
   */
  public function getAction()
  {
    return (string) $this->doc->UllFlowAction->slug;
  }
  
  /**
   * Get the current workflow doc
   */
  public function getDoc()
  {
    return $this->doc;
  }
  
}
