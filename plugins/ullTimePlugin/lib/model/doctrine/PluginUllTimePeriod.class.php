<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllTimePeriod extends BaseUllTimePeriod
{
  
  /**
   * Returns a list of dates for the current time period
   * 
   * Format:
   * 
   * array(
   *  '2009-09-01' => array(
   *    'date'    => '2009-09-01',
   *    'weekend' => 'false'
   *    ),
   *  '2009-09-02' => array(
   *    'date'    => '2009-09-02',
   *    'weekend' => 'false'
   *    ),
   *  ...
   * )
   *  
   * @return array
   */
  public function getDateList()
  {
    $return = array();
    
    for (
      $i = strtotime($this->from_date); 
      $i <= strtotime($this->to_date); 
      $i = strtotime('+ 1 day', $i)
    )
    {
      $date = date('Y-m-d', $i);
      $weekday = date('w', $i);
      $weekend = (in_array($weekday, array(0,6))) ? true : false;
      $calendarWeek = (int) date('W', $i); //cast to integer to remove leading 0
      
      $return[$date] = array('date' => $date, 'weekend' => $weekend, 'calendarWeek' => $calendarWeek);
    }
    
    return $return;
  }

}