<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsMenuItemTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Navigation', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('parent_ull_cms_item_id, name');
    $this->setListColumns(array('name', 'parent_ull_cms_item_id'));
    $this->setForeignRelationName(__('In navigation', null, 'ullCmsMessages'));
  }
  
}