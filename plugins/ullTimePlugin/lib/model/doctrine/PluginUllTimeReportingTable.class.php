<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllTimeReportingTable extends UllRecordTable
{

  /** 
   * Find the total work seconds by date and userId
   * 
   * @param $date
   * @param $userId
   * @return integer
   */
  public static function findTotalWorkSecondsByDateAndUserId($date, $userId)
  {
    $q = new Doctrine_Query;
    
    $q
      ->select('tp.total_work_seconds')
      ->from('UllTimeReporting tp')
      ->where('tp.date = ?', $date)
      ->addWhere('tp.ull_user_id = ?', $userId)
    ;
    
    $result = $q->fetchOne(null, Doctrine::HYDRATE_NONE);
    
    return $result[0];
  }
  
  
  /**
   * Find a row by date and user_id
   * 
   * @param $date
   * @param $ull_user_id
   * @return mixed
   */
  public static function findByDateAndUserId($date, $userId)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllTimeReporting tr')
      ->where('tr.date = ?', $date)
      ->addWhere('tr.ull_user_id = ?', $userId)
    ;

    $result = $q->fetchOne();
    
    return $result;
  } 
  
}