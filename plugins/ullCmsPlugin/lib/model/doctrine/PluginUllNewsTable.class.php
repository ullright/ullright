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
    $date = date('Y-m-d');
    $q = new Doctrine_Query;
    $q
      ->from('UllNews news')
      ->where('news.activation_date <= ?', $date)
      ->addWhere('news.deactivation_date > ?', $date)
      ->orderBy('news.activation_date desc')
    ;

    $result = $q->execute();
    
    return $result;
  }
  
  public static function findLatestNews()
  {
    $date = date('Y-m-d');
    $q = new Doctrine_Query;
    $q
      ->from('UllNews news')
      ->where('news.activation_date <= ?', $date)
      ->addWhere('news.deactivation_date > ?', $date)
      ->orderBy('news.activation_date desc')
      ->limit(1)
    ;

    $result = $q->fetchOne();
    
    return $result;
  }
  
  public static function findLatestActiveNews()
  {
    $date = date('Y-m-d');
    $q = new Doctrine_Query;
    $q
      ->from('UllNews news')
      ->where('news.activation_date <= ?', $date)
      ->addWhere('news.deactivation_date > ?', $date)
      ->orderBy('news.activation_date desc')
      ->limit(10)
    ;

    $result = $q->fetchOne();
    
    return $result;
  }
}