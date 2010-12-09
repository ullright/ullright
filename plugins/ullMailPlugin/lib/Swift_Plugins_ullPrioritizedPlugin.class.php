<?php
abstract class Swift_Plugins_ullPrioritizedPlugin implements Swift_Plugins_ullPrioritized
{
  protected $priority;
  
  public function getPriority()
  {
    return $this->priority;
  }

  public function setPriority($priority)
  {
    $this->priority = $priority;
  }
  
  public function hasPriority()
  {
    return ($this->priority !== null && $this->priority !== 0);
  }
}
