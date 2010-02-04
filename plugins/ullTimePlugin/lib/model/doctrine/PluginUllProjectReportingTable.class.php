<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllProjectReportingTable extends UllRecordTable
{
  
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
      ->from('UllProjectReporting pr')
      ->where('pr.date = ?', $date)
      ->addWhere('pr.ull_user_id = ?', $userId)
      ->orderBy('created_at, id')
    ;

    $result = $q->execute();
    
    return $result;
  }
  

  /**
   * Get the sum by date and user 
   * @param string $date
   * @param integer $userId
   * @return mixed
   */
  public static function findSumByDateAndUserId($date, $userId)
  {
    $q = new Doctrine_Query;
    $q
      ->select('SUM(pr.duration_seconds)')
      ->from('UllProjectReporting pr')
      ->where('pr.date = ?', $date)
      ->addWhere('pr.ull_user_id = ?', $userId)
    ;
    
    $result = $q->fetchOne(null, Doctrine::HYDRATE_NONE);
    
    return $result[0];
  }
  
  
  /**
   * Get a list of the most used projects in the last two weeks
   * @return unknown_type
   */
  public static function findLatestTopProjects()
  {
    $q = new Doctrine_Query;
    $q
      ->select('COUNT(pr.ull_project_id) as num, pr.ull_project_id, t.name')
      ->from('UllProjectReporting pr, pr.UllProject p, p.Translation t')
      ->where('pr.created_at > ?', date('Y-m-d', time() - 60 * 60 * 24 * 7 * 2))
      ->addWhere('t.lang = ?', substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2))
      ->addWhere('p.is_active = ?', true)
      ->groupBy('pr.ull_project_id')
      //->orderBy('num DESC')
      ->orderBy('t.name');
// deactivated because it removes the correct "num" value !?!      
//      ->limit(10)
    ;
    
    // 0 = ull_project_id
    // 1 = name
    // 2 = num
    $result = $q->execute(null, Doctrine::HYDRATE_NONE);
    
    $max = (count($result) > 10) ? 10 : count($result);   
    
    $return = array();
    
    for ($i = 0; $i < $max; $i++)
    {
      $return[$result[$i][0]] = $result[$i][1];
    }
    
    return $return;
  }    
}