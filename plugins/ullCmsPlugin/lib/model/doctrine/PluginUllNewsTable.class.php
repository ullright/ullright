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
      ->orderBy('news.activation_date')
    ;

    $result = $q->execute();
    
    return $result;
  }
}