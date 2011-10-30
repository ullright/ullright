<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsContentBlockTableConfiguration extends UllCmsItemTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->setName(__('Content blocks', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('title'));
    $this->setOrderBy('Parent->full_path, sequence, title');
    $this->setListColumns(array('title', 'Parent->full_path', 'UllCmsContentType->name', 'is_active', 'Updator->display_name', 'updated_at'));
  }
  
}