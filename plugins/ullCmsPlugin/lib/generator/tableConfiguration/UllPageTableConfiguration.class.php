<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllPageTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Pages', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('title'));
    $this->setOrderBy('UllNavigationItem->name, title');
    $this->setListColumns(array('title', 'UllNavigationItem->name', 'Updator->display_name', 'updated_at'));
//    $this->setForeignRelationName(__('In navigation', null, 'ullCmsMessages'));
  }
  
}