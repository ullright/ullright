<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCmsItemTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setForeignRelationName(__('Higher menu entry', null, 'ullCmsMessages'));
    $this->setForeignRelationName(__('Higher', null, 'ullCmsMessages'), 'Parent');
    $this->setFilterColumns(array('is_active' => 'checked'));
  }
  
}