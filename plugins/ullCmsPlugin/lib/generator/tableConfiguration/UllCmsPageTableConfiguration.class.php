<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsPageTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Pages', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('title'));
    $this->setOrderBy('Parent->name, title');
    $this->setListColumns(array('title', 'parent_ull_cms_item_id', 'Updator->display_name', 'updated_at'));
//    $this->setForeignRelationName(__('In navigation', null, 'ullCmsMessages'));
  }
  
}