<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllMailLoggedMessageTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Logged Mail Messages', null, 'ullMailMessages'));
    $this->setSearchColumns(array('to_list'));
    $this->setOrderBy('sent_at DESC');
    $this->setListColumns(array(
      'sender',
      'to_list',
      'subject',
      'sent_at',
    ));
  }  
  
}