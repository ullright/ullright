<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsMenuItemTableConfiguration extends UllCmsItemTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->setName(__('Menu entries', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('full_path, sequence');
    $this->setListColumns(array('full_path'));
//    $this->setFilterColumns(array('allow_sub_items' => 'checked'));
  }
  
}