<?php
/**
 * TableConfiguration for UllJobTitle
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllJobTitleTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Job titles', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
    $this->setForeignRelationName(__('Job title', null, 'ullCoreMessages'));
  }
  
}