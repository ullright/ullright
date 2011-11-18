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

  /**
   * Find editions by UllNewsletterMailingList->id ordered by submitted_at desc
   * 
   * @param integer $mailingListId
   */
  public static function findByMailingListIdNewestFirst($mailingListId)
  {
    $q = new UllQuery('UllNewsletterEdition');
    $q
      ->addWhere('UllNewsLetterEditionMailingList.ull_newsletter_mailing_list_id = ?', $mailingListId)
      ->addWhere('submitted_at IS NOT NULL')
      ->addWhere('is_active = ?', true)
      ->addOrderBy('submitted_at DESC')
    ;
    
    return $q->execute();
  }
}