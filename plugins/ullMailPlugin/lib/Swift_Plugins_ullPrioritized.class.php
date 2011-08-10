<?php
interface Swift_Plugins_ullPrioritized
{
  public function getPriority();

  /**
   * Set the order of the swift plugins.
   * 
   * A value < 0 means the plugin is ordered before "normal/unprioritized" plugins
   * A value > 0 means the plugin is ordered after "normal/unprioritized" plugins
   * 
   * @param $priority
   */
  public function setPriority($priority);
  
  public function hasPriority();
}
