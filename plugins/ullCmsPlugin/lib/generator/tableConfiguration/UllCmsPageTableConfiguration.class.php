<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsPageTableConfiguration extends UllCmsItemTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->setName(__('Pages', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('title'));
    $this->setOrderBy('Parent->full_path, sequence, title');
    $this->setListColumns(array('title', 'Parent->full_path', 'Updator->display_name', 'updated_at'));
//    $this->setForeignRelationName(__('In navigation', null, 'ullCmsMessages'));
  }
  
}