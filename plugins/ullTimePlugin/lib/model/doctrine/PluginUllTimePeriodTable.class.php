<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllTimePeriodTable extends UllRecordTable
{
  
  /**
   * Find a period slug by a given date
   * 
   * @param $date
   * @return string
   */
  public static function findSlugByDate($date)
  {
    $q = new Doctrine_Query;
    $q
      ->select('tp.slug')
      ->from('UllTimePeriod tp')
      ->where('tp.from_date <= ?', $date)
      ->addWhere('tp.to_date >= ?', $date)
    ;
    
    $result = $q->fetchOne(null, Doctrine::HYDRATE_NONE);
    
    return $result[0];
  }

  
  /**
   * Find the current and all past periods
   * @param $date
   * @return unknown_type
   */
  public static function findCurrentAndPast($date = null)
  {
    if (!$date)
    {
      $date = date('Y-m-d');
    }
    
    $q = new Doctrine_Query;
    $q
      ->from('UllTimePeriod tp')
      ->where('tp.from_date <= ?', $date)
      ->orderBy('tp.from_date DESC')
    ;
    
    return $q->execute();
  }
  
  
  /**
   * Check if a given period overlapps an existing period
   * 
   * @param $fromDate
   * @param $toDate
   * @return false or an array of Doctrine records of overlapping existing periods
   */
  public static function periodExists($fromDate, $toDate)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllTimePeriod tp')
      ->where('tp.from_date BETWEEN ? AND ?', array($fromDate, $toDate))
      ->orWhere('tp.to_date BETWEEN ? AND ?', array($fromDate, $toDate))
    ;
    $result = $q->execute(null, Doctrine::HYDRATE_ARRAY);
    
    return count($result) ? $result: null;
  }
  
  
  /**
   * Find periods for one year in the future
   * @param $date
   * @return unknown_type
   */
  public static function findOneYearInFuture()
  {
    $date = date('Y-m-d');
    
    $q = new Doctrine_Query;
    $q
      ->from('UllTimePeriod tp')
      ->where('tp.from_date > ?', $date)
      ->addWhere('tp.to_date < ?', date('Y-m-d', strtotime('+13 months')))
      ->orderBy('tp.from_date DESC')
    ;
    
    return $q->execute();
  }  
  
}
  