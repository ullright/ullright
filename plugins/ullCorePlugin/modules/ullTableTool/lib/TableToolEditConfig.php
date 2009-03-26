<?php

abstract class TableToolEditConfig
{
  public function allowDelete()
  {
    return true;
  }
  
  public function hasActionButtons()
  {
    return false;
  }
  
  public function getActionButtons()
  {
  }
}
