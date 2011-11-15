<?php

/**
 * PluginUllNewsletterEditionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllNewsletterEditionTable extends UllRecordTable
{
  /**
   * Returns an instance of this class.
   *
   * @return object PluginUllNewsletterEditionTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('PluginUllNewsletterEdition');
  }

  
  /**
   * Find editions which are ready to be spooled
   * 
   */
  public static function findEditionsToBeSpooled()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllNewsletterEdition e')
      ->where('e.submitted_at IS NOT NULL')
      ->addWhere('e.queued_at IS NULL')
      ->addWhere('e.is_active = ?', true)
    ;
    
    return $q->execute();
  }
}