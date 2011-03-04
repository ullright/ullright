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
      'num_total_recipients',
      'num_sent_recipients',
      'num_read_emails',
      'num_failed_emails',
      'UllNewsletterEditionMailingLists',
      'is_active',
      'submitted_at',
      'Sender->display_name',
    ));
//    $this->setForeignRelationName(__('Layout', null, 'ullMailMessages'));
  }  
  
}