<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllTimeReporting extends BaseUllTimeReporting
{
  
  /**
   * pre save hook
   * 
   * auto calculate totals
   *
   * @param unknown_type $event
   */
  public function preSave($event)
  {
    if ($this->begin_work_at && $this->end_work_at)
    {
      $this->total_work_seconds = strtotime($this->end_work_at) - strtotime($this->begin_work_at);
    }  
    
    $this->total_break_seconds = 0;
    
    if ($this->begin_break1_at && $this->end_break1_at)
    {
      $this->total_break_seconds += strtotime($this->end_break1_at) - strtotime($this->begin_break1_at);
    }
    
    if ($this->begin_break2_at && $this->end_break2_at)
    {
      $this->total_break_seconds += strtotime($this->end_break2_at) - strtotime($this->begin_break2_at);
    }    
    
    if ($this->begin_break3_at && $this->end_break3_at)
    {
      $this->total_break_seconds += strtotime($this->end_break3_at) - strtotime($this->begin_break3_at);
    }    
  }  

}