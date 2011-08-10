<?php
abstract class Swift_Plugins_ullPrioritizedPlugin implements Swift_Plugins_ullPrioritized
{
  protected $priority;
  
  public function getPriority()
  {
    return $this->priority;
  }

  /**
   * Set the order of the swift plugins.
   * 
   * A value < 0 means the plugin is ordered before "normal/unprioritized" plugins
   * A value > 0 means the plugin is ordered after "normal/unprioritized" plugins
   * 
   * @param $priority
   */
  public function setPriority($priority)
  {
    $this->priority = $priority;
  }
  
  public function hasPriority()
  {
    return ($this->priority !== null && $this->priority !== 0);
  }
}
