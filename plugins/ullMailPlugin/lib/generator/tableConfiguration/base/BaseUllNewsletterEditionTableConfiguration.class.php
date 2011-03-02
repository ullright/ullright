<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllNewsletterEditionTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Newsletter', null, 'ullNewsletterMessages'));
    $this->setSearchColumns(array('subject'));
    $this->setOrderBy('submitted_at DESC');

    $this->setListColumns(array(
      'subject',
      'UllNewsletterEditionMailingLists',
      'submitted_at',
      'Sender->display_name',
      'is_active',
      'num_total_recipients',
      'num_sent_recipients',
      'num_failed_emails',
      'num_read_emails',
    ));
//    $this->setForeignRelationName(__('Layout', null, 'ullMailMessages'));
  }  
  
}