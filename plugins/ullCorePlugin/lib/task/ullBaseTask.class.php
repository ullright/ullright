<?php

abstract class ullBaseTask extends sfBaseTask
{
  
  /**
   * Gets a sfConfig option and check's that it is set
   * 
   * @param string $option
   * @return string
   * @throws InvalidArgumentException
   */
  protected function getRequiredSfConfigOption($option)
  {
    if (sfConfig::has($option))
    {
      return sfConfig::get($option);
    }
    else
    {
      throw new InvalidArgumentException('Required sfConfig option not set: ' . $option);
    }
  }
  
  
}