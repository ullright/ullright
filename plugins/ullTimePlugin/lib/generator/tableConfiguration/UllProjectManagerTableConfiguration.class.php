<?php
/**
 * TableConfiguration for UllProjectManager
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllProjectManagerTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Project Managers', null, 'ullTimeMessages'));
    $this->setListColumns(array('UllProject->name', 'UllUser->display_name'));
    $this->setSearchColumns(array('UllProject->name', 'UllUser->display_name'));
    $this->setOrderBy('UllProject->name');
    
  }
  
}