<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllNewsletterMailingListTableConfiguration extends UllTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Newsletter mailing list', null, 'ullMailMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
    $this->setFilterColumns(array('is_active' => null));
  }
}