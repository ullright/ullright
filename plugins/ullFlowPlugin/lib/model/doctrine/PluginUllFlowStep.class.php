<?php

abstract class PluginUllFlowStep extends BaseUllFlowStep
{
  
  /**
   * To string
   *
   * @return string
   */
  public function __toString()
  {
    return (string) $this->label;
  }

}