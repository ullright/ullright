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
    for($i = strtotime($this->from_date); $i <= strtotime($this->to_date); $i += 60 * 60 * 24)
    {
      $date = date('Y-m-d', $i);
      $weekday = date('w', $i);
      $weekend = (in_array($weekday, array(0,6))) ? true : false;
      
      $return[$date] = array('date' => $date, 'weekend' => $weekend);
    }
    
    return $return;
  }

}