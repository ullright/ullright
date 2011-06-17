<?php
/**
 * dynamic form for ullFlow
 *
 */
class ullFlowForm extends ullGeneratorForm
{
  
  protected
    $ullFlowAction = null
  ;
  
  /**
   * Configures the form
   *
   */
  public function configure()
  {
    parent::configure();
  }
  

  /**
   * Query also the virtual columns for the default values
   *
   * @see sfDoctrineForm
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    $this->setDefaults(array_merge($this->getDefaults(), 
        $this->object->getVirtualValuesAsArray()));
        
    if (!$this->getDefault('effort_date'))
    {
      $this->setDefault('effort_date', date('Y-m-d')); 
    }        
  }
  
  
  /**
   * Update also the virtual Columns
   *
   * @see sfDoctrineForm
   * @return Doctrine_Record
   */
  public function updateObject($values = null)
  {
    parent::updateObject();
    
    $this->performAction();
    
    return $this->object;
  }
  
  
  /**
   * Checks for a valid UllFlowAction and triggers the workflow rule handling
   *
   * @throws InvalidArgumentException if no valid UllFlowAction->slug is given
   */
  protected function performAction()
  { 
    $actionSlug = sfContext::getInstance()->getRequest()->getParameter('action_slug', 'save_close');

    if ($this->ullFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug($actionSlug))
    {
      $ullFlowActionHandlerValues = array();
      foreach ($this->values as $key => $value)
      {
        if (strstr($key, 'ull_flow_action_'))
        {
          $ullFlowActionHandlerValues[$key] = $value;          
        }
      }
      
      $this->object->performAction($this->ullFlowAction, $ullFlowActionHandlerValues);
    }
    else
    {
      throw new InvalidArgumentException('Invalid UllFlowAction given: ' . $actionSlug); 
    }
  } 
    
}
