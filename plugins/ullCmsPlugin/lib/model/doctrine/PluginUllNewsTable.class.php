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
    $q = PluginUllNewsTable::queryActiveNews();
    $result = $q->execute();
    
    return $result;
  }
  
  public static function findLatestNews()
  {
    $q = PluginUllNewsTable::queryActiveNews();
    $result = $q->fetchOne();
    
    return $result;
  }
  
  public static function findLatestActiveNews()
  {
    $q = PluginUllNewsTable::queryActiveNews();
    $q->limit(sfConfig::get('app_ull_news_rss_number_of_entries'));
    $result = $q->execute();
    
    return $result;
  }
  
  protected static function queryActiveNews(){
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