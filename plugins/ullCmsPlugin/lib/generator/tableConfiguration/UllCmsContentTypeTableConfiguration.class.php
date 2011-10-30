<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsContentTypeTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this->setName(__('Content types', null, 'ullCmsMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
    $this->setListColumns(array('name', 'type', 'Updator->display_name', 'updated_at'));
    $this->setForeignRelationName(__('Content type', null, 'ullCmsMessages'));
  }
  
}