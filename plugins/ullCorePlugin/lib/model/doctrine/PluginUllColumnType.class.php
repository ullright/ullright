<?php

abstract class PluginUllColumnType extends BaseUllColumnType
{
  
  /**
   * to string method
   *
   * @return string
   */
  public function __toString()
  {
    
    return $this->label;
  }

}