<?php

class UllUserTableToolEditConfig extends TableToolEditConfig 
{
  public function allowDelete()
  {
    return false;
  }
  
  public function hasActionButtons()
  {
    return true;
  }
  
  public function getActionButtons()
  {
    //return submit_tag(__('Generate Email and username', null, 'common'));
  }
}
