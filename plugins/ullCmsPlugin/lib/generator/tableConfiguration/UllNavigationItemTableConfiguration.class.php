<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllNavigationItemTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Navigation', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
//    $this->setListColumns(array('name'));
    $this->setForeignRelationName(__('In navigation', null, 'ullCmsMessages'));
  }
  
}