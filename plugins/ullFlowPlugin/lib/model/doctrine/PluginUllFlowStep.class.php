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
    // Necessary e.g. for UllFlowStepAction admin
    return (string) $this->UllFlowApp . ' - ' . $this->label;
  }

}