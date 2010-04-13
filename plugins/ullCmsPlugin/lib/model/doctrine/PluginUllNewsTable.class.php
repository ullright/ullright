<?php
/**
 */
class PluginUllNewsTable extends UllRecordTable
{
  /**
   * Get a list of all active news
   * @return unknown
   */
  public static function findActiveNews()
  {
    $q = PluginUllNewsTable::activeNewsSql();
    $result = $q->execute();
    
    return $result;
  }
  
  public static function findLatestNews()
  {
    $q = PluginUllNewsTable::activeNewsSql();
    $result = $q->fetchOne();
    
    return $result;
  }
  
  public static function findLatestActiveNews()
  {
    $q = PluginUllNewsTable::activeNewsSql();
    $q->limit(10);
    $result = $q->execute();
    
    return $result;
  }
  
  private static function activeNewsSql(){
    $date = date('Y-m-d');
    $q = new Doctrine_Query;
    $q
      ->from('UllNews news')
      ->where('news.activation_date <= ?', $date)
      ->addWhere('(news.deactivation_date > ? OR news.deactivation_date is NULL)', $date)
      ->orderBy('news.activation_date desc')
    ;
    
    return $q;
  }
}