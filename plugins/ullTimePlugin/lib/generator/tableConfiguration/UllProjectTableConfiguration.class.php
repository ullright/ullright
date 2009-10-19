<?php
/**
 * TableConfiguration for UllProject
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllProjectTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Projects', null, 'ullTimeMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setOrderBy('slug');
  }
  
}