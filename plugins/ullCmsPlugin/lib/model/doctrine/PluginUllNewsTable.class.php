<?php
/**
 */
class PluginUllNewsTable extends UllRecordTable
{
  /**
   * Get a list of all active news
   * @return array
   */
  public static function findActiveNews()
  {
    $q = PluginUllNewsTable::queryActiveNews();
    $result = $q->execute();
    
    return $result;
  }
  
  
  /**
   * Get the latest news entry (only one)
   * @return array
   */
  public static function findLatestNews()
  {
    $q = PluginUllNewsTable::queryActiveNews();
    $result = $q->fetchOne();
    
    return $result;
  }
  
  
  /**
   * Get a list of the latest active news
   * @return array
   */
  public static function findLatestActiveNews()
  {
    $q = PluginUllNewsTable::queryActiveNews();
    $q->limit(sfConfig::get('app_ull_news_rss_number_of_entries', 999));
    $result = $q->execute();
    
    return $result;
  }
  
  
  /**
   * Creates a Doctrine_Query for active news
   * @return Doctrine_Query
   */
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